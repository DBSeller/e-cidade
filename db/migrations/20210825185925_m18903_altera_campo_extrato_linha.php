<?php

use Classes\PostgresMigration;

class M18903AlteraCampoExtratoLinha extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL

update db_syscampo set nomecam = 'k86_documento',
                       conteudo = 'varchar(50)',
                       descricao = 'Documento',
                       valorinicial = '',
                       rotulo = 'Documento',
                       nulo = 'f',
                       tamanho = 50,
                       maiusculo = 't',
                       autocompl = 'f',
                       aceitatipo = 0,
                       tipoobj = 'text',
                       rotulorel = 'Documento'
                       where codcam = 10080;

ALTER TABLE extratolinha ALTER COLUMN k86_documento TYPE varchar(50);

SQL;

      $this->execute($sql);
    }


    public function down()
    {

        $sql = <<<SQL
update db_syscampo set nomecam = 'k86_documento',
                       conteudo = 'varchar(20)',
                       descricao = 'Documento',
                       valorinicial = '',
                       rotulo = 'Documento',
                       nulo = 'f',
                       tamanho = 20,
                       maiusculo = 't',
                       autocompl = 'f',
                       aceitatipo = 0,
                       tipoobj = 'text',
                       rotulorel = 'Documento'
                       where codcam = 10080;

ALTER TABLE extratolinha ALTER COLUMN k86_documento TYPE varchar(20);

SQL;
      $this->execute($sql);
    }
}
