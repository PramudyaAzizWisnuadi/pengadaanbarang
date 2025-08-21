<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departemens = Departemen::withCount(['users', 'pengadaanBarangs', 'kategoriBarangs'])
            ->orderBy('nama_departemen')
            ->get();

        return view('departemen.index', compact('departemens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departemen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_departemen' => 'required|string|max:10|unique:departemens,kode_departemen',
            'nama_departemen' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        Departemen::create([
            'kode_departemen' => $request->kode_departemen,
            'nama_departemen' => $request->nama_departemen,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departemen $departemen)
    {
        $departemen->load(['users', 'pengadaanBarangs', 'kategoriBarangs']);
        return view('departemen.show', compact('departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departemen $departemen)
    {
        return view('departemen.edit', compact('departemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departemen $departemen)
    {
        $request->validate([
            'kode_departemen' => 'required|string|max:10|unique:departemens,kode_departemen,' . $departemen->id,
            'nama_departemen' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        $departemen->update([
            'kode_departemen' => $request->kode_departemen,
            'nama_departemen' => $request->nama_departemen,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departemen $departemen)
    {
        // Check if departemen has users or pengadaan
        if ($departemen->users()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Tidak dapat menghapus departemen yang masih memiliki user');
        }

        if ($departemen->pengadaanBarangs()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Tidak dapat menghapus departemen yang masih memiliki pengadaan');
        }

        $departemen->delete();

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil dihapus');
    }
}
