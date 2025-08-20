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
        $this->checkSuperAdminAccess();

        // If AJAX request for DataTables
        if ($request->ajax()) {
            $users = User::with('departemenRelation')->latest()->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    $btn = '<div class="btn-group btn-group-sm" role="group">';
                    $btn .= '<a href="' . route('users.show', $user) . '" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>';
                    $btn .= '<a href="' . route('users.edit', $user) . '" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>';

                    if ($user->id !== Auth::id()) {
                        $btn .= '<button type="button" class="btn btn-outline-danger delete-user" data-id="' . $user->id . '" title="Hapus"><i class="bi bi-trash"></i></button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('departemen_info', function ($user) {
                    // Jika ada relationship departemen yang dimuat dan departemen_id ada
                    if ($user->departemen_id && $user->relationLoaded('departemenRelation') && $user->departemenRelation) {
                        return '<span class="badge bg-primary">' . $user->departemenRelation->kode_departemen . '</span><br><small>' . $user->departemenRelation->nama_departemen . '</small>';
                    }
                    // Jika hanya ada string departemen (data lama)
                    elseif (!empty($user->getAttributes()['departemen'])) {
                        return '<span class="badge bg-secondary">-</span><br><small>' . $user->getAttributes()['departemen'] . '</small>';
                    }
                    return '<span class="badge bg-secondary">-</span><br><small>Tidak ada departemen</small>';
                })
                ->addColumn('is_current_user', function ($user) {
                    if ($user->id === Auth::id()) {
                        return '<span class="badge bg-info">Anda</span>';
                    }
                    return '';
                })
                ->addColumn('created_date', function ($user) {
                    return $user->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action', 'departemen_info', 'is_current_user'])
                ->make(true);
        }

        // Regular view request - load users directly
        $users = User::with('departemenRelation')->latest()->get();
        return view('users.index', compact('users'));
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
