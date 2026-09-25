<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalProcessed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class WithdrawalRequestController extends Controller
{
    /**
     * List withdrawal requests awaiting review, plus recent decisions.
     */
    public function index(): View
    {
        return view('admin.withdrawal-requests.index', [
            'pending' => Withdrawal::with('shop')->where('status', 'pending')->latest()->get(),
            'reviewed' => Withdrawal::with('shop')->whereIn('status', ['paid', 'rejected'])->latest('processed_at')->take(20)->get(),
        ]);
    }

    /**
     * Approve a withdrawal request: mark it paid.
     */
    public function approve(Withdrawal $withdrawal): RedirectResponse
    {
        $withdrawal->status = 'paid';
        $withdrawal->processed_at = now();
        $withdrawal->save();

        try {
            $withdrawal->shop->sellerProfile->user->notify(new WithdrawalProcessed($withdrawal));
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with('status', 'withdrawal-request-approved');
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $withdrawal->status = 'rejected';
        $withdrawal->processed_at = now();
        $withdrawal->rejection_reason = $validated['rejection_reason'] ?? null;
        $withdrawal->save();

        try {
            $withdrawal->shop->sellerProfile->user->notify(new WithdrawalProcessed($withdrawal));
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with('status', 'withdrawal-request-rejected');
    }
}
