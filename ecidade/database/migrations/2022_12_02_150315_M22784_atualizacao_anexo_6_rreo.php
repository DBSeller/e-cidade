<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22784AtualizacaoAnexo6Rreo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigoPpProcessado = DB::select("select nextval('orcparamseqcoluna_o115_sequencial_seq')")[0]->nextval;
        $codigoSaldoLiquidado = DB::select("select nextval('orcparamseqcoluna_o115_sequencial_seq')")[0]->nextval;

        DB::connection()->getPdo()->exec(<<<SQL
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem)
values (264, 84, '(-) Restos a Pagar Processados - Intra', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '(-) Restos a Pagar Processados - Intra', 't', 'f', 84, 4, '', 'f', 4),
       (265, 41, '(-) Restos a Pagar Processados - Intra', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', '(-) Restos a Pagar Processados - Intra', 't', 'f', 41, 3, '', 'f', 4);

insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio)
values ($codigoPpProcessado, 2022, 'RP - Processado', 1, '', 'inscricao_rp_processado', '', 0, 264),
       ($codigoSaldoLiquidado, 2022, 'RP - Saldo dos RP inscritos como processados no exercício', 1, '', 'saldo_rp_processado', '', 0, 264);

insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula)
values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoPpProcessado, 1, 6, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoPpProcessado, 1, 7, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoPpProcessado, 1, 8, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoPpProcessado, 1, 9, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoPpProcessado, 1, 10, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoPpProcessado, 1, 11, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoSaldoLiquidado, 2, 6, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoSaldoLiquidado, 2, 7, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoSaldoLiquidado, 2, 8, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoSaldoLiquidado, 2, 9, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoSaldoLiquidado, 2, 10, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 264, $codigoSaldoLiquidado, 2, 11, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 265, $codigoPpProcessado, 1, 14, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 265, $codigoPpProcessado, 1, 15, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 265, $codigoPpProcessado, 1, 16, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 265, $codigoPpProcessado, 1, 12, ''),
       (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 265, $codigoPpProcessado, 1, 13, '');

insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula
  from orcparamseqorcparamseqcoluna
 where o116_codparamrel = 265 and o116_codseq = 40 and o116_ordem > 1
order by o116_ordem;

insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 264, 84, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331910000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="333910000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="344910000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="345910000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');

insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 265, 41, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331910000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="333910000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="344910000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="345910000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
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
delete from orcparamseqfiltropadrao where o132_orcparamrel = 264 and o132_orcparamseq = 84;
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 264 and o116_codseq = 84;
delete from orcparamseq where o69_codparamrel = 264 and o69_codseq = 84;

delete from orcparamseqfiltropadrao where o132_orcparamrel = 265 and o132_orcparamseq = 41;
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 265 and o116_codseq = 41;
delete from orcparamseq where o69_codparamrel = 265 and o69_codseq = 41;
SQL
        );
    }
}
