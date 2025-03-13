<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProcessamentoRecadastramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recursoshumanos.processamentorecadastramento', function (Blueprint $table) {
            $table->increments('h260_sequencial');
            $table->integer('h260_regist');
            $table->integer('h260_codproc');
            $table->boolean('h260_status');
            $table->text('h260_erro')->nullable();
            $table->integer('h260_usuario');
            $table->integer('h260_instit');
            $table->timestamp('h260_data');

            $table->foreign('h260_regist')->references('rh01_regist')->on('pessoal.rhpessoal');
            $table->foreign('h260_codproc')->references('p58_codproc')->on('protocolo.protprocesso');
            $table->foreign('h260_instit')->references('codigo')->on('configuracoes.db_config');
            $table->foreign('h260_usuario')->references('id_usuario')->on('configuracoes.db_usuarios');
        });

        $this->upDicionarioDados();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionarioDados();
        Schema::dropIfExists('recursoshumanos.processamentorecadastramento');
    }


    public function upDicionarioDados()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        insert into db_sysarquivo values (1010873, 'processamentorecadastramento', 'Salvar informações de processamento do recadastramento', 'h260', '2022-03-18', 'Processamento do Recadastramento', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (29,1010873);
        insert into db_syscampo values(1013806,'h260_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1013807,'h260_regist','int4','Matricula do servidor','0', 'Matricula',10,'f','f','f',1,'text','Matricula');
        insert into db_syscampo values(1013808,'h260_codproc','int4','Código do Processo','0', 'Código do Processo',10,'f','f','f',1,'text','Código do Processo');
        insert into db_syscampo values(1013809,'h260_status','bool','Status do Processamento','f', 'Status',1,'f','f','f',5,'text','Status');
        insert into db_syscampo values(1013810,'h260_erro','text','Erro do processamento','', 'Erro',1,'t','t','f',0,'text','Erro');
        insert into db_syscampo values(1013811,'h260_usuario','int4','Usuário que executou o processamento','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
        insert into db_syscampo values(1013812,'h260_instit','int4','Instituição do processamento','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
        insert into db_syscampo values(1013813,'h260_data','date','Data do processamento do recadastramento','null', 'Data Processamento',10,'f','f','f',1,'text','Data Processamento');

        insert into db_sysarqcamp values(1010873,1013806,1,0);
        insert into db_sysarqcamp values(1010873,1013807,2,0);
        insert into db_sysarqcamp values(1010873,1013808,3,0);
        insert into db_sysarqcamp values(1010873,1013809,4,0);
        insert into db_sysarqcamp values(1010873,1013810,5,0);
        insert into db_sysarqcamp values(1010873,1013812,6,0);
        insert into db_sysarqcamp values(1010873,1013811,7,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010873,1013806,1,1013806);
        insert into db_sysforkey values(1010873,1013807,1,1153,0);
        insert into db_sysforkey values(1010873,1013808,1,403,0);
        insert into db_sysforkey values(1010873,1013812,1,83,0);
        insert into db_sysforkey values(1010873,1013811,1,109,0);
        insert into db_sysarqcamp values(1010873,1013813,8,0);
        insert into db_syssequencia values(1001041, 'processamentorecadastramento_h260_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228637 ,'Processamento Recadastramento' ,'Processamento Recadastramento' ,'rh4_recadastramento_processo.php' ,'1' ,'1' ,'Processamento do Recadastramento' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228637 ,550 ,2323 );

        drop view if exists public.cadastro_pessoal_2008;
        alter table rhpesdoc alter column rh16_carth_n type varchar(12);
        alter table rhpesdoc alter column rh16_ctps_n type varchar(11);
        alter table rhpesdoc alter column rh16_zonael type varchar(4);
        ALTER TABLE protocolo.cgm ALTER COLUMN z01_mae TYPE varchar(70) USING z01_mae::varchar;
        ALTER TABLE protocolo.cgm ALTER COLUMN z01_pai TYPE varchar(70) USING z01_pai::varchar;
        ALTER TABLE pessoal.rhpesdoc ALTER COLUMN r16_carth_cat TYPE varchar(4) USING r16_carth_cat::varchar;
        ALTER TABLE protocolo.cgmalt ALTER COLUMN z05_mae TYPE varchar(70) USING z05_mae::varchar;
        ALTER TABLE protocolo.cgmalt ALTER COLUMN z05_pai TYPE varchar(70) USING z05_pai::varchar;
SQL
        );

        $this->createView();
    }

    public function downDicionarioDados()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        delete from db_syssequencia where codsequencia = 1001041;
        delete from db_sysforkey where codarq = 1010873;
        delete from db_sysarqcamp where codarq = 1010873;
        delete from db_sysprikey where codarq = 1010873;
        delete from db_sysarqcamp where codarq = 1010873;
        delete from db_syscampo where codcam in (1013806,1013807,1013808,1013809,1013810,1013811,1013812,1013813);
        delete from db_sysarqmod where codarq in (1010873);
        delete from db_sysarquivo where codarq in (1010873);
        delete from db_menu where id_item_filho = 228637;
        delete from db_itensmenu where id_item = 228637;
SQL
        );
    }

    public function createView()
    {

    DB::connection()->getPdo()->exec(<<<SQL

CREATE VIEW public.cadastro_pessoal_2008 AS
SELECT rhpessoalmov.rh02_funcao,
     rhpessoalmov.rh02_instit,
     rhpessoalmov.rh02_anousu,
     rhpessoalmov.rh02_mesusu,
     rhpessoalmov.rh02_regist,
     rhpessoalmov.rh02_portadormolestia,
     rhpessoalmov.rh02_deficientefisico,
     rhpessoal.rh01_regist,
     rhpessoalmov.rh02_hrssem,
     rhpessoalmov.rh02_hrsmen,
     padroes.r02_anousu,
     padroes.r02_mesusu,
     padroes.r02_regime,
     padroes.r02_codigo,
     padroes.r02_descr,
     padroes.r02_valor,
     padroes.r02_hrssem,
     padroes.r02_hrsmen,
     padroes.r02_tipo,
     padroes.r02_form,
     padroes.r02_minimo,
     padroes.r02_instit,
     cgm.z01_nome,
     rhpessoal.rh01_admiss,
     rhpesrescisao.rh05_recis,
     rhpessoal.rh01_sexo,
     rhpessoal.rh01_nasc,
     rhpessoalmov.rh02_tbprev,
     rhlota.r70_estrut,
     rhlota.r70_descr,
     rhpessoalmov.rh02_funcao AS rh01_funcao,
     btrim((rhfuncao.rh37_descr)::TEXT) AS rh37_descr,
     rhinstrucao.rh21_descr,
     rhestcivil.rh08_descr,
     rhipe.rh14_matipe,
     rhpesdoc.rh16_titele,
     rhpesdoc.rh16_zonael,
     rhpesdoc.rh16_secaoe,
     rhpesdoc.rh16_reserv,
     rhpesdoc.rh16_catres,
     rhpesdoc.rh16_ctps_n,
     rhpesdoc.rh16_ctps_s,
     rhpesdoc.rh16_ctps_d,
     rhpesdoc.rh16_ctps_uf,
     rhpesdoc.rh16_pis,
     cgm.z01_cgccpf,
     cgm.z01_ident,
     cgm.z01_telef,
     cgm.z01_ender,
     cgm.z01_compl,
     cgm.z01_numero,
     cgm.z01_munic,
     cgm.z01_bairro,
     cgm.z01_cep,
     cgm.z01_numcgm,
     rhpesdoc.rh16_carth_n,
     rhpesdoc.r16_carth_cat,
     rhpesdoc.rh16_carth_val,
     rhpespadrao.rh03_padrao,
     rhregime.rh30_descr,
     rhregime.rh30_regime,
     rhregime.rh30_vinculo,
     rhpesbanco.rh44_codban,
     rhpesbanco.rh44_agencia,
     rhpesbanco.rh44_dvagencia,
     rhpesbanco.rh44_conta,
     rhpesbanco.rh44_dvconta,
     rhlocaltrab.rh55_estrut,
     rhlocaltrab.rh55_descr,
     rhpessoal.rh01_trienio,
     rhpessoal.rh01_progres,
     rhraca.rh18_descr,
     f.rh37_descr AS h07_cant,
     admissao.h07_regist,
     admissao.h07_tipadm,
     admissao.h07_dato,
     admissao.h07_dhist,
     admissao.h07_ddem,
     admissao.h07_icon,
     admissao.h07_ires,
     admissao.h07_class,
     admissao.h07_refe,
     admissao.h07_area,
     admissao.h07_nrato,
     admissao.h07_nrfich,
     admissao.h07_impofi,
     admissao.h07_dpubl,
     admissao.h07_fundam,
     admissao.h07_defet,
     admissao.h07_tempor,
     admissao.h07_termin,
     flegal.h04_descr,
     areas.h05_descr,
     concur.h06_refer,
     concur.h06_eaber,
     concur.h06_daber,
     concur.h06_ehomo,
     concur.h06_dhomo,
     concur.h06_concur,
     concur.h06_dvalid,
     concur.h06_dprorr,
     concur.h06_dpubl,
     concur.h06_nrproc,
     ( SELECT    CASE
                     WHEN (rhpessoalmov.rh02_tbprev = 0) THEN 'SEM PREVIDENCIA'::bpchar
ELSE inssirf.r33_nome
END AS r33_nome
FROM inssirf
WHERE ((inssirf.r33_codtab = (rhpessoalmov.rh02_tbprev + 2))
	AND (inssirf.r33_anousu = rhpessoalmov.rh02_anousu)
		AND (rhpessoalmov.rh02_mesusu = inssirf.r33_mesusu)
			AND (rhpessoalmov.rh02_instit = inssirf.r33_instit))
LIMIT 1) AS r33_nome
FROM ((((((((((((((((((((((rhpessoal
JOIN cgm ON
((rhpessoal.rh01_numcgm = cgm.z01_numcgm)))
JOIN rhpessoalmov ON
(((rhpessoalmov.rh02_anousu = fc_anofolha(rhpessoalmov.rh02_instit))
	AND (rhpessoalmov.rh02_mesusu = fc_mesfolha(rhpessoalmov.rh02_instit))
		AND (rhpessoalmov.rh02_regist = rhpessoal.rh01_regist))))
LEFT JOIN rhpesrescisao ON
((rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes)))
JOIN rhlota ON
(((rhlota.r70_codigo = rhpessoalmov.rh02_lota)
	AND (rhlota.r70_instit = rhpessoalmov.rh02_instit))))
JOIN rhfuncao ON
(((rhpessoal.rh01_funcao = rhfuncao.rh37_funcao)
	AND (rhfuncao.rh37_instit = rhpessoalmov.rh02_instit))))
JOIN rhinstrucao ON
((rhpessoal.rh01_instru = rhinstrucao.rh21_instru)))
JOIN rhestcivil ON
((rhpessoal.rh01_estciv = rhestcivil.rh08_estciv)))
LEFT JOIN rhiperegist ON
((rhiperegist.rh62_regist = rhpessoal.rh01_regist)))
LEFT JOIN rhipe ON
(((rhipe.rh14_sequencia = rhiperegist.rh62_sequencia)
	AND (rhipe.rh14_instit = rhpessoalmov.rh02_instit))))
LEFT JOIN rhpeslocaltrab ON
(((rhpeslocaltrab.rh56_seqpes = rhpessoalmov.rh02_seqpes)
	AND (rhpeslocaltrab.rh56_princ = TRUE))))
LEFT JOIN rhlocaltrab ON
(((rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo)
	AND (rhlocaltrab.rh55_instit = rhpessoalmov.rh02_instit))))
LEFT JOIN rhpesdoc ON
((rhpesdoc.rh16_regist = rhpessoal.rh01_regist)))
LEFT JOIN rhpespadrao ON
((rhpessoalmov.rh02_seqpes = rhpespadrao.rh03_seqpes)))
LEFT JOIN padroes ON
(((padroes.r02_anousu = rhpespadrao.rh03_anousu)
	AND (padroes.r02_mesusu = rhpespadrao.rh03_mesusu)
		AND (padroes.r02_regime = rhpespadrao.rh03_regime)
			AND (padroes.r02_codigo = rhpespadrao.rh03_padrao)
				AND (padroes.r02_instit = rhpessoalmov.rh02_instit))))
JOIN rhregime ON
(((rhregime.rh30_codreg = rhpessoalmov.rh02_codreg)
	AND (rhregime.rh30_instit = rhpessoalmov.rh02_instit))))
LEFT JOIN rhpesbanco ON
((rhpesbanco.rh44_seqpes = rhpessoalmov.rh02_seqpes)))
LEFT JOIN admissao ON
((rhpessoal.rh01_regist = admissao.h07_regist)))
LEFT JOIN rhfuncao f ON
(((f.rh37_funcao = (admissao.h07_cant)::integer)
	AND (f.rh37_instit = rhpessoalmov.rh02_instit))))
LEFT JOIN flegal ON
((flegal.h04_codigo = admissao.h07_fundam)))
LEFT JOIN concur ON
((concur.h06_refer = admissao.h07_refe)))
LEFT JOIN areas ON
((areas.h05_codigo = admissao.h07_area)))
LEFT JOIN rhraca ON
((rhpessoal.rh01_raca = rhraca.rh18_raca)))
ORDER BY cgm.z01_nome;

SQL
);
    }
}
