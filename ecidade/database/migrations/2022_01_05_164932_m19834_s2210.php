<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19834S2210 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
        $this->upFormulario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
        $this->downFormulario();
    }

    private function upDicionario()
    {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010846, 'esoacidentetrabalho', 'Comunicação de Acidente de Trabalho', 'eso36', '2022-01-06', 'Comunicação de Acidente de Trabalho', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (81,1010846);
            insert into configuracoes.db_syscampo values(1013589,'eso36_sequencial','int4','Código único sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1013590,'eso36_matricula','int4','Código de matrícula do servidor.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into configuracoes.db_syscampo values(1013591,'eso36_instit','int4','Código de instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into configuracoes.db_syscampo values(1013592,'eso36_data','date','Data de ocorrência do acidente','null', 'Data Acidente',10,'f','f','f',1,'text','Data Acidente');
            delete from configuracoes.db_sysarqcamp where codarq = 1010846;
            insert into configuracoes.db_sysarqcamp values(1010846,1013589,1,0);
            insert into configuracoes.db_sysarqcamp values(1010846,1013590,2,0);
            insert into configuracoes.db_sysarqcamp values(1010846,1013591,3,0);
            insert into configuracoes.db_sysarqcamp values(1010846,1013592,4,0);
            delete from configuracoes.db_sysprikey where codarq = 1010846;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010846,1013589,1,1013589);
            delete from configuracoes.db_sysforkey where codarq = 1010846 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010846,1013590,1,1153,0);
            delete from configuracoes.db_sysforkey where codarq = 1010846 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010846,1013591,1,83,0);
            insert into configuracoes.db_syssequencia values(1001026, 'esoacidentetrabalho_eso36_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001026 where codarq = 1010846 and codcam = 1013589;
            delete from configuracoes.db_sysarqcamp where codarq = 1010846;
            insert into configuracoes.db_sysarqcamp values(1010846,1013589,1,1001026);
            insert into configuracoes.db_sysarqcamp values(1010846,1013590,2,0);
            insert into configuracoes.db_sysarqcamp values(1010846,1013591,3,0);
            insert into configuracoes.db_sysarqcamp values(1010846,1013592,4,0);
            insert into configuracoes.db_syscampo values(1013593,'eso36_avaliacaogruporesposta','int4','Sequencial resposta referenciando na resposta do S2210','0', 'Sequencial resposta',10,'f','f','f',1,'text','Sequencial resposta');
            insert into configuracoes.db_sysarqcamp values(1010846,1013593,5,0);
            update configuracoes.db_syscampo set nomecam = 'eso36_empregador', conteudo = 'int4', descricao = 'Código de cgm do empregador', valorinicial = '0', rotulo = 'Empregador', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Empregador' where codcam = 1013591;
            delete from configuracoes.db_sysforkey where codarq = 1010846 and referen = 83;
            insert into configuracoes.db_sysforkey values(1010846,1013591,1,42,0);
            insert into configuracoes.db_syscampo values(1013594,'eso36_cpf','varchar(11)','CPF','', 'CPF',11,'f','t','f',0,'text','CPF');
            update configuracoes.db_syscampo set nomecam = 'eso36_matricula', conteudo = 'int4', descricao = 'Código de matrícula do servidor.', valorinicial = '0', rotulo = 'Matrícula', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Matrícula' where codcam = 1013590;
            delete from configuracoes.db_sysforkey where codarq = 1010846 and referen = 1153;


            -- Menu
            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228612 ,'Segurança e Saúde do Trabalho (SST)' ,'Segurança e Saúde do Trabalho (SST)' ,'' ,'1' ,'1' ,'Segurança e Saúde do Trabalho (SST) para preenchimento de dados do eSocial.' ,'true' );
            delete from configuracoes.db_menu where id_item_filho = 228612 AND modulo = 10216;
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,228612 ,21 ,10216 );
            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228613 ,'Comunicação de Acidente de Trabalho (CAT)' ,'Comunicação de Acidente de Trabalho (CAT)' ,'eso01_preenchimentocat.php' ,'1' ,'1' ,'Rotina de preenchimento de dados do layout S-2210 do eSocial.' ,'true' );
            delete from configuracoes.db_menu where id_item_filho = 228613 AND modulo = 10216;
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228612 ,228613 ,1 ,10216 );
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codarq = 1010846 and codcam between 1013589 and 1013592;
            delete from configuracoes.db_sysarqcamp where codarq = 1010846 and codcam = 1013593;
            delete from configuracoes.db_sysarqcamp where codarq = 1010846 and codcam = 1013594;
            delete from configuracoes.db_syssequencia where codsequencia = 1001026;
            delete from configuracoes.db_sysforkey where codarq = 1010846 and codcam in (1013590,1013591);
            delete from configuracoes.db_sysprikey where codarq = 1010846;
            delete from configuracoes.db_syscampo where codcam between 1013589 and 1013592;
            delete from configuracoes.db_syscampo where codcam = 1013593;
            delete from configuracoes.db_syscampo where codcam = 1013594;
            delete from configuracoes.db_sysarqmod where codmod = 81 and codarq = 1010846;
            delete from configuracoes.db_sysarquivo where codarq = 1010846;

            -- Menu
            delete from configuracoes.db_menu where id_item_filho in (228612, 228613);
            delete from configuracoes.db_itensmenu where id_item in (228612, 228613);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function upEstrutura()
    {
        $sql = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE esocial.esoacidentetrabalho_eso36_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: esocial
            CREATE TABLE esocial.esoacidentetrabalho(
            eso36_sequencial		     int4  default nextval('esoacidentetrabalho_eso36_sequencial_seq'),
            eso36_matricula		         int4  default 0,
            eso36_empregador	         int4 NOT NULL default 0,
            eso36_data		             date default null,
            eso36_avaliacaogruporesposta int4 NOT NULL default 0,
            eso36_cpf		             varchar(11)  NOT NULL default '',
            CONSTRAINT esoacidentetrabalho_sequ_pk PRIMARY KEY (eso36_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE esocial.esoacidentetrabalho
            ADD CONSTRAINT esoacidentetrabalho_empregador_fk FOREIGN KEY (eso36_empregador)
            REFERENCES protocolo.cgm;
SQL;
    DB::connection()->getPdo()->exec($sql);

    }

    private function downEstrutura()
    {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS esocial.esoacidentetrabalho;
        --Drop sequences
        DROP SEQUENCE IF EXISTS esocial.esoacidentetrabalho_eso36_sequencial_seq;
SQL;
    DB::connection()->getPdo()->exec($sql);
    }


    private function upFormulario()
    {
        $sql = <<<SQL
            --Formulario
            insert into habitacao.avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 4000105 ,5 ,'S-2210 - Comunicação de Acidente de Trabalho' ,'s2210-comunicacao-de-acidente-de-trabalho' ,'S-2210 - Comunicação de Acidente de Trabalho' ,'true' ,'' ,'true' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000234 ,4000105 ,'Informações de identificação do trabalhador e do vínculo.' ,'informacoes-de-identificacao-do-traba61d6d85096898' ,'ideVinculo' ,1 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000307 ,2 ,4000234 ,'CPF' ,'cpf61d6d85113602' ,'true' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'cpfTrab' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000307;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001399 ,4000307 ,'' ,'61d6d851e299f' ,'true' ,0 ,'' ,'cpfTrab' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000308 ,2 ,4000234 ,'Matrícula (Não preencher no caso de Trabalhador Sem Vínculo de Emprego/Estatutário - TSVE sem informação de matrícula no evento S-2300.)' ,'matricula-nao-preencher-no-caso-de-trabalhador-sem' ,'false' ,'true' ,2 ,6 ,'' ,0 ,'false' ,'' ,'matricula' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000308;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001400 ,4000308 ,'' ,'61d6d8533bd5d' ,'true' ,0 ,'' ,'matricula' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000309 ,2 ,4000234 ,'Código da categoria do trabalhador. Informar somente no caso de TSVE sem informação de matrícula no evento S-2300, caso contrário, informar conforme Tabela 1.' ,'codigo-da-categoria-do-trabalhador-informar-soment' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codCateg' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000309;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001401 ,4000309 ,'' ,'61d6d854c527a' ,'true' ,0 ,'' ,'codCateg' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000235 ,4000105 ,'Comunicação de Acidente de Trabalho - CAT' ,'comunicacao-de-acidente-de-trabalho-cat' ,'cat' ,2 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000310 ,2 ,4000235 ,'Data do acidente. Deve ser uma data válida, igual ou anterior à data atual e igual ou posterior à data de admissão do trabalhador e à data de início da obrigatoriedade deste evento para o empregador no eSocial.' ,'data-do-acidente' ,'true' ,'true' ,1 ,5 ,'' ,0 ,'false' ,'' ,'dtAcid' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000310;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001402 ,4000310 ,'' ,'61d6d856774f9' ,'true' ,0 ,'' ,'dtAcid' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000311 ,1 ,4000235 ,'Tipo de acidente de trabalho' ,'tipo-de-acidente-de-trabalho' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpAcid' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000311;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001403 ,4000311 ,'1 - Típico' ,'1-tipico' ,'false' ,0 ,'1' ,'tpAcid_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001404 ,4000311 ,'2 - Doença' ,'2-doenca' ,'false' ,0 ,'2' ,'tpAcid_2' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001405 ,4000311 ,'3 - Trajeto' ,'3-trajeto' ,'false' ,0 ,'3' ,'tpAcid_3' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000312 ,2 ,4000235 ,'Hora do acidente, no formato HHMM. Se informada, deve estar no intervalo entre 0000 e 2359, criticando inclusive a segunda parte do número, que indica os minutos, que deve ser menor ou igual a 59. Ex 1030.' ,'hora-do-acidente-no-formato-hhmm' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'hrAcid' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000312;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001406 ,4000312 ,'' ,'61d6d859e7354' ,'true' ,0 ,'' ,'hrAcid' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000313 ,2 ,4000235 ,'Horas trabalhadas antes da ocorrência do acidente, no formato HHMM. Se informada, deve estar no intervalo entre 0000 e 2359, criticando inclusive a segunda parte do número, que indica os minutos, que deve ser menor ou igual a 59. Ex. 1030.' ,'horas-trabalhadas-antes-da-ocorrencia-do-acidente-' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'hrsTrabAntesAcid' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000313;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001407 ,4000313 ,'' ,'61d6d85b2bb77' ,'true' ,0 ,'' ,'hrsTrabAntesAcid' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000314 ,1 ,4000235 ,'Tipo de CAT' ,'tipo-de-cat' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'tpCat' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000314;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001408 ,4000314 ,'1 - Inicial' ,'1-inicial' ,'false' ,0 ,'1' ,'tpCat_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001409 ,4000314 ,'2 - Reabertura' ,'2-reabertura' ,'false' ,0 ,'2' ,'tpCat_2' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001410 ,4000314 ,'3 - Comunicação de óbito' ,'3-comunicacao-de-obito' ,'false' ,0 ,'3' ,'tpCat_3' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000315 ,1 ,4000235 ,'Houve óbito' ,'houve-obito' ,'true' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'indCatObito' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000315;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001411 ,4000315 ,'Não' ,'nao61d6d85f4bb16' ,'false' ,0 ,'N' ,'indCatObito_N' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001412 ,4000315 ,'Sim' ,'sim61d6d85fce805' ,'false' ,0 ,'S' ,'indCatObito_S' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000316 ,2 ,4000235 ,'Data do óbito' ,'data-do-obito' ,'false' ,'true' ,7 ,5 ,'' ,0 ,'false' ,'' ,'dtObito' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000316;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001413 ,4000316 ,'' ,'61d6d8611dbf5' ,'true' ,0 ,'' ,'dtObito' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000317 ,1 ,4000235 ,'Houve comunicação à autoridade policial' ,'houve-comunicacao-a-autoridade-policial' ,'true' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'indComunPolicia' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000317;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001414 ,4000317 ,'Não' ,'nao61d6d86257d13' ,'false' ,0 ,'N' ,'indComunPolicia_N' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001415 ,4000317 ,'Sim' ,'sim61d6d862cf236' ,'false' ,0 ,'S' ,'indComunPolicia_S' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000318 ,2 ,4000235 ,'Código da situação geradora do acidente ou da doença profissional' ,'codigo-da-situacao-geradora-do-acidente-ou-da-doen' ,'true' ,'true' ,9 ,1 ,'' ,0 ,'false' ,'' ,'codSitGeradora' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000318;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001416 ,4000318 ,'' ,'61d6d8641b26f' ,'true' ,0 ,'' ,'codSitGeradora' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000319 ,1 ,4000235 ,'Iniciativa da CAT' ,'iniciativa-da-cat' ,'true' ,'true' ,10 ,1 ,'' ,0 ,'false' ,'' ,'iniciatCAT' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000319;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001417 ,4000319 ,'1 - Empregador' ,'1-empregador' ,'false' ,0 ,'1' ,'iniciatCAT_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001418 ,4000319 ,'2 - Ordem judicial' ,'2-ordem-judicial' ,'false' ,0 ,'2' ,'iniciatCAT_2' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001419 ,4000319 ,'3 - Determinação de órgão fiscalizador' ,'3-determinacao-de-orgao-fiscalizador' ,'false' ,0 ,'3' ,'iniciatCAT_3' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000320 ,2 ,4000235 ,'Observação' ,'observacao61d6d86710241' ,'false' ,'true' ,11 ,1 ,'' ,0 ,'false' ,'' ,'obsCAT' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000320;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001420 ,4000320 ,'' ,'61d6d867cd4b2' ,'true' ,0 ,'' ,'obsCAT' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000236 ,4000105 ,'Local do acidente' ,'local-do-acidente' ,'localAcidente' ,3 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000321 ,1 ,4000236 ,'Tipo de local do acidente' ,'tipo-de-local-do-acidente' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpLocal' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000321;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001421 ,4000321 ,'1 - Estabelecimento do empregador no Brasil' ,'1-estabelecimento-do-empregador-no-brasil' ,'false' ,0 ,'1' ,'tpLocal_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001422 ,4000321 ,'2 - Estabelecimento do empregador no exterior' ,'2-estabelecimento-do-empregador-no-exterior' ,'false' ,0 ,'2' ,'tpLocal_2' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001423 ,4000321 ,'3 - Estabelecimento de terceiros onde o empregador presta serviços' ,'3-estabelecimento-de-terceiros-onde-o-empregador-p' ,'false' ,0 ,'3' ,'tpLocal_3' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001424 ,4000321 ,'4 - Via pública' ,'4-via-publica' ,'false' ,0 ,'4' ,'tpLocal_4' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001425 ,4000321 ,'5 - Área rural' ,'5-area-rural' ,'false' ,0 ,'5' ,'tpLocal_5' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001426 ,4000321 ,'6 - Embarcação' ,'6-embarcacao' ,'false' ,0 ,'6' ,'tpLocal_6' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001427 ,4000321 ,'9 - Outros' ,'9-outros61d6d86d0bac2' ,'false' ,0 ,'9' ,'tpLocal_9' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000322 ,2 ,4000236 ,'Especificação do local do acidente (pátio, rampa de acesso, posto de trabalho, etc.)' ,'especificacao-do-local-do-acidente-patio-rampa-de-' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'dscLocal' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000322;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001428 ,4000322 ,'' ,'61d6d86fdcc07' ,'true' ,0 ,'' ,'dscLocal' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000323 ,2 ,4000236 ,'Tipo de logradouro' ,'tipo-de-logradouro61d6d8709a9d8' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'tpLograd' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000323;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001429 ,4000323 ,'' ,'61d6d8728d505' ,'true' ,0 ,'' ,'tpLograd' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000324 ,2 ,4000236 ,'Descrição do logradouro' ,'descricao-do-logradouro61d6d87315733' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'dscLograd' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000324;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001430 ,4000324 ,'' ,'61d6d873d5015' ,'true' ,0 ,'' ,'dscLograd' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000325 ,2 ,4000236 ,'Número do logradouro (Se não houver número a ser informado, preencher com "S/N")' ,'numero-do-logradouro-se-nao-houver-n61d6d87461788' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'nrLograd' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000325;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001431 ,4000325 ,'' ,'61d6d875a2350' ,'true' ,0 ,'' ,'nrLograd' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000326 ,2 ,4000236 ,'Complemento do logradouro' ,'complemento-do-logradouro61d6d876295c1' ,'false' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'complemento' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000326;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001432 ,4000326 ,'' ,'61d6d876eb8ee' ,'true' ,0 ,'' ,'complemento' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000327 ,2 ,4000236 ,'Nome do bairro/distrito' ,'nome-do-bairrodistrito61d6d87771090' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'bairro' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000327;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001433 ,4000327 ,'' ,'61d6d8783fe8d' ,'true' ,0 ,'' ,'bairro' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000328 ,2 ,4000236 ,'CEP' ,'cep61d6d878bc9cd' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'cep' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000328;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001434 ,4000328 ,'' ,'61d6d8798cebb' ,'true' ,0 ,'' ,'cep' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000329 ,2 ,4000236 ,'Município' ,'municipio61d6d87a145b3' ,'false' ,'true' ,9 ,1 ,'' ,0 ,'false' ,'' ,'codMunic' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000329;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001435 ,4000329 ,'' ,'61d6d87ad5ae1' ,'true' ,0 ,'' ,'codMunic' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000330 ,2 ,4000236 ,'UF' ,'uf61d6d87b5b41a' ,'false' ,'true' ,10 ,1 ,'' ,0 ,'false' ,'' ,'uf' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000330;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001436 ,4000330 ,'' ,'61d6d87c23700' ,'true' ,0 ,'' ,'uf' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000331 ,2 ,4000236 ,'País' ,'pais61d6d87c9c715' ,'false' ,'true' ,11 ,1 ,'' ,0 ,'false' ,'' ,'pais' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000331;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001437 ,4000331 ,'' ,'61d6d87d6bedf' ,'true' ,0 ,'' ,'pais' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000332 ,2 ,4000236 ,'Código de Endereçamento Postal' ,'codigo-de-enderecamento-postal61d6d87deaa62' ,'false' ,'true' ,12 ,1 ,'' ,0 ,'false' ,'' ,'codPostal' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000332;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001438 ,4000332 ,'' ,'61d6d87eb6f25' ,'true' ,0 ,'' ,'codPostal' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000237 ,4000105 ,'Identificação do local onde ocorreu o acidente.' ,'identificacao-do-local-onde-ocorreu-o-acidente' ,'ideLocalAcid' ,4 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000333 ,1 ,4000237 ,'Inscrição do local onde ocorreu o acidente.' ,'inscricao-do-local-onde-ocorreu-o-acidente' ,'true' ,'true' ,1 ,6 ,'' ,0 ,'false' ,'' ,'tpInsc' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000333;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001439 ,4000333 ,'1 - CNPJ' ,'1-cnpj' ,'false' ,0 ,'1' ,'tpInsc_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001440 ,4000333 ,'3 - CAEPF' ,'3-caepf' ,'false' ,0 ,'3' ,'tpInsc_3' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001441 ,4000333 ,'4 - CNO' ,'4-cno' ,'false' ,0 ,'4' ,'tpInsc_4' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000334 ,2 ,4000237 ,'Informar o número de inscrição do estabelecimento.' ,'informar-o-numero-de-inscricao-do-estabelecimento' ,'true' ,'true' ,2 ,6 ,'' ,0 ,'false' ,'' ,'nrInsc' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000334;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001442 ,4000334 ,'' ,'61d6d88344503' ,'true' ,0 ,'' ,'nrInsc' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000238 ,4000105 ,'Detalhamento da parte atingida pelo acidente de trabalho.' ,'detalhamento-da-parte-atingida-pelo-acidente-de-tr' ,'parteAtingida' ,5 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000335 ,2 ,4000238 ,'Código correspondente à parte atingida.' ,'codigo-correspondente-a-parte-atingida' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'codParteAting' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000335;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001443 ,4000335 ,'' ,'61d6d884e58da' ,'true' ,0 ,'' ,'codParteAting' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000336 ,1 ,4000238 ,'Lateralidade da(s) parte(s) atingida(s).' ,'lateralidade-das-partes-atingidas' ,'true' ,'true' ,2 ,6 ,'' ,0 ,'false' ,'' ,'lateralidade' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000336;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001444 ,4000336 ,'0 - Não aplicável' ,'0-nao-aplicavel61d6d8862eaf8' ,'false' ,0 ,'0' ,'lateralidade_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001445 ,4000336 ,'1 - Esquerda' ,'1-esquerda' ,'false' ,0 ,'1' ,'lateralidade_2' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001446 ,4000336 ,'2 - Direita' ,'2-direita' ,'false' ,0 ,'2' ,'lateralidade_3' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001447 ,4000336 ,'3 - Ambas' ,'3-ambas' ,'false' ,0 ,'3' ,'lateralidade_4' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000239 ,4000105 ,'Detalhamento do agente causador do acidente de trabalho' ,'detalhamento-do-agente-causador-do-acidente-de-tra' ,'agenteCausador' ,6 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000337 ,2 ,4000239 ,'Código correspondente ao agente causador do acidente.' ,'codigo-correspondente-ao-agente-causador-do-aciden' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'codAgntCausador' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000337;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001448 ,4000337 ,'' ,'61d6d88941a67' ,'true' ,0 ,'' ,'codAgntCausador' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000240 ,4000105 ,'Atestado médico' ,'atestado-medico' ,'atestado' ,7 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000338 ,2 ,4000240 ,'Data do atendimento.' ,'data-do-atendimento' ,'true' ,'true' ,1 ,5 ,'' ,0 ,'false' ,'' ,'dtAtendimento' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000338;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001449 ,4000338 ,'' ,'61d6d88ade0ce' ,'true' ,0 ,'' ,'dtAtendimento' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000339 ,2 ,4000240 ,'Hora do atendimento, no formato HHMM. Se informada, deve estar no intervalo entre 0000 e 2359, criticando inclusive a segunda parte do número, que indica os minutos, que deve ser menor ou igual a 59. Ex. 1030.' ,'hora-do-atendimento' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'hrAtendimento' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000339;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001450 ,4000339 ,'' ,'61d6d88cc17da' ,'true' ,0 ,'' ,'hrAtendimento' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000340 ,1 ,4000240 ,'Indicativo de internação.' ,'indicativo-de-internacao' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'indInternacao' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000340;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001451 ,4000340 ,'Sim' ,'sim61d6d88e7dcef' ,'false' ,0 ,'S' ,'indInternacao_S' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001452 ,4000340 ,'Não' ,'nao61d6d88f72f51' ,'false' ,0 ,'N' ,'indInternacao_N' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000341 ,2 ,4000240 ,'Duração estimada do tratamento, em dias.' ,'duracao-estimada-do-tratamento-em-dias' ,'true' ,'true' ,4 ,6 ,'' ,0 ,'false' ,'' ,'durTrat' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000341;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001453 ,4000341 ,'' ,'61d6d89248bee' ,'true' ,0 ,'' ,'durTrat' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000342 ,1 ,4000240 ,'Indicativo de afastamento do trabalho durante o tratamento.' ,'indicativo-de-afastamento-do-trabalho-durante-o-tr' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'indAfast' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000342;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001454 ,4000342 ,'Sim' ,'sim61d6d8937fdd9' ,'false' ,0 ,'S' ,'indAfast_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001455 ,4000342 ,'Não' ,'nao61d6d89410f4c' ,'false' ,0 ,'N' ,'indAfast_2' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000343 ,2 ,4000240 ,'Preencher com a descrição da natureza da lesão.' ,'preencher-com-a-descricao-da-natureza-da-lesao' ,'true' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'dscLesao' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000343;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001456 ,4000343 ,'' ,'61d6d895514b1' ,'true' ,0 ,'' ,'dscLesao' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000344 ,2 ,4000240 ,'Descrição complementar da lesão.' ,'descricao-complementar-da-lesao' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'dscCompLesao' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000344;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001457 ,4000344 ,'' ,'61d6d89696807' ,'true' ,0 ,'' ,'dscCompLesao' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000345 ,2 ,4000240 ,'Diagnóstico provável.' ,'diagnostico-provavel' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'diagProvavel' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000345;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001458 ,4000345 ,'' ,'61d6d897dfd09' ,'true' ,0 ,'' ,'diagProvavel' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000346 ,2 ,4000240 ,'Classificação Internacional de Doenças - CID.' ,'classificacao-internacional-de-doencas-cid' ,'true' ,'true' ,9 ,1 ,'' ,0 ,'false' ,'' ,'codCID' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000346;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001459 ,4000346 ,'' ,'61d6d899b893d' ,'true' ,0 ,'' ,'codCID' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000347 ,2 ,4000240 ,'Observação.' ,'observacao61d6d89a42e8b' ,'false' ,'true' ,10 ,1 ,'' ,0 ,'false' ,'' ,'observacao' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000347;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001460 ,4000347 ,'' ,'61d6d89b70024' ,'true' ,0 ,'' ,'observacao' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000241 ,4000105 ,'Médico/Dentista que emitiu o atestado.' ,'medicodentista-que-emitiu-o-atestado61d6d89d2d101' ,'emitente' ,8 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000348 ,2 ,4000241 ,'Nome do médico/dentista que emitiu o atestado.' ,'nome-do-medicodentista-que-emitiu-o-atestado' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmEmit' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000348;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001461 ,4000348 ,'' ,'61d6d89e6a0d4' ,'true' ,0 ,'' ,'nmEmit' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000349 ,1 ,4000241 ,'Órgão de classe.' ,'orgao-de-classe' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'ideOC' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000349;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001462 ,4000349 ,'1 - Conselho Regional de Medicina - CRM' ,'1-conselho-regional-de-medicina-crm' ,'false' ,0 ,'1' ,'ideOC_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001463 ,4000349 ,'2 - Conselho Regional de Odontologia - CRO' ,'2-conselho-regional-de-odontologia-cro' ,'false' ,0 ,'2' ,'ideOC_2' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001464 ,4000349 ,'3 - Registro do Ministério da Saúde - RMS' ,'3-registro-do-ministerio-da-saude-rms' ,'false' ,0 ,'3' ,'ideOC_3' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000350 ,2 ,4000241 ,'Número de inscrição no órgão de classe.' ,'numero-de-inscricao-no-orgao-de-class61d6d8a679f8a' ,'true' ,'true' ,3 ,6 ,'' ,0 ,'false' ,'' ,'nrOC' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000350;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001465 ,4000350 ,'' ,'61d6d8a74960b' ,'true' ,0 ,'' ,'nrOC' );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000351 ,2 ,4000241 ,'Sigla da UF/Estado' ,'sigla-da-ufestado' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'ufOC' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000351;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001466 ,4000351 ,'' ,'61d6d8a89036c' ,'true' ,0 ,'' ,'ufOC' );
            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000242 ,4000105 ,'Grupo que indica a CAT anterior, no caso de CAT de reabertura ou de comunicação de óbito.' ,'grupo-que-indica-a-cat-anterior-no-caso-de-cat-de-' ,'catOrigem' ,9 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000352 ,2 ,4000242 ,'Informar o número do recibo da última CAT' ,'informar-o-numero-do-recibo-da-ultima-cat' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nrRecCatOrig' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000352;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001467 ,4000352 ,'' ,'61d6d8aa4cf26' ,'true' ,0 ,'' ,'nrRecCatOrig' );

            --Tipo de formulario
            insert into recursoshumanos.esocialformulariotipo values(37, 'S-2210 - Comunicação de Acidente de Trabalho');
            insert into recursoshumanos.esocialversaoformulario values(87, 'S1.0', 4000105, 37);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downFormulario()
    {
        $sql = <<<SQL
            -- Tipo de Formulario
            delete from recursoshumanos.esocialversaoformulario where rh211_sequencial = 87;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 37;

            -- Formulario
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from  habitacao.avaliacaogrupopergunta where db102_avaliacao = 4000105));
            delete from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from  habitacao.avaliacaogrupopergunta where db102_avaliacao = 4000105);
            delete from habitacao.avaliacaogrupopergunta where db102_avaliacao = 4000105;
            delete from habitacao.avaliacao where db101_sequencial = 4000105;
SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
