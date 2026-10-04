<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function index(): View
    {
        return view('admin.advertisements.index', [
            'pending' => Advertisement::with('shop', 'product')->where('status', 'pending')->latest()->get(),
            'active' => Advertisement::with('shop', 'product')->currentlyActive()->latest()->get(),
            'history' => Advertisement::with('shop', 'product', 'reviewedBy')
                ->where(function ($query) {
                    $query->where('status', 'rejected')
                        ->orWhere(fn ($query) => $query->where('status', 'active')->where('ends_at', '<', now()));
                })
                ->latest()
                ->take(20)
                ->get(),
        ]);
    }

    public function approve(Request $request, Advertisement $advertisement): RedirectResponse
    {
        abort_unless($advertisement->status === 'pending', 404);

        $advertisement->status = 'active';
        $advertisement->starts_at = now();
        $advertisement->ends_at = now()->addDays($advertisement->duration_days);
        $advertisement->reviewed_by = $request->user()->id;
        $advertisement->reviewed_at = now();
        $advertisement->save();

        return back()->with('status', 'advertisement-approved');
    }

    public function reject(Request $request, Advertisement $advertisement): RedirectResponse
    {
        abort_unless($advertisement->status === 'pending', 404);

        $advertisement->status = 'rejected';
        $advertisement->reviewed_by = $request->user()->id;
        $advertisement->reviewed_at = now();
        $advertisement->save();

        return back()->with('status', 'advertisement-rejected');
    }
}
