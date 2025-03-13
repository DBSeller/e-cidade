<?php

use Classes\PostgresMigration;

class M14278VinculoDeducoesReceita extends PostgresMigration
{
    public function down()
    {
    }

    public function up()
    {
        $this->execute(<<<SQL_UP_ACERTO

create table bkp_depara_deducao as
        select rec.k02_codigo as rec_principal,
               rec.k02_estorc as rec_principal_estrutural,
               rec.k02_anousu as ano,
               ded.k02_codigo as rec_deducao,
               ded.k02_estorc as rec_deducao_estrutural
          from taborc rec
               join taborc ded on substr(ded.k02_estorc, 2, 15) = substr(rec.k02_estorc, 2, 15)
                              and ded.k02_anousu = rec.k02_anousu
         where rec.k02_anousu >= 2019
           and substr(ded.k02_estorc, 1, 1) = '9'
           and substr(rec.k02_estorc, 1, 1) = '4';

delete from taborcvinculodeducao where k164_anousu >= 2019;

insert into taborcvinculodeducao
     select nextval('taborcvinculodeducao_k164_sequencial_seq'),
            rec_principal,
            rec_deducao,
            ano
       from bkp_depara_deducao;

SQL_UP_ACERTO
);
    }
}
