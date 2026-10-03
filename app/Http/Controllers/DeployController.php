<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function migrate(Request $request): JsonResponse
    {
        $expected = config('app.deploy_migrate_token');

        if (! $expected || ! hash_equals($expected, (string) $request->query('token'))) {
            abort(404);
        }

        Artisan::call('migrate', ['--force' => true]);

        return response()->json(['output' => Artisan::output()]);
    }
}
