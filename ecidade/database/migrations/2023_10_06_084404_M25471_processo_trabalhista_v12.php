<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M25471ProcessoTrabalhistaV12 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionarioTabelaRhpessoalProcessoJudicialEsocial();
        $this->upEstruturaTabelaRhpessoalProcessoJudicialEsocial();

        $this->upDicionarioTabelaRhpessoalProcessoContrato();
        $this->upEstruturaTabelaRhpessoalProcessoContrato();

        $this->upDicionarioTabelaRhpessoalProcessoPeriodo();
        $this->upEstruturaTabelaRhpessoalProcessoPeriodo();

        $this->upDicionarioTabelaRhpessoalProcessoAbono();
        $this->upEstruturaTabelaRhpessoalProcessoAbono();

        $this->upDicionarioTabelaRhProcessoIRRFComp();
        $this->upEstruturaTabelaRhProcessoIRRFComp();

        $this->upDicionarioTabelaRhProcessoTributoIRRF();
        $this->upEstruturaTabelaRhProcessoTributoIRRF();

        $this->upDicionarioTabelaRhProcessoAdvogado();
        $this->upEstruturaTabelaRhProcessoAdvogado();

        $this->upDicionarioTabelaRhProcessoDependente();
        $this->upEstruturaTabelaRhProcessoDependente();

        $this->upDicionarioTabelaRhProcessoPensao();
        $this->upEstruturaTabelaRhProcessoPensao();

        $this->upDicionarioTabelaRhProcessoRetencao();
        $this->upEstruturaTabelaRhProcessoRetencao();

        $this->upDicionarioTabelaRhProcessoValorRetencao();
        $this->upEstruturaTabelaRhProcessoValorRetencao();

        $this->upDicionarioTabelaRhProcessoReducaoSuspensa();
        $this->upEstruturaTabelaRhProcessoReducaoSuspensa();

        $this->upDicionarioTabelaRhProcessoSuspensaPensao();
        $this->upEstruturaTabelaRhProcessoSuspensaPensao();

        $this->upDicionarioTabelaRhProcessoDesligamento();
        $this->upEstruturaTabelaRhProcessoDesligamento();

        $this->upDicionarioTabelaRhPessoalProcessoEstatutario();
        $this->upEstruturaTabelaRhPessoalProcessoEstatutario();

        $this->upDicionarioTabelaRhPessoalProcessoVinculo();
        $this->upEstruturaTabelaRhPessoalProcessoVinculo();

        $this->upDicionarioTabelaRhPessoalProcessoRemuneracao();
        $this->upEstruturaTabelaRhPessoalProcessoRemuneracao();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downEstruturaTabelaRhProcessoSuspensaPensao();
        $this->downDicionarioTabelaRhProcessoSuspensaPensao();

        $this->downDicionarioTabelaRhProcessoReducaoSuspensa();
        $this->downEstruturaTabelaRhProcessoReducaoSuspensa();

        $this->downDicionarioTabelaRhProcessoValorRetencao();
        $this->downEstruturaTabelaRhProcessoValorRetencao();

        $this->downDicionarioTabelaRhProcessoPensao();
        $this->downEstruturaTabelaRhProcessoPensao();

        $this->downDicionarioTabelaRhProcessoRetencao();
        $this->downEstruturaTabelaRhProcessoRetencao();

        $this->downDicionarioTabelaRhProcessoDependente();
        $this->downEstruturaTabelaRhProcessoDependente();

        $this->downDicionarioTabelaRhProcessoAdvogado();
        $this->downEstruturaTabelaRhProcessoAdvogado();

        $this->downDicionarioTabelaRhProcessoTributoIRRF();
        $this->downEstruturaTabelaRhProcessoTributoIRRF();

        $this->downDicionarioTabelaRhProcessoIRRFComp();
        $this->downEstruturaTabelaRhProcessoIRRFComp();

        $this->downDicionarioTabelaRhpessoalProcessoAbono();
        $this->downEstruturaTabelaRhpessoalProcessoAbono();


        $this->downDicionarioTabelaRhpessoalProcessoPeriodo();
        $this->downEstruturaTabelaRhpessoalProcessoPeriodo();
        
        $this->downDicionarioTabelaRhpessoalProcessoContrato();
        $this->downEstruturaTabelaRhpessoalProcessoContrato();

        $this->downDicionarioTabelaRhpessoalProcessoJudicialEsocial();
        $this->downEstruturaTabelaRhpessoalProcessoJudicialEsocial();

        $this->downDicionarioTabelaRhProcessoDesligamento();
        $this->downEstruturaTabelaRhProcessoDesligamento();

        $this->downDicionarioTabelaRhPessoalProcessoEstatutario();
        $this->downEstruturaTabelaRhPessoalProcessoEstatutario();

        $this->downDicionarioTabelaRhPessoalProcessoVinculo();
        $this->downEstruturaTabelaRhPessoalProcessoVinculo();

        $this->downDicionarioTabelaRhPessoalProcessoRemuneracao();
        $this->downEstruturaTabelaRhPessoalProcessoRemuneracao();

    }

    private function upDicionarioTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            delete from configuracoes.db_syscampodef where codcam = 1014816;
            delete from configuracoes.db_syscampodep where codcam = 1014816;
            delete from configuracoes.db_syscampo where codcam = 1014816;
            delete from configuracoes.db_syscampodef where codcam = 1014817;
            delete from configuracoes.db_syscampodep where codcam = 1014817;
            delete from configuracoes.db_syscampo where codcam = 1014817;
            insert into configuracoes.db_syssequencia values(1001179, 'rhpessoalprocessoduracao_rh276_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_syscampo set nomecam = 'rh276_clauassec', conteudo = 'varchar(1)', descricao = 'Indicar se o contrato por prazo determinado contém cláusula assecuratória do direito recíproco de rescisão antes da data de seu término.', valorinicial = '', rotulo = 'Cláusula assecuratória', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Cláusula assecuratória' where codcam = 1014872;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            insert into configuracoes.db_syscampo values(1014816,'rh270_compini','varchar(7)','Competência inicial a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência Inicial',7,'t','t','f',0,'text','Competência Inicial');
            insert into configuracoes.db_syscampo values(1014817,'rh270_compfim','varchar(7)','Competência final a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência Final ',7,'t','t','f',0,'text','Competência Final ');
            delete from configuracoes.db_syssequencia where codsequencia = 1001179;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial DROP COLUMN IF EXISTS rh270_compini CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial DROP COLUMN IF EXISTS rh270_compfim CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessoduracao ALTER COLUMN rh276_clauassec DROP NOT null;
            CREATE SEQUENCE IF NOT EXISTS recursoshumanos.rhpessoalprocessoduracao_rh276_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            ALTER TABLE recursoshumanos.rhpessoalprocessoduracao ALTER COLUMN rh276_sequencial SET DEFAULT nextval('rhpessoalprocessoduracao_rh276_sequencial_seq');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial ADD IF NOT EXISTS rh270_compini varchar(7) NULL DEFAULT '';
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial ADD IF NOT EXISTS rh270_compfim varchar(7) NULL DEFAULT '';

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014839 AND codarq=1011034;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014847 AND codarq=1011034;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014848 AND codarq=1011034;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014849 AND codarq=1011034;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014851 AND codarq=1011034;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014853 AND codarq=1011034;
            delete from configuracoes.db_syscampodef where codcam = 1014839;
            delete from configuracoes.db_syscampodep where codcam = 1014839;
            delete from configuracoes.db_syscampo where codcam = 1014839;
            delete from configuracoes.db_syscampodef where codcam = 1014847;
            delete from configuracoes.db_syscampodep where codcam = 1014847;
            delete from configuracoes.db_syscampo where codcam = 1014847;
            delete from configuracoes.db_syscampodef where codcam = 1014848;
            delete from configuracoes.db_syscampodep where codcam = 1014848;
            delete from configuracoes.db_syscampo where codcam = 1014848;
            delete from configuracoes.db_syscampodef where codcam = 1014849;
            delete from configuracoes.db_syscampodep where codcam = 1014849;
            delete from configuracoes.db_syscampo where codcam = 1014849;
            delete from configuracoes.db_syscampodef where codcam = 1014851;
            delete from configuracoes.db_syscampodep where codcam = 1014851;
            delete from configuracoes.db_syscampo where codcam = 1014851;
            delete from configuracoes.db_syscampodef where codcam = 1014853;
            delete from configuracoes.db_syscampodep where codcam = 1014853;
            delete from configuracoes.db_syscampo where codcam = 1014853;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014850 AND codarq=1011034;
            delete from configuracoes.db_syscampodef where codcam = 1014850;
            delete from configuracoes.db_syscampodep where codcam = 1014850;
            delete from configuracoes.db_syscampo where codcam = 1014850;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014852 AND codarq=1011034;
            delete from configuracoes.db_syscampodef where codcam = 1014852;
            delete from configuracoes.db_syscampodep where codcam = 1014852;
            delete from configuracoes.db_syscampo where codcam = 1014852;
            insert into configuracoes.db_syscampo values(1015363,'rh273_indreperc','int4','Indicativo de repercussão do processo trabalhista ou de demanda submetida à CCP ou ao NINTER.','0', 'Indicativo de repercussão',10,'t','f','f',1,'text','Indicativo de repercussão');
            insert into configuracoes.db_syscampo values(1015364,'rh273_indensd ','varchar(10)','Houve decisão para pagamento da indenização substitutiva do seguro-desemprego?','', 'Indenização substitutiva',10,'t','t','f',0,'text','Indenização substitutiva');
            insert into configuracoes.db_syscampo values(1015365,'rh273_indenabono','varchar(10)','Houve decisão para pagamento da indenização substitutiva de abono salarial?','', 'Indenização abono salarial',10,'t','t','f',0,'text','Indenização abono salarial');
            delete from configuracoes.db_sysarqcamp where codarq = 1011034;
            insert into configuracoes.db_sysarqcamp values(1011034,1014830,1,1001121);
            insert into configuracoes.db_sysarqcamp values(1011034,1014831,2,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014832,3,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014833,4,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014834,5,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014835,6,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014836,7,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014837,8,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014838,9,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014840,10,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014841,11,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014842,12,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014845,13,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014846,14,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1015363,15,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1015365,16,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1015364,17,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(1014839, 'rh273_indunic', 'varchar(1)', 'Indicativo se houve reconhecimento de unicidade contratual (declaração da continuidade do contrato de trabalho, considerando como único dois ou mais vínculos sucessivos informados no eSocial).', '', 'Unicidade contratual ', 1, true, true, false, 0, 'text', 'Unicidade contratual ');
            INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(1014847, 'rh273_repercproc', 'int4', 'Repercussão do processo trabalhista ou de demanda submetida à CCP ou ao NINTER.', '0', 'Repercussão do processo ', 1, true, false, false, 1, 'text', 'Repercussão do processo ');
            INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(1014848, 'rh273_vrremunc', 'float4', 'Valor total das verbas remuneratórias a serem pagas ao trabalhador.', '0', 'Valor total', 14, true, false, false, 4, 'text', 'Valor total');
            INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(1014849, 'rh273_vrapi', 'float4', 'Valor do aviso prévio indenizado pago ao empregado.', '0', 'Valor do aviso prévio ', 14, true, false, false, 4, 'text', 'Valor do aviso prévio ');
            INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(1014851, 'rh273_vrinden', 'float4', 'Valor total das demais verbas indenizatórias a serem pagas ao trabalhador.', '0', 'Valor total das demais', 14, true, false, false, 4, 'text', 'Valor total das demais');
            INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(1014853, 'rh273_pagdiretoresc', 'varchar(1)', 'A indenização compensatória (multa rescisória) do FGTS transacionada foi paga diretamente ao trabalhador mediante decisão/autorização judicial?', '', ' Indenização compensatória ', 1, true, true, false, 0, 'text', ' Indenização compensatória ');
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015363 AND codarq=1011034;
            delete from configuracoes.db_syscampodef where codcam = 1015363;
            delete from configuracoes.db_syscampodep where codcam = 1015363;
            delete from configuracoes.db_syscampo where codcam = 1015363;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015364 AND codarq=1011034;
            delete from configuracoes.db_syscampodef where codcam = 1015364;
            delete from configuracoes.db_syscampodep where codcam = 1015364;
            delete from configuracoes.db_syscampo where codcam = 1015364;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015365 AND codarq=1011034;
            delete from configuracoes.db_syscampodef where codcam = 1015365;
            delete from configuracoes.db_syscampodep where codcam = 1015365;
            delete from configuracoes.db_syscampo where codcam = 1015365;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessocontrato');
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_indunic CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_repercproc CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_vrremunc CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_vrapi CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_vrinden CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_pagdiretoresc CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_vr13api CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_baseindenfgts CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_indreperc int4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_indensd varchar(1) NULL DEFAULT ''::varchar;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_indenabono varchar(1) NULL DEFAULT ''::varchar;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessocontrato');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessocontrato');
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_indunic varchar(1) NULL DEFAULT ''::varchar;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_repercproc int4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_vrremunc float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_vrapi float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_vrinden float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_pagdiretoresc varchar(1) NULL DEFAULT ''::varchar;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_vr13api float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato ADD IF NOT EXISTS rh273_baseindenfgts float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_indreperc CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_indensd CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato DROP COLUMN IF EXISTS rh273_indenabono CASCADE;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessocontrato');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014903 AND codarq=1011050 AND seqarq=6;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014904 AND codarq=1011050 AND seqarq=7;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014906 AND codarq=1011050 AND seqarq=9;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014907 AND codarq=1011050 AND seqarq=10;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014908 AND codarq=1011050 AND seqarq=11;
            delete from configuracoes.db_syscampodef where codcam = 1014903;
            delete from configuracoes.db_syscampodep where codcam = 1014903;
            delete from configuracoes.db_syscampo where codcam = 1014903;
            delete from configuracoes.db_syscampodef where codcam = 1014904;
            delete from configuracoes.db_syscampodep where codcam = 1014904;
            delete from configuracoes.db_syscampo where codcam = 1014904;
            delete from configuracoes.db_syscampodef where codcam = 1014906;
            delete from configuracoes.db_syscampodep where codcam = 1014906;
            delete from configuracoes.db_syscampo where codcam = 1014906;
            delete from configuracoes.db_syscampodef where codcam = 1014907;
            delete from configuracoes.db_syscampodep where codcam = 1014907;
            delete from configuracoes.db_syscampo where codcam = 1014907;
            delete from configuracoes.db_syscampodef where codcam = 1014908;
            delete from configuracoes.db_syscampodep where codcam = 1014908;
            delete from configuracoes.db_syscampo where codcam = 1014908;
            delete from configuracoes.db_syscampo where codcam = 1015368;
            INSERT INTO configuracoes.db_syscampo (codcam) VALUES (1015368);
            update configuracoes.db_syscampo set nomecam = 'rh282_vrbcfgtsdecant', conteudo = 'float4', descricao = 'Valor da base de cálculo de FGTS declarada anteriormente no eSocial e ainda não recolhida.', valorinicial = '0', rotulo = 'Valor da base FGTS não recolhida', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da base FGTS não recolhida' where codcam = 1015368;
            delete from configuracoes.db_syscampodep where codcam = 1015368;
            delete from configuracoes.db_syscampodef where codcam = 1015368;
            delete from configuracoes.db_syscampo where codcam = 1015367;
            INSERT INTO configuracoes.db_syscampo (codcam) VALUES (1015367);
            update configuracoes.db_syscampo set nomecam = 'rh282_vrbcfgtssefip', conteudo = 'float4', descricao = 'Valor da base de cálculo de FGTS declarada apenas em SEFIP (não informada no eSocial) e ainda não recolhida.', valorinicial = '0', rotulo = 'Valor da base FGTS com SEFIP', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da base FGTS com SEFIP' where codcam = 1015367;
            delete from configuracoes.db_syscampodep where codcam = 1015367;
            delete from configuracoes.db_syscampodef where codcam = 1015367;
            delete from configuracoes.db_syscampo where codcam = 1015366;
            INSERT INTO configuracoes.db_syscampo (codcam) VALUES (1015366);
            update configuracoes.db_syscampo set nomecam = 'rh282_vrbcfgtsproctrab', conteudo = 'float4', descricao = 'Valor da base de cálculo de FGTS ainda não declarada em SEFIP ou no eSocial, inclusive de verba reconhecida no processo trabalhista.', valorinicial = '0', rotulo = 'Valor da base FGTS sem SEFIP', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da base FGTS sem SEFIP' where codcam = 1015366;
            delete from configuracoes.db_syscampodep where codcam = 1015366;
            delete from configuracoes.db_syscampodef where codcam = 1015366;
            delete from configuracoes.db_sysarqcamp where codarq = 1011050;
            insert into configuracoes.db_sysarqcamp values(1011050,1014898,1,1001124);
            insert into configuracoes.db_sysarqcamp values(1011050,1014899,2,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014900,3,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014902,4,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014901,5,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014905,6,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014909,7,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014910,8,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1015368,9,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1015367,10,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1015366,11,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            insert into configuracoes.db_syscampo values(1014903,'rh282_vrbcfgts','float4','Valor da base de cálculo do FGTS sobre a remuneração do trabalhador (sem 13° salário).','0', 'Valor FGTS',14,'t','f','f',4,'text','Valor FGTS');
            insert into configuracoes.db_syscampo values(1014904,'rh282_vrbcfgts13','float4','Valor da base de cálculo do FGTS sobre a remuneração do trabalhador sobre o 13º salário.','0', 'Valor FGTS 13º',14,'t','f','f',4,'text','Valor FGTS 13º');
            insert into configuracoes.db_syscampo values(1014906,'rh282_vrbcfgtsguia','float4','Valor da base de cálculo do FGTS sobre a remuneração do trabalhador (sem 13° salário).','0', 'Base cálculo FGTS',14,'t','f','f',4,'text','Base cálculo FGTS');
            insert into configuracoes.db_syscampo values(1014907,'rh282_vrbcfgts13guia ','float4','Valor da base de cálculo do FGTS sobre a remuneração do trabalhador sobre o 13º salário.','0', 'Base cálculo FGTS 13º',14,'t','f','f',4,'text','Base cálculo FGTS 13º');
            insert into configuracoes.db_syscampo values(1014908,'rh282_pagdireto','varchar(1)','O FGTS transacionado referente a perRef foi pago diretamente ao trabalhador mediante decisão/autorização judicial','', 'Pago diretamente ao trabalhador ',1,'t','t','f',0,'text','Pago diretamente ao trabalhador ');
            delete from configuracoes.db_sysarqcamp where codarq = 1011050;
            insert into configuracoes.db_sysarqcamp values(1011050,1014898,1,1001124);
            insert into configuracoes.db_sysarqcamp values(1011050,1014899,2,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014900,3,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014902,4,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014901,5,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014903,6,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014904,7,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014905,8,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014906,9,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014907,10,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014908,11,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014909,12,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014910,13,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessoperiodo');
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgts;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgts13;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgtsguia;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgts13guia;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_pagdireto;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgtsdecant float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgtssefip float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgtsproctrab float4 NULL DEFAULT 0;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessoperiodo');

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessoperiodo');
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgts float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgts13 float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgtsguia float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_vrbcfgts13guia float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo ADD rh282_pagdireto varchar(1) NULL DEFAULT ''::varchar;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgtsdecant;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgtssefip;
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo DROP COLUMN IF EXISTS rh282_vrbcfgtsproctrab;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessoperiodo');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011134, 'rhpessoalprocessoabono', 'Identificação do(s) ano(s)-base em que houve indenização substitutiva de abono salarial.', 'rh302', '2023-09-06', 'Ano Abono', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011134);
            insert into configuracoes.db_sysarqarq values (1011036,1011134);
            insert into configuracoes.db_syscampo values(1015373,'rh302_sequencial','int8','Sequencial único da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015484,'rh302_sequencialprocessocontrato','int4','Sequencial que referencia a tabela RHPESSOALPROCESSOCONTRATO','0', 'Sequencial contrato',10,'f','f','f',1,'text','Sequencial contrato');
            insert into configuracoes.db_syscampo values(1015375,'rh302_anobase','varchar(4)','Ano-base em que houve indenização substitutiva do abono salarial.','', 'Ano abono',4,'f','t','f',0,'text','Ano abono');
            delete from configuracoes.db_sysarqcamp where codarq = 1011134;
            insert into configuracoes.db_sysarqcamp values(1011134,1015373,1,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015484,2,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015375,3,0);
            delete from configuracoes.db_sysprikey where codarq = 1011134;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011134,1015373,1,1015373);
            delete from configuracoes.db_sysarqcamp where codarq = 1011134;
            insert into configuracoes.db_sysarqcamp values(1011134,1015373,1,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015484,2,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015375,3,0);
            insert into configuracoes.db_syssequencia values(1001162, 'rhpessoalprocessoabono_rh302_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001162 where codarq = 1011134 and codcam = 1015373;
            delete from configuracoes.db_sysarqarq where codarq = 1011147;
            insert into configuracoes.db_sysarqarq values(1011032,1011147);
            delete from configuracoes.db_sysprikey where codarq = 1011147;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011147,1015455,1,1015455);
            delete from configuracoes.db_sysforkey where codarq = 1011134 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011134,1015484,1,1011034,0);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011134;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011134 and codcam = 1015373;
            delete from configuracoes.db_syssequencia where codsequencia = 1001162;
            delete from configuracoes.db_sysarqcamp where codarq = 1011134;
            delete from configuracoes.db_syscampo where codcam  in (1015373, 1015484, 1015375);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011036 and codarq = 1011134;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011134;
            delete from configuracoes.db_sysarquivo where codarq = 1011134;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessoabono_rh302_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessoabono(
            rh302_sequencial		            int8 NOT NULL default nextval('rhpessoalprocessoabono_rh302_sequencial_seq'),
            rh302_sequencialprocessocontrato	int4 NOT NULL default 0,
            rh302_anobase		                varchar(4)  default '',
            CONSTRAINT rhpessoalprocessoabono_sequ_pk PRIMARY KEY (rh302_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessoabono
            ADD CONSTRAINT rhpessoalprocessoabono_sequencialprocessocontrato_fk FOREIGN KEY (rh302_sequencialprocessocontrato)
            REFERENCES recursoshumanos.rhpessoalprocessocontrato;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoabono;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhpessoalprocessoabono_rh302_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011147);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessoirrfcomp', descricao = 'Informações relacionadas à retenção na fonte, aos rendimentos tributáveis e não tributáveis, deduções e/ou isenções, etc., de acordo com a legislação aplicada ao imposto de renda.', sigla = 'rh310', dataincl = '2023-10-09', rotulo = 'IRRF complementar', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011147;
            insert into configuracoes.db_sysarqmod values(29, 1011147);
            delete from configuracoes.db_sysarqarq where codarq = 1011147;
            insert into configuracoes.db_sysarqarq values(1011032,1011147);
            insert into configuracoes.db_syscampo values(1015455);
            update configuracoes.db_syscampo set nomecam = 'rh310_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela.', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015455;
            delete from configuracoes.db_syscampodep where codcam = 1015455;
            delete from configuracoes.db_syscampodef where codcam = 1015455;
            insert into configuracoes.db_syscampo values(1015456);
            update configuracoes.db_syscampo set nomecam = 'rh310_sequencialprocessoservidor', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPESSOALPROCESSOSERVIDOR', valorinicial = '0', rotulo = 'Sequencial vínculo servidor', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vínculo servidor' where codcam = 1015456;
            delete from configuracoes.db_syscampodep where codcam = 1015456;
            delete from configuracoes.db_syscampodef where codcam = 1015456;
            insert into configuracoes.db_syscampo values(1015457);
            update configuracoes.db_syscampo set nomecam = 'rh310_dtlaudo', conteudo = 'date', descricao = 'Data da moléstia grave atribuída pelo laudo.', valorinicial = 'null', rotulo = 'Data laudo', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data laudo' where codcam = 1015457;
            delete from configuracoes.db_syscampodep where codcam = 1015457;
            delete from configuracoes.db_syscampodef where codcam = 1015457;
            insert into configuracoes.db_syscampo values(1015458);
            update configuracoes.db_syscampo set nomecam = 'rh310_cpfdep', conteudo = 'varchar(11)', descricao = 'Número de inscrição no CPF.', valorinicial = '', rotulo = 'CPF', nulo = 't', tamanho = 11, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CPF' where codcam = 1015458;
            delete from configuracoes.db_syscampodep where codcam = 1015458;
            delete from configuracoes.db_syscampodef where codcam = 1015458;
            insert into configuracoes.db_syscampo values(1015459);
            update configuracoes.db_syscampo set nomecam = 'rh310_dtnascto', conteudo = 'date', descricao = 'Preencher com a data de nascimento.', valorinicial = 'null', rotulo = 'Data de nascimento', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data de nascimento' where codcam = 1015459;
            delete from configuracoes.db_syscampodep where codcam = 1015459;
            delete from configuracoes.db_syscampodef where codcam = 1015459;
            insert into configuracoes.db_syscampo values(1015460);
            update configuracoes.db_syscampo set nomecam = 'rh310_nome', conteudo = 'varchar(70)', descricao = 'Nome do dependente.', valorinicial = '', rotulo = 'Nome do dependente', nulo = 't', tamanho = 70, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Nome do dependente' where codcam = 1015460;
            delete from configuracoes.db_syscampodep where codcam = 1015460;
            delete from configuracoes.db_syscampodef where codcam = 1015460;
            insert into configuracoes.db_syscampo values(1015461);
            update configuracoes.db_syscampo set nomecam = 'rh310_depirrf', conteudo = 'varchar(1)', descricao = 'Somente informar este campo em caso de dependente do trabalhador para fins de dedução de seu rendimento tributável pelo Imposto de Renda.', valorinicial = '', rotulo = 'Dependente rendimento tributável', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Dependente rendimento tributável' where codcam = 1015461;
            delete from configuracoes.db_syscampodep where codcam = 1015461;
            delete from configuracoes.db_syscampodef where codcam = 1015461;
            insert into configuracoes.db_syscampo values(1015462);
            update configuracoes.db_syscampo set nomecam = 'rh310_tpdep', conteudo = 'varchar(2)', descricao = 'Tipo de dependente.', valorinicial = '', rotulo = 'Tipo de dependente', nulo = 't', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Tipo de dependente' where codcam = 1015462;
            delete from configuracoes.db_syscampodep where codcam = 1015462;
            delete from configuracoes.db_syscampodef where codcam = 1015462;
            insert into configuracoes.db_syscampo values(1015463);
            update configuracoes.db_syscampo set nomecam = 'rh310_descrdep', conteudo = 'varchar(100)', descricao = 'Informar a descrição da dependência.', valorinicial = '', rotulo = 'Descrição da dependência', nulo = 't', tamanho = 100, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição da dependência' where codcam = 1015463;
            delete from configuracoes.db_syscampodep where codcam = 1015463;
            delete from configuracoes.db_syscampodef where codcam = 1015463;
            delete from configuracoes.db_sysarqcamp where codarq = 1011147;
            insert into configuracoes.db_sysarqcamp values(1011147,1015455,1,1001170);
            insert into configuracoes.db_sysarqcamp values(1011147,1015456,2,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015457,3,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015458,4,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015459,5,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015460,6,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015461,7,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015462,8,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015463,9,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011147,1001170,1,1011147);
            insert into configuracoes.db_syssequencia values(1001170);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessoirrfcomp_rh310_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001170;
            update configuracoes.db_sysarqcamp set codsequencia = 1001170 where codarq = 1011147 and codcam = 1015455;
            delete from configuracoes.db_sysforkey where codarq = 1011147 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011147,1015456,1,1011032,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011147;
            delete from configuracoes.db_sysprikey where codarq = 1011147;
            delete from configuracoes.db_sysarqcamp where codarq = 1011147;
            delete from configuracoes.db_syscampo where codcam in (1015455, 1015456, 1015457, 1015458, 1015459, 1015460, 1015461, 1015462, 1015463);
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011147;
            delete from configuracoes.db_sysarquivo where codarq = 1011147;
            delete from configuracoes.db_syssequencia where codsequencia = 1001170;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011147 and codcam = 1001170;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhprocessoirrfcomp_rh310_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhprocessoirrfcomp(
            rh310_sequencial		            int4 NOT NULL default nextval('rhprocessoirrfcomp_rh310_sequencial_seq'),
            rh310_sequencialprocessoservidor	int4 NOT NULL default 0,
            rh310_dtlaudo		                date  default null,
            rh310_cpfdep		                varchar(11)   default '',
            rh310_dtnascto		                date  default null,
            rh310_nome		                    varchar(70)   default '',
            rh310_depirrf		                varchar(1)   default '',
            rh310_tpdep		                    varchar(2)   default '',
            rh310_descrdep		                varchar(100)   default '');

        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhprocessoirrfcomp
        ADD CONSTRAINT rhprocessoirrfcomp_sequencialprocessoservidor_fk FOREIGN KEY (rh310_sequencialprocessoservidor)
        REFERENCES recursoshumanos.rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessoirrfcomp;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoirrfcomp_rh310_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoTributoIRRF() {
        $sql  = <<<SQL
            insert into configuracoes.db_syscampo values(1015400);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrrendtrib', conteudo = 'float4', descricao = 'Valor do rendimento tributável mensal do Imposto de Renda.', valorinicial = '0', rotulo = 'Rendimento tributável', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Rendimento tributável' where codcam = 1015400;
            delete from configuracoes.db_syscampodep where codcam = 1015400;
            delete from configuracoes.db_syscampodef where codcam = 1015400;
            insert into configuracoes.db_syscampo values(1015401);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrrendtrib13', conteudo = 'float4', descricao = 'Valor do rendimento tributável do Imposto de Renda referente ao 13º salário - Tributação exclusiva.', valorinicial = '0', rotulo = 'Rendimento tributável 13', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Rendimento tributável 13' where codcam = 1015401;
            delete from configuracoes.db_syscampodep where codcam = 1015401;
            delete from configuracoes.db_syscampodef where codcam = 1015401;
            insert into configuracoes.db_syscampo values(1015402);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrrendmolegrave', conteudo = 'float4', descricao = 'Valor do rendimento isento por ser portador de moléstia grave atestada por laudo médico.', valorinicial = '0', rotulo = 'Valor moléstia grave', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor moléstia grave' where codcam = 1015402;
            delete from configuracoes.db_syscampodep where codcam = 1015402;
            delete from configuracoes.db_syscampodef where codcam = 1015402;
            insert into configuracoes.db_syscampo values(1015403);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrrendIsen65', conteudo = 'float4', descricao = 'Valor de parcela isenta de aposentadoria para beneficiário de 65 anos ou mais.', valorinicial = '0', rotulo = 'Aposentadoria 65 anos', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Aposentadoria 65 anos' where codcam = 1015403;
            delete from configuracoes.db_syscampodep where codcam = 1015403;
            delete from configuracoes.db_syscampodef where codcam = 1015403;
            insert into configuracoes.db_syscampo values(1015404);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrjurosmora', conteudo = 'float4', descricao = 'Juros de mora recebidos, devidos pelo atraso no pagamento de remuneração por exercício de emprego, cargo ou função.', valorinicial = '0', rotulo = 'Juros de mora', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Juros de mora' where codcam = 1015404;
            delete from configuracoes.db_syscampodep where codcam = 1015404;
            delete from configuracoes.db_syscampodef where codcam = 1015404;
            insert into configuracoes.db_syscampo values(1015405);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrrendIsenntrib', conteudo = 'float4', descricao = 'Valor de outros rendimentos isentos ou não tributáveis.', valorinicial = '0', rotulo = 'Rendimentos isentos', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Rendimentos isentos' where codcam = 1015405;
            delete from configuracoes.db_syscampodep where codcam = 1015405;
            delete from configuracoes.db_syscampodef where codcam = 1015405;
            insert into configuracoes.db_syscampo values(1015406);
            update configuracoes.db_syscampo set nomecam = 'rh299_descIsenntrib', conteudo = 'varchar(60)', descricao = 'Descrição do rendimento isento ou não tributável informado', valorinicial = '', rotulo = 'Rendimento isento', nulo = 't', tamanho = 60, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Rendimento isento' where codcam = 1015406;
            delete from configuracoes.db_syscampodep where codcam = 1015406;
            delete from configuracoes.db_syscampodef where codcam = 1015406;
            insert into configuracoes.db_syscampo values(1015407);
            update configuracoes.db_syscampo set nomecam = 'rh299_vrprevoficial', conteudo = 'float4', descricao = 'Valor referente à previdência oficial.', valorinicial = '0', rotulo = 'Previdência oficial', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Previdência oficial' where codcam = 1015407;
            delete from configuracoes.db_syscampodep where codcam = 1015407;
            delete from configuracoes.db_syscampodef where codcam = 1015407;
            insert into configuracoes.db_syscampo values(1015408);
            update configuracoes.db_syscampo set nomecam = 'rh299_descrra', conteudo = 'varchar(50)', descricao = 'Descrição dos Rendimentos Recebidos Acumuladamente - RRA.', valorinicial = '', rotulo = 'Rendimentos Recebidos Acumuladamente', nulo = 't', tamanho = 50, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Rendimentos Recebidos Acumuladamente' where codcam = 1015408;
            delete from configuracoes.db_syscampodep where codcam = 1015408;
            delete from configuracoes.db_syscampodef where codcam = 1015408;
            insert into configuracoes.db_syscampo values(1015409);
            update configuracoes.db_syscampo set nomecam = 'rh299_qtdmesesrra', conteudo = 'int4', descricao = 'Número de meses relativo aos Rendimentos Recebidos Acumuladamente - RRA.', valorinicial = '0', rotulo = 'Número de meses', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Número de meses' where codcam = 1015409;
            delete from configuracoes.db_syscampodep where codcam = 1015409;
            delete from configuracoes.db_syscampodef where codcam = 1015409;
            insert into configuracoes.db_syscampo values(1015410);
            update configuracoes.db_syscampo set nomecam = 'rh299_vlrdespcustas', conteudo = 'float4', descricao = 'Preencher com o valor das despesas com custas judiciais.', valorinicial = '0', rotulo = 'Custas judiciais', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Custas judiciais' where codcam = 1015410;
            delete from configuracoes.db_syscampodep where codcam = 1015410;
            delete from configuracoes.db_syscampodef where codcam = 1015410;
            insert into configuracoes.db_syscampo values(1015411);
            update configuracoes.db_syscampo set nomecam = 'rh299_vlrdespadvogados', conteudo = 'float4', descricao = 'Preencher com o valor total das despesas com advogado(s).', valorinicial = '0', rotulo = 'Despesas com advogado(s)', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Despesas com advogado(s)' where codcam = 1015411;
            delete from configuracoes.db_syscampodep where codcam = 1015411;
            delete from configuracoes.db_syscampodef where codcam = 1015411;
            delete from configuracoes.db_sysarqarq where codarq = 1011103;
            insert into configuracoes.db_sysarqarq values(1011032,1011103);
            delete from configuracoes.db_sysarqcamp where codarq = 1011103;
            insert into configuracoes.db_sysarqcamp values(1011103,1015195,1,1001136);
            insert into configuracoes.db_sysarqcamp values(1011103,1015196,2,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015197,3,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015198,4,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015294,5,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015400,6,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015401,7,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015402,8,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015403,9,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015404,10,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015405,11,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015406,12,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015407,13,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015408,14,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015409,15,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015410,16,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015411,17,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoTributoIRRF() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysarqcamp where codarq = 1011103;
            delete from configuracoes.db_syscampo where codcam in (1015400, 1015401, 1015402, 1015403, 1015404, 1015405, 1015406, 1015407, 1015408, 1015409, 1015410, 1015411);
            insert into configuracoes.db_sysarqcamp values(1011103,1015195,1,1001136);
            insert into configuracoes.db_sysarqcamp values(1011103,1015196,2,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015197,3,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015198,4,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015294,5,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoTributoIRRF() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhprocessotributoirrf');
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrrendtrib		float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrrendtrib13		float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrrendmolegrave	float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrrendIsen65		float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrjurosmora		float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrrendIsenntrib	float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_descIsenntrib		varchar(60) default '';
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vrprevoficial		float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_descrra		    varchar(50) default '';
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_qtdmesesrra		int4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vlrdespcustas		float4 default 0;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf ADD IF NOT EXISTS rh299_vlrdespadvogados	float4 default 0;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhprocessotributoirrf');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoTributoIRRF() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhprocessotributoirrf');
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrrendtrib;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrrendtrib13;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrrendmolegrave;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrrendIsen65;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrjurosmora;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrrendIsenntrib;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_descIsenntrib;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vrprevoficial;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_descrra;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_qtdmesesrra;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vlrdespcustas;
            ALTER TABLE recursoshumanos.rhprocessotributoirrf DROP COLUMN IF EXISTS  rh299_vlrdespadvogados;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhprocessotributoirrf');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011137);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessoadvogado', descricao = 'Identificação dos advogados', sigla = 'rh303', dataincl = '2023-10-10', rotulo = 'Identificação dos advogados', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011137;
            insert into configuracoes.db_sysarqmod values(29, 1011137);
            delete from configuracoes.db_sysarqarq where codarq = 1011137;
            insert into configuracoes.db_sysarqarq values(1011103,1011137);
            insert into configuracoes.db_syscampo values(1015412);
            update configuracoes.db_syscampo set nomecam = 'rh303_sequencial', conteudo = 'int8', descricao = 'Sequencial único da tabela', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015412;
            delete from configuracoes.db_syscampodep where codcam = 1015412;
            delete from configuracoes.db_syscampodef where codcam = 1015412;
            insert into configuracoes.db_syscampo values(1015413);
            update configuracoes.db_syscampo set nomecam = 'rh303_sequencialtributoirrf', conteudo = 'int8', descricao = 'Sequencial vinculo tabela RHPROCESSOTRIBUTOIRRF (FK)', valorinicial = '0', rotulo = 'Sequencial vinculo tributo', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vinculo tributo' where codcam = 1015413;
            delete from configuracoes.db_syscampodep where codcam = 1015413;
            delete from configuracoes.db_syscampodef where codcam = 1015413;
            insert into configuracoes.db_syscampo values(1015414);
            update configuracoes.db_syscampo set nomecam = 'rh303_tpInsc', conteudo = 'int4', descricao = 'Preencher com o código correspondente ao tipo de inscrição do advogado.', valorinicial = '0', rotulo = 'Tipo de inscrição', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de inscrição' where codcam = 1015414;
            delete from configuracoes.db_syscampodep where codcam = 1015414;
            delete from configuracoes.db_syscampodef where codcam = 1015414;
            insert into configuracoes.db_syscampo values(1015415);
            update configuracoes.db_syscampo set nomecam = 'rh303_nrInsc', conteudo = 'varchar(14)', descricao = 'Informar o número de inscrição do advogado.', valorinicial = '', rotulo = 'Número de inscrição', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número de inscrição' where codcam = 1015415;
            delete from configuracoes.db_syscampodep where codcam = 1015415;
            delete from configuracoes.db_syscampodef where codcam = 1015415;
            insert into configuracoes.db_syscampo values(1015416);
            update configuracoes.db_syscampo set nomecam = 'rh303_vlradv', conteudo = 'float4', descricao = 'Valor da despesa com o advogado,.', valorinicial = '0', rotulo = 'Valor despesa', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor despesa' where codcam = 1015416;
            delete from configuracoes.db_syscampodep where codcam = 1015416;
            delete from configuracoes.db_syscampodef where codcam = 1015416;
            delete from configuracoes.db_sysarqcamp where codarq = 1011137;
            insert into configuracoes.db_sysarqcamp values(1011137,1015412,1,1001163);
            insert into configuracoes.db_sysarqcamp values(1011137,1015413,2,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015414,3,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015415,4,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015416,5,0);
            delete from configuracoes.db_sysforkey where codarq = 1011137 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011137,1015413,1,1011103,0);
            insert into configuracoes.db_syssequencia values(1001163);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessoadvogado_rh303_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001163;
            update configuracoes.db_sysarqcamp set codsequencia = 1001163 where codarq = 1011137 and codcam = 1015412;
            delete from configuracoes.db_sysprikey where codarq = 1011137;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011137,1015412,1,1015412);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011137 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011137;
            delete from configuracoes.db_sysarqcamp where codarq = 1011137;
            delete from configuracoes.db_sysforkey where codcam = 1015413;
            delete from configuracoes.db_syscampodef where codcam in (1015412, 1015413, 1015414, 1015415, 1015416);
            delete from configuracoes.db_syscampodep where codcam in (1015412, 1015413, 1015414, 1015415, 1015416);
            delete from configuracoes.db_syscampo where codcam in (1015412, 1015413, 1015414, 1015415, 1015416);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011034 and codarq = 1011137;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011137;
            delete from configuracoes.db_sysarquivo where codarq = 1011137;
            delete from configuracoes.db_syssequencia where codsequencia = 1001163;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhprocessoadvogado_rh303_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Modulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhprocessoadvogado(
        rh303_sequencial		    int8 NOT NULL default 0,
        rh303_sequencialtributoirrf	int8 NOT NULL default 0,
        rh303_tpInsc		        int4  default 0,
        rh303_nrInsc		        varchar(14)   default '',
        rh303_vlradv		        float4 default 0,
        CONSTRAINT rhprocessoadvogado_sequ_pk PRIMARY KEY (rh303_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhprocessoadvogado
        ADD CONSTRAINT rhprocessoadvogado_sequencialtributoirrf_fk FOREIGN KEY (rh303_sequencialtributoirrf)
        REFERENCES recursoshumanos.rhprocessotributoirrf;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessoadvogado;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoadvogado_rh303_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoDependente() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011138);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessodependente', descricao = 'Dedução do rendimento tributável relativa a dependentes.', sigla = 'rh304', dataincl = '2023-10-10', rotulo = 'Tributável dependentes', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011138;
            insert into configuracoes.db_sysarqmod values(29, 1011138);
            delete from configuracoes.db_sysarqarq where codarq = 1011138;
            insert into configuracoes.db_sysarqarq values(1011103,1011138);
            insert into configuracoes.db_syscampo values(1015417);
            update configuracoes.db_syscampo set nomecam = 'rh304_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015417;
            delete from configuracoes.db_syscampodep where codcam = 1015417;
            delete from configuracoes.db_syscampodef where codcam = 1015417;
            insert into configuracoes.db_syscampo values(1015418);
            update configuracoes.db_syscampo set nomecam = 'rh304_sequencialtributoirrf', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPROCESSOTRIBUTOIRRF', valorinicial = '0', rotulo = 'Sequencial vinculo tributo', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vinculo tributo' where codcam = 1015418;
            delete from configuracoes.db_syscampodep where codcam = 1015418;
            delete from configuracoes.db_syscampodef where codcam = 1015418;
            insert into configuracoes.db_syscampo values(1015419);
            update configuracoes.db_syscampo set nomecam = 'rh304_tprend', conteudo = 'int4', descricao = 'Tipo de rendimento tributável relativo a dependentes', valorinicial = '0', rotulo = 'Tipo de rendimento', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de rendimento' where codcam = 1015419;
            delete from configuracoes.db_syscampodep where codcam = 1015419;
            delete from configuracoes.db_syscampodef where codcam = 1015419;
            insert into configuracoes.db_syscampo values(1015420);
            update configuracoes.db_syscampo set nomecam = 'rh304_cpfdep', conteudo = 'varchar(11)', descricao = 'Número de inscrição do dependente no CPF.', valorinicial = '', rotulo = 'CPF', nulo = 't', tamanho = 11, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CPF' where codcam = 1015420;
            delete from configuracoes.db_syscampodep where codcam = 1015420;
            delete from configuracoes.db_syscampodef where codcam = 1015420;
            insert into configuracoes.db_syscampo values(1015421);
            update configuracoes.db_syscampo set nomecam = 'rh304_vlrdeducao', conteudo = 'float4', descricao = 'Valor da dedução da base de cálculo.', valorinicial = '0', rotulo = 'Valor da dedução', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da dedução' where codcam = 1015421;
            delete from configuracoes.db_syscampodep where codcam = 1015421;
            delete from configuracoes.db_syscampodef where codcam = 1015421;
            delete from configuracoes.db_sysarqcamp where codarq = 1011138;
            insert into configuracoes.db_sysarqcamp values(1011138,1015417,1,1001164);
            insert into configuracoes.db_sysarqcamp values(1011138,1015418,2,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015419,3,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015420,4,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015421,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011138;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011138,1015417,1,1015417);
            delete from configuracoes.db_sysforkey where codarq = 1011138 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011138,1015418,1,1011103,0);
            insert into configuracoes.db_syssequencia values(1001164);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessodependente_rh304_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001164;
            update configuracoes.db_sysarqcamp set codsequencia = 1001164 where codarq = 1011138 and codcam = 1015417;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoDependente() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011138;
            delete from configuracoes.db_sysprikey where codarq = 1011138;
            delete from configuracoes.db_sysarqcamp where codarq = 1011138;
            delete from configuracoes.db_sysforkey where codcam = 1015417;
            delete from configuracoes.db_syscampodef where codcam in (1015417, 1015418, 1015419, 1015420, 1015421);
            delete from configuracoes.db_syscampodep where codcam in (1015417, 1015418, 1015419, 1015420, 1015421);
            delete from configuracoes.db_syscampo where codcam in (1015417, 1015418, 1015419, 1015420, 1015421);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011034 and codarq = 1011138;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011138;
            delete from configuracoes.db_sysarquivo where codarq = 1011138;
            delete from configuracoes.db_syssequencia where codsequencia = 1001164;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoDependente() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessodependente_rh304_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Modulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessodependente(
            rh304_sequencial		    int4 NOT NULL default nextval('rhprocessodependente_rh304_sequencial_seq'),
            rh304_sequencialtributoirrf int4 NOT NULL default 0,
            rh304_tprend		        int4 default 0,
            rh304_cpfdep		        varchar(11) default '',
            rh304_vlrdeducao		    float4 default 0,
            CONSTRAINT rhprocessodependente_sequ_pk PRIMARY KEY (rh304_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessodependente
            ADD CONSTRAINT rhprocessodependente_sequencialtributoirrf_fk FOREIGN KEY (rh304_sequencialtributoirrf)
            REFERENCES recursoshumanos.rhprocessotributoirrf;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoDependente() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS rhprocessodependente;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS rhprocessodependente_rh304_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoPensao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011140);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessopensao', descricao = 'Informação dos beneficiários da pensão alimentícia.', sigla = 'rh305', dataincl = '2023-10-10', rotulo = 'Pensão alimentícia', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011140;
            insert into configuracoes.db_sysarqmod values(29, 1011140);
            delete from configuracoes.db_sysarqarq where codarq = 1011140;
            insert into configuracoes.db_sysarqarq values(1011103,1011140);
            delete from configuracoes.db_sysprikey where codarq = 1011140;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011140,1015422,1,1015422);
            insert into configuracoes.db_syssequencia values(1001165);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessopensao_rh305_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001165;
            update configuracoes.db_sysarqcamp set codsequencia = 1001165 where codarq = 1011140 and codcam = 1015422;
            insert into configuracoes.db_syscampo values (1015422);
            update configuracoes.db_syscampo set nomecam = 'rh305_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela.', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015422;
            delete from configuracoes.db_syscampodep where codcam = 1015422;
            delete from configuracoes.db_syscampodef where codcam = 1015422;
            insert into configuracoes.db_syscampo values (1015423);
            update configuracoes.db_syscampo set nomecam = 'rh305_sequencialtributoirrf', conteudo = 'float4', descricao = 'Sequencial que vincula a tabela RHPROCESSOTRIBUTOIRRF', valorinicial = '0', rotulo = 'Sequencial vinculo tributo', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Sequencial vinculo tributo' where codcam = 1015423;
            delete from configuracoes.db_syscampodep where codcam = 1015423;
            delete from configuracoes.db_syscampodef where codcam = 1015423;
            insert into configuracoes.db_syscampo values (1015424);
            update configuracoes.db_syscampo set nomecam = 'rh305_tprend', conteudo = 'int4', descricao = 'Tipo de rendimento dos beneficiários da pensão alimentícia.', valorinicial = '0', rotulo = 'Tipo de rendimento', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de rendimento' where codcam = 1015424;
            delete from configuracoes.db_syscampodep where codcam = 1015424;
            delete from configuracoes.db_syscampodef where codcam = 1015424;
            insert into configuracoes.db_syscampo values (1015425);
            update configuracoes.db_syscampo set nomecam = 'rh305_cpfdep', conteudo = 'varchar(11)', descricao = 'Número do CPF do dependente/beneficiário da pensão alimentícia.', valorinicial = '', rotulo = 'CPF', nulo = 't', tamanho = 11, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CPF' where codcam = 1015425;
            delete from configuracoes.db_syscampodep where codcam = 1015425;
            delete from configuracoes.db_syscampodef where codcam = 1015425;
            insert into configuracoes.db_syscampo values (1015426);
            update configuracoes.db_syscampo set nomecam = 'rh305_vlrpensao', conteudo = 'float4', descricao = 'Valor relativo à dedução do rendimento tributável correspondente a pagamento de pensão alimentícia.', valorinicial = '0', rotulo = 'Valor pensão', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor pensão' where codcam = 1015426;
            delete from configuracoes.db_syscampodep where codcam = 1015426;
            delete from configuracoes.db_syscampodef where codcam = 1015426;
            delete from configuracoes.db_sysarqcamp where codarq = 1011140;
            insert into configuracoes.db_sysarqcamp values(1011140,1015422,1,1001165);
            insert into configuracoes.db_sysarqcamp values(1011140,1015423,2,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015424,3,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015425,4,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015426,5,0);
            delete from configuracoes.db_sysforkey where codarq = 1011140;
            insert into configuracoes.db_sysforkey values(1011140,1015423,1,1011103,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoPensao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011140;
            delete from configuracoes.db_sysprikey where codarq = 1011140;
            delete from configuracoes.db_sysarqcamp where codarq = 1011140;
            delete from configuracoes.db_sysforkey where codcam = 1015417;
            delete from configuracoes.db_syscampodef where codcam in (1015422, 1015423, 1015424, 1015425, 1015426);
            delete from configuracoes.db_syscampodep where codcam in (1015422, 1015423, 1015424, 1015425, 1015426);
            delete from configuracoes.db_syscampo where codcam in (1015422, 1015423, 1015424, 1015425, 1015426);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011103 and codarq = 1011140;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011140;
            delete from configuracoes.db_sysarquivo where codarq = 1011140;
            delete from configuracoes.db_syssequencia where codsequencia = 1001165;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoPensao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessopensao_rh305_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Modulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessopensao(
            rh305_sequencial		    int4 NOT NULL default nextval('rhprocessopensao_rh305_sequencial_seq'),
            rh305_sequencialtributoirrf int4 NOT NULL default 0,
            rh305_tprend		        int4  default 0,
            rh305_cpfdep		        varchar(11) default '',
            rh305_vlrpensao		        float4 default 0,
            CONSTRAINT rhprocessopensao_sequ_pk PRIMARY KEY (rh305_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessopensao
            ADD CONSTRAINT rhprocessopensao_sequencialtributoirrf_fk FOREIGN KEY (rh305_sequencialtributoirrf)
            REFERENCES recursoshumanos.rhprocessotributoirrf;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoPensao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessopensao;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessopensao_rh305_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011141);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessoretencao', descricao = 'Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais.', sigla = 'rh306', dataincl = '2023-10-10', rotulo = 'Retenção de tributos', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011141;
            insert into configuracoes.db_sysarqmod values(29, 1011141);
            delete from configuracoes.db_sysarqarq where codarq = 1011141;
            insert into configuracoes.db_sysarqarq values(1011103,1011141);
            delete from configuracoes.db_sysprikey where codarq = 1011141;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011141,1015427,1,1015427);
            insert into configuracoes.db_syscampo values(1015427);
            update configuracoes.db_syscampo set nomecam = 'rh306_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015427;
            delete from configuracoes.db_syscampodep where codcam = 1015427;
            delete from configuracoes.db_syscampodef where codcam = 1015427;
            insert into configuracoes.db_syscampo values(1015428);
            update configuracoes.db_syscampo set nomecam = 'rh306_sequencialtributoirrf', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPROCESSOTRIBUTOIRRF', valorinicial = '0', rotulo = 'Sequencial vinculo tributo', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vinculo tributo' where codcam = 1015428;
            delete from configuracoes.db_syscampodep where codcam = 1015428;
            delete from configuracoes.db_syscampodef where codcam = 1015428;
            insert into configuracoes.db_syscampo values(1015429);
            update configuracoes.db_syscampo set nomecam = 'rh306_tpprocret', conteudo = 'int4', descricao = 'Código correspondente ao tipo de processo.', valorinicial = '0', rotulo = 'Tipo de processo', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de processo' where codcam = 1015429;
            delete from configuracoes.db_syscampodep where codcam = 1015429;
            delete from configuracoes.db_syscampodef where codcam = 1015429;
            insert into configuracoes.db_syscampo values(1015430);
            update configuracoes.db_syscampo set nomecam = 'rh306_nrprocret', conteudo = 'varchar(21)', descricao = 'Número do processo administrativo/judicial.', valorinicial = '', rotulo = 'Número do processo', nulo = 't', tamanho = 21, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número do processo' where codcam = 1015430;
            delete from configuracoes.db_syscampodep where codcam = 1015430;
            delete from configuracoes.db_syscampodef where codcam = 1015430;
            insert into configuracoes.db_syscampo values(1015431);
            update configuracoes.db_syscampo set nomecam = 'rh306_codsusp', conteudo = 'varchar(14)', descricao = 'Código do indicativo da suspensão, atribuído pelo empregador em S-1070.', valorinicial = '', rotulo = 'Indicativo da suspensão', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Indicativo da suspensão' where codcam = 1015431;
            delete from configuracoes.db_syscampodep where codcam = 1015431;
            delete from configuracoes.db_syscampodef where codcam = 1015431;
            delete from configuracoes.db_sysarqcamp where codarq = 1011141;
            insert into configuracoes.db_sysarqcamp values(1011141,1015427,1,1001166);
            insert into configuracoes.db_sysarqcamp values(1011141,1015428,2,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015429,3,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015430,4,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015431,5,0);
            insert into configuracoes.db_syssequencia values(1001166);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessoretencao_rh306_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001166;
            update configuracoes.db_sysarqcamp set codsequencia = 1001166 where codarq = 1011140 and codcam = 1015422;
            delete from configuracoes.db_sysforkey where codarq = 1011141;
            insert into configuracoes.db_sysforkey values(1011141,1015428,1,1011103,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011141;
            delete from configuracoes.db_sysprikey where codarq = 1011141;
            delete from configuracoes.db_sysarqcamp where codarq = 1011141;
            delete from configuracoes.db_sysforkey where codcam = 1015417;
            delete from configuracoes.db_syscampodef where codcam in (1015427, 1015428, 1015429, 1015430, 1015431);
            delete from configuracoes.db_syscampodep where codcam in (1015427, 1015428, 1015429, 1015430, 1015431);
            delete from configuracoes.db_syscampo where codcam in (1015427, 1015428, 1015429, 1015430, 1015431);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011103 and codarq = 1011141;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011141;
            delete from configuracoes.db_sysarquivo where codarq = 1011141;
            delete from configuracoes.db_syssequencia where codsequencia = 1001166;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
                -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoretencao_rh306_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Modulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoretencao(
            rh306_sequencial		    int4 NOT NULL default  nextval('rhprocessoretencao_rh306_sequencial_seq'),
            rh306_sequencialtributoirrf int4 NOT NULL default 0,
            rh306_tpprocret		        int4  default 0,
            rh306_nrprocret		        varchar(21) default '',
            rh306_codsusp		        varchar(14) default '',
            CONSTRAINT rhprocessoretencao_sequ_pk PRIMARY KEY (rh306_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoretencao
            ADD CONSTRAINT rhprocessoretencao_sequencialtributoirrf_fk FOREIGN KEY (rh306_sequencialtributoirrf)
            REFERENCES recursoshumanos.rhprocessotributoirrf;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessoretencao;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoretencao_rh306_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011142);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessovalorretencao', descricao = 'Informações de valores relacionados a não retenção de tributos ou a depósitos judiciais.', sigla = 'rh307', dataincl = '2023-10-10', rotulo = 'Valores retenção de tributos', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011142;
            insert into configuracoes.db_sysarqmod values(29, 1011142);
            delete from configuracoes.db_sysarqarq where codarq = 1011142;
            insert into configuracoes.db_sysarqarq values(1011141,1011142);
            insert into configuracoes.db_syscampo values(1015432);
            update configuracoes.db_syscampo set nomecam = 'rh307_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015432;
            delete from configuracoes.db_syscampodep where codcam = 1015432;
            delete from configuracoes.db_syscampodef where codcam = 1015432;
            insert into configuracoes.db_syscampo values(1015433);
            update configuracoes.db_syscampo set nomecam = 'rh307_sequencialretencao', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPROCESSORETENCAO', valorinicial = '0', rotulo = 'Sequencial vinculo retencao', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vinculo retencao' where codcam = 1015433;
            delete from configuracoes.db_syscampodep where codcam = 1015433;
            delete from configuracoes.db_syscampodef where codcam = 1015433;
            insert into configuracoes.db_syscampo values(1015434);
            update configuracoes.db_syscampo set nomecam = 'rh307_indapuracao', conteudo = 'int4', descricao = 'Indicativo de período de apuração. Valores válidos: 1 - Mensal 2 - Anual (13° salário)', valorinicial = '0', rotulo = 'Período de apuração', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Período de apuração' where codcam = 1015434;
            delete from configuracoes.db_syscampodep where codcam = 1015434;
            delete from configuracoes.db_syscampodef where codcam = 1015434;
            insert into configuracoes.db_syscampo values(1015435);
            update configuracoes.db_syscampo set nomecam = 'rh307_vlrnretido', conteudo = 'float4', descricao = 'Valor da retenção que deixou de ser efetuada em função de processo administrativo ou judicial', valorinicial = '0', rotulo = 'Valor da retenção', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da retenção' where codcam = 1015435;
            delete from configuracoes.db_syscampodep where codcam = 1015435;
            delete from configuracoes.db_syscampodef where codcam = 1015435;
            insert into configuracoes.db_syscampo values(1015436);
            update configuracoes.db_syscampo set nomecam = 'rh307_vlrdepjud', conteudo = 'float4', descricao = 'Valor do depósito judicial em função de processo administrativo ou judicial.', valorinicial = '0', rotulo = 'Valor do depósito judicial', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor do depósito judicial' where codcam = 1015436;
            delete from configuracoes.db_syscampodep where codcam = 1015436;
            delete from configuracoes.db_syscampodef where codcam = 1015436;
            insert into configuracoes.db_syscampo values(1015437);
            update configuracoes.db_syscampo set nomecam = 'rh307_vlrcmpanocal', conteudo = 'float4', descricao = 'Valor da compensação relativa ao ano calendário em função de processo judicial.', valorinicial = '0', rotulo = 'Valor da compensação', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da compensação' where codcam = 1015437;
            delete from configuracoes.db_syscampodep where codcam = 1015437;
            delete from configuracoes.db_syscampodef where codcam = 1015437;
            insert into configuracoes.db_syscampo values(1015438);
            update configuracoes.db_syscampo set nomecam = 'rh307_vlrcmpanoant', conteudo = 'float4', descricao = 'Valor da compensação relativa a anos anteriores em função de processo judicial.', valorinicial = '0', rotulo = 'Valor da compensação', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da compensação' where codcam = 1015438;
            delete from configuracoes.db_syscampodep where codcam = 1015438;
            delete from configuracoes.db_syscampodef where codcam = 1015438;
            insert into configuracoes.db_syscampo values(1015439);
            update configuracoes.db_syscampo set nomecam = 'rh307_vlrrendsusp', conteudo = 'float4', descricao = 'Valor do rendimento com exigibilidade suspensa.', valorinicial = '0', rotulo = 'Exigibilidade suspensa', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Exigibilidade suspensa' where codcam = 1015439;
            delete from configuracoes.db_syscampodep where codcam = 1015439;
            delete from configuracoes.db_syscampodef where codcam = 1015439;
            delete from configuracoes.db_sysarqcamp where codarq = 1011142;
            insert into configuracoes.db_sysarqcamp values(1011142,1015432,1,1001167);
            insert into configuracoes.db_sysarqcamp values(1011142,1015433,2,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015434,3,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015435,4,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015436,5,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015437,6,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015438,7,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015439,8,0);
            insert into configuracoes.db_syssequencia values(1001167);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessovalorretencao_rh307_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001167;
            update configuracoes.db_sysarqcamp set codsequencia = 1001167 where codarq = 1011142 and codcam = 1015432;
            delete from configuracoes.db_sysprikey where codarq = 1011142;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011142,1015432,1,1015432);
            delete from configuracoes.db_sysforkey where codarq = 1011142 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011142,1015433,1,1011141,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011142;
            delete from configuracoes.db_sysprikey where codarq = 1011142;
            delete from configuracoes.db_sysarqcamp where codarq = 1011142;
            delete from configuracoes.db_sysforkey where codcam = 1015417;
            delete from configuracoes.db_syscampodef where codcam in (1015432, 1015433, 1015434, 1015435, 1015436, 1015437, 1015438, 1015439);
            delete from configuracoes.db_syscampodep where codcam in (1015432, 1015433, 1015434, 1015435, 1015436, 1015437, 1015438, 1015439);
            delete from configuracoes.db_syscampo where codcam in (1015432, 1015433, 1015434, 1015435, 1015436, 1015437, 1015438, 1015439);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011141 and codarq = 1011142;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011142;
            delete from configuracoes.db_sysarquivo where codarq = 1011142;
            delete from configuracoes.db_syssequencia where codsequencia = 1001167;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhprocessovalorretencao_rh307_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Modulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhprocessovalorretencao(
        rh307_sequencial		    int4 NOT NULL default nextval('rhprocessovalorretencao_rh307_sequencial_seq'),
        rh307_sequencialretencao    int4 NOT NULL default 0,
        rh307_indapuracao		    int4  default 0,
        rh307_vlrnretido		    float4  default 0,
        rh307_vlrdepjud		        float4  default 0,
        rh307_vlrcmpanocal		    float4  default 0,
        rh307_vlrcmpanoant		    float4  default 0,
        rh307_vlrrendsusp		    float4 default 0,
        CONSTRAINT rhprocessovalorretencao_sequ_pk PRIMARY KEY (rh307_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhprocessovalorretencao
        ADD CONSTRAINT rhprocessovalorretencao_sequencialretencao_fk FOREIGN KEY (rh307_sequencialretencao)
        REFERENCES recursoshumanos.rhprocessoretencao;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessovalorretencao;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessovalorretencao_rh307_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoReducaoSuspensa() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011143);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessoreducaosuspensa', descricao = 'Detalhamento das deduções com exigibilidade suspensa.', sigla = 'rh308', dataincl = '2023-10-10', rotulo = 'Exigibilidade suspensa.', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011143;
            insert into configuracoes.db_sysarqmod values(29, 1011143);
            delete from configuracoes.db_sysarqarq where codarq = 1011143;
            insert into configuracoes.db_syscampo values(1015440);
            update configuracoes.db_syscampo set nomecam = 'rh308_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015440;
            delete from configuracoes.db_syscampodep where codcam = 1015440;
            delete from configuracoes.db_syscampodef where codcam = 1015440;
            insert into configuracoes.db_syscampo values(1015441);
            update configuracoes.db_syscampo set nomecam = 'rh308_sequencialvalorretencao', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPROCESSOVALORRETENCAO', valorinicial = '0', rotulo = 'Sequencial vinculo valor retenção', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vinculo valor retenção' where codcam = 1015441;
            delete from configuracoes.db_syscampodep where codcam = 1015441;
            delete from configuracoes.db_syscampodef where codcam = 1015441;
            insert into configuracoes.db_syscampo values(1015442);
            update configuracoes.db_syscampo set nomecam = 'rh308_indtpdeducao', conteudo = 'int4', descricao = 'Indicativo do tipo de dedução.', valorinicial = '0', rotulo = 'Tipo de dedução', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de dedução' where codcam = 1015442;
            delete from configuracoes.db_syscampodep where codcam = 1015442;
            delete from configuracoes.db_syscampodef where codcam = 1015442;
            insert into configuracoes.db_syscampo values(1015443);
            update configuracoes.db_syscampo set nomecam = 'rh308_vlrdedsusp', conteudo = 'float4', descricao = 'Valor da dedução da base de cálculo do imposto de renda com exigibilidade suspensa.', valorinicial = '0', rotulo = 'Valor da dedução', nulo = 'f', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da dedução' where codcam = 1015443;
            delete from configuracoes.db_syscampodep where codcam = 1015443;
            delete from configuracoes.db_syscampodef where codcam = 1015443;
            delete from configuracoes.db_sysarqcamp where codarq = 1011143;
            insert into configuracoes.db_sysarqcamp values(1011143,1015440,1,1001168);
            insert into configuracoes.db_sysarqcamp values(1011143,1015441,2,0);
            insert into configuracoes.db_sysarqcamp values(1011143,1015442,3,0);
            insert into configuracoes.db_sysarqcamp values(1011143,1015443,4,0);
            insert into configuracoes.db_sysarqarq values(1011142,1011143);
            delete from configuracoes.db_sysprikey where codarq = 1011143;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011143,1015440,1,1015440);
            delete from configuracoes.db_sysforkey where codarq = 1011143 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011143,1015441,1,1011142,0);
            insert into configuracoes.db_syssequencia values(1001168);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessoreducaosuspensa_rh308_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001168;
            update configuracoes.db_sysarqcamp set codsequencia = 1001168 where codarq = 1011143 and codcam = 1015440;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoReducaoSuspensa() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011143;
            delete from configuracoes.db_sysprikey where codarq = 1011143;
            delete from configuracoes.db_sysarqcamp where codarq = 1011143;
            delete from configuracoes.db_sysforkey where codcam = 1015441;
            delete from configuracoes.db_syscampodef where codcam in (1015440, 1015441, 1015442, 1015443);
            delete from configuracoes.db_syscampodep where codcam in (1015440, 1015441, 1015442, 1015443);
            delete from configuracoes.db_syscampo where codcam in (1015440, 1015441, 1015442, 1015443);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011142 and codarq = 1011143;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011143;
            delete from configuracoes.db_sysarquivo where codarq = 1011143;
            delete from configuracoes.db_syssequencia where codsequencia = 1001168;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoReducaoSuspensa() {
        $sql  = <<<SQL
        -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoreducaosuspensa_rh308_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Modulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoreducaosuspensa(
            rh308_sequencial		        int4 NOT NULL default nextval('rhprocessoreducaosuspensa_rh308_sequencial_seq'),
            rh308_sequencialvalorretencao   int4 NOT NULL default 0,
            rh308_indtpdeducao		        int4  default 0,
            rh308_vlrdedsusp		        float4 default 0,
            CONSTRAINT rhprocessoreducaosuspensa_sequ_pk PRIMARY KEY (rh308_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoreducaosuspensa
            ADD CONSTRAINT rhprocessoreducaosuspensa_sequencialvalorretencao_fk FOREIGN KEY (rh308_sequencialvalorretencao)
            REFERENCES recursoshumanos.rhprocessovalorretencao;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoReducaoSuspensa() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessoreducaosuspensa;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoreducaosuspensa_rh308_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoSuspensaPensao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values(1011144);
            update configuracoes.db_sysarquivo set nomearq = 'rhprocessosuspensapensao', descricao = 'Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia', sigla = 'rh309', dataincl = '2023-10-11', rotulo = 'Deduções suspensas pensão', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1011144;
            insert into configuracoes.db_sysarqmod values(29, 1011144);
            delete from configuracoes.db_sysarqarq where codarq = 1011144;
            insert into configuracoes.db_sysarqarq values(1011143,1011144);
            insert into configuracoes.db_syscampo values(1015444);
            update configuracoes.db_syscampo set nomecam = 'rh309_sequencial', conteudo = 'int4', descricao = 'Sequencial único da tabela', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1015444;
            delete from configuracoes.db_syscampodep where codcam = 1015444;
            delete from configuracoes.db_syscampodef where codcam = 1015444;
            insert into configuracoes.db_syscampo values(1015445);
            update configuracoes.db_syscampo set nomecam = 'rh309_sequencialreducaosuspensa', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPROCESSOREDUCAOSUSPENSA', valorinicial = '0', rotulo = 'Sequencial vinculo', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial vinculo' where codcam = 1015445;
            delete from configuracoes.db_syscampodep where codcam = 1015445;
            delete from configuracoes.db_syscampodef where codcam = 1015445;
            insert into configuracoes.db_syscampo values(1015446);
            update configuracoes.db_syscampo set nomecam = 'rh309_cpfdep', conteudo = 'varchar(11)', descricao = 'Número de inscrição no CPF.', valorinicial = '', rotulo = 'CPF', nulo = 't', tamanho = 11, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CPF' where codcam = 1015446;
            delete from configuracoes.db_syscampodep where codcam = 1015446;
            delete from configuracoes.db_syscampodef where codcam = 1015446;
            insert into configuracoes.db_syscampo values(1015447);
            update configuracoes.db_syscampo set nomecam = 'rh309_vlrdepensusp', conteudo = 'float4', descricao = 'Valor da dedução relativa a dependentes ou a pensão alimentícia com exigibilidade suspensa.', valorinicial = '0', rotulo = 'Valor da dedução', nulo = 't', tamanho = 15, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da dedução' where codcam = 1015447;
            delete from configuracoes.db_syscampodep where codcam = 1015447;
            delete from configuracoes.db_syscampodef where codcam = 1015447;
            delete from configuracoes.db_sysarqcamp where codarq = 1011144;
            insert into configuracoes.db_sysarqcamp values(1011144,1015444,1,1001169);
            insert into configuracoes.db_sysarqcamp values(1011144,1015445,2,0);
            insert into configuracoes.db_sysarqcamp values(1011144,1015446,3,0);
            insert into configuracoes.db_sysarqcamp values(1011144,1015447,4,0);
            insert into configuracoes.db_syssequencia values(1001169);
            update configuracoes.db_syssequencia set nomesequencia = 'rhprocessosuspensapensao_rh309_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001169;
            update configuracoes.db_sysarqcamp set codsequencia = 1001169 where codarq = 1011144 and codcam = 1015444;
            delete from configuracoes.db_sysprikey where codarq = 1011144;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011144,1015444,1,1015444);
            delete from configuracoes.db_sysforkey where codarq = 1011144 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011144,1015445,1,1011143,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoSuspensaPensao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011144;
            delete from configuracoes.db_sysprikey where codarq = 1011144;
            delete from configuracoes.db_sysarqcamp where codarq = 1011144;
            delete from configuracoes.db_sysforkey where codcam = 1015441;
            delete from configuracoes.db_syscampodef where codcam in (1015444, 1015445, 1015446, 1015447);
            delete from configuracoes.db_syscampodep where codcam in (1015444, 1015445, 1015446, 1015447);
            delete from configuracoes.db_syscampo where codcam in (1015444, 1015445, 1015446, 1015447);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011142 and codarq = 1011144;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011144;
            delete from configuracoes.db_sysarquivo where codarq = 1011144;
            delete from configuracoes.db_syssequencia where codsequencia = 1001169;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoSuspensaPensao() {
        $sql  = <<<SQL
            CREATE SEQUENCE recursoshumanos.rhprocessosuspensapensao_rh309_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Modulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessosuspensapensao(
            rh309_sequencial		        int4 NOT NULL default nextval('rhprocessosuspensapensao_rh309_sequencial_seq'),
            rh309_sequencialreducaosuspensa int4 NOT NULL default 0,
            rh309_cpfdep		            varchar(11) default '',
            rh309_vlrdepensusp		        float4 default 0,
            CONSTRAINT rhprocessosuspensapensao_sequ_pk PRIMARY KEY (rh309_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessosuspensapensao
            ADD CONSTRAINT rhprocessosuspensapensao_sequencialreducaosuspensa_fk FOREIGN KEY (rh309_sequencialreducaosuspensa)
            REFERENCES recursoshumanos.rhprocessoreducaosuspensa;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoSuspensaPensao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessosuspensapensao;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessosuspensapensao_rh309_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoDesligamento() {
        $sql  = <<<SQL
        DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015340 AND codarq=1011046;
        insert into configuracoes.db_syscampo values(1015340);
        update db_syscampo set nomecam = 'rh279_pensalim', conteudo = 'int4', descricao = 'Indicativo de pensão alimentícia para fins de retenção de FGTS. 0 - Não existe pensão alimentícia 1 - Percentual de pensão alimentícia 2 - Valor de pensão alimentícia 3 - Percentual e valor de pensão alimentícia', valorinicial = '0', rotulo = 'Indicativo de pensão', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Indicativo de pensão' where codcam = 1015340;
        delete from db_syscampodep where codcam = 1015340;
        delete from db_syscampodef where codcam = 1015340;
        DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015341 AND codarq=1011046;
        insert into configuracoes.db_syscampo values(1015341);
        update db_syscampo set nomecam = 'rh279_percaliment', conteudo = 'float4', descricao = 'Percentual a ser destinado a pensão alimentícia.', valorinicial = '0', rotulo = 'Percentual pensão', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Percentual pensão' where codcam = 1015341;
        delete from db_syscampodep where codcam = 1015341;
        delete from db_syscampodef where codcam = 1015341;
        DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015342 AND codarq=1011046;
        insert into configuracoes.db_syscampo values(1015342);
        update configuracoes.db_syscampo set nomecam = 'rh279_vlralim', conteudo = 'float4', descricao = 'Valor da pensão alimentícia.', valorinicial = '0', rotulo = 'Valor da pensão', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor da pensão' where codcam = 1015342;
        delete from configuracoes.db_syscampodep where codcam = 1015342;
        delete from configuracoes.db_syscampodef where codcam = 1015342;
        delete from configuracoes.db_sysarqcamp where codarq = 1011046;
        insert into configuracoes.db_sysarqcamp values(1011046,1014883,1,1001159);
        insert into configuracoes.db_sysarqcamp values(1011046,1014884,2,0);
        insert into configuracoes.db_sysarqcamp values(1011046,1014885,3,0);
        insert into configuracoes.db_sysarqcamp values(1011046,1014886,4,0);
        insert into configuracoes.db_sysarqcamp values(1011046,1014887,5,0);
        insert into configuracoes.db_sysarqcamp values(1011046,1015340,6,0);
        insert into configuracoes.db_sysarqcamp values(1011046,1015341,7,0);
        insert into configuracoes.db_sysarqcamp values(1011046,1015342,8,0);
        update configuracoes.db_syssequencia set nomesequencia = 'rhpessoalprocessodesligamento_rh279_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001159;
        update configuracoes.db_sysarqcamp set codsequencia = 1001159 where codarq = 1011046 and codcam = 1014883;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoDesligamento() {
        $sql  = <<<SQL
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015340 AND codarq=1011046;
            delete from configuracoes.db_syscampo where codcam = 1015340;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015341 AND codarq=1011046;
            delete from configuracoes.db_syscampo where codcam = 1015341;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015342 AND codarq=1011046;
            delete from configuracoes.db_syscampo where codcam = 1015342;
            delete from configuracoes.db_sysarqcamp where codarq = 1011046;
            insert into configuracoes.db_sysarqcamp values(1011046,1014883,1,1001159);
            insert into configuracoes.db_sysarqcamp values(1011046,1014884,2,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014885,3,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014886,4,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014887,5,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoDesligamento() {
        $sql  = <<<SQL
            CREATE SEQUENCE IF NOT EXISTS recursoshumanos.rhpessoalprocessodesligamento_rh279_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessodesligamento');
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento ADD rh279_pensalim int4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento ADD rh279_percaliment float4 NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento ADD rh279_vlralim float4 NULL DEFAULT 0;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessodesligamento');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoDesligamento() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessodesligamento');
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento DROP COLUMN IF EXISTS rh279_pensalim;
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento DROP COLUMN IF EXISTS rh279_percaliment;
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento DROP COLUMN IF EXISTS rh279_vlralim;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessodesligamento');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhPessoalProcessoEstatutario() {
        $sql  = <<<SQL
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015362 AND codarq=1011042;
            delete from configuracoes.db_syscampodef where codcam = 1015362;
            delete from configuracoes.db_syscampodep where codcam = 1015362;
            delete from configuracoes.db_syscampo where codcam = 1015362;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015361 AND codarq=1011042;
            delete from configuracoes.db_syscampodef where codcam = 1015361;
            delete from configuracoes.db_syscampodep where codcam = 1015361;
            delete from configuracoes.db_syscampo where codcam = 1015361;
            delete from configuracoes.db_sysarqcamp where codarq = 1011042;
            insert into configuracoes.db_sysarqcamp values(1011042,1014877,1,1001158);
            insert into configuracoes.db_sysarqcamp values(1011042,1014878,2,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014879,3,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014880,4,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014881,5,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014882,6,0);
            insert into configuracoes.db_syssequencia values(1001158);
            update configuracoes.db_syssequencia set nomesequencia = 'rhpessoalprocessoestatutario_rh278_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001158;
            update configuracoes.db_sysarqcamp set codsequencia = 1001158 where codarq = 1011042 and codcam = 1014877;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downDicionarioTabelaRhPessoalProcessoEstatutario() {
        $sql  = <<<SQL
            insert into configuracoes.db_syscampo values(1015362);
            update configuracoes.db_syscampo set nomecam = 'rh278_mtvdesligtsv', conteudo = 'varchar(2)', descricao = 'Motivo do término do diretor não empregado, com FGTS. Valores válidos: 01 - Exoneração do diretor não empregado sem justa causa, por deliberação da assembleia, dos sócios cotistas ou da autoridade competente 02 - Término de mandato do diretor não empregado que não tenha sido reconduzido ao cargo 03 - Exoneração a pedido de diretor não empregado 04 - Exoneração do diretor não empregado por culpa recíproca ou força maior 05 - Morte do diretor não empregado 06 - Exoneração do diretor não empregado por falência, encerramento ou supressão de parte da empresa 99 - Outros', valorinicial = '', rotulo = 'Código motivo', nulo = 't', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código motivo' where codcam = 1015362;
            delete from configuracoes.db_syscampodep where codcam = 1015362;
            delete from configuracoes.db_syscampodef where codcam = 1015362;
            insert into configuracoes.db_syscampo values(1015361);
            update configuracoes.db_syscampo set nomecam = 'rh278_dtterm', conteudo = 'date', descricao = 'Data do término de TSVE.', valorinicial = 'null', rotulo = 'Data do término', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data do término' where codcam = 1015361;
            delete from configuracoes.db_syscampodep where codcam = 1015361;
            delete from configuracoes.db_syscampodef where codcam = 1015361;
            delete from configuracoes.db_sysarqcamp where codarq = 1011042;
            insert into configuracoes.db_sysarqcamp values(1011042,1014877,1,1001158);
            insert into configuracoes.db_sysarqcamp values(1011042,1014878,2,1001158);
            insert into configuracoes.db_sysarqcamp values(1011042,1014879,3,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014880,4,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014881,5,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014882,6,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1015362,7,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1015361,8,0);
            delete from configuracoes.db_syssequencia where codsequencia = 1001158;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function upEstruturaTabelaRhPessoalProcessoEstatutario() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessoestatutario');
            ALTER TABLE recursoshumanos.rhpessoalprocessoestatutario DROP COLUMN IF EXISTS rh278_mtvdesligtsv;
            ALTER TABLE recursoshumanos.rhpessoalprocessoestatutario DROP COLUMN IF EXISTS rh278_dtterm;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessoestatutario');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhPessoalProcessoEstatutario() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessoestatutario');
            ALTER TABLE recursoshumanos.rhpessoalprocessoestatutario ADD rh278_mtvdesligtsv varchar(2)  NULL DEFAULT '';
            ALTER TABLE recursoshumanos.rhpessoalprocessoestatutario ADD rh278_dtterm date default null;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessoestatutario');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhPessoalProcessoVinculo() {
        $sql  = <<<SQL
            insert into configuracoes.db_syssequencia values(1001157);
            update configuracoes.db_syssequencia set nomesequencia = 'rhpessoalprocessovinculo_rh274_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001157;
            update configuracoes.db_sysarqcamp set codsequencia = 1001157 where codarq = 1011036 and codcam = 1014858;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhPessoalProcessoVinculo() {
        $sql  = <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001157;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011036 and codcam = 1014858;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhPessoalProcessoVinculo() {
        $sql  = <<<SQL
            CREATE SEQUENCE IF NOT EXISTS recursoshumanos.rhpessoalprocessovinculo_rh274_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhPessoalProcessoVinculo() {
        $sql  = <<<SQL
        DROP SEQUENCE IF EXISTS recursoshumanos.rhpessoalprocessovinculo_rh274_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function upDicionarioTabelaRhPessoalProcessoRemuneracao() {
        $sql  = <<<SQL
        delete from configuracoes.db_sysforkey where codarq = 1011033;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014825 AND codarq=1011033;
            delete from configuracoes.db_syscampodef where codcam = 1014825;
            delete from configuracoes.db_syscampodep where codcam = 1014825;
            delete from configuracoes.db_syscampo where codcam = 1014825;
            insert into configuracoes.db_syscampo values(1015481);
            update configuracoes.db_syscampo set nomecam = 'rh272_sequencialprocessocontrato', conteudo = 'int4', descricao = 'Sequencial que vincula a tabela RHPESSOALPROCESSOCONTRATO', valorinicial = '0', rotulo = 'Sequencial contrato', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial contrato' where codcam = 1015481;
            delete from configuracoes.db_sysforkey where codarq = 1011033;
            insert into configuracoes.db_sysforkey values(1011033,1015481,1,1011034,0);
            delete from configuracoes.db_sysarqcamp where codarq = 1011033;
            insert into configuracoes.db_sysarqcamp values(1011033,1014824,1,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1015481,2,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014826,3,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014827,4,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014828,5,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014829,6,0);
            insert into configuracoes.db_syssequencia values(1001171);
            update configuracoes.db_syssequencia set nomesequencia = 'rhpessoalprocessoremuneracao_rh272_sequencial_seq', incrseq = 1, minvalueseq = 1, maxvalueseq = 9223372036854775807, startseq = 1, cacheseq = 1 where codsequencia = 1001171;
            update configuracoes.db_sysarqcamp set codsequencia = 1001171 where codarq = 1011033 and codcam = 1014824;


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhPessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011033;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1014825 AND codarq=1011033;
            delete from configuracoes.db_syscampodef where codcam = 1014825;
            delete from configuracoes.db_syscampodep where codcam = 1014825;
            delete from configuracoes.db_syscampo where codcam = 1014825;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam=1015481 AND codarq=1011033;
            delete from configuracoes.db_syscampodef where codcam = 1015481;
            delete from configuracoes.db_syscampodep where codcam = 1015481;
            delete from configuracoes.db_syscampo where codcam = 1015481;
            delete from configuracoes.db_sysarqcamp where codarq = 1011033;
            insert into configuracoes.db_sysarqcamp values(1011033,1014824,1,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014826,2,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014827,3,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014828,4,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014829,5,0);
            delete from configuracoes.db_syssequencia where codsequencia = 1001171;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011033 and codcam = 1014824;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhPessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessoremuneracao');
            ALTER TABLE recursoshumanos.rhpessoalprocessoremuneracao DROP COLUMN IF EXISTS rh271_sequencialprocessoservidor CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessoremuneracao ADD IF NOT EXISTS rh272_sequencialprocessocontrato int4 NOT NULL DEFAULT 0;
            ALTER TABLE recursoshumanos.rhpessoalprocessoremuneracao ADD CONSTRAINT rhpessoalprocessoremuneracao_sequencialprocessocontrato_fk FOREIGN KEY (rh272_sequencialprocessocontrato) REFERENCES rhpessoalprocessocontrato(rh273_sequencial);
            ALTER TABLE recursoshumanos.rhpessoalprocessoremuneracao DROP CONSTRAINT IF EXISTS rhpessoalprocessoremuneracao_sequencialprocessoservidor_fk;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessoremuneracao');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhPessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            select configuracoes.fc_auditoria_remove_funcao('recursoshumanos.rhpessoalprocessoremuneracao');
            ALTER TABLE recursoshumanos.rhpessoalprocessoremuneracao DROP COLUMN IF EXISTS rh272_sequencialprocessocontrato CASCADE;
            ALTER TABLE recursoshumanos.rhpessoalprocessoremuneracao ADD IF NOT EXISTS rh271_sequencialprocessoservidor int4 NOT NULL DEFAULT 0;
            select configuracoes.fc_auditoria_cria_funcao('recursoshumanos.rhpessoalprocessoremuneracao');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
