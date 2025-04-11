<?php

namespace App\Http\Controllers;

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

    public function login()
    {
        return view('auth.login');
    }

}
