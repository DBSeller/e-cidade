<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Laravel\Passport\Client;

class M20063CriaClientIdCentralDeMatriculas extends Migration
{
    public function up()
    {
        DB::table('oauth_clients')->insert([
            'name' => 'MatriculaOnline',
            'secret' => Str::random(40),
            'redirect' => 'http://localhost',
            'personal_access_client' => true,
            'password_client' => false,
            'revoked' => false
        ]);
    }

    public function down()
    {
        Client::query()->where('name', '=', 'MatriculaOnline')->delete();
    }
}
