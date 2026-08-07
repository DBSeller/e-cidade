<?php

use Classes\PostgresMigration;

class M10182FormularioAlteracaoTrabalhadorSemVinculo extends PostgresMigration
{

    private $codigoFormulario = 3000032;

    public function up()
    {
        $this->criarFormulario();
        $this->criarEstruturaDicionarioDados();
        $this->criarMenu();
    }



    public function down()
    {

        $this->execute(
            <<<SQL_DOWN_FORMULARIO
            
DROP TABLE IF EXISTS avaliacaogruporespostatsvealteracao CASCADE;
DROP SEQUENCE IF EXISTS avaliacaogruporespostatsvealteracao_eso23_sequencial_seq;

delete from db_syssequencia where codsequencia = 1000767;
delete from db_syscadind where codcam in (1009955, 1009956, 1009958);
delete from db_sysindices where codarq = 1010318;
delete from db_sysforkey where codarq = 1010318;
delete from db_sysprikey where codarq = 1010318;
delete from db_sysarqcamp where codarq = 1010318;
delete from db_syscampo where codcam in (1009955, 1009956, 1009958);
delete from db_sysarqmod where codarq = 1010318;
delete from db_sysarquivo where codarq = 1010318;
     
drop table if exists avaliacao_10182;
drop table if exists avaliacaogrupopergunta_10182;
drop table if exists avaliacaopergunta_10182;
drop table if exists avaliacaoperguntaopcao_10182;

create temp table avaliacao_10182 as select * from avaliacao where db101_sequencial = {$this->codigoFormulario};
create temp table avaliacaogrupopergunta_10182 as select * from avaliacaogrupopergunta where db102_avaliacao = {$this->codigoFormulario};
create temp table avaliacaopergunta_10182 as select * from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = {$this->codigoFormulario});
create temp table avaliacaoperguntaopcao_10182 as select * from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta_10182);

delete from esocialversaoformulario where rh211_esocialformulariotipo = 20;
delete from esocialformulariotipo where rh209_sequencial = 20; 

delete from avaliacaoperguntaopcao where db104_sequencial in (select db104_sequencial from avaliacaoperguntaopcao_10182);
delete from avaliacaopergunta      where db103_sequencial in (select db103_sequencial from avaliacaopergunta_10182);
delete from avaliacaogrupopergunta where db102_sequencial in (select db102_sequencial from avaliacaogrupopergunta_10182);
delete from avaliacao              where db101_sequencial in (select db101_sequencial from avaliacao_10182);

delete from db_menu where id_item_filho = 10581;
delete from db_itensfilho where id_item = 10581;
delete from db_itensmenu where id_item = 10581;
SQL_DOWN_FORMULARIO
        );
    }

    private function criarMenu()
    {
        $this->execute(
            <<<SQL_UP_MENU
insert into db_itensmenu values( 10581, 'Alteração', 'Alteração cadastral de TSVE', 'eso02_preenchimentoesocial001.php?formularioTipo=20', '1', '1', '', '1'	);
insert into db_itensfilho (id_item, codfilho) values(10581,1);
insert into db_menu values(10570,10581,3,10216);
SQL_UP_MENU
        );
    }

    private function criarEstruturaDicionarioDados()
    {
        $this->execute(
            <<<SQL_UP_DICIONARIO
            
insert into db_sysarquivo values (1010318, 'avaliacaogruporespostatsvealteracao', 'avaliacaogruporespostatsvealteracao', 'eso23', '2018-09-18', 'avaliacaogruporespostatsvealteracao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (81,1010318);
insert into db_syscampo values(1009955,'eso23_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1009956,'eso23_avaliacaogruporesposta','int4','Grupo de Resposta','0', 'Grupo de Resposta',10,'f','f','f',1,'text','Grupo de Resposta');
insert into db_syscampo values(1009958,'eso23_rhpessoal','int4','Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
delete from db_sysarqcamp where codarq = 1010318;
insert into db_sysarqcamp values(1010318,1009955,1,0);
insert into db_sysarqcamp values(1010318,1009956,2,0);
insert into db_sysarqcamp values(1010318,1009958,3,0);
delete from db_sysprikey where codarq = 1010318;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010318,1009955,1,1009958);
insert into db_sysforkey values(1010318,1009956,1,2987,0);
insert into db_sysforkey values(1010318,1009958,1,1153,0);
insert into db_sysindices values(1008327,'avaliacaogruporespostatsvealteracao_avaliacaogruporesposta_in',1010318,'0');
insert into db_syscadind values(1008327,1009956,1);
insert into db_sysindices values(1008328,'avaliacaogruporespostatsvealteracao_rhpessoal_in',1010318,'0');
insert into db_syscadind values(1008328,1009958,1);
insert into db_syssequencia values(1000767, 'avaliacaogruporespostatsvealteracao_eso23_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000767 where codarq = 1010318 and codcam = 1009955;


DROP TABLE IF EXISTS avaliacaogruporespostatsvealteracao CASCADE;
DROP SEQUENCE IF EXISTS avaliacaogruporespostatsvealteracao_eso23_sequencial_seq;

CREATE SEQUENCE avaliacaogruporespostatsvealteracao_eso23_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE avaliacaogruporespostatsvealteracao(
eso23_sequencial     int4 NOT NULL default 0,
eso23_avaliacaogruporesposta   int4 NOT NULL default 0,
eso23_rhpessoal    int4 not null default 0,
CONSTRAINT avaliacaogruporespostatsvealteracao_sequ_pk PRIMARY KEY (eso23_sequencial));

ALTER TABLE avaliacaogruporespostatsvealteracao
ADD CONSTRAINT avaliacaogruporespostatsvealteracao_avaliacaogruporesposta_fk FOREIGN KEY (eso23_avaliacaogruporesposta)
REFERENCES avaliacaogruporesposta;

ALTER TABLE avaliacaogruporespostatsvealteracao
ADD CONSTRAINT avaliacaogruporespostatsvealteracao_rhpessoal_fk FOREIGN KEY (eso23_rhpessoal)
REFERENCES rhpessoal;

CREATE  INDEX avaliacaogruporespostatsvealteracao_avaliacaogruporesposta_in ON avaliacaogruporespostatsvealteracao(eso23_avaliacaogruporesposta);
CREATE  INDEX avaliacaogruporespostatsvealteracao_rhpessoal_in ON avaliacaogruporespostatsvealteracao(eso23_rhpessoal);

SQL_UP_DICIONARIO
        );
    }

    private function criarFormulario()
    {
        $this->execute(
            <<<SQL_UP_FORMULARIO

insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000032 ,5 ,'S-2306 - Alteração TSVE' ,'s2306-alteracao-tsve' ,'S-2205 - Alteração de Trabalhador sem Vínculo Empregatício' ,'true' ,'' ,'true' );

insert into esocialformulariotipo values (20, 'S-2306 - Alteração Cadastral de TSVE');
insert into esocialversaoformulario values (nextval('esocialversaoformulario_rh211_sequencial_seq'), 2.4, 3000032, 20);

insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000479 ,3000032 ,'Identificação do Trabalhador Sem Vínculo de Emprego' ,'identificacao-do-trabalhador-sem-vinculo-de-empreg' ,'ideTrabSemVinculo' ,1 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002152 ,2 ,3000479 ,'CPF' ,'cpf5babdf2a6e581' ,'true' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'cpfTrab' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002152;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000452 ,3002152 ,'' ,'5babdf2a77e46' ,'true' ,0 ,'' ,'cpfTrab' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002153 ,2 ,3000479 ,'NIS' ,'nis' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nisTrab' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002153;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000453 ,3002153 ,'' ,'5babdf2a8007e' ,'true' ,0 ,'' ,'nisTrab' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002154 ,2 ,3000479 ,'Código da Categoria' ,'codigo-da-categoria' ,'true' ,'true' ,3 ,6 ,'' ,0 ,'false' ,'' ,'codCateg' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002154;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000454 ,3002154 ,'' ,'5babdf2a81d67' ,'true' ,0 ,'' ,'codCateg' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000480 ,3000032 ,'Dados da Alteração' ,'dados-da-alteracao' ,'infoTSVAlteracao' ,2 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002155 ,2 ,3000480 ,'Data de Alteração' ,'data-de-alteracao' ,'true' ,'true' ,1 ,5 ,'' ,0 ,'false' ,'' ,'dtAlteracao' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002155;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000455 ,3002155 ,'' ,'5babdf2a84286' ,'true' ,0 ,'' ,'dtAlteracao' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002156 ,1 ,3000480 ,'Natureza da Atividade' ,'natureza-da-atividade5babdf2a84d89' ,'false' ,'true' ,2 ,6 ,'' ,0 ,'false' ,'' ,'natAtividade' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002156;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000456 ,3002156 ,'Trabalho Urbano' ,'trabalho-urbano5babdf2a883cd' ,'false' ,0 ,'1' ,'natAtividade_1' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000457 ,3002156 ,'Trabalho Rural' ,'trabalho-rural5babdf2a88f40' ,'false' ,0 ,'2' ,'natAtividade_2' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000481 ,3000032 ,'Cargo/Função' ,'cargofuncao' ,'cargoFuncao' ,3 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002157 ,2 ,3000481 ,'Código do Cargo' ,'codigo-do-cargo' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'codCargo' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002157;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000458 ,3002157 ,'' ,'5babdf2a8b41d' ,'true' ,0 ,'' ,'codCargo' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002158 ,2 ,3000481 ,'Código da Função' ,'codigo-da-funcao' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'codFuncao' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002158;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000459 ,3002158 ,'' ,'5babdf2a8ce1e' ,'true' ,0 ,'' ,'codFuncao' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000482 ,3000032 ,'Remuneração' ,'remuneracao5babdf2a8d91e' ,'remuneracao' ,4 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002159 ,2 ,3000482 ,'Valor Remunerado' ,'valor-remunerado' ,'false' ,'true' ,1 ,8 ,'' ,0 ,'false' ,'' ,'vrSalFx' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002159;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000460 ,3002159 ,'' ,'5babdf2a8f2d6' ,'true' ,0 ,'' ,'vrSalFx' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002160 ,1 ,3000482 ,'Unidade de Pagamento' ,'unidade-de-pagamento' ,'false' ,'true' ,2 ,6 ,'' ,0 ,'false' ,'' ,'undSalFixo' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002160;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000461 ,3002160 ,'Por Hora' ,'por-hora5babdf2a920b9' ,'false' ,0 ,'1' ,'undSalFixo_1' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000462 ,3002160 ,'Por Dia' ,'por-dia5babdf2a92dd2' ,'false' ,0 ,'2' ,'undSalFixo_2' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000463 ,3002160 ,'Por Semana' ,'por-semana5babdf2a93950' ,'false' ,0 ,'3' ,'undSalFixo_3' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000464 ,3002160 ,'Por Quinzena' ,'por-quinzena5babdf2a945b0' ,'false' ,0 ,'4' ,'undSalFixo_4' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000465 ,3002160 ,'Por Mês' ,'por-mes5babdf2a95168' ,'false' ,0 ,'5' ,'undSalFixo_5' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000466 ,3002160 ,'Por Tarefa' ,'por-tarefa5babdf2a98cd1' ,'false' ,0 ,'6' ,'undSalFixo_6' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000467 ,3002160 ,'Não Aplicável - salário exclusivamente variável' ,'nao-aplicavel-salario-exclusivament5babdf2a9972f' ,'false' ,0 ,'7' ,'undSalFixo_7' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002161 ,2 ,3000482 ,'Descrição do Salário por Tarefa' ,'descricao-do-salario-por-tarefa' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'dscSalVar' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002161;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000468 ,3002161 ,'' ,'5babdf2a9b0bb' ,'true' ,0 ,'' ,'dscSalVar' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000483 ,3000032 ,'Informações do Estagiário' ,'informacoes-do-estagiario' ,'infoEstagiario' ,5 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002162 ,1 ,3000483 ,'Natureza do Estágio' ,'natureza-do-estagio5babdf2a9c531' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'natEstagio' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002162;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000469 ,3002162 ,'Obrigatório' ,'obrigatorio5babdf2a9d622' ,'false' ,0 ,'O' ,'natEstagio_O' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000470 ,3002162 ,'Não Obrigatório' ,'nao-obrigatorio5babdf2a9e05a' ,'false' ,0 ,'N' ,'natEstagio_N' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002163 ,1 ,3000483 ,'Nível do Estágio' ,'nivel-do-estagio5babdf2a9ebef' ,'false' ,'true' ,2 ,6 ,'' ,0 ,'false' ,'' ,'nivEstagio' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002163;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000471 ,3002163 ,'Fundamental' ,'fundamental5babdf2a9fe35' ,'false' ,0 ,'1' ,'nivEstagio_1' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000472 ,3002163 ,'Médio' ,'medio5babdf2aa0882' ,'false' ,0 ,'2' ,'nivEstagio_2' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000473 ,3002163 ,'Formação Profissional' ,'formacao-profissional5babdf2aa1269' ,'false' ,0 ,'3' ,'nivEstagio_3' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000474 ,3002163 ,'Superior' ,'superior5babdf2aa1c1e' ,'false' ,0 ,'4' ,'nivEstagio_4' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000475 ,3002163 ,'Especial' ,'especial5babdf2aa42b9' ,'false' ,0 ,'8' ,'nivEstagio_8' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000476 ,3002163 ,'Mãe Social (Lei 7644 de 1987)' ,'mae-social-lei-7644-de-19875babdf2aa4ddf' ,'false' ,0 ,'9' ,'nivEstagio_9' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002164 ,2 ,3000483 ,'Área de Atuação do Estagiário' ,'area-de-atuacao-do-estagiario5babdf2aa5940' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'areaAtuacao' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002164;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000477 ,3002164 ,'' ,'5babdf2aa6845' ,'true' ,0 ,'' ,'areaAtuacao' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002165 ,2 ,3000483 ,'Número da Apólice de Seguro' ,'numero-da-apolice-de-seguro' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'nrApol' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002165;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000478 ,3002165 ,'' ,'5babdf2aa84c0' ,'true' ,0 ,'' ,'nrApol' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002166 ,2 ,3000483 ,'Valor da Bolsa - se o estágio for remunerado' ,'valor-da-bolsa-se-o-estagio-for-rem5babdf2aa8f08' ,'false' ,'true' ,5 ,8 ,'' ,0 ,'false' ,'' ,'vlrBolsa' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002166;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000479 ,3002166 ,'' ,'5babdf2aa9fbd' ,'true' ,0 ,'' ,'vlrBolsa' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002167 ,2 ,3000483 ,'Data prevista para o término do estágio' ,'data-prevista-para-o-termino-do-estag5babdf2aaaa6a' ,'false' ,'true' ,6 ,5 ,'' ,0 ,'false' ,'' ,'dtPrevTerm' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002167;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000480 ,3002167 ,'' ,'5babdf2aaba6b' ,'true' ,0 ,'' ,'dtPrevTerm' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000484 ,3000032 ,'Instituição de Ensino' ,'instituicao-de-ensino5babdf2aac6c8' ,'instEnsino' ,6 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002168 ,2 ,3000484 ,'CNPJ' ,'cnpj' ,'false' ,'true' ,1 ,3 ,'' ,0 ,'false' ,'' ,'instEnsino_cnpjInstEnsino' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002168;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000481 ,3002168 ,'' ,'5babdf2aadd91' ,'true' ,0 ,'' ,'instEnsino_cnpjInstEnsino' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002169 ,2 ,3000484 ,'Nome' ,'nome5babdf2aae726' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'instEnsino_nmRazao' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002169;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000482 ,3002169 ,'' ,'5babdf2aaf6a9' ,'true' ,0 ,'' ,'instEnsino_nmRazao' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002170 ,2 ,3000484 ,'Logradouro' ,'logradouro5babdf2ab2d2f' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'instEnsino_dscLograd' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002170;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000483 ,3002170 ,'' ,'5babdf2ab4098' ,'true' ,0 ,'' ,'instEnsino_dscLograd' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002171 ,2 ,3000484 ,'Número' ,'numero5babdf2ab4b47' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'instEnsino_nrLograd' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002171;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000484 ,3002171 ,'' ,'5babdf2ab79af' ,'true' ,0 ,'' ,'instEnsino_nrLograd' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002172 ,2 ,3000484 ,'Bairro' ,'bairro5babdf2ab84fa' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'instEnsino_bairro' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002172;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000485 ,3002172 ,'' ,'5babdf2ab9bde' ,'true' ,0 ,'' ,'instEnsino_bairro' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002173 ,2 ,3000484 ,'CEP' ,'cep5babdf2abc7ac' ,'false' ,'true' ,6 ,2 ,'' ,0 ,'false' ,'' ,'instEnsino_cep' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002173;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000486 ,3002173 ,'' ,'5babdf2abd829' ,'true' ,0 ,'' ,'instEnsino_cep' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002174 ,2 ,3000484 ,'Código do Município' ,'codigo-do-municipio' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'instEnsino_codMunic' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002174;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000487 ,3002174 ,'' ,'5babdf2abefb9' ,'true' ,0 ,'' ,'instEnsino_codMunic' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002175 ,2 ,3000484 ,'UF' ,'uf5babdf2abfcaa' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'instEnsino_uf' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002175;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000488 ,3002175 ,'' ,'5babdf2ac0d6e' ,'true' ,0 ,'' ,'instEnsino_uf' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000485 ,3000032 ,'Agente de Integração' ,'agente-de-integracao5babdf2ac1760' ,'ageIntegracao' ,7 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002176 ,2 ,3000485 ,'CNPJ' ,'cnpj5babdf2ac1ffb' ,'false' ,'true' ,1 ,3 ,'' ,0 ,'false' ,'' ,'ageIntegracao_cnpjAgntInteg' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002176;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000489 ,3002176 ,'' ,'5babdf2ac2fc7' ,'true' ,0 ,'' ,'ageIntegracao_cnpjAgntInteg' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002177 ,2 ,3000485 ,'Nome' ,'nome5babdf2ac3bd2' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'ageIntegracao_nmRazao' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002177;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000490 ,3002177 ,'' ,'5babdf2ac4c55' ,'true' ,0 ,'' ,'ageIntegracao_nmRazao' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002178 ,2 ,3000485 ,'Logradouro' ,'logradouro5babdf2ac5674' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'ageIntegracao_dscLograd' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002178;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000491 ,3002178 ,'' ,'5babdf2ac65db' ,'true' ,0 ,'' ,'ageIntegracao_dscLograd' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002179 ,2 ,3000485 ,'Número' ,'numero5babdf2ac6f56' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'ageIntegracao_nrLograd' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002179;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000492 ,3002179 ,'' ,'5babdf2ac7fc0' ,'true' ,0 ,'' ,'ageIntegracao_nrLograd' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002180 ,2 ,3000485 ,'Bairro' ,'bairro5babdf2acca09' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'ageIntegracao_bairro' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002180;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000493 ,3002180 ,'' ,'5babdf2acdbb9' ,'true' ,0 ,'' ,'ageIntegracao_bairro' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002181 ,2 ,3000485 ,'CEP' ,'cep5babdf2ace8d2' ,'false' ,'true' ,6 ,2 ,'' ,0 ,'false' ,'' ,'ageIntegracao_cep' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002181;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000494 ,3002181 ,'' ,'5babdf2ad10b5' ,'true' ,0 ,'' ,'ageIntegracao_cep' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002182 ,2 ,3000485 ,'Código do Município' ,'codigo-do-municipio5babdf2ad1cd4' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'ageIntegracao_codMunic' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002182;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000495 ,3002182 ,'' ,'5babdf2ad2f43' ,'true' ,0 ,'' ,'ageIntegracao_codMunic' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002183 ,2 ,3000485 ,'UF' ,'uf5babdf2ad3eb9' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'ageIntegracao_uf' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002183;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000496 ,3002183 ,'' ,'5babdf2ad5296' ,'true' ,0 ,'' ,'ageIntegracao_uf' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000486 ,3000032 ,'Supervisor do Estágio' ,'supervisor-do-estagio5babdf2ad5dbb' ,'supervisorEstagio' ,8 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002184 ,2 ,3000486 ,'CPF' ,'cpf5babdf2ad66dd' ,'false' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'cpfSupervisor' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002184;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000497 ,3002184 ,'' ,'5babdf2ad7655' ,'true' ,0 ,'' ,'cpfSupervisor' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002185 ,2 ,3000486 ,'Nome' ,'nome5babdf2ad81ca' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nmSuperv' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002185;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000498 ,3002185 ,'' ,'5babdf2ad9388' ,'true' ,0 ,'' ,'nmSuperv' );

SQL_UP_FORMULARIO
        );
    }
}
