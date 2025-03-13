<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20223FontesRecurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into orcamento.fontesiconfi
values ('1570','Transferências do Governo Federal referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('1571','Transferências do Estado referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('1572','Transferências de Municípios referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('1574','Operações de Crédito Vinculadas à Educação', 3),
       ('1575','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Educação', 3),
       ('1631','Transferências do Governo Federal referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('1632','Transferências do Estado referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('1633','Transferências de Municípios referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('1634','Operações de Crédito vinculadas à Saúde',4),
       ('1636','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Saúde',4),
       ('1665','Transferências de Convênios e outros Repasses vinculados à Assistência Social', 5),
       ('1700','Outras Transferências de Convênios ou Repasses da União', 6),
       ('1701','Outras Transferências de Convênios ou Repasses dos Estados', 6),
       ('1702','Outras Transferências de Convênios ou Repasses dos Municípios', 6),
       ('1703','Outras Transferências de Convênios ou Contratos de Repasse de outras Entidades', 6),
       ('1754','Recursos de Operações de Crédito', 7);
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
        DB::connection()->getPdo()->exec(<<<SQL
delete from orcamento.fontesiconfi where codigo_siconfi in (
    '1570','1571','1572','1574','1575','1631','1632','1633','1634','1636','1665','1700','1701','1702','1703','1754'
);
SQL
        );
    }
}
