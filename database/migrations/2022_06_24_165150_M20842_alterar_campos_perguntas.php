<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20842AlterarCamposPerguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

select setval(  'avaliacaopergunta_db103_sequencial_seq', 
                (select max(db103_sequencial) from habitacao.avaliacaopergunta)
            );

insert into habitacao.avaliacaopergunta (   db103_sequencial, 
                                            db103_avaliacaotiporesposta, 
                                            db103_avaliacaogrupopergunta, 
                                            db103_descricao, 
                                            db103_ativo, 
                                            db103_ordem, 
                                            db103_identificador, 
                                            db103_tipo, 
                                            db103_identificadorcampo
                                        )
values (    nextval('avaliacaopergunta_db103_sequencial_seq'), 
            3, 
            3000007, 
            'Quantidade de equipamentos para o processo ensino aprendizagem:', 
            true, 
            2, 
            'quantidades-equipamentos', 
            1, 
            'pergunta_quantidades_equipamentos'
        );

update habitacao.avaliacaoperguntaopcao 
set db104_avaliacaopergunta = currval('avaliacaopergunta_db103_sequencial_seq') 
where db104_identificadorcampo in ( 'dvd', 
                                    'aparelho_de_som', 
                                    'aparelho_de_televisao', 
                                    'lousa_digital', 
                                    'projetor_multimidia_data_show'
                                  );

insert into habitacao.avaliacaopergunta (   db103_sequencial, 
                                            db103_avaliacaotiporesposta, 
                                            db103_avaliacaogrupopergunta, 
                                            db103_descricao, 
                                            db103_ativo, 
                                            db103_ordem, 
                                            db103_identificador, 
                                            db103_tipo, 
                                            db103_identificadorcampo
                                        )
values (    nextval('avaliacaopergunta_db103_sequencial_seq'), 
            3, 
            3000007, 
            'Quantidade de computadores em uso pelos alunos:', 
            true, 
            3, 
            'quantidades-computadores', 
            1, 
            'pergunta_quantidades_computadores'
        );

update habitacao.avaliacaoperguntaopcao 
set db104_avaliacaopergunta = currval('avaliacaopergunta_db103_sequencial_seq') 
where db104_identificadorcampo in ('computadores_portateis', 'tablets', 'equipamentos_computadores_mesa');

update habitacao.avaliacaopergunta 
set db103_descricao = 'Equipamentos existentes na escola para uso técnico e administrativo:' 
where db103_sequencial = 3000010;

select setval(  'avaliacaoperguntaopcao_db104_sequencial_seq', 
                (select max(db104_sequencial) from habitacao.avaliacaoperguntaopcao)
            );

insert into habitacao.avaliacaoperguntaopcao (  db104_sequencial, 
                                                db104_avaliacaopergunta, 
                                                db104_descricao, 
                                                db104_aceitatexto, 
                                                db104_identificador, 
                                                db104_peso, 
                                                db104_identificadorcampo
                                            )
values (    nextval('avaliacaoperguntaopcao_db104_sequencial_seq'), 
            3000010, 
            'Computadores', 
            false, 
            'computadores', 
            0, 
            'computadores'
        );

update habitacao.avaliacaoperguntaopcao 
set db104_aceitatexto = false
where db104_identificadorcampo in ( 'antena_parabolica', 
                                    'computadores', 
                                    'copiadora', 
                                    'impressora', 
                                    'impressora_multifuncional', 
                                    'scanner'
                                  );

insert into habitacao.avaliacaoperguntaopcao (  db104_sequencial,
                                                db104_avaliacaopergunta,
                                                db104_descricao, 
                                                db104_aceitatexto, 
                                                db104_identificador, 
                                                db104_peso, 
                                                db104_valorresposta, 
                                                db104_identificadorcampo
                                             )
values (nextval('avaliacaoperguntaopcao_db104_sequencial_seq'),
    3000000,
    'Laboratório específico para a educação profissional',
    'f',
    'laboratorio-específico-para-a-educacao-profissiona',
    0,
    null,
    'laboratorio_educacao_profissional'
),
(nextval('avaliacaoperguntaopcao_db104_sequencial_seq'),
    3000000,
    'Salas de oficinas da educação profissional',
    'f',
    'salas-de-oficinas-educacao-profissional',
    0,
    null,
    'salas_oficinas_educacao_profissional'
),
(nextval('avaliacaoperguntaopcao_db104_sequencial_seq'),
    4000229,
    'Materiais para educação profissional',
    'f',
    'materiais-para-educacao-profissional',
    0,
    null,
    'materiais_para_educacao_profissional'
);

delete from avaliacaoperguntaopcaolayoutcampo
where ed313_avaliacaoperguntaopcao in (
    select db104_sequencial from habitacao.avaliacaoperguntaopcao
    where db104_avaliacaopergunta = 3000010 and db104_identificadorcampo in (   'videocassete', 
                                                                                'retroprojetor', 
                                                                                'fax', 
                                                                                'maquina_fotografica_filmadora'
                                                                            )
);

delete from habitacao.avaliacaogrupoperguntaresposta
where db108_avaliacaoresposta in (
    select db106_sequencial from habitacao.avaliacaoresposta
    where db106_avaliacaoperguntaopcao in (
    	select db104_sequencial from habitacao.avaliacaoperguntaopcao
    	where db104_avaliacaopergunta = 3000010 and db104_identificadorcampo in (   'videocassete', 
                                                                                    'retroprojetor', 
                                                                                    'fax', 
                                                                                    'maquina_fotografica_filmadora'
                                                                                )
	)
);

delete from habitacao.avaliacaoresposta
where db106_avaliacaoperguntaopcao in (
    select db104_sequencial from habitacao.avaliacaoperguntaopcao
    where db104_avaliacaopergunta = 3000010 and db104_identificadorcampo in (   'videocassete', 
                                                                                'retroprojetor', 
                                                                                'fax', 
                                                                                'maquina_fotografica_filmadora'
                                                                            )
);

delete from habitacao.avaliacaoperguntaopcao 
where db104_avaliacaopergunta = 3000010 and db104_identificadorcampo in (   'videocassete', 
                                                                            'retroprojetor', 
                                                                            'fax', 
                                                                            'maquina_fotografica_filmadora'
                                                                        );

SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL

update habitacao.avaliacaoperguntaopcao 
set db104_avaliacaopergunta = 3000010
where db104_identificadorcampo in (
    'dvd', 
    'aparelho_de_som', 
    'aparelho_de_televisao', 
    'lousa_digital', 
    'projetor_multimidia_data_show', 
    'computadores_portateis', 
    'tablets', 
    'equipamentos_computadores_mesa'
);

delete from habitacao.avaliacaopergunta 
where db103_identificadorcampo in ('pergunta_quantidades_equipamentos', 'pergunta_quantidades_computadores');

update habitacao.avaliacaopergunta 
set db103_descricao = 'Equipamentos existentes:' 
where db103_sequencial = 3000010;

delete from habitacao.avaliacaoperguntaopcao 
where db104_identificadorcampo = 'computadores';

update habitacao.avaliacaoperguntaopcao 
set db104_aceitatexto = true
where db104_identificadorcampo in ( 'antena_parabolica', 
                                    'computadores', 
                                    'copiadora', 
                                    'impressora', 
                                    'impressora_multifuncional', 
                                    'scanner'
                                   );

delete from habitacao.avaliacaoperguntaopcao 
where db104_identificadorcampo in ( 'laboratorio_educacao_profissional', 
                                    'salas_oficinas_educacao_profissional', 
                                    'materiais_para_educacao_profissional'
                                  );

insert into habitacao.avaliacaoperguntaopcao (  db104_sequencial, 
                                                db104_avaliacaopergunta, 
                                                db104_descricao, 
                                                db104_aceitatexto, 
                                                db104_identificador, 
                                                db104_peso, 
                                                db104_identificadorcampo
                                            )
values (    nextval('avaliacaoperguntaopcao_db104_sequencial_seq'), 
            3000010, 
            'Videocassete',  
            true, 
            'videocassete', 
            0, 
            'videocassete'
        );

insert into habitacao.avaliacaoperguntaopcao (  db104_sequencial, 
                                                db104_avaliacaopergunta, 
                                                db104_descricao, 
                                                db104_aceitatexto, 
                                                db104_identificador, 
                                                db104_peso, 
                                                db104_identificadorcampo
                                            )
values (    nextval('avaliacaoperguntaopcao_db104_sequencial_seq'), 
            3000010, 
            'Retroprojetor', 
            true, 
            'retroprojetor', 
            0, 
            'retroprojetor'
        );

insert into habitacao.avaliacaoperguntaopcao (  db104_sequencial, 
                                                db104_avaliacaopergunta, 
                                                db104_descricao, 
                                                db104_aceitatexto, 
                                                db104_identificador, 
                                                db104_peso, 
                                                db104_identificadorcampo
                                            )
values (nextval('avaliacaoperguntaopcao_db104_sequencial_seq'), 3000010, 'Fax', true, 'fax', 0, 'fax');

insert into habitacao.avaliacaoperguntaopcao (  db104_sequencial, 
                                                db104_avaliacaopergunta, 
                                                db104_descricao, 
                                                db104_aceitatexto, 
                                                db104_identificador, 
                                                db104_peso, 
                                                db104_identificadorcampo
                                            )
values (    nextval('avaliacaoperguntaopcao_db104_sequencial_seq'), 
            3000010, 'Máquina Fotográfica/Filmadora', 
            true, 'maquina_fotografica', 
            0, 
            'maquina_fotografica_filmadora'
        );

SQL
        );
    }
}
