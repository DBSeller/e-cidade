<?php

use Classes\PostgresMigration;

class M13298AlteracoesRegistro30 extends PostgresMigration
{
    public function up()
    {
        $this->necessidade();
        $this->cadastroAluno();
        $this->recursosEspeciais();
        $this->cadastroRecHumano();
        $this->atualizaFormularioCenso();
        $this->incluiDicionarioFormacaoCensoDisciplina();
        $this->incluiTabelaFormacaoCensoDisciplina();
    }
    private function necessidade()
    {
        $this->execute("update necessidade set ed48_c_descr = 'TRANSTORNO DO ESPECTRO AUTISTA' where ed48_i_codigo = 109");
    }
    private function recursosEspeciais()
    {
        $this->execute("
            insert into recursosavaliacaoinep values (111, 'CD com áudio para deficiente visual');
            insert into recursosavaliacaoinep values (112, 'Prova de Língua Portuguesa como Segunda Língua para surdos e deficientes auditivos');
            insert into recursosavaliacaoinep values (113, 'Prova em Vídeo em Libras');
        ");
    }

    private function cadastroAluno()
    {
        // dicionario
        $this->execute("
            insert into db_syscampo values(1010417,'ed47_localizacaodiferenciada','int4','Campo para o censo 1 - Área de assentamento 2 - Terra indígena 3 - Área onde se localiza comunidade remanescente de quilombos 7 - Não está em área de localização diferenciada','0', 'Localização diferenciada',10,'t','f','f',1,'text','Localização diferenciada');
            insert into db_syscampo values(1010418,'ed47_paisresidencia','int4','País de residencia','0', 'País de residencia',10,'t','f','f',1,'text','País de residencia');
            
            insert into db_sysarqcamp values(1010051,1010418,73,0);
            insert into db_sysarqcamp values(1010051,1010417,74,0);
            insert into db_sysforkey values(1010051,1010418,1,1942,0);
            insert into db_sysindices values(1008447,'aluno_paisresidencia_in',1010051,'0');
            insert into db_syscadind values(1008447,1010418,1);
        ");
        // alteração na estrutura
        $this->execute("
            alter table aluno add column ed47_paisresidencia int default null;
            alter table aluno add column ed47_localizacaodiferenciada int default null;
            alter table aluno add constraint aluno_paisresidencia_fk foreign key (ed47_paisresidencia) references pais;
        ");

        $this->execute("update aluno set ed47_paisresidencia = 10;");
    }

    private function cadastroRecHumano()
    {
        // dicionário
        $this->execute("
            insert into db_syscampo values(1010419,'ed20_paisresidencia ','int4','País de Residencia','0', 'País de Residencia',10,'t','f','f',1,'text','País de Residencia');
            insert into db_syscampo values(1010420,'ed20_localizacaodiferenciada','int4','Localização Diferenciada é um campo para o envio do censo: 1 - Área de assentamento 2 - Terra indígena 3 - Área onde se localiza comunidade remanescente de quilombos 7 - Não está em área de localização diferenciada ','0', 'Localização Diferenciada',10,'t','f','f',1,'text','Localização Diferenciada');
            insert into db_syscampo values(1010421,'ed20_tipoensinomedio ','float4','Tipo de Ensino Médio é um campo para o censo: 1 - Formação geral 2 - Modalidade normal (magistério) 3 - Curso técnico 4 - Magistério indígena modalidade normal','0', 'Tipo de Ensino Médio',10,'t','f','f',4,'text','Tipo de Ensino Médio');
            insert into db_sysarqcamp values(1010087,1010421,31,0);
            insert into db_sysarqcamp values(1010087,1010420,32,0);
            insert into db_sysarqcamp values(1010087,1010419,33,0);
            insert into db_sysforkey values(1010087,1010419,1,1942,0);
            insert into db_sysindices values(1008448,'rechumano_paisresidencia_in',1010087,'0');
            insert into db_syscadind values(1008448,1010419,1);
        ");

        // estrutura
        $this->execute("
            alter table rechumano add column ed20_paisresidencia int default null;
            alter table rechumano add column ed20_localizacaodiferenciada int default null;
            alter table rechumano add column ed20_tipoensinomedio int default null;
            ALTER TABLE rechumano ADD CONSTRAINT rechumano_paisresidencia_fk FOREIGN KEY (ed20_paisresidencia) REFERENCES pais;
        ");

        $this->execute("update rechumano set ed20_paisresidencia = 10;");
    }

    public function down()
    {
        // necessidade
        $this->execute("update necessidade set ed48_c_descr = 'AUTISMO INFANTIL' where ed48_i_codigo = 109");

        // aluno
        $this->execute("
            alter table aluno drop column ed47_paisresidencia;
            alter table aluno drop column ed47_localizacaodiferenciada;

            delete from db_syscadind where codind = 1008447;
            delete from db_sysindices where codind = 1008447;
            delete from db_sysforkey where codcam in (1010418);
            delete from db_sysarqcamp  where codcam in (1010417, 1010418);
            delete from db_syscampo  where codcam in (1010417, 1010418);
        ");

        // recursosEspeciais
        $this->execute("delete from recursosavaliacaoinep where ed326_sequencial in (111,112,113);");

        // cadastroRecHumano
        $this->execute("
            alter table rechumano drop column ed20_paisresidencia;
            alter table rechumano drop column ed20_localizacaodiferenciada;
            alter table rechumano drop column ed20_tipoensinomedio;
            delete from db_syscadind where codind = 1008448;
            delete from db_sysindices where codind = 1008448;
            delete from db_sysforkey where codcam in (1010419);
            delete from db_sysarqcamp  where codcam in (1010419, 1010420, 1010421);
            delete from db_syscampo  where codcam in (1010419, 1010420, 1010421);
        ");

        $this->reverteFormularioCenso();
        $this->excluiDicionarioFormacaoCensoDisciplina();
        $this->excluiTabelaFormacaoCensoDisciplina();
    }

    private function atualizaFormularioCenso()
    {
        $sql = "
            update avaliacao set db101_sequencial = 3000002 , db101_avaliacaotipo = 3 , db101_descricao = 'CENSO RECURSO HUMANO' , db101_identificador = 'censo-recurso-humano' , db101_ativo = 'true' , db101_permiteedicao = 'false' where db101_sequencial = 3000002;
            update avaliacaogrupopergunta set db102_sequencial = 3000002 , db102_avaliacao = 3000002 , db102_descricao = 'RH' , db102_identificador = 'rh' , db102_identificadorcampo = 'grupo_rh' , db102_ordem = 1 where db102_sequencial = 3000002;
            update avaliacaopergunta set db103_sequencial = 3000012 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000002 , db103_descricao = 'Pós - Graduação:' , db103_identificador = 'pos-graduacao' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'pos_graduacao' where db103_sequencial = 3000012;
            update avaliacaoperguntaopcao set db104_sequencial = 3000073 , db104_avaliacaopergunta = 3000012 , db104_descricao = 'Especialização' , db104_identificador = 'especializacao' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especializacao' where db104_sequencial = 3000073;
            update avaliacaoperguntaopcao set db104_sequencial = 3000074 , db104_avaliacaopergunta = 3000012 , db104_descricao = 'Mestrado' , db104_identificador = 'mestrado' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'mestrado' where db104_sequencial = 3000074;
            update avaliacaoperguntaopcao set db104_sequencial = 3000075 , db104_avaliacaopergunta = 3000012 , db104_descricao = 'Doutorado' , db104_identificador = 'doutorado' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'doutorado' where db104_sequencial = 3000075;
            update avaliacaoperguntaopcao set db104_sequencial = 3000076 , db104_avaliacaopergunta = 3000012 , db104_descricao = 'Nenhum' , db104_identificador = 'nenhum5cbdce4bb1a1f' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'nenhum' where db104_sequencial = 3000076;
            update avaliacaopergunta set db103_sequencial = 3000013 , db103_avaliacaotiporesposta = 3 , db103_avaliacaogrupopergunta = 3000002 , db103_descricao = 'Outros Cursos:' , db103_identificador = 'outros-cursos' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'outros_cursos' where db103_sequencial = 3000013;
            update avaliacaoperguntaopcao set db104_sequencial = 3000077 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para Creche' , db104_identificador = 'especifico-para-creche' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_creche' where db104_sequencial = 3000077;
            update avaliacaoperguntaopcao set db104_sequencial = 3000078 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para Pré-Escola' , db104_identificador = 'especifico-para-preescola' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_pre_escola' where db104_sequencial = 3000078;
            update avaliacaoperguntaopcao set db104_sequencial = 3000079 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para anos iniciais do ensino fundamenta' , db104_identificador = 'especifico-para-anos-iniciais-do-ensino-fundamenta' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_anos_iniciais' where db104_sequencial = 3000079;
            update avaliacaoperguntaopcao set db104_sequencial = 3000080 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para ano finais do ensino fundamental' , db104_identificador = 'especifico-para-ano-finais-do-ensino-fundamental' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_anos_finais' where db104_sequencial = 3000080;
            update avaliacaoperguntaopcao set db104_sequencial = 3000081 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para ensino médio' , db104_identificador = 'especifico-para-ensino-medio' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_ensino_medio' where db104_sequencial = 3000081;
            update avaliacaoperguntaopcao set db104_sequencial = 3000082 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para educação de jovens e adultos' , db104_identificador = 'especifico-para-educacao-de-jovens-e-adultos' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_eja' where db104_sequencial = 3000082;
            update avaliacaoperguntaopcao set db104_sequencial = 3000084 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para Educação Indígena' , db104_identificador = 'especifico-para-educacao-indigena' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_indigena' where db104_sequencial = 3000084;
            update avaliacaoperguntaopcao set db104_sequencial = 3000114 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para educação do campo' , db104_identificador = 'especifico-para-educacao-do-campo' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_campo' where db104_sequencial = 3000114;
            update avaliacaoperguntaopcao set db104_sequencial = 3000115 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para educação ambiental' , db104_identificador = 'especifico-para-educacao-ambiental' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_ambiental' where db104_sequencial = 3000115;
            update avaliacaoperguntaopcao set db104_sequencial = 3000116 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para educação em direitos humanos' , db104_identificador = 'especifico-para-educacao-em-direitos-humanos' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_direitos_humanos' where db104_sequencial = 3000116;
            update avaliacaoperguntaopcao set db104_sequencial = 3000117 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Gênero e diversidade sexual' , db104_identificador = 'genero-e-diversidade-sexual' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'genero_diversidade_sexual' where db104_sequencial = 3000117;
            update avaliacaoperguntaopcao set db104_sequencial = 3000118 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Direitos de criança e adolescente' , db104_identificador = 'direitos-de-crianca-e-adolescente' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'direito_crianca_adolescente' where db104_sequencial = 3000118;
            update avaliacaoperguntaopcao set db104_sequencial = 3000119 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Relações etnicorraciais/hist/cult afo-br e african' , db104_identificador = 'relacoes-etnicorraciaishistcult-afobr-e-african' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'relacoes_etnicorraciais' where db104_sequencial = 3000119;
            update avaliacaoperguntaopcao set db104_sequencial = 3000120 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Outros' , db104_identificador = 'outros5cbdce4bbf3f4' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'outros_cursos' where db104_sequencial = 3000120;
            update avaliacaoperguntaopcao set db104_sequencial = 3000121 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Nenhum' , db104_identificador = 'nenhum5cbdce4bc049b' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'nenhum_curso' where db104_sequencial = 3000121;
            update avaliacaoperguntaopcao set db104_sequencial = 3000127 , db104_avaliacaopergunta = 3000013 , db104_descricao = 'Específico para Educação especial' , db104_identificador = 'especifico-para-educacao-especial' , db104_aceitatexto = 'false' , db104_identificadorcampo = 'especifico_educacao_especial' where db104_sequencial = 3000127;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001293 ,3000013 ,'Gestão Escolar' ,'gestao-escolar' ,'false' ,0 ,'' ,'gestao_escolar' );
        ";

        $this->execute($sql);
    }

    private function reverteFormularioCenso()
    {
        $this->execute("delete from avaliacaoperguntaopcao where db104_sequencial = 4001293;");
    }

    private function excluiDicionarioFormacaoCensoDisciplina()
    {
        $sql = "
            delete from db_sysarqcamp where codarq = 1010441;
            delete from db_sysprikey where codarq = 1010441;
            delete from db_sysforkey where codarq = 1010441;
            delete from db_sysarqmod where codarq = 1010441;
            delete from db_syssequencia where codsequencia = 1000831;
            delete from db_syscadind where codcam in (1010438, 1010439, 1010440);
            delete from db_syscampo where codcam in (1010438, 1010439, 1010440);
            delete from db_sysindices where codarq = 1010441;
            delete from db_sysarquivo where codarq = 1010441;
        ";
        $this->execute($sql);
    }

    private function incluiDicionarioFormacaoCensoDisciplina()
    {
        $sql = "
            insert into db_sysarquivo values (1010441, 'formacaocensodisciplina', 'Guarda o vínculo entre formacao e censodisciplina', 'ed145', '2019-04-23', 'formacaocensodisciplina', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (1008004,1010441);
            insert into db_syscampo values(1010438,'ed145_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010439,'ed145_formacao','int4','Formação','0', 'Formação',10,'f','f','f',1,'text','Formação');
            insert into db_syscampo values(1010440,'ed145_censodisciplina','int4','Censo','0', 'Censo',10,'f','f','f',1,'text','Censo');
            insert into db_sysarqcamp values(1010441,1010438,1,0);
            insert into db_sysarqcamp values(1010441,1010439,2,0);
            insert into db_sysarqcamp values(1010441,1010440,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010441,1010438,1,1010438);
            insert into db_sysforkey values(1010441,1010439,1,1010089,0);
            insert into db_sysforkey values(1010441,1010440,1,2412,0);
            insert into db_sysindices values(1008449,'formacaocensodisciplina_ed145_sequencial_seq',1010441,'0');
            insert into db_syscadind values(1008449,1010438,1);
            insert into db_syssequencia values(1000831, 'formacaocensodisciplina_ed145_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000831 where codarq = 1010441 and codcam = 1010438;
        ";
        $this->execute($sql);
    }

    private function excluiTabelaFormacaoCensoDisciplina()
    {
        $sql = "
            DROP SEQUENCE IF EXISTS formacaocensodisciplina_ed145_sequencial_seq;
            DROP INDEX IF EXISTS formacaocensodisciplina_ed145_sequencial_seq;
            DROP TABLE formacaocensodisciplina;
        ";
        $this->execute($sql);
    }

    private function incluiTabelaFormacaoCensoDisciplina()
    {
        $sql = "
            CREATE SEQUENCE formacaocensodisciplina_ed145_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE formacaocensodisciplina(
            ed145_sequencial		int4 default nextval('formacaocensodisciplina_ed145_sequencial_seq'),
            ed145_formacao		int4 default 0,
            ed145_censodisciplina		int4 default 0,
            CONSTRAINT formacaocensodisciplina_sequ_pk PRIMARY KEY (ed145_sequencial));
            
            ALTER TABLE formacaocensodisciplina
            ADD CONSTRAINT formacaocensodisciplina_formacao_fk FOREIGN KEY (ed145_formacao)
            REFERENCES formacao;
            
            ALTER TABLE formacaocensodisciplina
            ADD CONSTRAINT formacaocensodisciplina_censodisciplina_fk FOREIGN KEY (ed145_censodisciplina)
            REFERENCES censodisciplina;
            
            -- CREATE  INDEX formacaocensodisciplina_ed145_sequencial_seq ON formacaocensodisciplina(ed145_sequencial);
        ";
        $this->execute($sql);
    }
}
