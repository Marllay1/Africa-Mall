<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::with('sellerProfile')->latest()->paginate(20),
        ]);
    }

    public function block(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403);

        $user->is_blocked = true;
        $user->save();

        return back()->with('status', 'user-blocked');
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->is_blocked = false;
        $user->save();

        return back()->with('status', 'user-unblocked');
    }
}
