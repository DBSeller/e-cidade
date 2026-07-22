<?php

use Classes\PostgresMigration;

class M12063AcertoModalidadeCodigoTribunal extends PostgresMigration
{
    public function up()
    {
        $sql = <<<STRING
begin;

select setval('pctipocompratribunal_l44_sequencial_seq', max(l44_sequencial)) from  pctipocompratribunal;
insert into pctipocompratribunal values(nextval('pctipocompratribunal_l44_sequencial_seq'), '15', 'Chamada Pública-PNAE:Programa Nacional de Alimentação Escolar', 'RS', 'CPP');
STRING;

        $this->execute($sql);
    }

    public function down()
    {
        $this->execute("delete from pctipocompratribunal where l44_codigotribunal = '15'");
    }
}
