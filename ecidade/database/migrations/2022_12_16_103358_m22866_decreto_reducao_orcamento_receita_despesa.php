<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22866DecretoReducaoOrcamentoReceitaDespesa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


         $sql = <<<SQL

           update conhistdoc set c53_descr = 'REDUÇÃO DO ORÇAMENTO - DESPESA', c53_tipo = 60 where c53_coddoc = 8;
           update conhistdoc set c53_descr = 'REDUÇÃO DO ORÇAMENTO - RECEITA' where c53_coddoc = 104;

           insert into orcsuplemtipo select 1050, 'REDUÇÃO DO ORÇAMENTO', 8, 8, 104, false, null, null;





           create temp table w_contrans as
             select *
               from contrans
               left join contranslan on (c46_seqtrans) = (c45_seqtrans)
               left join contranslr on  (c47_seqtranslan) = (c46_seqtranslan)
               left join contranslrelemento on (c114_contranslr) = (c47_seqtranslr)
               where c45_coddoc in (104, 8);

           delete from contranslrelemento using w_contrans where contranslrelemento.c114_sequencial = w_contrans.c114_sequencial;
           delete from contranslr using w_contrans where contranslr.c47_seqtranslr = w_contrans.c47_seqtranslr;
           delete from contranslan using w_contrans where contranslan.c46_seqtranslan = w_contrans.c46_seqtranslan;
           delete from contrans using w_contrans where contrans.c45_seqtrans = w_contrans.c45_seqtrans;



           insert into contrans
             select nextval('contrans_c45_seqtrans_seq'),
                    2022,
                    8,
                    1;

           insert into contranslan select  nextval('contranslan_c46_seqtranslan_seq'),
                                           currval('contrans_c45_seqtrans_seq'),
                                           9011,
                                           'PRIMEIRO LANÇAMENTO',
                                           0,
                                           true,
                                           0,
                                           'PRIMEIRO LANÇAMENTO',
                                           1;

            insert into contranslan select  nextval('contranslan_c46_seqtranslan_seq'),
                                            currval('contrans_c45_seqtrans_seq'),
                                            9011,
                                            'SEGUNDO LANÇAMENTO',
                                            0,
                                            true,
                                            0,
                                            'SEGUNDO LANÇAMENTO',
                                            2;

            insert into contrans
             select nextval('contrans_c45_seqtrans_seq'),
                    2022,
                    104,
                    1;

            insert into contranslan select  nextval('contranslan_c46_seqtranslan_seq'),
                                            currval('contrans_c45_seqtrans_seq'),
                                            9011,
                                            'PRIMEIRO LANÇAMENTO',
                                            0,
                                            true,
                                            0,
                                            'PRIMEIRO LANÇAMENTO',
                                            1;
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

            update conhistdoc set c53_descr = 'ESTORNO SUPLEMENTAÇÃO' where c53_coddoc = 8;
            update conhistdoc set c53_descr = 'ESTORNO PREVISÃO ADICIONAL' where c53_coddoc = 104;

            delete from orcsuplemtipo where o48_tiposup = 1050;

            create temp table w_contrans as
              select *
                from contrans
                left join contranslan on (c46_seqtrans) = (c45_seqtrans)
                left join contranslr on  (c47_seqtranslan)= (c46_seqtranslan)
                left join contranslrelemento on (c114_contranslr)= (c47_seqtranslr)
                where c45_coddoc in (104, 8);

            delete from contranslrelemento using w_contrans where contranslrelemento.c114_sequencial = w_contrans.c114_sequencial;
            delete from contranslr using w_contrans where contranslr.c47_seqtranslr = w_contrans.c47_seqtranslr;
            delete from contranslan using w_contrans where contranslan.c46_seqtranslan = w_contrans.c46_seqtranslan;
            delete from contrans using w_contrans where contrans.c45_seqtrans = w_contrans.c45_seqtrans;

SQL;

          DB::connection()->getPdo()->exec($sql);

    }
}
