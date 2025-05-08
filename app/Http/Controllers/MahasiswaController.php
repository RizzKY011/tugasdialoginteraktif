<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // Menampilkan semua mahasiswa
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('dashboard', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|numeric|unique:mahasiswa,nim',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('dashboard')->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    // Menampilkan form edit mahasiswa
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('edit', compact('mahasiswa'));
    }

    // Update data mahasiswa
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|numeric|unique:mahasiswa,nim,' . $id,
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($validated);

        return redirect()->route('dashboard')->with('success', 'Mahasiswa berhasil diupdate');
    }

    // Menghapus mahasiswa
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus');
    }
}


