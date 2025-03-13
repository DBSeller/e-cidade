<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20866ViewCadastroPortariaAle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(
            <<<SQL
        create or replace VIEW pessoal.cadastro_portaria_ale AS
 SELECT rhpessoal.rh01_regist,
    cgm.z01_nome,
    portaria.h31_dtportaria,
    portaria.h31_numero,
    portaria.h31_anousu,
    rhpessoal.rh01_numcgm,
    cgm.z01_ident,
    cgm.z01_ender,
    cgm.z01_numero,
    cgm.z01_compl,
    cgm.z01_bairro,
    cgm.z01_cep,
    cgm.z01_munic,
    rhpessoal.rh01_admiss,
        CASE rhregime.rh30_regime
            WHEN 1 THEN 'ESTATUTARIO'::text
            WHEN 2 THEN 'CLT'::text
            WHEN 3 THEN 'EXTRA QUADRO'::text
            ELSE NULL::text
        END AS rh30_regime,
    rhfuncao.rh37_descr,
    rhlocaltrab.rh55_descr,
    padroes.r02_descr,
        CASE
            WHEN (substr((padroes.r02_descr)::text, 1, 1) = 'P'::text) THEN ''::text
            ELSE btrim(substr((padroes.r02_descr)::text, 3, 2))
        END AS r02_nivel,
        CASE
            WHEN (substr((padroes.r02_descr)::text, 1, 1) <> 'P'::text) THEN ''::text
            ELSE btrim(split_part((padroes.r02_descr)::text, '-'::text, 1))
        END AS r02_padrao,
        CASE
            WHEN (substr((padroes.r02_descr)::text, 1, 1) <> 'P'::text) THEN ''::text
            ELSE btrim(split_part((padroes.r02_descr)::text, '-'::text, 2))
        END AS r02_grau,
        CASE
            WHEN (substr((padroes.r02_descr)::text, 1, 1) = 'P'::text) THEN ''::text
            ELSE btrim(substr((padroes.r02_descr)::text, 6, 1))
        END AS r02_classe,
    tipoasse.h12_descr,
    btrim(portaria.h31_amparolegal) AS h31_amparolegal,
    assenta.h16_histor,
    assenta.h16_hist2,
    portariaproced.h40_descr,
    portariaenvolv.h42_descr,
    assenta.h16_dtconc,
    portaria.h31_portariatipo,
    f.rh37_descr AS h07_cant
   FROM ((((((((((((((((((((recursoshumanos.portaria
     JOIN recursoshumanos.portariaassenta ON ((portaria.h31_sequencial = portariaassenta.h33_portaria)))
     JOIN recursoshumanos.portariatipo ON ((portariatipo.h30_sequencial = portaria.h31_portariatipo)))
     JOIN recursoshumanos.portariaenvolv ON ((portariaenvolv.h42_sequencial = portariatipo.h30_portariaenvolv)))
     JOIN recursoshumanos.portariaproced ON ((portariaproced.h40_sequencial = portariatipo.h30_portariaproced)))
     JOIN recursoshumanos.assenta ON ((portariaassenta.h33_assenta = assenta.h16_codigo)))
     JOIN recursoshumanos.tipoasse ON ((assenta.h16_assent = tipoasse.h12_codigo)))
     JOIN pessoal.rhpessoal ON ((assenta.h16_regist = rhpessoal.rh01_regist)))
     JOIN protocolo.cgm ON ((rhpessoal.rh01_numcgm = cgm.z01_numcgm)))
     LEFT JOIN pessoal.rhpessoalmov ON (((rhpessoalmov.rh02_regist = rhpessoal.rh01_regist) AND (rhpessoalmov.rh02_anousu = public.fc_anofolha(rhpessoalmov.rh02_instit)) AND (rhpessoalmov.rh02_mesusu = public.fc_mesfolha(rhpessoalmov.rh02_instit)))))
     JOIN pessoal.rhfuncao ON (((rhfuncao.rh37_funcao = rhpessoal.rh01_funcao) AND (rhfuncao.rh37_instit = rhpessoalmov.rh02_instit))))
     LEFT JOIN pessoal.rhregime ON (((rhregime.rh30_codreg = rhpessoalmov.rh02_codreg) AND (rhregime.rh30_instit = rhpessoalmov.rh02_instit))))
     LEFT JOIN pessoal.rhpeslocaltrab ON (((rhpeslocaltrab.rh56_seqpes = rhpessoalmov.rh02_seqpes) AND (rhpeslocaltrab.rh56_princ = true))))
     LEFT JOIN pessoal.rhlocaltrab ON ((rhlocaltrab.rh55_codigo = rhpeslocaltrab.rh56_localtrab)))
     LEFT JOIN pessoal.rhpespadrao ON ((rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes)))
     LEFT JOIN pessoal.padroes ON (((padroes.r02_anousu = rhpessoalmov.rh02_anousu) AND (padroes.r02_mesusu = rhpessoalmov.rh02_mesusu) AND (padroes.r02_regime = rhregime.rh30_regime) AND (btrim((padroes.r02_codigo)::text) = btrim((rhpespadrao.rh03_padrao)::text)) AND (padroes.r02_instit = rhpessoalmov.rh02_instit))))
     LEFT JOIN recursoshumanos.admissao ON ((rhpessoal.rh01_regist = admissao.h07_regist)))
     LEFT JOIN pessoal.rhfuncao f ON (((f.rh37_funcao::text = admissao.h07_cant::text) AND (f.rh37_instit = rhpessoalmov.rh02_instit))))
     LEFT JOIN recursoshumanos.flegal ON ((flegal.h04_codigo = admissao.h07_fundam)))
     LEFT JOIN recursoshumanos.concur ON ((concur.h06_refer = admissao.h07_refe)))
     LEFT JOIN recursoshumanos.areas ON ((areas.h05_codigo = admissao.h07_area)));
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
        DB::connection()->getPdo()->exec(
            <<<SQL
        DROP VIEW pessoal.cadastro_portaria_ale;         
SQL
        );
    }
}
