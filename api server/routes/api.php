<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);
 
$api->version('v1', function (Router $api) {
    // mahasiswa
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\Auth\\SignUpController@register');
        $api->post('login', 'App\\Api\\V1\\Controllers\\Auth\\AuthenticateController@login');
        $api->post('recovery', 'App\\Api\\V1\\Controllers\\Auth\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\Auth\\ResetPasswordController@resetPassword');
        $api->post('logout', 'App\\Api\\V1\\Controllers\\Auth\\LogoutController@logout');
        $api->post('refresh-token', 'App\\Api\\V1\\Controllers\\Auth\\AuthenticateController@refreshToken');
    });

    // mimin
    $api->group(['prefix' => 'mimin'], function(Router $api) {
        $api->post('login', 'App\\Api\\V1\\Controllers\\Auth\\MiminController@login');
        $api->post('refresh-token', 'App\\Api\\V1\\Controllers\\Auth\\MiminController@refreshToken');
        $api->get('miminnya', 'App\\Api\\V1\\Controllers\\AdminController@akun');
    });

    // dosen
    $api->group(['prefix' => 'dosen'], function(Router $api) {
        $api->post('tambah', 'App\\Api\\V1\\Controllers\\Auth\\DosenController@register');
        $api->post('login', 'App\\Api\\V1\\Controllers\\Auth\\DosenController@login');
        $api->post('refresh-token', 'App\\Api\\V1\\Controllers\\Auth\\MiminController@refreshToken');

    });

    $api->group(['middleware' => ['jwt.auth']], function(Router $api) {

        // Mahasiswa menu
        $api->get('user/auth', 'App\Api\V1\Controllers\Auth\AuthenticateController@getAuthUser');
        $api->get('ambilKrs', 'App\Api\V1\Controllers\Mahasiswa\MakulController@pickKrs');
        $api->get('lihatPedoman', 'App\Api\V1\Controllers\Mahasiswa\MakulController@pedKrs');
        $api->get('belumAmbil', 'App\Api\V1\Controllers\Mahasiswa\MakulController@sdhKrs');

        // Semester Controller
        $api->get('belumValidSMS', 'App\Api\V1\Controllers\Mahasiswa\SemesterController@belumValid');
        $api->get('listSMS', 'App\Api\V1\Controllers\Mahasiswa\SemesterController@listSmsKhs');
        $api->get('detailSMS/{id}', 'App\Api\V1\Controllers\Mahasiswa\SemesterController@detailSemester');
        $api->post('addSMS', 'App\Api\V1\Controllers\Mahasiswa\SemesterController@addSemester');
        $api->delete('delSMS/{id}', 'App\Api\V1\Controllers\Mahasiswa\SemesterController@delSemester');

        //KRS Controller
        $api->get('ambilSMS', 'App\Api\V1\Controllers\Mahasiswa\KrsDataController@listSemester');
        $api->get('listKRS', 'App\Api\V1\Controllers\Mahasiswa\KrsDataController@listKrs');
        $api->post('addKRS', 'App\Api\V1\Controllers\Mahasiswa\KrsDataController@newKrs');
        $api->post('mDelKrs', 'App\Api\V1\Controllers\Mahasiswa\KrsDataController@delKrs');

        // KHS Controller
        $api->get('listKS', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@listSms');
        $api->get('smsKhs', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@ambilKS');
        $api->put('addKhsNilai/{id}', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@khsNilai');
        $api->post('ipkSMS', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@khsNewIpk');
        $api->get('msKrs/{id}', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@listKrs');
        $api->get('ambilUlang', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@ulangKhs');
        $api->get('krsNilai', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@tipeNilai');
        $api->patch('editNilai/{id}', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@gantiNilai');
        $api->patch('editIPK/{id}', 'App\Api\V1\Controllers\Mahasiswa\KhsDataController@gantiKhs');

        // Grafik Controller
        $api->get('gKrs', 'App\Api\V1\Controllers\Mahasiswa\GrafikController@grapKrs');
        $api->get('gKhs', 'App\Api\V1\Controllers\Mahasiswa\GrafikController@grapKhs');
        $api->get('tSks', 'App\Api\V1\Controllers\Mahasiswa\GrafikController@totalSks');
        $api->get('nilaiRata', 'App\Api\V1\Controllers\Mahasiswa\GrafikController@nRata');
        $api->get('tsKhs', 'App\Api\V1\Controllers\Mahasiswa\GrafikController@totalKhs');

        // Pengaturan KRS
        $api->post('cKrs', 'App\Api\V1\Controllers\Mahasiswa\PengaturanKrsController@cariKrs');
        $api->get('mul', 'App\Api\V1\Controllers\Mahasiswa\PengaturanKrsController@cariUlang');
        $api->get('dKrs', 'App\Api\V1\Controllers\Mahasiswa\PengaturanKrsController@detailKrs');

        // biodata
        $api->get('mbio', 'App\Api\V1\Controllers\Mahasiswa\BiodataController@info')->name('info');
        $api->post('bioNE', 'App\Api\V1\Controllers\Mahasiswa\BiodataController@dataBio')->name('dataBio');
        $api->post('password', 'App\Api\V1\Controllers\Mahasiswa\BiodataController@updatePassword');

        // mimin menu
        $api->get('admin/auth', 'App\Api\V1\Controllers\Auth\MiminController@getAuthUser');
        $api->get('dosen/auth', 'App\Api\V1\Controllers\Auth\DosenController@getAuthUser');
        
        // user dosen dan mahasiswa
        $api->resource('adminDosen', 'App\Api\V1\Controllers\Admin\UserDosenController');
        $api->resource('adminMahasiswa', 'App\Api\V1\Controllers\Admin\UserMahasiswaController');
        $api->get('aDF', 'App\Api\V1\Controllers\Admin\UserDosenController@ambilFakultas');

        // fakultas dan progdi
        $api->resource('adminJurusan', 'App\Api\V1\Controllers\Admin\JurusanController');
        $api->resource('adminFakultas', 'App\Api\V1\Controllers\Admin\FakultasController');
        $api->resource('adminProgdi', 'App\Api\V1\Controllers\Admin\ProgdiController');
        $api->get('adminKrsKurikulum/{jurusan}/{kurikulum}', 'App\Api\V1\Controllers\Admin\AdminMakulController@ambilKrsKurikulum');
        $api->get('adminKurikulum/{id}', 'App\Api\V1\Controllers\Admin\AdminMakulController@ambilKurikulum');
        $api->get('adminAmbilJurusan', 'App\Api\V1\Controllers\Admin\ProgdiController@ambilJurusan');
        $api->get('adminAmbilFakultas', 'App\Api\V1\Controllers\Admin\ProgdiController@ambilFakultas');
        
        // mata kuliah
        $api->get('adminMasterKrs', 'App\Api\V1\Controllers\Admin\AdminMakulController@ambilMasterKrs');
        $api->post('adminAddMakul', 'App\Api\V1\Controllers\Admin\AdminMakulController@tambahMakul');
        $api->patch('adminEditMakul/{id}', 'App\Api\V1\Controllers\Admin\AdminMakulController@editMakul');
        $api->delete('adminHapusMakul/{id}', 'App\Api\V1\Controllers\Admin\AdminMakulController@hapusMakul');

        // lihat detail mahasiswa
        $api->get('adminChartKrs/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@graphKrs');
        $api->get('adminChartKhs/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@graphKhs');
        $api->get('adminChartNilai/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@graphNilai');
        $api->get('adminLihatBiodata/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@lihatBio');
        $api->get('adminLihatKrs/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@lihatKrs');
        $api->get('adminDetailKrs/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@detailKrs');
        $api->get('adminLihatSms/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@lihatSemester');
        $api->get('adminListDetailSms/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@listDetailSms');
        $api->get('adminLihatKrsBelum/{nim}', 'App\Api\V1\Controllers\Admin\DetailMahasiswaController@krsBelumDiambil');
        $api->get('daftarMahasiswa', 'App\Api\V1\Controllers\Admin\UserMahasiswaController@listNim');
        //$api->post('akademik/nim', 'App\Api\V1\Controllers\Admin\UserMahasiswaController@importFile')->name('importFile');

        // dasboard admin
        $api->get('adminFakultasChart', 'App\Api\V1\Controllers\Admin\DashboardController@fakultasChart');


        // dosen
        $api->post('dosen/password', 'App\\Api\\V1\\Controllers\\Auth\\DosenController@changePass');
        $api->get('dosen/perwalian', 'App\Api\V1\Controllers\Auth\DosenController@dosPer');
        $api->get('dosenLihatKrs/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@lihatKrs');
        $api->get('dosenLihatBiodata/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@lihatBio');
        $api->get('dosenChartKhs/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@graphKhs');
        $api->get('dosenChartKrs/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@graphKrs');
        $api->get('dosenChartNilai/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@graphNilai');
        $api->get('dosenLihatSms/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@lihatSemester');
        $api->get('dosenLihatKrsBelum/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@krsBelumDiambil');
        $api->get('dosenDetailKrs/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@detailKrs');
        $api->get('dosenListDetailSms/{nim}', 'App\Api\V1\Controllers\Auth\DosenController@listDetailSms');
        $api->patch('dosenMEdit/{id}', 'App\Api\V1\Controllers\Auth\DosenController@mEdit');

    });

    $api->get('cekNim/{nim}', 'App\Api\V1\Controllers\Mahasiswa\AkademikController@cekNim');
    $api->get('adminAmbilJurusan', 'App\Api\V1\Controllers\Mahasiswa\AkademikController@ambilJurusan');
    $api->get('adminAmbilKategori', 'App\Api\V1\Controllers\Mahasiswa\AkademikController@ambilKategori');
    $api->get('mDaftarFakultas', 'App\Api\V1\Controllers\Mahasiswa\AkademikController@ambilFakultas');
    $api->get('mDaftarProgdi/{fakultas}', 'App\Api\V1\Controllers\Mahasiswa\AkademikController@ambilProgdi');
    $api->get('mDaftarWali/{fakultas}', 'App\Api\V1\Controllers\Mahasiswa\AkademikController@ambilWali');

    $api->resource('infoAka', 'App\Api\V1\Controllers\Mahasiswa\InfoAkaController');
    $api->post('akademik/nim', 'App\Api\V1\Controllers\Admin\UserMahasiswaController@importFile')->name('importFile');

});
