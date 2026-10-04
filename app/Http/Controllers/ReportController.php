<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const REASONS = ['contrefacon', 'contenu_inapproprie', 'arnaque', 'autre'];

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'in:'.implode(',', self::REASONS)],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $report = new Report($validated + ['status' => 'pending']);
        $report->product_id = $product->id;
        $report->reporter_id = $request->user()->id;
        $report->save();

        return back()->with('status', 'report-submitted');
    }
}
