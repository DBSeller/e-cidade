<?php

use Classes\PostgresMigration;

class M15727AcertoDoc3000 extends PostgresMigration
{

    public function down()
    {
        $this->execute(<<<SQL
delete from contranslr
where c47_seqtranslan in (select c46_seqtranslan
                          from contranslan
                                   join contrans on c45_seqtrans = c46_seqtrans
                          where c45_coddoc in (3000,3001)
                            and c45_anousu >= 2020);

delete from contranslan where c46_seqtrans in (select c45_seqtrans from contrans  where c45_coddoc in (3000,3001) and c45_anousu >= 2020);
delete from contrans where c45_coddoc in (3000,3001) and c45_anousu >= 2020;

SQL
);
    }

    public function up()
    {
        $this->down();

        $buscaInstitituicao = $this->fetchAll('select codigo from db_config order by 1;');

        foreach ($buscaInstitituicao as $dados) {

            $codigoInstituicao = $dados['codigo'];
            $this->execute(<<<SQL_UP

insert into contrans
     values (nextval('contrans_c45_seqtrans_seq'),
             2020,
             3000,
             $codigoInstituicao);

insert into contranslan
     values (nextval('contranslan_c46_seqtranslan_seq'),
             currval('contrans_c45_seqtrans_seq'),
             (select c50_codhist from conhist limit 1),
             '',
             0,
             true,
             0,
             'PRIMEIRO LANCAMENTO',
             1);

insert into contranslr
     values (nextval('contranslr_c47_seqtranslr_seq'),
             currval('contranslan_c46_seqtranslan_seq'),
               0,
               0,
               '',
               0,
               2020,
               $codigoInstituicao,
               null,
               null);


insert into contrans
     values (nextval('contrans_c45_seqtrans_seq'),
             2020,
             3001,
             $codigoInstituicao);

insert into contranslan
     values (nextval('contranslan_c46_seqtranslan_seq'),
             currval('contrans_c45_seqtrans_seq'),
             (select c50_codhist from conhist limit 1),
             '',
             0,
             true,
             0,
             'PRIMEIRO LANCAMENTO',
             1);

insert into contranslr
     values (nextval('contranslr_c47_seqtranslr_seq'),
             currval('contranslan_c46_seqtranslan_seq'),
               0,
               0,
               '',
               0,
               2020,
               $codigoInstituicao,
               null,
               null);

SQL_UP
);


        }
    }
}
