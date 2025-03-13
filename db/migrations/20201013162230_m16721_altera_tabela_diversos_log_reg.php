<?php

use Classes\PostgresMigration;

class M16721AlteraTabelaDiversosLogReg extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();

    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
        UPDATE db_syscampo SET nomecam = 'dv07_coddiver', conteudo = 'int4', descricao = 'Chave estrangeira que referencia a tabela diversos', valorinicial = '0', rotulo = 'coddiver', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'coddiver' where codcam = 1011861;

        UPDATE db_syscampo SET nomecam = 'dv07_dadovalido', conteudo = 'int4', descricao = 'Dado Valido da Planilha CSV da inclusão de diversos em lote na tabela diversoslotelogreg', valorinicial = '0', rotulo = 'Dado Valido', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Dado Valido' where codcam = 1011860;

        INSERT INTO db_syscampo VALUES(1011862,'dv07_dadoinvalido','varchar(255)','Dado Invalido da Planilha CSV da inclusão de diversos em lote na tabela diversoslotelogreg','', 'Dado Invalido',255,'f','t','f',0,'text','Dado Invalido');
        INSERT INTO db_sysarqcamp VALUES(1010626,1011862,6,0);

SQL
        );

    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
        DELETE FROM db_sysarqcamp WHERE codcam = 1011862;
        DELETE FROM db_syscampo WHERE codcam = 1011862;
SQL
        );

    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
        ALTER TABLE diversos.diversoslotelogreg RENAME COLUMN dv07_coddiv TO dv07_coddiver;
        ALTER TABLE diversos.diversoslotelogreg RENAME COLUMN dv07_dadoplanilha TO dv07_dadovalido;
        ALTER TABLE diversos.diversoslotelogreg ADD COLUMN dv07_dadoinvalido VARCHAR(255);
SQL
        );

    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
        ALTER TABLE diversos.diversoslotelogreg RENAME COLUMN dv07_coddiver TO dv07_coddiv;
        ALTER TABLE diversos.diversoslotelogreg RENAME COLUMN dv07_dadovalido TO dv07_dadoplanilha;
        ALTER TABLE diversos.diversoslotelogreg DROP COLUMN dv07_dadoinvalido;
SQL
        );

    }

}
