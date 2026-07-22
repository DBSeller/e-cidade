<?php

use Classes\PostgresMigration;

class M8844AlteracaoFormulario extends PostgresMigration
{

    public function up()
    {
        // Remove grupos e perguntas do Arquivo S1000 e S1005
        $this->execute(<<<SQL
-- Remove a pergunta Indicativo de Construtora:

delete from avaliacaogrupoperguntaresposta
 where db108_avaliacaoresposta in (select db106_sequencial
                                     from avaliacaoresposta
                                    where db106_avaliacaoperguntaopcao in (3003674, 3003673) );
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3003674, 3003673);
delete from avaliacaoperguntaopcao where db104_avaliacaopergunta = 3000880;
delete from avaliacaopergunta where db103_sequencial = 3000880;

-- Remove o grupo Informações exclusivas de organismos internacionais e outras instituições extraterritoriais;
delete from avaliacaogrupoperguntaresposta
 where db108_avaliacaoresposta in (select db106_sequencial
                                     from avaliacaoresposta
                                    where db106_avaliacaoperguntaopcao in (3003712, 3003711));
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3003712, 3003711);
delete from avaliacaoperguntaopcao where db104_avaliacaopergunta  = 3000908;
delete from avaliacaopergunta where db103_sequencial = 3000908;
delete from avaliacaogrupopergunta where db102_sequencial = 3000202;

-- Remove o grupo Registro preenchido exclusivamente por empresa construtora (CNO).
delete from avaliacaogrupoperguntaresposta
 where db108_avaliacaoresposta in (select db106_sequencial
                                     from avaliacaoresposta
                                    where db106_avaliacaoperguntaopcao in (3003748, 3003747));
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3003748, 3003747);
delete from avaliacaoperguntaopcao where db104_avaliacaopergunta = 3000931;
delete from avaliacaopergunta where db103_sequencial = 3000931;
delete from avaliacaogrupopergunta where db102_sequencial = 3000212;
SQL
        );
        $this->execute(<<<SQL
update avaliacaopergunta set db103_tipo = 8 where db103_sequencial in (3000923, 3000922);
update avaliacaopergunta set db103_tipo = 6 where db103_sequencial = 3000921;
SQL
        );
    }

    public function down()
    {

    }
}
