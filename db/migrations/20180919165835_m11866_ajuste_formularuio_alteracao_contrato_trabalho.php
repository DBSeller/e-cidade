<?php

use Classes\PostgresMigration;

class M11866AjusteFormularuioAlteracaoContratoTrabalho extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update avaliacaogrupopergunta set db102_identificadorcampo = 'altContratual', db102_descricao = 'Informações da alteração do contrato de trabalho' where db102_sequencial = 3000453;
            "
        );
    }

    public function down()
    {
        $this->execute("
                update avaliacaogrupopergunta set db102_identificadorcampo = 'ideVinculo', db102_descricao = 'Informações do Contrato de Trabalho'  where db102_sequencial = 3000453; 
            "
        );
    }
}
