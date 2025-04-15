<?php

namespace App\Http\Controllers;

use App\Models\Panorama;
use Illuminate\Http\Request;
use App\Models\sekolah;

class sekolahController extends Controller
{
    public function dashboard()
    {
        $sekolah = sekolah::all();
        return view('sekolah.sekolah', compact('sekolah'));
    }

    public function index()
    {
        return view('admin.sekolah');
    }
    public function index2()
    {
        return view('admin.panorama');
    }

    public function index3()
    {
        return view('admin.users');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function panorama()
    {
        $panorama = Panorama::all();
        return view('panorama.panorama', compact('panorama'));
    }
}
