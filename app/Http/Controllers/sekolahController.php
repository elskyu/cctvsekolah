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
        $sekolah = sekolah::all();
        return view('sekolah.index', compact('sekolah'));
    }

    public function create()
    {
        return view('sekolah.create');
    }

    public function store(Request $request)
    {
        $sekolah = new sekolah;
        $sekolah->namaWilayah = $request->namaWilayah;
        $sekolah->namaSekolah = $request->namaSekolah;
        $sekolah->namaTitik = $request->namaTitik;
        $sekolah->link = $request->link;
        $sekolah->save();
        return redirect()->route('sekolah.index');
    }

    public function edit($id)
    {
        $sekolah = sekolah::find($id);
        return view('sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = sekolah::find($id);
        $sekolah->namaWilayah = $request->namaWilayah;
        $sekolah->namaSekolah = $request->namaSekolah;
        $sekolah->namaTitik = $request->namaTitik;
        $sekolah->link = $request->link;
        $sekolah->save();
        return redirect()->route('sekolah.index');
    }

    public function delete($id)
    {
        $sekolah = sekolah::find($id);
        $sekolah->delete();
        return redirect()->route('sekolah.index');
    }

    public function checkDuplicate(Request $request)
    {
        $field = $request->get('field'); // namaTitik atau link
        $value = $request->get('value');
        $exists = sekolah::where($field, $value)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function getWilayah()
    {
        $sekolah = sekolah::select('namaWilayah')->distinct()->get(); // Ambil hanya kolom namaWilayah
        return response()->json($sekolah);
    }
}
