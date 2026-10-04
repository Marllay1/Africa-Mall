<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        return view('admin.payment-methods.index', [
            'paymentMethods' => PaymentMethod::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:payment_methods,code'],
            'label' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:10'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $paymentMethod = new PaymentMethod($validated + ['is_active' => true, 'position' => $validated['position'] ?? 0]);
        $paymentMethod->save();

        return back()->with('status', 'payment-method-created');
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:10'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $paymentMethod->fill($validated);
        $paymentMethod->save();

        return back()->with('status', 'payment-method-updated');
    }

    public function toggle(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->is_active = ! $paymentMethod->is_active;
        $paymentMethod->save();

        return back()->with('status', $paymentMethod->is_active ? 'payment-method-enabled' : 'payment-method-disabled');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        if (Payment::where('method', $paymentMethod->code)->exists()) {
            return back()->with('status', 'payment-method-in-use');
        }

        $paymentMethod->delete();

        return back()->with('status', 'payment-method-deleted');
    }
}
