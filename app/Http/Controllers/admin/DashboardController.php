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

        $users = User::with('pegawai')
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
            ->orderBy('username')
            ->paginate(20)
            ->withQueryString();

        return view('admin.dashboard.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }
}
