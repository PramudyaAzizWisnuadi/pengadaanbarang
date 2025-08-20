<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Departemen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Check if user has super admin access
     */
    private function checkSuperAdminAccess()
    {
        if (!Auth::user() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses manajemen user.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('departemenRelation');

            // Super admin bisa lihat semua user
            if (Auth::user()->role !== 'super_admin') {
                // Admin dan user hanya bisa lihat user di departemen yang sama
                $query->where('departemen', Auth::user()->departemen);
            }

            return datatables($query)
                ->addIndexColumn()
                ->addColumn('departemen_info', function ($user) {
                    if ($user->departemenRelation) {
                        return '<span class="badge bg-primary">' . $user->departemenRelation->kode_departemen . '</span><br>' .
                            '<small>' . $user->departemenRelation->nama_departemen . '</small>';
                    } else {
                        return '<span class="badge bg-secondary">-</span><br>' .
                            '<small>' . ($user->departemen ?? 'Tidak ada departemen') . '</small>';
                    }
                })
                ->addColumn('role_badge', function ($user) {
                    if ($user->role === 'super_admin') {
                        return '<span class="badge bg-danger">Super Admin</span>';
                    } elseif ($user->role === 'admin') {
                        return '<span class="badge bg-warning">Admin</span>';
                    } else {
                        return '<span class="badge bg-success">User</span>';
                    }
                })
                ->addColumn('created_date', function ($user) {
                    return $user->created_at->format('d/m/Y');
                })
                ->addColumn('action', function ($user) {
                    $actions = '<div class="btn-group btn-group-sm" role="group">';

                    $actions .= '<a href="' . route('users.show', $user->id) . '" class="btn btn-outline-info" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>';

                    $actions .= '<a href="' . route('users.edit', $user->id) . '" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>';

                    // Jangan tampilkan tombol hapus untuk user sendiri
                    if ($user->id !== Auth::id()) {
                        $actions .= '<button type="button" class="btn btn-outline-danger delete-user"
                                            data-id="' . $user->id . '" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>';
                    }

                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['departemen_info', 'role_badge', 'action'])
                ->make(true);
        }

        // Return view for non-AJAX requests
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkSuperAdminAccess();
        $departemens = Departemen::where('is_active', true)->orderBy('nama_departemen')->get();
        return view('users.create', compact('departemens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkSuperAdminAccess();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'jabatan' => 'required|string|max:255',
            'departemen_id' => 'required|exists:departemens,id',
            'departemen' => 'required|string|max:255',
            'role' => 'required|in:user,admin,super_admin',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'departemen_id' => $request->departemen_id,
            'departemen' => $request->departemen,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->checkSuperAdminAccess();
        $user->load('departemenRelation');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->checkSuperAdminAccess();
        $departemens = Departemen::where('is_active', true)->orderBy('nama_departemen')->get();
        return view('users.edit', compact('user', 'departemens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->checkSuperAdminAccess();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jabatan' => 'required|string|max:255',
            'departemen_id' => 'required|exists:departemens,id',
            'departemen' => 'required|string|max:255',
            'role' => 'required|in:user,admin,super_admin',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'departemen_id' => $request->departemen_id,
            'departemen' => $request->departemen,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->checkSuperAdminAccess();

        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun sendiri'
                ]);
            }
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
