<?php

use Classes\PostgresMigration;

class M10543AfastamentoEsocial extends PostgresMigration
{
    public function up()
    {
        $this->preMenu();
        $this->createTables();
        $this->createDinamicForm();
        $this->createFormulaCnpjSindicato();
        $this->createMenu();
        $this->addAtributosDinamicos();
    }

    public function down()
    {
        $this->downPreMenu();
        $this->dropTables();
        $this->deleteDinamicForm();
        $this->dropFormulaCnpjSindicato();
        $this->deleteMenu();
        $this->deleteAtributosDinamicos();
    }

    private function preMenu()
    {
        // afastamentoesocial
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010283, 'afastamentoservidoresocial', 'Guarda os afastamentos lançados para o servidor que foram configurados como assentamento do eSocial', 'eso12', '2018-06-04', 'Afastamento do eSocial', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (81,1010283);
insert into db_syscampo values(1009753,'eso12_sequencial','int4','Sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1009754,'eso12_assenta','int4','Código','null', 'Assenta',10,'t','f','f',1,'text','Código');
insert into db_syscampo values(1009755,'eso12_rhpessoal','int4','Matrícula do servidor','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
insert into db_sysarqcamp values(1010283,1009753,1,0);
insert into db_sysarqcamp values(1010283,1009754,2,0);
insert into db_sysarqcamp values(1010283,1009755,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010283,1009753,1,1009753);
insert into db_sysforkey values(1010283,1009754,1,528,0);
insert into db_sysforkey values(1010283,1009755,1,1153,0);
insert into db_sysindices values(1008281,'afastamentoservidoresocial_assenta_in',1010283,'0');
insert into db_syscadind values(1008281,1009754,1);
insert into db_sysindices values(1008282,'afastamentoservidoresocial_rhpessoal_in',1010283,'0');
insert into db_syscadind values(1008282,1009755,1);
insert into db_syssequencia values(1000734, 'afastamentoservidoresocial_eso12_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000734 where codarq = 1010283 and codcam = 1009753;
SQL
        );

        // avaliacaogruporespostaafastamentoesocial
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010284, 'avaliacaogruporespostaafastamentoesocial', 'vínculo do afastamento do eSocial e o formulário ', 'eso13', '2018-06-04', 'avaliacaogruporespostaafastamentoesocial', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (81,1010284);
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009756,'eso13_sequencial' ,'int4' ,'Código' ,'' ,'Código' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Código' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009757,'eso13_avaliacaogruporesposta' ,'int4' ,'Preenchimento' ,'' ,'Preenchimento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Preenchimento' );
insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009758,'eso13_afastamentoservidoresocial' ,'int4' ,'Afastamento do eSocial' ,'' ,'Afastamento do eSocial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Afastamento do eSocial' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010284 ,1009756 ,1 ,0 );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010284 ,1009757 ,2 ,0 );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010284 ,1009758 ,3 ,0 );
delete from db_sysprikey where codarq = 1010284;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010284,1009756,1,1009756);
delete from db_sysforkey where codarq = 1010284 and referen = 0;
insert into db_sysforkey values(1010284,1009757,1,2987,0);
delete from db_sysforkey where codarq = 1010284 and referen = 0;
insert into db_sysforkey values(1010284,1009758,1,1010283,0);
insert into db_sysindices values(1008283,'avaliacaogruporespostaafastamentoesocial_avaliacaogruporesposta_in',1010284,'0');
insert into db_syscadind values(1008283,1009757,1);
insert into db_sysindices values(1008284,'avaliacaogruporespostaafastamentoesocial_afastamentoesocial_in',1010284,'0');
insert into db_syscadind values(1008284,1009758,1);
insert into db_syssequencia values(1000735, 'avaliacaogruporespostaafastamentoesocial_eso13_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
SQL
        );
    }

    private function downPreMenu()
    {
        $this->execute(<<<SQL
delete from db_sysarqcamp where codarq in (1010283, 1010284);
delete from db_sysprikey where codarq in (1010283, 1010284);
delete from db_sysforkey where codarq in (1010283, 1010284);
delete from db_syscadind where codind in (1008281, 1008282, 1008283, 1008284);
delete from db_sysindices where codind in (1008281, 1008282, 1008283, 1008284);
delete from db_syssequencia where codsequencia in (1000734, 1000735);
delete from db_sysarqmod where codarq in (1010283, 1010284);
delete from db_syscampo where codcam in (1009753, 1009754, 1009755, 1009756, 1009757, 1009758);
delete from db_sysarquivo where codarq in (1010283, 1010284);
SQL
        );
    }

    private function dropTables()
    {
        $this->execute(<<<SQL
drop table if exists esocial.afastamentoservidoresocial cascade;
drop table if exists esocial.avaliacaogruporespostaafastamentoesocial cascade;

drop sequence if exists esocial.afastamentoservidoresocial_eso12_sequencial_seq;
drop sequence if exists esocial.avaliacaogruporespostaafastamentoesocial_eso13_sequencial_seq;
SQL
        );
    }

    private function createTables()
    {
        $this->execute(<<<SQL
create sequence esocial.afastamentoservidoresocial_eso12_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
create sequence esocial.avaliacaogruporespostaafastamentoesocial_eso13_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;

create table esocial.afastamentoservidoresocial(
eso12_sequencial int4 not null default nextval('afastamentoservidoresocial_eso12_sequencial_seq'),
eso12_assenta int4 default null,
eso12_rhpessoal int4 default 0,
constraint afastamentoservidoresocial_sequ_pk primary key (eso12_sequencial));

create table esocial.avaliacaogruporespostaafastamentoesocial(
eso13_sequencial int4 not null default nextval('avaliacaogruporespostaafastamentoesocial_eso13_sequencial_seq'),
eso13_avaliacaogruporesposta int4 not null ,
eso13_afastamentoservidoresocial int4 default 0,
constraint avaliacaogruporespostaafastamentoesocial_sequ_pk primary key (eso13_sequencial));

alter table esocial.afastamentoservidoresocial add constraint afastamentoservidoresocial_assenta_fk foreign key (eso12_assenta) references assenta;
alter table esocial.afastamentoservidoresocial add constraint afastamentoservidoresocial_rhpessoal_fk foreign key (eso12_rhpessoal) references rhpessoal;
alter table esocial.avaliacaogruporespostaafastamentoesocial add constraint avaliacaogruporespostaafastamentoesocial_avaliacaogruporesposta_fk foreign key (eso13_avaliacaogruporesposta) references avaliacaogruporesposta;
alter table esocial.avaliacaogruporespostaafastamentoesocial add constraint avaliacaogruporespostaafastamentoesocial_afastamentoservidoresocial_fk foreign key (eso13_afastamentoservidoresocial) references afastamentoservidoresocial;

create index afastamentoservidoresocial_assenta_in on esocial.afastamentoservidoresocial(eso12_assenta);
create index afastamentoservidoresocial_rhpessoal_in on esocial.afastamentoservidoresocial(eso12_rhpessoal);
create index avaliacaogruporespostaafastamentoesocial_avaliacaogruporesposta_in on esocial.avaliacaogruporespostaafastamentoesocial(eso13_avaliacaogruporesposta);
create index avaliacaogruporespostaafastamentoesocial_afastamentoesocial_in on esocial.avaliacaogruporespostaafastamentoesocial(eso13_afastamentoservidoresocial);
SQL
        );
    }
    
    private function createDinamicForm()
    {
        $this->execute(
            <<<SQL_DINAMIC_FORM
-- AJUSTA o last_value da sequencia para o maior sequencial informado na tabela esocialversaoformulario
select setval('esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) from esocialversaoformulario));

insert into avaliacao values (3000023, 5, 'S2230 - AFASTAMENTO TEMPORÁRIO', 'Registros do evento S-2230 - Afastamento Temporário', true, 's2230-afastamento-temporario', '', true);            
insert into esocialformulariotipo values (12, 'Afastamento Temporário');
insert into esocialversaoformulario values (nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000023, 12);

insert into avaliacaogrupopergunta values (3000251, 3000023, 'Informações de Identificação do Trabalhador e do Vínculo', 'informacoes-de-identificacao-do-traba5b16d32b6ff91', 'ideVinculo', 1);
insert into avaliacaogrupopergunta values (3000252, 3000023, 'Informações do Afastamento Temporário - Início', 'informacoes-do-afastamento-temporario-inicio', 'iniAfastamento', 1);
insert into avaliacaogrupopergunta values (3000253, 3000023, 'Informações complementares relativas ao atestado médico', 'informacoes-complementares-relativas-ao-atestado-m', 'infoAtestado', 1);
insert into avaliacaogrupopergunta values (3000254, 3000023, 'Médico/Dentista que emitiu o atestado', 'medicodentista-que-emitiu-o-atestado', 'emitente', 1);
insert into avaliacaogrupopergunta values (3000255, 3000023, 'Afastamento por Cessão ou Requisição do Trabalhador', 'afastamento-por-cessao-ou-requisicao-do-trabalhado', 'infoCessao', 1);
insert into avaliacaogrupopergunta values (3000256, 3000023, 'Informações Complementares - afastamento para exercício de mandato sindical', 'informacoes-complementares-afastamento-para-exerci', 'infoMandSind', 1);
insert into avaliacaogrupopergunta values (3000257, 3000023, 'Informações Complementares - afastamento para exercício de mandato sindical', 'informacoes-complementares-afastame5b16d32c281b1', 'infoRetif', 1);
insert into avaliacaogrupopergunta values (3000258, 3000023, 'Informaçõs do Término do Afastamento', 'informacos-do-termino-do-afastamento', 'fimAfastamento', 1);

insert into avaliacaopergunta values (3001072, 2, 3000251, 'CPF do Trabalhador', true, true, 1, 'cpf-do-trabalhador5b16d32b73f10', 1, '', 0, false, '', 'cpfTrab');
insert into avaliacaopergunta values (3001073, 2, 3000251, 'NIS do Trabalhador', false, true, 2, 'nis-do-trabalhador', 1, '', 0, false, '', 'nisTrab');
insert into avaliacaopergunta values (3001074, 2, 3000251, 'Matrícula', false, true, 3, 'matricula5b16d32b85f42', 1, '', 0, false, '', 'matricula');
insert into avaliacaopergunta values (3001075, 2, 3000251, 'Categoria', false, true, 4, 'categoria', 1, '', 0, false, '', 'codCateg');
insert into avaliacaopergunta values (3001076, 2, 3000252, 'Data de Início', true, true, 1, 'data-de-inicio', 1, '', 0, false, '', 'dtIniAfast');
insert into avaliacaopergunta values (3001077, 2, 3000252, 'Motivo do Afastamento', true, true, 2, 'motivo-do-afastamento', 1, '', 0, false, '', 'codMotAfast');
insert into avaliacaopergunta values (3001078, 2, 3000252, 'Afastamento decorre de mesmo motivo de afastamento anterior', false, true, 3, 'afastamento-decorre-de-mesmo-motivo-de-afastamento', 1, '', 0, false, '', 'infoMesmoMtv');
insert into avaliacaopergunta values (3001079, 2, 3000252, 'Tipo de Acidente de Trânsito', false, true, 4, 'tipo-de-acidente-de-transito', 1, '', 0, false, '', 'tpAcidTransito');
insert into avaliacaopergunta values (3001080, 2, 3000252, 'Observação', false, true, 5, 'observacao5b16d32bb697b', 1, '', 0, false, '', 'observacao');
insert into avaliacaopergunta values (3001081, 2, 3000253, 'CID - Classificação Internacional de Doenças', false, true, 1, 'cid-classificacao-internacional-de-doencas', 1, '', 0, false, '', 'codCID');
insert into avaliacaopergunta values (3001082, 2, 3000253, 'Quantidade de Dias Afastado', true, true, 2, 'quantidade-de-dias-afastado', 1, '', 0, false, '', 'qtdDiasAfast');
insert into avaliacaopergunta values (3001083, 2, 3000254, 'Nome do médico/dentista que emitiu o atestado.', true, true, 1, 'nome-do-medicodentista-que-emitiu-o-atestado', 1, '', 0, false, '', 'nmEmit');
insert into avaliacaopergunta values (3001084, 2, 3000254, 'Órgão de classe', true, true, 2, 'orgao-de-classe', 1, '', 0, false, '', 'ideOC');
insert into avaliacaopergunta values (3001085, 2, 3000254, 'Número de Inscrição no Órgão de Classe', true, true, 3, 'numero-de-inscricao-no-orgao-de-classe', 1, '', 0, false, '', 'nrOc');
insert into avaliacaopergunta values (3001086, 2, 3000254, 'Sigla da UF do órgão de classe', false, true, 4, 'sigla-da-uf-do-orgao-de-classe', 1, '', 0, false, '', 'ufOC');
insert into avaliacaopergunta values (3001087, 2, 3000255, 'CNPJ do órgão/entidade para o qual o trabalhador foi cedido', true, true, 1, 'cnpj-do-orgaoentidade-para-o-qual-o-trabalhador-fo', 1, '', 0, false, '', 'cnpjCess');
insert into avaliacaopergunta values (3001088, 2, 3000255, 'Ônus da cessão/requisição', true, true, 2, 'onus-da-cessaorequisicao', 1, '', 0, false, '', 'infOnus');
insert into avaliacaopergunta values (3001089, 2, 3000256, 'CNPJ do Sindicato', true, true, 1, 'cnpj-do-sindicato', 1, '', 0, false, '', 'cnpjSind');
insert into avaliacaopergunta values (3001090, 2, 3000256, 'Ônus da Remuneração', true, true, 2, 'onus-da-remuneracao', 1, '', 0, false, '', 'infOnusRemun');
insert into avaliacaopergunta values (3001091, 2, 3000257, 'Origem da Retificação', true, true, 1, 'origem-da-retificacao', 1, '', 0, false, '', 'origRetif');
insert into avaliacaopergunta values (3001092, 2, 3000257, 'Tipo do Processo', false, true, 2, 'tipo-do-processo5b16d32c33b74', 1, '', 0, false, '', 'tpProc');
insert into avaliacaopergunta values (3001093, 2, 3000257, 'Númerod do Processo', false, true, 3, 'numerod-do-processo', 1, '', 0, false, '', 'nrProc');
insert into avaliacaopergunta values (3001094, 2, 3000258, 'Data do Término do Afastamento', false, true, 1, 'data-do-termino-do-afastamento', 1, '', 0, false, '', 'dtTermAfast');

insert into avaliacaoperguntaopcao values (3004092, 3001072, 'Resposta 1', false, 'resposta-1', 0, '', 'cpfTrab');
insert into avaliacaoperguntaopcao values (3004093, 3001073, 'Resposta 1', false, 'resposta-15b16d32b82bf7', 0, '', 'nisTrab');
insert into avaliacaoperguntaopcao values (3004094, 3001074, 'Resposta 1', false, 'resposta-15b16d32b8ade1', 0, '', 'matricula');
insert into avaliacaoperguntaopcao values (3004095, 3001075, 'Resposta 1', false, 'resposta-15b16d32b92af1', 0, '', 'codCateg');
insert into avaliacaoperguntaopcao values (3004096, 3001076, 'Resposta 1', false, 'resposta-15b16d32b9c92b', 0, '', 'dtIniAfast');
insert into avaliacaoperguntaopcao values (3004097, 3001077, 'Resposta 1', false, 'resposta-15b16d32ba420b', 0, '', 'codMotAfast');
insert into avaliacaoperguntaopcao values (3004098, 3001078, 'Resposta 1', false, 'resposta-15b16d32baba21', 0, '', 'infoMesmoMtv');
insert into avaliacaoperguntaopcao values (3004099, 3001079, 'Resposta 1', false, 'resposta-15b16d32bb35b2', 0, '', 'tpAcidTransito');
insert into avaliacaoperguntaopcao values (3004100, 3001080, 'Resposta 1', false, 'resposta-15b16d32bbb738', 0, '', 'observacao');
insert into avaliacaoperguntaopcao values (3004101, 3001081, 'Resposta 1', false, 'resposta-15b16d32bc5e64', 0, '', 'codCID');
insert into avaliacaoperguntaopcao values (3004102, 3001082, 'Resposta 1', false, 'resposta-15b16d32bcd82a', 0, '', 'qtdDiasAfast');
insert into avaliacaoperguntaopcao values (3004103, 3001083, 'Resposta 1', false, 'resposta-15b16d32bd7dcd', 0, '', 'nmEmit');
insert into avaliacaoperguntaopcao values (3004104, 3001084, 'Resposta 1', false, 'resposta-15b16d32be050b', 0, '', 'ideOC');
insert into avaliacaoperguntaopcao values (3004105, 3001085, 'Resposta 1', false, 'resposta-15b16d32be8e21', 0, '', 'nrOc');
insert into avaliacaoperguntaopcao values (3004106, 3001086, 'Resposta 1', false, 'resposta-15b16d32bf1559', 0, '', 'ufOC');
insert into avaliacaoperguntaopcao values (3004107, 3001087, 'Resposta 1', false, 'resposta-15b16d32c0822e', 0, '', 'cnpjCess');
insert into avaliacaoperguntaopcao values (3004108, 3001088, 'Resposta 1', false, 'resposta-15b16d32c10ac4', 0, '', 'infOnus');
insert into avaliacaoperguntaopcao values (3004109, 3001089, 'Resposta 1', false, 'resposta-15b16d32c1c0e2', 0, '', 'cnpjSind');
insert into avaliacaoperguntaopcao values (3004110, 3001090, 'Resposta 1', false, 'resposta-15b16d32c2495d', 0, '', 'infOnusRemun');
insert into avaliacaoperguntaopcao values (3004111, 3001091, 'Resposta 1', false, 'resposta-15b16d32c3063c', 0, '', 'origRetif');
insert into avaliacaoperguntaopcao values (3004112, 3001092, 'Resposta 1', false, 'resposta-15b16d32c38e5b', 0, '', 'tpProc');
insert into avaliacaoperguntaopcao values (3004113, 3001093, 'Resposta 1', false, 'resposta-15b16d32c41817', 0, '', 'nrProc');
insert into avaliacaoperguntaopcao values (3004114, 3001094, 'Resposta 1', false, 'resposta-15b16d32c4c6b1', 0, '', 'dtTermAfast');
SQL_DINAMIC_FORM
        );
    }

    private function deleteDinamicForm()
    {

        $this->execute(
            <<<SQL_DOWN_DINAMIC_FORM
delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial 
                                                                       from avaliacaopergunta 
                                                                      where db103_avaliacaogrupopergunta 
                                                                         in (select db102_sequencial 
                                                                               from avaliacaogrupopergunta 
                                                                              where db102_avaliacao = 3000023));
                                                                              
delete from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial 
                                                                       from avaliacaogrupopergunta 
                                                                      where db102_avaliacao = 3000023);                                                                              
                                                                              
delete from avaliacaogrupopergunta where db102_avaliacao = 3000023;                                                                              
delete from esocialversaoformulario where rh211_avaliacao = 3000023;
delete from avaliacao where db101_sequencial = 3000023; 
delete from esocialformulariotipo where rh209_sequencial = 12;

SQL_DOWN_DINAMIC_FORM

        );
    }

    public function createFormulaCnpjSindicato()
    {
        $sql = "
            insert into db_formulas values ( 
            6687,  
            'ESOCIAL_CNPJ_SINDICATO',  
            'Retorna se o CNPJ informado é igual ao do empregador vinculado ao sindicato.',  
            'SELECT CASE \' (\'||cgm.z01_cgccpf||\') \'  
                  WHEN \'[CNPJ_SINDICATO_ESOCIAL]\'::varchar
                    THEN TRUE 
                    ELSE FALSE 
                END AS validacao,
                \'CNPJ informado deve ser diferente do qual o servidor está alocado.\' AS mensagem
            FROM rhpessoal 
            INNER JOIN rhpessoalmov ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist  
                                  AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\')::integer)  
                                  AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\')::integer) 
            INNER JOIN rhlota ON rhlota.r70_codigo = rhpessoalmov.rh02_lota 
            INNER JOIN db_config ON db_config.codigo = rhlota.r70_instit 
            INNER JOIN cgm ON cgm.z01_numcgm = db_config.numcgm 
            WHERE rh01_regist in [H16_REGIST]',  
            false); 
        ";
        $this->execute($sql);

        $sql = "UPDATE db_cadattdinamicoatributos set db109_db_formulas = 6687, db109_nome = 'cnpj_sindicato_esocial' WHERE db109_db_formulas = 6676 AND db109_descricao = 'CNPJ do sindicato';";
        $this->execute($sql);
    }

    public function dropFormulaCnpjSindicato()
    {
        $sql = "UPDATE db_cadattdinamicoatributos set db109_db_formulas = 6676, db109_nome = 'cnpj_entidade_esocial' WHERE db109_db_formulas = 6687 AND db109_descricao = 'CNPJ do sindicato';";
        $this->execute($sql);

        $sql = "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CNPJ_SINDICATO'";
        $this->execute($sql);
    }

    private function createMenu()
    {

        $this->execute(
            <<<UP_MENU
insert into db_itensmenu values( 10525, 'Datas para Envio', 'Configuração de envio para o eSocial', 'eso4_configuracaoenvio001.php', '1', '1', 'Configuração de de datas para envio do e-social.', '1'	);
insert into db_itensfilho (id_item, codfilho) values(10525,1);
insert into db_menu values(10475,10525,4,10216);
UP_MENU
        );
    }

    private function deleteMenu()
    {

        $this->execute(
            <<<DOWN_MENU
delete from db_menu where id_item_filho = 10525;
delete from db_itensfilho where id_item = 10525;
delete from db_itensmenu where id_item = 10525;
DOWN_MENU

        );
    }

    private function addAtributosDinamicos()
    {
        $this->execute(
            <<<UP_ATRIBUTOS
insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho'), null,   'Tipo de acidente de trânsito', '', 6, 'tipo_acidente_transito_esocial', 't', 't', 't');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 0, 'Nenhum');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 1, 'Atropelamento');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 2, 'Colisão');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 3, 'Outros');
insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho'), null,   'CID', '', 1, 'cid_esocial', 't', 't', 't');
insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho'), null,   'Nome do médico/dentista', '', 1, 'nome_medico_esocial', 't', 't', 't');
insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho'), null,   'Número de inscrição do órgão de classe', '', 1, 'tipo_orgao_medico_esocial', 't', 't', 't');
insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho'), null,   'Órgão de classe', '', 6, 'orgao_classe_esocial', 't', 't', 't');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 1, 'Conselho Regional de Medicina (CRM)');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 2, 'Conselho Regional de Odontologia (CRO)');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 3, 'Registro do Ministério da Saúde (RMS)');
insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho'), 15845,   'UF do órgão de classe', '', 1, 'uf_orgao_classe_esocial', 't', 't', 't');
UP_ATRIBUTOS
        );
    }

    private function deleteAtributosDinamicos()
    {
        $this->execute(
            <<<DOWN_ATRIBUTOS
delete from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = (select db109_sequencial from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'tipo_acidente_transito_esocial');
delete from db_cadattdinamicoatributosopcoes where db18_cadattdinamicoatributos = (select db109_sequencial from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'orgao_classe_esocial');
delete from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'tipo_acidente_transito_esocial';
delete from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'cid_esocial';
delete from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'nome_medico_esocial';
delete from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'tipo_orgao_medico_esocial';
delete from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'orgao_classe_esocial';
delete from db_cadattdinamicoatributos where db109_db_cadattdinamico = (select db118_sequencial from db_cadattdinamico where db118_descricao = 'Acidente/Doença não relacionada ao trabalho') and db109_nome = 'uf_orgao_classe_esocial';
DOWN_ATRIBUTOS
        );
    }

}
