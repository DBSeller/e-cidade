<?php

use Classes\PostgresMigration;

class M13115AcertoTransacaoDocumentos6000 extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into contranslrelemento
     select nextval('contranslrelemento_c114_sequencial_seq'),
            c47_seqtranslr,
            repeat('0', 15)
       from contrans
            join contranslan on contranslan.c46_seqtrans = contrans.c45_seqtrans
            join contranslr on contranslr.c47_seqtranslan = contranslan.c46_seqtranslan
      where contrans.c45_coddoc between 6000 and 6013
        and contrans.c45_anousu >= 2019
        and contranslan.c46_ordem = 1
        and not exists(select 1 from contranslrelemento where c114_contranslr = c47_seqtranslr);

SQL_UP
);

    }

    public function down()
    {

    }

}
