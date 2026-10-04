<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Dispute;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisputeController extends Controller
{
    public function index(): View
    {
        return view('admin.disputes.index', [
            'open' => Dispute::with('order.user', 'order.shop')->where('status', 'open')->latest()->get(),
            'resolved' => Dispute::with('order.user', 'order.shop', 'resolvedBy')->where('status', 'resolved')->latest('resolved_at')->take(20)->get(),
        ]);
    }

    public function show(Dispute $dispute): View
    {
        $dispute->load('order.items.product', 'order.user', 'order.shop.sellerProfile', 'resolvedBy');

        $conversation = null;

        if ($dispute->order->user_id) {
            $conversation = Conversation::where('shop_id', $dispute->order->shop_id)
                ->where('customer_id', $dispute->order->user_id)
                ->with('messages.sender')
                ->first();
        }

        return view('admin.disputes.show', [
            'dispute' => $dispute,
            'conversation' => $conversation,
        ]);
    }

    /**
     * Find-or-create the dossier for an order already marked 'litige' (self-healing
     * for orders that reached that status before this module existed).
     */
    public function forOrder(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'litige', 404);

        $dispute = $order->dispute ?? (function () use ($order) {
            $dispute = new Dispute(['status' => 'open']);
            $dispute->order_id = $order->id;
            $dispute->save();

            return $dispute;
        })();

        return redirect()->route('admin.disputes.show', $dispute);
    }

    public function updateNotes(Request $request, Dispute $dispute): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $dispute->admin_notes = $validated['admin_notes'] ?? null;
        $dispute->save();

        return back()->with('status', 'dispute-notes-saved');
    }

    public function resolve(Request $request, Dispute $dispute): RedirectResponse
    {
        abort_unless($dispute->isOpen(), 404);

        $validated = $request->validate([
            'resolution' => ['required', 'in:refunded,rejected'],
            'resolution_notes' => ['required', 'string', 'max:2000'],
            'sanction_seller' => ['nullable', 'boolean'],
        ]);

        $dispute->resolution = $validated['resolution'];
        $dispute->resolution_notes = $validated['resolution_notes'];
        $dispute->status = 'resolved';
        $dispute->resolved_by = $request->user()->id;
        $dispute->resolved_at = now();
        $dispute->save();

        $order = $dispute->order;
        $order->status = $validated['resolution'] === 'refunded' ? 'remboursee' : 'delivered';
        $order->save();

        $sellerProfile = $order->shop->sellerProfile;

        if (($validated['sanction_seller'] ?? false) && $sellerProfile?->isActive()) {
            $sellerProfile->suspendBy($request->user());
        }

        return redirect()->route('admin.disputes.index')->with('status', 'dispute-resolved');
    }
}
