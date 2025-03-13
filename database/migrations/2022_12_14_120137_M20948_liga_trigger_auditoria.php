<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948LigaTriggerAuditoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplano');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoconta');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanocontabancaria');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoexerecurso');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoextra');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoreduzcgm');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.contacorrentedetalhe');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoreduz');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.clabensconplano');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoatributos');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoconplanoorcamento');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoconsaldo');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanocontacorrente');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanogrupo');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoorcamentoanalitica');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.avaliacaogruporespostaconta');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoorcamentoconta');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoorcamentocontabancaria');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoorcamentogrupo');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.conplanoorcamento');
select configuracoes.fc_auditoria_cria_funcao('material.materialestoquegrupoconta');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orccenarioeconomicoconplano');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orcdotacao');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orcelemento');
select configuracoes.fc_auditoria_cria_funcao('orcamento.ppadotacao');
select configuracoes.fc_auditoria_cria_funcao('orcamento.ppadotacaoorcdotacao');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orccenarioeconomicoconplanoorcamento');
select configuracoes.fc_auditoria_cria_funcao('orcamento.ppaestimativareceita');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orcfontesdes');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orcreceita');
select configuracoes.fc_auditoria_cria_funcao('orcamento.orcfontes');
select configuracoes.fc_auditoria_cria_funcao('empenho.classificacaocredoreselemento');
select configuracoes.fc_auditoria_cria_funcao('caixa.taborc');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.pcasp');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.pcaspconplano');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.planoreceita');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.planodespesa');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.planoreceitaconplanoorcamento');
select configuracoes.fc_auditoria_cria_funcao('contabilidade.planodespesaconplanoorcamento');
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
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplano');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoconta');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanocontabancaria');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoexerecurso');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoextra');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoreduzcgm');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.contacorrentedetalhe');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoreduz');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.clabensconplano');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoatributos');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoconplanoorcamento');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoconsaldo');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanocontacorrente');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanogrupo');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoorcamentoanalitica');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.avaliacaogruporespostaconta');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoorcamentoconta');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoorcamentocontabancaria');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoorcamentogrupo');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.conplanoorcamento');
select configuracoes.fc_auditoria_remove_funcao('material.materialestoquegrupoconta');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orccenarioeconomicoconplano');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orcdotacao');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orcelemento');
select configuracoes.fc_auditoria_remove_funcao('orcamento.ppadotacao');
select configuracoes.fc_auditoria_remove_funcao('orcamento.ppadotacaoorcdotacao');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orccenarioeconomicoconplanoorcamento');
select configuracoes.fc_auditoria_remove_funcao('orcamento.ppaestimativareceita');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orcfontesdes');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orcreceita');
select configuracoes.fc_auditoria_remove_funcao('orcamento.orcfontes');
select configuracoes.fc_auditoria_remove_funcao('empenho.classificacaocredoreselemento');
select configuracoes.fc_auditoria_remove_funcao('caixa.taborc');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.pcasp');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.pcaspconplano');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.planoreceita');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.planodespesa');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.planoreceitaconplanoorcamento');
select configuracoes.fc_auditoria_remove_funcao('contabilidade.planodespesaconplanoorcamento');
SQL
        );
    }
}
