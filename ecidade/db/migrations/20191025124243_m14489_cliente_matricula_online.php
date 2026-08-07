<?php

use Classes\PostgresMigration;

class M14489ClienteMatriculaOnline extends PostgresMigration
{
    public function up()
    {
        $key = sha1(md5(time() . rand(0, 9999) . 'RAND_KEY'));
        $this->execute("
            INSERT INTO configuracoes.api_clients (db172_nome, db172_descricao, db172_chave, db172_ultima_utilizacao)
            VALUES ('MatriculaOnline', 'Matrícula Online', '{$key}', null);
        ");
    }

    public function down()
    {
        $this->execute("delete from configuracoes.api_clients where db172_nome = 'MatriculaOnline';");
    }
}
