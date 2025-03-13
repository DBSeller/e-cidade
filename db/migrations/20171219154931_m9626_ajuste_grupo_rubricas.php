<?php

use Classes\PostgresMigration;

class M9626AjusteGrupoRubricas extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO avaliacaogrupopergunta values ( 3000231, 3000016, 'Informações de identificação da rubrica', 'informacoes-rubrica', 'ideRubrica' );
            UPDATE avaliacaopergunta SET db103_avaliacaogrupopergunta = 3000231 WHERE db103_sequencial IN (3000943, 3000942, 3000941, 3000940, 3000939);
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            UPDATE avaliacaopergunta SET db103_avaliacaogrupopergunta = 3000217 WHERE db103_sequencial IN (3000943, 3000942, 3000941, 3000940, 3000939);
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial = 3000231;
        ";
        $this->execute($sql);
    }
}
