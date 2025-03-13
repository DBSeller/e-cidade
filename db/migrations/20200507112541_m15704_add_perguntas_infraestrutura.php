<?php

use Classes\PostgresMigration;

class M15704AddPerguntasInfraestrutura extends PostgresMigration
{
    public function up()
    {
        $sql = "update avaliacaoperguntaopcao
                    set db104_descricao = 'Técnicos(as), monitores(as), supervisores(as) ou auxiliares de laboratório(s), de apoio a tecnologias educacionais ou em multimeios/multimídias eletrônico-digitais.'
                        where db104_sequencial = 4001265;

                insert into avaliacaoperguntaopcao(db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
                       values (4001323, 4000238, 'Vice-diretor(a) ou diretor(a) adjunto(a), profissionais responsáveis pela gestão administrativa e/ou financeira', 'vicediretora_ou_diretora_adjuntoa_profissionais_re', 't', 0, '', 'gestores_escola'),
                              (4001324 ,4000238 ,'Orientador(a) comunitário(a) ou assistente social' ,'orientadora_comunitarioa_ou_assistente_social' ,'t' ,0 ,'' ,'orientador_comunitario' );";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "update avaliacaoperguntaopcao
                    set db104_descricao = 'Técnicos(as), monitores(as) ou auxiliares de laboratório(s)'
                        where db104_sequencial = 4001265;

                delete from avaliacaoperguntaopcao where db104_sequencial in (4001323, 4001324);";

        $this->execute($sql);
    }
}




