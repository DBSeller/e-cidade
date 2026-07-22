<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20741InclusaoDocumentosApropriacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

           update conhistdoc set c53_descr = 'BAIXA DA RETEN플O APROPRIADA E CONSIGNADOS - RPP' where c53_coddoc = 6008;
           update conhistdoc set c53_descr = 'BAIXA DA RETEN플O APROPRIADA E CONSIGNADOS - RPNP' where c53_coddoc = 6010;
           update conhistdoc set c53_descr = 'ESTORNO BAIXA DA RETEN플O APROPRIADA E CONSIGNADOS- RPP' where c53_coddoc = 6009;
           update conhistdoc set c53_descr = 'ESTORNO BAIXA DA RETEN플O APROPRIADA E CONSIGNADOS- RPNP' where c53_coddoc = 6011;
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      $sql = <<<SQL

        update conhistdoc set c53_descr = 'RETEN플O DE VALORES CONSIGNADOS - RPP' where c53_coddoc = 6008;
        update conhistdoc set c53_descr = 'RETEN플O DE VALORES CONSIGNADOS - RPNP' where c53_coddoc = 6010;
        update conhistdoc set c53_descr = 'ESTORNO RETEN플O DE VALORES CONSIGNADOS - RPP' where c53_coddoc = 6009;
        update conhistdoc set c53_descr = 'ESTORNO RETEN플O DE VALORES CONSIGNADOS - RPNP' where c53_coddoc = 6011;
SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
