<?php

use Classes\PostgresMigration;

/**
 * Class M13694DescricaoCodigoCategoriaTrabalhador
 */
class M13694DescricaoCodigoCategoriaTrabalhador extends PostgresMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $sql = "
            UPDATE avaliacaoperguntaopcao
            SET db104_descricao = db104_valorresposta || ' - ' || db104_descricao
            WHERE db104_avaliacaopergunta = 3000827
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
            SET db104_descricao = substr(db104_descricao, 7)
            WHERE db104_avaliacaopergunta = 3000827
        ";

        $this->execute($sql);
    }
}
