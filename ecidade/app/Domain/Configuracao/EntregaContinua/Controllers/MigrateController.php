<?php

namespace App\Domain\Configuracao\EntregaContinua\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateController extends Controller
{
    public function migrate()
    {
        DB::transaction(function () {
            Artisan::call("migrate", ['--force' => true]);
        });
        $obj = Artisan::output();
        return response()->json((object)['error' => false, 'message' => $obj], 200);
    }
}
