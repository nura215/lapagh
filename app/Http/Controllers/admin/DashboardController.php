<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->toString();
        $sortBy = $request->string('sort')->trim()->toString();
        $sortDir = strtolower($request->string('dir')->toString()) === 'asc' ? 'asc' : 'desc';

        $sortableColumns = [
            'nama' => 'pegawai.nama',
            'nip' => 'pegawai.nip',
            'status_pegawai' => 'pegawai.status_pegawai',
            'jabatan' => 'pegawai.jabatan',
            'username' => 'users.username',
        ];

        if (! array_key_exists($sortBy, $sortableColumns)) {
            $sortBy = 'username';
            $sortDir = 'asc';
        }

        $users = User::query()
            ->select('users.*')
            ->with('pegawai')
            ->leftJoin('pegawai', 'pegawai.user_id', '=', 'users.id')
            ->whereIn('role', [User::ROLE_ASN, User::ROLE_NON_ASN])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('username', 'like', "%{$search}%")
                        ->orWhereHas('pegawai', function ($pegawai) use ($search) {
                            $pegawai->where('nama', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%")
                                ->orWhere('status_pegawai', 'like', "%{$search}%")
                                ->orWhere('jabatan', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy($sortableColumns[$sortBy], $sortDir)
            ->orderBy('users.id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.dashboard.index', [
            'users' => $users,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }
}
