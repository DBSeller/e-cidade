<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20568AdicaoHelpMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/contabilidade/#!CadastroPlanosdeContasOrcamentario.md' where id_item = 9073;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/contabilidade/#!CadastroPlanodeContasPCASP.md' where id_item = 9069;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/contabilidade/#!cadastrosHistoricoLancamentos.md' where id_item = 3356;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/contabilidade/#!cadastrosTiposdeCompra.md' where id_item = 3359;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/contabilidade/#!ConsultasLancamentosContabeis.md' where id_item = 9472;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/caixa/#!manutencao_receitas.md' where id_item = 46;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/caixa/#!tutoriais_conciliacao_sem_carga.md' where id_item = 228484;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/planejamento/#!tutorial_ppa.md' where id_item = 228360;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosParametrosMacroeconomicos.md' where id_item = 7206;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosCenarioMacroeconomico.md' where id_item = 7210;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosTiposdeRecursos.md' where id_item = 3177;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosOrgaos.md' where id_item = 3205;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosUnidades.md' where id_item = 3209;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosFuncoes.md' where id_item = 3181;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadstrosSubfuncao.md' where id_item = 3185;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosProgramas.md' where id_item = 3189;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosAtividadesProjetos.md' where id_item = 3193;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosIndicadoresPPA.md' where id_item = 4288;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosProdutosPPA.md' where id_item = 4087;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosPPALDO.md' where id_item = 7202;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosPeriododeIndicadores.md' where id_item = 7252;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosUnidadesResponsaveis.md' where id_item = 7256;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!CadastrosSubtitulos.md' where id_item = 7260;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosAssociacaoParametros.md' where id_item = 7242;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosAssociacaoParametros.md' where id_item = 7243;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosPPALDOLOA.md' where id_item = 7247;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosPPALDOLOA.md' where id_item = 7248;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 4107;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7263;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7265;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7299;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7300;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7305;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7757;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!RelatoriosdoPPA.md' where id_item = 7788;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosSuplementacoesReducoes.md' where id_item = 3245;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosProcSuplementacao.md' where id_item = 3255;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosDesprocSuplementacao.md' where id_item = 4466;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosManutencaodeSuplementacao.md' where id_item = 3245;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosPrevReceita.md' where id_item = 3219;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ProcedimentosMetasdaDespesa.md' where id_item = 3213;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ConsultaReceita.md' where id_item = 3250;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/orcamento/#!ConsultaDotacao.md' where id_item = 3249;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ConsultaEmpenho.md' where id_item = 3492;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ConsultaEmpenho.md' where id_item = 3404;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!RelatoriosNotaEmpenho.md' where id_item = 3671;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!RelatoriodeMovimentacaoEmpenho.md' where id_item = 4337;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosEmissaoEmpenho.md' where id_item = 3489;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!CadastrosRetencoes.md' where id_item = 6912;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosAnula%C3%A7%C3%A3oEmpenho.md' where id_item = 3494;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosAutEmpenho.md' where id_item = 2568;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosEstornodeLiquidacao.md' where id_item = 3505;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosLancarRetencoes.md' where id_item = 7795;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosLiqSemOrdem.md' where id_item = 437510;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosLiquidacao.md' where id_item = 3504;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!ProcedimentosNotadeDesconto.md' where id_item = 1985513;
update db_itensmenu set help = 'https://e-cidade.wiki.br/financeiro/empenho/#!RelatoriosExecucaoRestos.md' where id_item = 826055;
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
update db_itensmenu set help = '' where id_item = 9073;
update db_itensmenu set help = '' where id_item = 9069;
update db_itensmenu set help = '' where id_item = 3356;
update db_itensmenu set help = '' where id_item = 3359;
update db_itensmenu set help = '' where id_item = 9472;
update db_itensmenu set help = '' where id_item = 46;
update db_itensmenu set help = '' where id_item = 228484;
update db_itensmenu set help = '' where id_item = 228360;
update db_itensmenu set help = '' where id_item = 7206;
update db_itensmenu set help = '' where id_item = 7210;
update db_itensmenu set help = '' where id_item = 3177;
update db_itensmenu set help = '' where id_item = 3205;
update db_itensmenu set help = '' where id_item = 3209;
update db_itensmenu set help = '' where id_item = 3181;
update db_itensmenu set help = '' where id_item = 3185;
update db_itensmenu set help = '' where id_item = 3189;
update db_itensmenu set help = '' where id_item = 3193;
update db_itensmenu set help = '' where id_item = 4288;
update db_itensmenu set help = '' where id_item = 4087;
update db_itensmenu set help = '' where id_item = 7202;
update db_itensmenu set help = '' where id_item = 7252;
update db_itensmenu set help = '' where id_item = 7256;
update db_itensmenu set help = '' where id_item = 7260;
update db_itensmenu set help = '' where id_item = 7242;
update db_itensmenu set help = '' where id_item = 7243;
update db_itensmenu set help = '' where id_item = 7247;
update db_itensmenu set help = '' where id_item = 7248;
update db_itensmenu set help = '' where id_item = 4107;
update db_itensmenu set help = '' where id_item = 7263;
update db_itensmenu set help = '' where id_item = 7265;
update db_itensmenu set help = '' where id_item = 7299;
update db_itensmenu set help = '' where id_item = 7300;
update db_itensmenu set help = '' where id_item = 7305;
update db_itensmenu set help = '' where id_item = 7757;
update db_itensmenu set help = '' where id_item = 7788;
update db_itensmenu set help = '' where id_item = 3245;
update db_itensmenu set help = '' where id_item = 3255;
update db_itensmenu set help = '' where id_item = 4466;
update db_itensmenu set help = '' where id_item = 3245;
update db_itensmenu set help = '' where id_item = 3219;
update db_itensmenu set help = '' where id_item = 3213;
update db_itensmenu set help = '' where id_item = 3250;
update db_itensmenu set help = '' where id_item = 3249;
update db_itensmenu set help = '' where id_item = 3492;
update db_itensmenu set help = '' where id_item = 3404;
update db_itensmenu set help = '' where id_item = 3671;
update db_itensmenu set help = '' where id_item = 4337;
update db_itensmenu set help = '' where id_item = 3489;
update db_itensmenu set help = '' where id_item = 6912;
update db_itensmenu set help = '' where id_item = 3494;
update db_itensmenu set help = '' where id_item = 2568;
update db_itensmenu set help = '' where id_item = 3505;
update db_itensmenu set help = '' where id_item = 7795;
update db_itensmenu set help = '' where id_item = 437510;
update db_itensmenu set help = '' where id_item = 3504;
update db_itensmenu set help = '' where id_item = 1985513;
update db_itensmenu set help = '' where id_item = 826055;
SQL
        );
    }
}
