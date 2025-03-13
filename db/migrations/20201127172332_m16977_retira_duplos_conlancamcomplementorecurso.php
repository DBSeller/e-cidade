<?php

use Classes\PostgresMigration;

class M16977RetiraDuplosConlancamcomplementorecurso extends PostgresMigration
{

    public function up()
            {
                $this->execute("
                                 with manter as (
                                 select min(o201_sequencial), o201_codlan, o201_complemento
                                   from contabilidade.conlancamcomplementorecurso
                                   group by o201_codlan, o201_complemento
                                 ) delete from conlancamcomplementorecurso 
                                   using manter
                                 where conlancamcomplementorecurso.o201_codlan = manter.o201_codlan 
                                   and conlancamcomplementorecurso.o201_complemento= manter.o201_complemento
                                   and o201_sequencial != min;
        ");
    }

    public function down() {}

}
