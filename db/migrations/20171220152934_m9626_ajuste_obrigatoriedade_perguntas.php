<?php

use Classes\PostgresMigration;

/**
 * Class M9626AjusteObrigatoriedadePerguntas
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M9626AjusteObrigatoriedadePerguntas extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("
             update avaliacaopergunta set db103_obrigatoria = true where db103_identificador = 'identificador-rubricas';
             update avaliacaopergunta set db103_obrigatoria = true where db103_identificador = 'codigo-de-classificacao-da-rubrica-de-acordo-com-a';
         ");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute("
             update avaliacaopergunta set db103_obrigatoria = false where db103_identificador = 'identificador-rubricas';
             update avaliacaopergunta set db103_obrigatoria = false where db103_identificador = 'codigo-de-classificacao-da-rubrica-de-acordo-com-a';
         ");
    }
}
