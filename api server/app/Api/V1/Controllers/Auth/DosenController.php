<?php
namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Transformers\DosensTransformer;
use App\Http\Requests;
use App\Dosen;
use App\User;
use App\Models\Makul;
use App\Models\Biodata;
use App\Models\KrsData;
use App\Models\KrsUtama;
use App\Models\KhsData;
use App\Models\Semester;
use Config;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use JWTAuth;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DB;
use Response;

class DosenController extends Controller
{

    public function __construct()
    {
        $this->dosen = new Dosen;
        Config::set('jwt.user', 'App\Dosen');
        Config::set('auth.providers.users.model', \App\Dosen::class);
    }

    public function login(Request $request)
    {

        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
        $request->merge([$field => $request->input('login')]);

        $credentials = $request->only($field, 'password');
        $token = null;

        try {
            // verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials', 'message' => 'Oups! Terjadi kesalahan, coba ulangi.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token', 'message' => 'Could not create token. Try again'], 500);
        }
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $newDosen = [
            'nip' => $request->get('nip'),
            'username' => $request->get('username'),
            'name' => $request->get('name'),
            'fakultas' => $request->get('fakultas'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];
        try {
            $dosen = Dosen::create($newDosen);
        } catch (Exception $e) {
            return response()->json(['error' => 'Dosen sudah ada.'],401);
        }
        $token = JWTAuth::fromUser($dosen);
        return response()->json(compact('token'));
    }
    
    public function getAuthUser()
    {

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        // the token is valid and we have found the user via the sub claim
        return $this->item($user, new DosensTransformer);
    }

    public function refreshToken()
    {

        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->error('Token NOT provided!', 401);
        }
        $token = JWTAuth::refresh($token);
        return response()->json(compact('token'));
    }

    public function updatePass(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        $this->validate($request, [
           'pass'     => 'required'
        ]);
 
        $putUser = Dosen::findOrFail($user->id);

        $putUser->password = $request->pass;

        if(!$putUser->save()) {

            throw new HttpException(500);
        }
        else {

            return response()->json(['status' => 'success', 'message' => 'password changed.' ], 200);
        }
    }

    public function dosPer()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $siswa = User::where('users.wali_id', $user->id)
                ->join('fakultas', 'users.fakultas', '=', 'fakultas.id')
                ->join('jurusans', 'users.progdi', '=', 'jurusans.id')
                ->join('krs_utamas', 'users.nim', '=', 'krs_utamas.nim')
                ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
                ->select(
                    'users.id',
                    'users.fakultas as fid', 
                    'users.progdi as pid',
                    'users.nim',
                    'users.name as nama',
                    'users.email',
                    'fakultas.nama as fakultas',
                    'jurusans.nama_jur as progdi',
                    DB::raw("count(krs_utamas.id) as makul"),
                    DB::raw("sum(makuls.sks) as sks")
                )
                ->groupBy('users.nim')
                ->get();

        foreach($siswa as $m) {

            $sms = DB::table('semesters')->where('nim', $m->nim)
                ->latest()->first();

            $makul = Makul::where('jurusan', $m->pid)->count();

            $m->makul = $m->makul.' dari '.$makul;
            $m->sms   = $sms->angka;
        }

        $data = $siswa->sortBy('makul');


        $res = [];
        foreach ($data  as $key => $value) {
            $res[] = $value;
        }
        
        return Response::json($res); 
    }

    public function lihatKrs($nim) {
        $user = JWTAuth::parseToken()->authenticate();

        $data = KrsUtama::where('krs_utamas.nim', $nim)
            ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
            ->join('kategori_makuls', 'makuls.kategori', '=', 'kategori_makuls.id')
            ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
            ->select('kategori_makuls.id', 'kategori_makuls.nama as kategori', 'nilais.id as nilai', 'nilais.huruf', 'makuls.nama_mk as namaMkl', DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS sks'))
            ->orderBy('kategori_makuls.id', 'asc')
            ->orderBy('makuls.kode_mk', 'asc')
            ->get();

        // Decode your JSON and create a placeholder array
        $objects = json_decode($data);
        $grouped = array();
        // Loop JSON objects
        foreach($objects as $object) {
            if(!array_key_exists($object->id, $grouped)) { // a new ID...
                 $newObject = new \stdClass();

                 // Copy the ID/ID_NAME, and create an ITEMS placeholder
                 $newObject->id = $object->id;
                 $newObject->kategori = $object->kategori;
                 $newObject->makul = array();

                 // Save this new object
                 $grouped[$object->id] = $newObject;
            }

            $taskObject = new \stdClass();

            // Copy the TASK/TASK_NAME
            $taskObject->idkrs = $object->id;
            $taskObject->namaMk = $object->namaMkl;
            $taskObject->nilai = $object->nilai;
            $taskObject->huruf = $object->huruf;
            $taskObject->sks = $object->sks;

            // Append this new task to the ITEMS array
            $grouped[$object->id]->makul[] = $taskObject;
        }

        // We use array_values() to remove the keys used to identify similar objects
        // And then re-encode this data :)
        $grouped = array_values($grouped);
        $json = json_encode($grouped);

            return response()->json($json);
    }

    public function lihatBio($nim) {
        $user = JWTAuth::parseToken()->authenticate();

        return User::where('users.nim', $nim)
            ->join('biodatas', 'users.nim', '=', 'biodatas.nim')
            ->select('users.name as nama', 'biodatas.asal', 'biodatas.nim', 'biodatas.kelas', 'biodatas.lahir', 'biodatas.hp', 'biodatas.bekerja', 'biodatas.kelamin', 'biodatas.keterangan')
            ->first();
    }


    public function graphKhs($nim) {
        $user = JWTAuth::parseToken()->authenticate();

        $ips = KrsUtama::where('krs_utamas.nim', '=', $nim)
                ->join('semesters', 'krs_utamas.semester_id', '=', 'semesters.id')
                ->select(DB::raw("sum(krs_utamas.nilai) as nilai"))
                ->orderBy('krs_utamas.semester_id')
                ->groupBy('krs_utamas.semester_id')
                ->pluck('nilai');

        $ipsnama = KrsUtama::where('krs_utamas.nim', '=', $nim)
                ->join('semesters', 'krs_utamas.semester_id', '=', 'semesters.id')
                ->select('semesters.nama as nama')
                ->orderBy('krs_utamas.semester_id')
                ->groupBy('krs_utamas.semester_id')
                ->pluck('nama');

        return response()->json([
            "namaSms" => $ipsnama,
            "nilaiSms" => $ips

        ]);
    }

    public function graphNilai($nim) {

        $user = JWTAuth::parseToken()->authenticate();
        
        $label = DB::table("krs_utamas")->where('krs_utamas.nim', $nim)
                ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
                ->select('nilais.huruf as nama')
                ->orderBy('krs_utamas.nilai', 'desc')
                ->groupBy('krs_utamas.nilai')
                ->pluck('nama');

        $angka = DB::table("krs_utamas")->where('krs_utamas.nim', $nim)
                ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
                ->select(DB::raw("count(krs_utamas.id) as nilai"))
                ->orderBy('krs_utamas.nilai', 'desc')
                ->groupBy('krs_utamas.nilai')
                ->pluck('nilai');

        return response()->json([
            "label" => $label,
            "angka" => $angka
        ]);
    }

    public function graphKrs($nim) {
        $user = JWTAuth::parseToken()->authenticate();

         $label = DB::table("krs_datas")->where('krs_datas.nim', $nim)
                 //->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
                 ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                 ->select(DB::raw('CONCAT(semesters.nama," ", semesters.angka) AS nama'))
                 ->groupBy('krs_datas.semester_id')
                 ->pluck('nama');

        $sks = DB::table("krs_datas")->where('krs_datas.nim', $nim)
                ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
                ->select(DB::raw("sum(makuls.sks) as sks"))
                ->groupBy('krs_datas.semester_id')
                ->pluck('sks');

        return response()->json([
            "labels" => $label,
            "rows" => $sks
        ]);
    }


    public function lihatSemester($nim) {
        $user = JWTAuth::parseToken()->authenticate();

        $data = Semester::where('semesters.nim', $nim)
            ->join('krs_datas', 'semesters.id', '=', 'krs_datas.semester_id')
            ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
            ->select('semesters.id','semesters.nama as semester', 'semesters.keterangan as ket', 'makuls.kode_mk as kodeMkl', 'makuls.nama_mk as namaMkl', 'krs_datas.status', DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS sks'))
            ->orderBy('semesters.id', 'asc')
            ->orderBy('makuls.id', 'asc')
            ->get();

        // Decode your JSON and create a placeholder array
        $objects = json_decode($data);
        $grouped = array();
        // Loop JSON objects
        foreach($objects as $object) {
            if(!array_key_exists($object->id, $grouped)) { // a new ID...
                 $newObject = new \stdClass();

                 // Copy the ID/ID_NAME, and create an ITEMS placeholder
                 $newObject->id = $object->id;
                 $newObject->semester = $object->semester;
                 $newObject->keterangan = $object->ket;
                 $newObject->makul = array();

                 // Save this new object
                 $grouped[$object->id] = $newObject;
            }

            $taskObject = new \stdClass();

            // Copy the TASK/TASK_NAME
            $taskObject->kode_makul = $object->kodeMkl;
            $taskObject->nama_makul = $object->namaMkl;
            $taskObject->status_makul = $object->status;
            $taskObject->sks = $object->sks;

            // Append this new task to the ITEMS array
            $grouped[$object->id]->makul[] = $taskObject;
        }

        // We use array_values() to remove the keys used to identify similar objects
        // And then re-encode this data :)
        $grouped = array_values($grouped);
        $json = json_encode($grouped);

            return response()->json($json);
    }

    public function krsBelumDiambil($nim) {
        $user = JWTAuth::parseToken()->authenticate();

        $mahasiswa = User::where('nim', $nim)->first();

        $krsnya = KrsUtama::where('nim', '=', $mahasiswa->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->select('makuls.id as makulId')
        ->pluck('makulId');

        if ($krsnya->isEmpty()) {
           return response()->json(['error' => 'tidak_ada_data', 'message' => 'Tidak ada data.'], 500);
        }
        else {

            $krs = Makul::where('makuls.jurusan', '=', $mahasiswa->progdi)
                ->whereNotIn('makuls.id', $krsnya)
                ->join('status_makuls', 'makuls.status', '=', 'status_makuls.id')
                ->select('makuls.kode_mk as sublabel', 'makuls.nama_mk as label', 'status_makuls.wp as status', DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS sks'))
                ->orderBy('makuls.id', 'asc')
                ->get();

            return response()->json($krs);
        }
    }

    public function detailKrs($nim) {
        $user = JWTAuth::parseToken()->authenticate();

        $mahasiswa = User::where('nim', $nim)->first();

        $krsnya = KrsUtama::where('nim', '=', $mahasiswa->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', 1)
        ->count('makuls.id');

        $sksnya = KrsUtama::where('nim', '=', $mahasiswa->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', 1)
        ->pluck('makuls.sks');
        $msks = $sksnya->sum();

        $wajib = Makul::where('makuls.jurusan', '=', $mahasiswa->progdi)
                ->where('makuls.status', '=', 1)
                ->count('makuls.id');

        $sksttl = Makul::where('makuls.jurusan', '=', $mahasiswa->progdi)
                ->where('makuls.status', '=', 1)
                ->pluck('makuls.sks');
        $total = $sksttl->sum();

        $krspilihan = KrsUtama::where('nim', '=', $mahasiswa->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', 2)
        ->count('makuls.id');

        $skspilihan = KrsUtama::where('nim', '=', $mahasiswa->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', 2)
        ->pluck('makuls.sks');
        $totalskspilih = $skspilihan->sum();

        $pilihan = Makul::where('makuls.jurusan', '=', $mahasiswa->progdi)
                ->where('makuls.status', '=', 2)
                ->count('makuls.id');

        $ttlsksplh = Makul::where('makuls.jurusan', '=', $mahasiswa->progdi)
                ->where('makuls.status', '=', 2)
                ->pluck('makuls.sks');
        $totalpilihan = $ttlsksplh->sum();

        return response()->json([
                "amakul" => $wajib,
                "asks" => $total,
                "msks" => $msks,
                "mambil" => $krsnya,
                "makulpilih" => $pilihan,
                "totalskspilih" => $totalpilihan,
                "skspilihan" => $totalskspilih,
                "pilihandiambil" => $krspilihan
        ]);
    }

    public function mEdit(Request $request,$id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'nim' => 'required',
            'email' => 'required',
            'name' => 'required'
        ]);

        if(!$request->password){

            $edita = User::findOrFail($id);
            $edita->nim = $request->nim;
            $edita->email = $request->email;
            $edita->name = $request->name;
            $edita->save();
            
            return $edita;
        }
        else {

            $edita = User::findOrFail($id);
            $edita->nim = $request->nim;
            $edita->email = $request->email;
            $edita->name = $request->name;
            $edita->password = $request->password;
            $edita->save();

            return $edita;
        }
    }

    public function listDetailSms($nim)
    {
        $user = JWTAuth::parseToken()->authenticate();

          return Semester::where('semesters.nim', '=', $nim)
            ->select('semesters.nama as nama', 'semesters.keterangan as ket')
            ->orderBy('semesters.created_at', 'asc')
            ->get()
            ->toArray();
    }
}