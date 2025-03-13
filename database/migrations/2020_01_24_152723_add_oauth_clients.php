<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;

class AddOauthClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('oauth_clients')->insert([
            'id'        => 9999999,
            'name'      => 'Laravel Password Grant Client',
            'secret'    => '4ed0NUlmQI20AbQZCrzeXxOOlDfjB73ib6juqqpS',
            'redirect'  => 'http://localhost',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Client::query()->where('id', 9999999)->delete();
    }
}
