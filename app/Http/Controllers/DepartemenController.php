<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    /**
     * Check if user has super admin access
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
    public function index(Request $request)
    {
        $this->checkSuperAdminAccess();

        // If AJAX request for DataTables
        if ($request->ajax()) {
            $departemens = Departemen::withCount(['users', 'pengadaanBarangs', 'kategoriBarangs'])->get();

            return DataTables::of($departemens)
                ->addIndexColumn()
                ->addColumn('action', function ($departemen) {
                    $btn = '<div class="btn-group btn-group-sm" role="group">';
                    $btn .= '<button type="button" class="btn btn-outline-info view-departemen" data-id="' . $departemen->id . '" title="Lihat Detail"><i class="bi bi-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-outline-warning edit-departemen" data-id="' . $departemen->id . '" title="Edit"><i class="bi bi-pencil"></i></button>';
                    $btn .= '<button type="button" class="btn btn-outline-danger delete-departemen" data-id="' . $departemen->id . '" title="Hapus"><i class="bi bi-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('kode_badge', function ($departemen) {
                    return '<span class="badge bg-secondary">' . $departemen->kode_departemen . '</span>';
                })
                ->addColumn('users_badge', function ($departemen) {
                    return '<span class="badge bg-info">' . $departemen->users_count . '</span>';
                })
                ->addColumn('pengadaan_badge', function ($departemen) {
                    return '<span class="badge bg-warning">' . $departemen->pengadaan_barangs_count . '</span>';
                })
                ->addColumn('kategori_badge', function ($departemen) {
                    return '<span class="badge bg-success">' . $departemen->kategori_barangs_count . '</span>';
                })
                ->addColumn('status_badge', function ($departemen) {
                    if ($departemen->is_active) {
                        return '<span class="badge bg-success">Aktif</span>';
                    } else {
                        return '<span class="badge bg-danger">Nonaktif</span>';
                    }
                })
                ->rawColumns(['action', 'kode_badge', 'users_badge', 'pengadaan_badge', 'kategori_badge', 'status_badge'])
                ->make(true);
        }

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkSuperAdminAccess();

        $request->validate([
            'kode_departemen' => 'required|string|max:10|unique:departemens,kode_departemen',
            'nama_departemen' => 'required|string|max:100',
            'kepala_departemen' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $departemen = Departemen::create([
                'kode_departemen' => $request->kode_departemen,
                'nama_departemen' => $request->nama_departemen,
                'kepala_departemen' => $request->kepala_departemen,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil ditambahkan',
                'data' => $departemen
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan departemen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Departemen $departemen)
    {
        $this->checkSuperAdminAccess();

        if ($request->ajax()) {
            $departemen->load(['users', 'pengadaanBarangs', 'kategoriBarangs']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $departemen->id,
                    'kode_departemen' => $departemen->kode_departemen,
                    'nama_departemen' => $departemen->nama_departemen,
                    'kepala_departemen' => $departemen->kepala_departemen,
                    'deskripsi' => $departemen->deskripsi,
                    'is_active' => $departemen->is_active,
                    'created_at' => $departemen->created_at,
                    'updated_at' => $departemen->updated_at,
                    'users_count' => $departemen->users->count(),
                    'pengadaan_count' => $departemen->pengadaanBarangs->count(),
                    'kategori_count' => $departemen->kategoriBarangs->count()
                ]
            ]);
        }

        return view('departemen.show', compact('departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departemen $departemen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departemen $departemen)
    {
        $this->checkSuperAdminAccess();

        $request->validate([
            'kode_departemen' => 'required|string|max:10|unique:departemens,kode_departemen,' . $departemen->id,
            'nama_departemen' => 'required|string|max:100',
            'kepala_departemen' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $departemen->update([
                'kode_departemen' => $request->kode_departemen,
                'nama_departemen' => $request->nama_departemen,
                'kepala_departemen' => $request->kepala_departemen,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil diupdate',
                'data' => $departemen
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate departemen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Departemen $departemen)
    {
        $this->checkSuperAdminAccess();

        try {
            // Check if departemen has related data
            $hasUsers = $departemen->users()->count() > 0;
            $hasPengadaan = $departemen->pengadaanBarangs()->count() > 0;
            $hasKategori = $departemen->kategoriBarangs()->count() > 0;

            if ($hasUsers || $hasPengadaan || $hasKategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Departemen tidak dapat dihapus karena masih memiliki data terkait (users, pengadaan, atau kategori)'
                ], 400);
            }

            $departemen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Departemen berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus departemen: ' . $e->getMessage()
            ], 500);
        }
    }
}
