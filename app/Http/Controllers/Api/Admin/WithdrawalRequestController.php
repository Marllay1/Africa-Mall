<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawalResource;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalProcessed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class WithdrawalRequestController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'pending' => WithdrawalResource::collection(
                Withdrawal::with('shop')->where('status', 'pending')->latest()->get()
            ),
            'reviewed' => WithdrawalResource::collection(
                Withdrawal::with('shop')->whereIn('status', ['paid', 'rejected'])->latest('processed_at')->take(20)->get()
            ),
        ]);
    }

    public function approve(Withdrawal $withdrawal): WithdrawalResource
    {
        $withdrawal->status = 'paid';
        $withdrawal->processed_at = now();
        $withdrawal->save();

        try {
            $withdrawal->shop->sellerProfile->user->notify(new WithdrawalProcessed($withdrawal));
        } catch (Throwable $e) {
            report($e);
        }

        return new WithdrawalResource($withdrawal->load('shop'));
    }

    public function reject(Request $request, Withdrawal $withdrawal): WithdrawalResource
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

        return new WithdrawalResource($withdrawal->load('shop'));
    }
}
