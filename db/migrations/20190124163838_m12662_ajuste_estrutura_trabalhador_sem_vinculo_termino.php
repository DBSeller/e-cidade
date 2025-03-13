<?php

use Classes\PostgresMigration;

class M12662AjusteEstruturaTrabalhadorSemVinculoTermino extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upTabela();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downTabela();
    }

    private function upDicionario() 
    {
        $sql = <<<SQL
        INSERT INTO db_syscampo VALUES(1009966,'eso24_codigorescisao','text','Código de rescisão para o eSocial formado da matrícula junto da competência.','', 'Código de rescisão',1,'f','t','f',0,'text','Código de rescisão');
        INSERT INTO db_syscampo VALUES(1009967,'eso24_cgmempregador','int4','Código CGM do empregador.','0', 'CGM do empregador',11,'f','f','f',1,'text','CGM do empregador');

        INSERT INTO db_sysarqcamp VALUES(1010321,1009966,4,0);
        INSERT INTO db_sysarqcamp VALUES(1010321,1009967,5,0);
SQL;
        $this->execute($sql);
    }

    private function upTabela()
    {
        $sql = <<<SQL
        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc ADD COLUMN eso24_codigorescisao character varying(50);
        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc ADD COLUMN eso24_cgmempregador  int4 not null;

        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc
            ADD CONSTRAINT avaliacaogruporespostatertrabasemvinc_cgm_fk FOREIGN KEY (eso24_cgmempregador)
            REFERENCES cgm;
SQL;
        $this->execute($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL
        DELETE FROM db_sysarqcamp WHERE codarq = 1010321 AND codcam IN (1009966, 1009967);
        DELETE FROM db_syscampo WHERE codcam IN (1009966, 1009967);
SQL;
        $this->execute($sql);
    }

    private function downTabela()
    {
        $sql = <<<SQL
        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc DROP COLUMN eso24_codigorescisao; 
        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc DROP COLUMN eso24_cgmempregador; 
SQL;
        $this->execute($sql);
    }
}
