<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;

class AdvertisementController extends Controller
{
    public function click(Advertisement $advertisement): RedirectResponse
    {
        abort_unless($advertisement->isCurrentlyActive(), 404);

        $advertisement->increment('clicks_count');

        return redirect()->route('products.show', $advertisement->product);
    }
}
