<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartemenController extends Controller
{
    /**
     * Check if user is super admin
     */
    private function checkSuperAdminAccess()
    {
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses manajemen departemen.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkSuperAdminAccess();
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
        $this->checkSuperAdminAccess();
        return view('departemen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkSuperAdminAccess();
        $request->validate([
            'nama_departemen' => 'required|string|max:255|unique:departemens',
            'kode_departemen' => 'required|string|max:10|unique:departemens',
            'deskripsi' => 'nullable|string',
            'kepala_departemen' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        Departemen::create($request->all());

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departemen $departemen)
    {
        $this->checkSuperAdminAccess();
        $departemen->load(['users', 'pengadaanBarangs', 'kategoriBarangs']);

        return view('departemen.show', compact('departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departemen $departemen)
    {
        $this->checkSuperAdminAccess();
        return view('departemen.edit', compact('departemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departemen $departemen)
    {
        $this->checkSuperAdminAccess();
        $request->validate([
            'nama_departemen' => 'required|string|max:255|unique:departemens,nama_departemen,' . $departemen->id,
            'kode_departemen' => 'required|string|max:10|unique:departemens,kode_departemen,' . $departemen->id,
            'deskripsi' => 'nullable|string',
            'kepala_departemen' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $departemen->update($request->all());

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departemen $departemen)
    {
        $this->checkSuperAdminAccess();
        if ($departemen->users()->count() > 0 || $departemen->pengadaanBarangs()->count() > 0) {
            return redirect()->route('departemen.index')
                ->with('error', 'Departemen tidak dapat dihapus karena masih memiliki data terkait');
        }

        $departemen->delete();

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil dihapus');
    }
}
