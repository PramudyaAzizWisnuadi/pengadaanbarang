<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBarang;
use App\Models\BarangPengadaan;
use App\Models\KategoriBarang;
use App\Exports\PengadaanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PengadaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Base query with department filtering
        $baseQuery = PengadaanBarang::query();

        // Only filter by department if user is not super admin
        if (Auth::user() && Auth::user()->departemen_id && Auth::user()->role !== 'super_admin') {
            $baseQuery->where('departemen_id', Auth::user()->departemen_id);
        }

        // Super admin filters
        if (Auth::user()->role === 'super_admin') {
            // Filter by departemen
            if ($request->filled('departemen')) {
                $baseQuery->where('departemen', $request->departemen);
            }

            // Filter by status
            if ($request->filled('status')) {
                $baseQuery->where('status', $request->status);
            }

            // Filter by tanggal pengajuan
            if ($request->filled('tanggal')) {
                $baseQuery->whereDate('tanggal_pengajuan', $request->tanggal);
            }
        }

        // Fetch pengadaan data for blade template
        $pengadaans = (clone $baseQuery)
            ->with(['barangPengadaan', 'departemen'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics efficiently
        $statistics = (clone $baseQuery)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "draft" THEN 1 ELSE 0 END) as draft,
                SUM(CASE WHEN status = "submitted" THEN 1 ELSE 0 END) as submitted,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
            ')
            ->first();

        return view('pengadaan.index', compact('pengadaans', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Filter categories by user's department if they have one
        $kategoris = KategoriBarang::active()->orderBy('nama_kategori');

        // Only filter by department if user is not super admin
        if (Auth::user() && Auth::user()->departemen_id && Auth::user()->role !== 'super_admin') {
            $kategoris->where('departemen_id', Auth::user()->departemen_id);
        }

        $kategoris = $kategoris->get();

        return view('pengadaan.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'tanggal_dibutuhkan' => 'required|date|after:today',
            'skip_approval' => 'sometimes|boolean',
            'alasan_skip_approval' => 'nullable|required_if:skip_approval,true|string|max:500',
            'barang' => 'required|array|min:1',
            'barang.*.kategori_barang_id' => 'required|exists:kategori_barangs,id',
            'barang.*.nama_barang' => 'required|string|max:255',
            'barang.*.spesifikasi' => 'required|string',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.satuan' => 'required|string|max:50',
            'barang.*.harga_estimasi' => 'required|numeric|min:0',
            'barang.*.prioritas' => 'required|integer|in:1,2,3',
        ]);

        DB::transaction(function () use ($request) {
            // Determine status based on skip_approval
            $status = 'draft';
            if ($request->skip_approval) {
                $status = 'approved'; // Langsung approved jika skip approval
            }

            // Determine departemen_id based on user role
            $departemenId = Auth::user()->departemen_id;
            if (Auth::user()->role === 'super_admin') {
                // For super admin, find departemen_id based on selected departemen name
                $departemen = \App\Models\Departemen::where('nama_departemen', $request->departemen)->first();
                $departemenId = $departemen ? $departemen->id : null;
            }

            // Create pengadaan
            $pengadaan = PengadaanBarang::create([
                'kode_pengadaan' => PengadaanBarang::generateKodePengadaan(),
                'user_id' => Auth::id(),
                'departemen_id' => $departemenId,
                'nama_pemohon' => $request->nama_pemohon,
                'jabatan' => $request->jabatan,
                'departemen' => $request->departemen,
                'keterangan' => $request->keterangan,
                'tanggal_pengajuan' => now()->toDateString(),
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
                'status' => $status,
                'skip_approval' => $request->skip_approval ?? false,
                'alasan_skip_approval' => $request->skip_approval ? $request->alasan_skip_approval : null,
                'tanggal_approval' => $request->skip_approval ? now() : null,
                'approved_by' => $request->skip_approval ? Auth::id() : null,
            ]);

            $totalEstimasi = 0;

            // Create barang items
            foreach ($request->barang as $barangData) {
                $totalHarga = $barangData['jumlah'] * $barangData['harga_estimasi'];
                $totalEstimasi += $totalHarga;

                BarangPengadaan::create([
                    'pengadaan_barang_id' => $pengadaan->id,
                    'kategori_barang_id' => $barangData['kategori_barang_id'],
                    'nama_barang' => $barangData['nama_barang'],
                    'spesifikasi' => $barangData['spesifikasi'],
                    'merk' => $barangData['merk'] ?? null,
                    'jumlah' => $barangData['jumlah'],
                    'satuan' => $barangData['satuan'],
                    'harga_estimasi' => $barangData['harga_estimasi'],
                    'total_harga' => $totalHarga,
                    'keterangan' => $barangData['keterangan'] ?? null,
                    'prioritas' => $barangData['prioritas'],
                ]);
            }

            // Update total estimasi
            $pengadaan->update(['total_estimasi' => $totalEstimasi]);
        });

        return redirect()->route('pengadaan.index')
            ->with('success', 'Pengadaan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengadaanBarang $pengadaan)
    {
        $pengadaan->load(['barangPengadaan.kategoriBarang', 'user', 'approvedBy']);
        return view('pengadaan.show', compact('pengadaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengadaanBarang $pengadaan)
    {
        if ($pengadaan->status !== 'draft') {
            return redirect()->route('pengadaan.show', $pengadaan)
                ->with('error', 'Pengadaan hanya dapat diedit pada status draft');
        }

        // Filter categories by user's department if they have one
        $kategoris = KategoriBarang::active()->orderBy('nama_kategori');

        // Only filter by department if user is not super admin
        if (Auth::user() && Auth::user()->departemen_id && Auth::user()->role !== 'super_admin') {
            $kategoris->where('departemen_id', Auth::user()->departemen_id);
        }

        $kategoris = $kategoris->get();
        $pengadaan->load('barangPengadaan');

        return view('pengadaan.edit', compact('pengadaan', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengadaanBarang $pengadaan)
    {
        if ($pengadaan->status !== 'draft') {
            return redirect()->route('pengadaan.show', $pengadaan)
                ->with('error', 'Pengadaan hanya dapat diedit pada status draft');
        }

        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'tanggal_dibutuhkan' => 'required|date|after:today',
            'barang' => 'required|array|min:1',
            'barang.*.kategori_barang_id' => 'required|exists:kategori_barangs,id',
            'barang.*.nama_barang' => 'required|string|max:255',
            'barang.*.spesifikasi' => 'required|string',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.satuan' => 'required|string|max:50',
            'barang.*.harga_estimasi' => 'required|numeric|min:0',
            'barang.*.prioritas' => 'required|integer|in:1,2,3',
        ]);

        DB::transaction(function () use ($request, $pengadaan) {
            // Update pengadaan
            $pengadaan->update([
                'nama_pemohon' => $request->nama_pemohon,
                'jabatan' => $request->jabatan,
                'departemen' => $request->departemen,
                'keterangan' => $request->keterangan,
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
            ]);

            // Delete existing barang items
            $pengadaan->barangPengadaan()->delete();

            $totalEstimasi = 0;

            // Create new barang items
            foreach ($request->barang as $barangData) {
                $totalHarga = $barangData['jumlah'] * $barangData['harga_estimasi'];
                $totalEstimasi += $totalHarga;

                BarangPengadaan::create([
                    'pengadaan_barang_id' => $pengadaan->id,
                    'kategori_barang_id' => $barangData['kategori_barang_id'],
                    'nama_barang' => $barangData['nama_barang'],
                    'spesifikasi' => $barangData['spesifikasi'],
                    'merk' => $barangData['merk'] ?? null,
                    'jumlah' => $barangData['jumlah'],
                    'satuan' => $barangData['satuan'],
                    'harga_estimasi' => $barangData['harga_estimasi'],
                    'total_harga' => $totalHarga,
                    'keterangan' => $barangData['keterangan'] ?? null,
                    'prioritas' => $barangData['prioritas'],
                ]);
            }

            // Update total estimasi
            $pengadaan->update(['total_estimasi' => $totalEstimasi]);
        });

        return redirect()->route('pengadaan.show', $pengadaan)
            ->with('success', 'Pengadaan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengadaanBarang $pengadaan)
    {
        if ($pengadaan->status !== 'draft') {
            return redirect()->route('pengadaan.index')
                ->with('error', 'Pengadaan hanya dapat dihapus pada status draft');
        }

        $pengadaan->delete();

        return redirect()->route('pengadaan.index')
            ->with('success', 'Pengadaan berhasil dihapus');
    }

    /**
     * Submit pengadaan for approval
     */
    public function submit(PengadaanBarang $pengadaan)
    {
        if ($pengadaan->status !== 'draft') {
            return redirect()->route('pengadaan.show', $pengadaan)
                ->with('error', 'Pengadaan sudah disubmit sebelumnya');
        }

        $pengadaan->update(['status' => 'submitted']);

        return redirect()->route('pengadaan.show', $pengadaan)
            ->with('success', 'Pengadaan berhasil disubmit untuk approval');
    }

    /**
     * Approve pengadaan
     */
    public function approve(Request $request, PengadaanBarang $pengadaan)
    {
        $request->validate([
            'catatan_approval' => 'nullable|string|max:1000',
            'foto_approval' => 'required|image|mimes:jpeg,png,jpg|max:10240' // Max 10MB before compression
        ]);

        if ($pengadaan->status !== 'submitted') {
            return redirect()->route('pengadaan.show', $pengadaan)
                ->with('error', 'Pengadaan tidak dalam status submitted');
        }

        // Compress and save photo
        $imageService = new \App\Services\ImageCompressionService();
        $fotoPath = $imageService->compressImage($request->file('foto_approval'));

        $pengadaan->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'tanggal_approval' => now(),
            'catatan_approval' => $request->catatan_approval,
            'foto_approval' => $fotoPath
        ]);

        return redirect()->route('pengadaan.show', $pengadaan)
            ->with('success', 'Pengadaan berhasil diapprove');
    }

    /**
     * Reject pengadaan
     */
    public function reject(Request $request, PengadaanBarang $pengadaan)
    {
        $request->validate([
            'catatan_approval' => 'required|string'
        ]);

        $pengadaan->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'tanggal_approval' => now(),
            'catatan_approval' => $request->catatan_approval
        ]);

        return redirect()->route('pengadaan.show', $pengadaan)
            ->with('success', 'Pengadaan berhasil direject');
    }

    /**
     * Complete pengadaan (mark as completed)
     */
    public function complete(PengadaanBarang $pengadaan)
    {
        // Jika skip approval, tidak perlu status approved
        if (!$pengadaan->skip_approval && $pengadaan->status !== 'approved') {
            return redirect()->route('pengadaan.show', $pengadaan)
                ->with('error', 'Pengadaan harus diapprove terlebih dahulu');
        }

        $pengadaan->update(['status' => 'completed']);

        return redirect()->route('pengadaan.show', $pengadaan)
            ->with('success', 'Pengadaan berhasil diselesaikan');
    }

    /**
     * Bypass approval untuk pengadaan darurat
     */
    public function bypassApproval(Request $request, PengadaanBarang $pengadaan)
    {
        $request->validate([
            'alasan_skip_approval' => 'required|string|max:500',
            'foto_approval' => 'required|image|mimes:jpeg,png,jpg|max:10240' // Max 10MB before compression
        ]);

        if ($pengadaan->status !== 'submitted') {
            return redirect()->route('pengadaan.show', $pengadaan)
                ->with('error', 'Pengadaan harus dalam status submitted');
        }

        // Compress and save photo
        $imageService = new \App\Services\ImageCompressionService();
        $fotoPath = $imageService->compressImage($request->file('foto_approval'));

        $pengadaan->update([
            'status' => 'approved',
            'skip_approval' => true,
            'alasan_skip_approval' => $request->alasan_skip_approval,
            'approved_by' => Auth::id(),
            'tanggal_approval' => now(),
            'catatan_approval' => 'Disetujui tanpa approval formal: ' . $request->alasan_skip_approval,
            'foto_approval' => $fotoPath
        ]);

        return redirect()->route('pengadaan.show', $pengadaan)
            ->with('success', 'Pengadaan berhasil disetujui tanpa approval formal');
    }

    /**
     * Print pengadaan to PDF
     */
    public function print(PengadaanBarang $pengadaan)
    {
        $pengadaan->load(['barangPengadaan.kategoriBarang', 'user', 'approvedBy']);

        return view('pengadaan.print', compact('pengadaan'));
    }

    /**
     * Show laporan pengadaan
     */
    public function laporan(Request $request)
    {
        // Check if user has permission to view reports
        $currentUser = Auth::user();
        if (!in_array($currentUser->role, ['admin', 'super_admin'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan Super Admin yang dapat melihat laporan.');
        }

        $query = PengadaanBarang::query()->with(['barangPengadaan.kategoriBarang', 'user', 'approvedBy']);

        // Role-based access control: hanya super admin yang bisa melihat semua departemen
        if ($currentUser->role !== 'super_admin') {
            // Admin departemen hanya bisa melihat data departemen mereka sendiri
            if ($currentUser->departemen_id) {
                $userDepartemen = \App\Models\Departemen::find($currentUser->departemen_id);
                if ($userDepartemen) {
                    $query->where('departemen', $userDepartemen->nama_departemen);
                }
            } else {
                // Jika tidak ada departemen_id, fallback ke departemen string
                $query->where('departemen', $currentUser->departemen);
            }
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->where('tanggal_pengajuan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal_pengajuan', '<=', $request->end_date);
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan departemen
        if ($request->filled('departemen') && $request->departemen !== 'all') {
            $query->where('departemen', $request->departemen);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('barangPengadaan', function ($q) use ($request) {
                $q->where('kategori_barang_id', $request->kategori);
            });
        }

        $pengadaans = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        // Statistik untuk laporan
        $statistics = [
            'total_pengadaan' => $pengadaans->count(),
            'total_estimasi' => $pengadaans->sum('total_estimasi'),
            'draft' => $pengadaans->where('status', 'draft')->count(),
            'submitted' => $pengadaans->where('status', 'submitted')->count(),
            'approved' => $pengadaans->where('status', 'approved')->count(),
            'rejected' => $pengadaans->where('status', 'rejected')->count(),
            'completed' => $pengadaans->where('status', 'completed')->count(),
        ];

        // Data untuk filter
        $departemens = PengadaanBarang::distinct('departemen')->pluck('departemen')->sort();

        // Filter kategori berdasarkan departemen user
        $kategorisQuery = KategoriBarang::active()->orderBy('nama_kategori');
        if ($currentUser->role !== 'super_admin') {
            // Admin departemen hanya melihat kategori departemen mereka sendiri
            if ($currentUser->departemen_id) {
                $kategorisQuery->where('departemen_id', $currentUser->departemen_id);
            }
        }
        $kategoris = $kategorisQuery->get();

        $statuses = ['draft', 'submitted', 'approved', 'rejected', 'completed'];

        return view('pengadaan.laporan', compact('pengadaans', 'statistics', 'departemens', 'kategoris', 'statuses'));
    }

    /**
     * Export laporan to Excel/PDF
     */
    public function exportLaporan(Request $request)
    {
        // Check if user has permission to export reports
        $currentUser = Auth::user();
        if (!in_array($currentUser->role, ['admin', 'super_admin'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan Super Admin yang dapat mengexport laporan.');
        }

        $format = $request->get('format', 'excel'); // default excel

        $query = PengadaanBarang::query()->with(['barangPengadaan.kategoriBarang', 'user', 'approvedBy']);

        // Role-based access control: hanya super admin yang bisa melihat semua departemen
        if ($currentUser->role !== 'super_admin') {
            // Admin departemen hanya bisa melihat data departemen mereka sendiri
            if ($currentUser->departemen_id) {
                $userDepartemen = \App\Models\Departemen::find($currentUser->departemen_id);
                if ($userDepartemen) {
                    $query->where('departemen', $userDepartemen->nama_departemen);
                }
            } else {
                // Jika tidak ada departemen_id, fallback ke departemen string
                $query->where('departemen', $currentUser->departemen);
            }
        }

        // Apply same filters as laporan method
        if ($request->filled('start_date')) {
            $query->where('tanggal_pengajuan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal_pengajuan', '<=', $request->end_date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('departemen') && $request->departemen !== 'all') {
            $query->where('departemen', $request->departemen);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('barangPengadaan', function ($q) use ($request) {
                $q->where('kategori_barang_id', $request->kategori);
            });
        }

        $pengadaans = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        $statistics = [
            'total_pengadaan' => $pengadaans->count(),
            'total_estimasi' => $pengadaans->sum('total_estimasi'),
            'draft' => $pengadaans->where('status', 'draft')->count(),
            'submitted' => $pengadaans->where('status', 'submitted')->count(),
            'approved' => $pengadaans->where('status', 'approved')->count(),
            'rejected' => $pengadaans->where('status', 'rejected')->count(),
            'completed' => $pengadaans->where('status', 'completed')->count(),
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($pengadaans, $statistics, $request);
        } else {
            return $this->exportToPdf($pengadaans, $statistics, $request);
        }
    }

    /**
     * Export to Excel (XLSX format)
     */
    private function exportToExcel($pengadaans, $statistics, $request)
    {
        $filename = 'laporan_pengadaan_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new PengadaanExport($pengadaans, $statistics, $request), $filename);
    }

    /**
     * Export to PDF
     */
    private function exportToPdf($pengadaans, $statistics, $request)
    {
        $pdf = Pdf::loadView('pengadaan.export-laporan', compact('pengadaans', 'statistics', 'request'));

        $filename = 'laporan_pengadaan_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print laporan (for direct print without download)
     */
    public function printLaporan(Request $request)
    {
        // Check if user has permission to print reports
        $currentUser = Auth::user();
        if (!in_array($currentUser->role, ['admin', 'super_admin'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan Super Admin yang dapat mencetak laporan.');
        }

        $query = PengadaanBarang::query()->with(['barangPengadaan.kategoriBarang', 'user', 'approvedBy']);

        // Role-based access control: hanya super admin yang bisa melihat semua departemen
        if ($currentUser->role !== 'super_admin') {
            // Admin departemen hanya bisa melihat data departemen mereka sendiri
            if ($currentUser->departemen_id) {
                $userDepartemen = \App\Models\Departemen::find($currentUser->departemen_id);
                if ($userDepartemen) {
                    $query->where('departemen', $userDepartemen->nama_departemen);
                }
            } else {
                // Jika tidak ada departemen_id, fallback ke departemen string
                $query->where('departemen', $currentUser->departemen);
            }
        }

        // Apply same filters as laporan method
        if ($request->filled('start_date')) {
            $query->where('tanggal_pengajuan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal_pengajuan', '<=', $request->end_date);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('departemen') && $request->departemen !== 'all') {
            $query->where('departemen', $request->departemen);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('barangPengadaan', function ($q) use ($request) {
                $q->where('kategori_barang_id', $request->kategori);
            });
        }

        $pengadaans = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        $statistics = [
            'total_pengadaan' => $pengadaans->count(),
            'total_estimasi' => $pengadaans->sum('total_estimasi'),
            'draft' => $pengadaans->where('status', 'draft')->count(),
            'submitted' => $pengadaans->where('status', 'submitted')->count(),
            'approved' => $pengadaans->where('status', 'approved')->count(),
            'rejected' => $pengadaans->where('status', 'rejected')->count(),
            'completed' => $pengadaans->where('status', 'completed')->count(),
        ];

        return view('pengadaan.export-laporan', compact('pengadaans', 'statistics', 'request'));
    }

    /**
     * Show statistik pengadaan (halaman terpisah)
     */
    public function statistik(Request $request)
    {
        // Check if user has permission to view statistics
        $currentUser = Auth::user();
        if (!in_array($currentUser->role, ['admin', 'super_admin'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan Super Admin yang dapat melihat statistik.');
        }

        $query = PengadaanBarang::query()->with(['barangPengadaan.kategoriBarang', 'user', 'approvedBy']);

        // Role-based access control: hanya super admin yang bisa melihat semua departemen
        if ($currentUser->role !== 'super_admin') {
            // Admin departemen hanya bisa melihat data departemen mereka sendiri
            if ($currentUser->departemen_id) {
                $userDepartemen = \App\Models\Departemen::find($currentUser->departemen_id);
                if ($userDepartemen) {
                    $query->where('departemen', $userDepartemen->nama_departemen);
                }
            } else {
                // Jika tidak ada departemen_id, fallback ke departemen string
                $query->where('departemen', $currentUser->departemen);
            }
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->where('tanggal_pengajuan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('tanggal_pengajuan', '<=', $request->end_date);
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan departemen
        if ($request->filled('departemen') && $request->departemen !== 'all') {
            $query->where('departemen', $request->departemen);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('barangPengadaan', function ($q) use ($request) {
                $q->where('kategori_barang_id', $request->kategori);
            });
        }

        $pengadaans = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        // Statistik untuk halaman statistik
        $statistics = [
            'total_pengadaan' => $pengadaans->count(),
            'total_estimasi' => $pengadaans->sum('total_estimasi'),
            'draft' => $pengadaans->where('status', 'draft')->count(),
            'submitted' => $pengadaans->where('status', 'submitted')->count(),
            'approved' => $pengadaans->where('status', 'approved')->count(),
            'rejected' => $pengadaans->where('status', 'rejected')->count(),
            'completed' => $pengadaans->where('status', 'completed')->count(),
        ];

        // Statistik tingkat persetujuan
        $totalSubmitted = $statistics['submitted'] + $statistics['approved'] + $statistics['rejected'] + $statistics['completed'];
        $statistics['approval_rate'] = $totalSubmitted > 0 ? (($statistics['approved'] + $statistics['completed']) / $totalSubmitted) * 100 : 0;

        // Statistik per departemen
        $departmenStats = $pengadaans->groupBy('departemen')->map(function ($items, $dept) {
            return [
                'departemen' => $dept,
                'total' => $items->count(),
                'approved' => $items->where('status', 'approved')->count() + $items->where('status', 'completed')->count(),
                'pending' => $items->where('status', 'submitted')->count(),
                'rejected' => $items->where('status', 'rejected')->count(),
                'total_estimasi' => $items->sum('total_estimasi'),
            ];
        });

        // Statistik per bulan (6 bulan terakhir)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthPengadaans = $pengadaans->filter(function ($item) use ($date) {
                return $item->tanggal_pengajuan->format('Y-m') === $date->format('Y-m');
            });

            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'total' => $monthPengadaans->count(),
                'approved' => $monthPengadaans->where('status', 'approved')->count() + $monthPengadaans->where('status', 'completed')->count(),
                'rejected' => $monthPengadaans->where('status', 'rejected')->count(),
                'total_estimasi' => $monthPengadaans->sum('total_estimasi'),
            ];
        }

        // Data untuk filter
        $departemens = PengadaanBarang::distinct('departemen')->pluck('departemen')->sort();

        // Filter kategori berdasarkan departemen user
        $kategorisQuery = KategoriBarang::active()->orderBy('nama_kategori');
        if ($currentUser->role !== 'super_admin') {
            // Admin departemen hanya melihat kategori departemen mereka sendiri
            if ($currentUser->departemen_id) {
                $kategorisQuery->where('departemen_id', $currentUser->departemen_id);
            }
        }
        $kategoris = $kategorisQuery->get();

        $statuses = ['draft', 'submitted', 'approved', 'rejected', 'completed'];

        return view('pengadaan.statistik', compact('statistics', 'departmenStats', 'monthlyStats', 'departemens', 'kategoris', 'statuses'));
    }
}
