<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Build base query with department filtering
        $baseQuery = KategoriBarang::query();

        // Only filter by department if user is not super admin
        if (Auth::user() && Auth::user()->departemen_id && Auth::user()->role !== 'super_admin') {
            $baseQuery->where('departemen_id', Auth::user()->departemen_id);
        }

        // If AJAX request for DataTables
        if ($request->ajax()) {
            $kategoris = (clone $baseQuery)->withCount('barangPengadaan')
                ->with('departemen')
                ->orderBy('nama_kategori')
                ->get();

            return DataTables::of($kategoris)
                ->addIndexColumn()
                ->addColumn('action', function ($kategori) {
                    $btn = '<div class="btn-group btn-group-sm" role="group">';
                    $btn .= '<a href="' . route('kategori.show', $kategori) . '" class="btn btn-outline-primary" title="Lihat Detail"><i class="bi bi-eye"></i></a>';
                    $btn .= '<a href="' . route('kategori.edit', $kategori) . '" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>';

                    // Toggle status button
                    if ($kategori->is_active) {
                        $btn .= '<button type="button" class="btn btn-outline-secondary toggle-status" data-id="' . $kategori->id . '" title="Nonaktifkan"><i class="bi bi-eye-slash"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-outline-success toggle-status" data-id="' . $kategori->id . '" title="Aktifkan"><i class="bi bi-eye"></i></button>';
                    }

                    // Delete button
                    if ($kategori->barang_pengadaan_count == 0) {
                        $btn .= '<button type="button" class="btn btn-outline-danger delete-kategori" data-id="' . $kategori->id . '" title="Hapus"><i class="bi bi-trash"></i></button>';
                    } else {
                        $btn .= '<button type="button" class="btn btn-outline-danger disabled" title="Tidak dapat dihapus karena masih digunakan"><i class="bi bi-trash"></i></button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('status', function ($kategori) {
                    if ($kategori->is_active) {
                        return '<span class="badge bg-success">Aktif</span>';
                    } else {
                        return '<span class="badge bg-secondary">Nonaktif</span>';
                    }
                })
                ->addColumn('pengadaan_count', function ($kategori) {
                    return '<span class="badge bg-info">' . $kategori->barang_pengadaan_count . '</span>';
                })
                ->addColumn('created_date', function ($kategori) {
                    return $kategori->created_at->format('d/m/Y');
                })
                ->editColumn('deskripsi', function ($kategori) {
                    return Str::limit($kategori->deskripsi, 50) ?: '-';
                })
                ->rawColumns(['action', 'status', 'pengadaan_count'])
                ->make(true);
        }

        // Get statistics for cards with department filtering
        $totalKategoris = (clone $baseQuery)->count();
        $kategoriAktif = (clone $baseQuery)->where('is_active', true)->count();
        $kategoriNonaktif = (clone $baseQuery)->where('is_active', false)->count();
        $totalPengadaan = (clone $baseQuery)->withCount('barangPengadaan')->get()->sum('barang_pengadaan_count');

        return view('kategori.index', compact('totalKategoris', 'kategoriAktif', 'kategoriNonaktif', 'totalPengadaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departemens = null;

        // If super admin, get all departments for selection
        if (Auth::user() && Auth::user()->role === 'super_admin') {
            $departemens = \App\Models\Departemen::orderBy('nama_departemen')->get();
        }

        return view('kategori.create', compact('departemens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'nama_kategori' => 'required|string|max:255|unique:kategori_barangs',
            'deskripsi' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean'
        ];

        // Add departemen validation for super admin
        if (Auth::user() && Auth::user()->role === 'super_admin') {
            $validationRules['departemen_id'] = 'required|exists:departemens,id';
        }

        $request->validate($validationRules);

        // Determine departemen_id
        $departemenId = Auth::user()->departemen_id;
        if (Auth::user()->role === 'super_admin') {
            $departemenId = $request->departemen_id;
        }

        KategoriBarang::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'departemen_id' => $departemenId,
            'is_active' => $request->boolean('is_active', false)
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriBarang $kategori)
    {
        $kategori->load(['barangPengadaan.pengadaanBarang']);
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriBarang $kategori)
    {
        $departemens = null;

        // If super admin, get all departments for selection
        if (Auth::user() && Auth::user()->role === 'super_admin') {
            $departemens = \App\Models\Departemen::orderBy('nama_departemen')->get();
        }

        return view('kategori.edit', compact('kategori', 'departemens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriBarang $kategori)
    {
        $validationRules = [
            'nama_kategori' => 'required|string|max:255|unique:kategori_barangs,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean'
        ];

        // Add departemen validation for super admin
        if (Auth::user() && Auth::user()->role === 'super_admin') {
            $validationRules['departemen_id'] = 'required|exists:departemens,id';
        }

        $request->validate($validationRules);

        $updateData = [
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', false)
        ];

        // Add departemen_id if super admin
        if (Auth::user()->role === 'super_admin') {
            $updateData['departemen_id'] = $request->departemen_id;
        }

        $kategori->update($updateData);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, KategoriBarang $kategori)
    {
        // Cek apakah kategori masih digunakan
        if ($kategori->barangPengadaan()->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan dalam pengadaan'
                ]);
            }
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan dalam pengadaan');
        }

        $kategori->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        }

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, KategoriBarang $kategori)
    {
        $kategori->update(['is_active' => !$kategori->is_active]);

        $status = $kategori->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Kategori berhasil {$status}"
            ]);
        }

        return redirect()->route('kategori.index')
            ->with('success', "Kategori berhasil {$status}");
    }

    /**
     * Get kategori by departemen name for AJAX
     */
    public function getKategoriByDepartemen($departemenName)
    {
        $departemen = \App\Models\Departemen::where('nama_departemen', $departemenName)->first();

        if (!$departemen) {
            return response()->json([]);
        }

        $kategoris = KategoriBarang::where('departemen_id', $departemen->id)
            ->where('is_active', true)
            ->orderBy('nama_kategori')
            ->select('id', 'nama_kategori')
            ->get();

        return response()->json($kategoris);
    }
}
