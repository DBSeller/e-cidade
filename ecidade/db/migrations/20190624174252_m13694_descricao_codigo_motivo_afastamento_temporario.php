<?php

use Classes\PostgresMigration;

class M13694DescricaoCodigoMotivoAfastamentoTemporario extends PostgresMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $sql = "
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = db104_valorresposta || ' - ' || db104_descricao
            WHERE db104_avaliacaopergunta = 3000858
        ";

        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down()
    {
        $sql = "
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = substr(db104_descricao, 6)
            WHERE db104_avaliacaopergunta = 3000858
        ";

        $this->execute($sql);
    }
}
