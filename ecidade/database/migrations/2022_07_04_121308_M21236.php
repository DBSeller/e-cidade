<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21236 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
drop trigger IF EXISTS tg_valida_data_conciliacao on concilia;
drop trigger IF EXISTS tg_valida_data_conciliacao on extratolinha;
drop trigger IF EXISTS tg_valida_data_conciliacao on extratosaldo;
drop trigger IF EXISTS tg_valida_data_conciliacao on conciliaitem;
drop trigger IF EXISTS tg_valida_data_conciliacao on conciliapendcorrente;
drop trigger IF EXISTS tg_valida_data_conciliacao on conciliapendextrato;
drop trigger IF EXISTS tg_valida_data_conciliacao on conciliacor;
drop trigger IF EXISTS tg_valida_data_conciliacao on conciliaextrato;
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
