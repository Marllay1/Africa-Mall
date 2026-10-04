<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index', [
            'pending' => Report::with('product.shop', 'reporter')->where('status', 'pending')->latest()->get(),
            'resolved' => Report::with('product.shop', 'reporter', 'reviewer')->whereIn('status', ['reviewed', 'dismissed'])->latest('reviewed_at')->take(20)->get(),
        ]);
    }

    public function review(Request $request, Report $report): RedirectResponse
    {
        abort_unless($report->status === 'pending', 404);

        $validated = $request->validate([
            'status' => ['required', 'in:reviewed,dismissed'],
        ]);

        $report->status = $validated['status'];
        $report->reviewed_by = $request->user()->id;
        $report->reviewed_at = now();
        $report->save();

        return back()->with('status', 'report-updated');
    }
}
