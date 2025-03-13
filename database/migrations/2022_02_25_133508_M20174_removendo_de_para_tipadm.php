<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20174RemovendoDeParaTipadm extends Migration
{
    /**
     *  Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upAdmissao();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downAdmissao();
    }

    private function upAdmissao()
    {
       DB::update("UPDATE recursoshumanos.admissao SET h07_tipadm =
        CASE
            WHEN h07_tipadm IS NULL OR h07_tipadm = '' THEN '01'
            WHEN trim(h07_tipadm)='07' THEN '01'
            WHEN trim(h07_tipadm)='08' THEN '02'
            WHEN trim(h07_tipadm)='09' THEN '03'
            WHEN trim(h07_tipadm)='10' THEN '04'
            WHEN trim(h07_tipadm)='11' THEN '05'
            WHEN trim(h07_tipadm)='12' THEN '06'
            WHEN trim(h07_tipadm)='13' THEN '07'
            WHEN trim(h07_tipadm)='14' THEN '08'
            WHEN trim(h07_tipadm)='15' THEN '09'
            WHEN trim(h07_tipadm)='16' THEN '10'
            WHEN trim(h07_tipadm)='17' THEN '11'
            WHEN trim(h07_tipadm)='18' THEN '12'
            WHEN trim(h07_tipadm)='19' THEN '13'
            WHEN trim(h07_tipadm)='20' THEN '14'
            WHEN trim(h07_tipadm)='21' THEN '15'
            WHEN trim(h07_tipadm)='22' THEN '16'
            WHEN trim(h07_tipadm)='23' THEN '17'
            ELSE h07_tipadm
        END");
    }

    private function downAdmissao()
    {
        DB::update("UPDATE recursoshumanos.admissao SET h07_tipadm =
        CASE
            WHEN h07_tipadm IS NULL OR h07_tipadm = '' THEN '01'
            WHEN h07_tipadm::integer=1 THEN '07'
            WHEN h07_tipadm::integer=2  THEN '08'
            WHEN h07_tipadm::integer=3  THEN '09'
            WHEN h07_tipadm::integer=4  THEN '10'
            WHEN h07_tipadm::integer=5  THEN '11'
            WHEN h07_tipadm::integer=6  THEN '12'
            WHEN h07_tipadm::integer=7  THEN '13'
            WHEN h07_tipadm::integer=8  THEN '14'
            WHEN h07_tipadm::integer=9  THEN '15'
            WHEN h07_tipadm::integer=10 THEN '16'
            WHEN h07_tipadm::integer=11 THEN '17'
            WHEN h07_tipadm::integer=12 THEN '18'
            WHEN h07_tipadm::integer=13 THEN '19'
            WHEN h07_tipadm::integer=14 THEN '20'
            WHEN h07_tipadm::integer=15 THEN '21'
            WHEN h07_tipadm::integer=16 THEN '22'
            WHEN h07_tipadm::integer=17 THEN '23'
            ELSE h07_tipadm
        END");
    }
}
