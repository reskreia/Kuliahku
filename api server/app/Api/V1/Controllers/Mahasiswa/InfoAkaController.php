<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InfoAka;

class InfoAkaController extends Controller
{
    public function index()
    {
        $data = InfoAka::select('judul', 'deskripsi', 'created_at')
                ->orderBy('created_at', 'desc')
                ->paginate(7);
        
        return $data->toArray();
    }
    
    public function show($id)
    {
		return Post::find($id);
    }
}
