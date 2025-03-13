<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20613AlteraHelpDosItensDeMenuEmContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_categoria.md' WHERE id_item = 9676;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_categoria.md' WHERE id_item = 9677;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_categoria.md' WHERE id_item = 9678;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_comissoes.md' WHERE id_item = 8276;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_comissoes.md' WHERE id_item = 8277;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_comissoes.md' WHERE id_item = 8278;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_garantia.md' WHERE id_item = 8318;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_garantia.md' WHERE id_item = 8319;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_garantia.md' WHERE id_item = 8320;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_grupos_de_acordos.md' WHERE id_item = 8266;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_grupos_de_acordos.md' WHERE id_item = 8267;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_grupos_de_acordos.md' WHERE id_item = 8268;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_natureza_dos_acordos.md' WHERE id_item = 8258;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_natureza_dos_acordos.md' WHERE id_item = 8259;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_natureza_dos_acordos.md' WHERE id_item = 8260;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_penalidades.md' WHERE id_item = 8282;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_penalidades.md' WHERE id_item = 8283;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_penalidades.md' WHERE id_item = 8284;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_tipos_acordo.md' WHERE id_item = 8262;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_tipos_acordo.md' WHERE id_item = 8263;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!cadastros_tipos_acordo.md' WHERE id_item = 8264;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!consultas_acordos.md' WHERE id_item = 8564;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_acordo.md' WHERE id_item = 8290;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_acordo.md' WHERE id_item = 8291;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_acordo.md' WHERE id_item = 9846;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_aditamentos.md' WHERE id_item = 8569;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_aditamentos.md' WHERE id_item = 8573;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_aditamentos.md' WHERE id_item = 8588;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_aditamentos.md' WHERE id_item = 8589;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_aditamentos.md' WHERE id_item = 10227;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_anexar_documentos.md' WHERE id_item = 9606;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_anulacao_do_contrato.md' WHERE id_item = 8424;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_anulacao_do_contrato.md' WHERE id_item = 8425;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_anulacao_do_contrato.md' WHERE id_item = 8426;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_assinatura_do_contrato.md' WHERE id_item = 8417;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_assinatura_do_contrato.md' WHERE id_item = 8418;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_autorizacao_de_empenho_contratos.md' WHERE id_item = 8563;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_autorizacao_de_empenho_contratos.md' WHERE id_item = 8567;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_configurar_cronograma_execucao.md' WHERE id_item = 8812;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_eventos.md' WHERE id_item = 10225;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_execucao_acordo.md' WHERE id_item = 8459;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_homologacao_do_contrato.md' WHERE id_item = 8410;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_homologacao_do_contrato.md' WHERE id_item = 8411;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_paralisacao.md' WHERE id_item = 9921;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_paralisacao.md' WHERE id_item = 9922;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_paralisacao.md' WHERE id_item = 9923;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_parametros.md' WHERE id_item = 228399;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_reativacao.md' WHERE id_item = 9926;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_reativacao.md' WHERE id_item = 9927;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_regime_de_competencia.md' WHERE id_item = 8580;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_regime_de_competencia.md' WHERE id_item = 10418;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_rescisao_do_contrato.md' WHERE id_item = 8420;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_rescisao_do_contrato.md' WHERE id_item = 8421;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_rescisao_do_contrato.md' WHERE id_item = 8422;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!procedimentos_vincular_contrato_com_empenhos.md' WHERE id_item = 10221;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_conferencia_acordos_a_vencer.md' WHERE id_item = 8596;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_contratos_sem_programacao_competencia.md' WHERE id_item = 10464;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_extrato_de_contrato.md' WHERE id_item = 9939;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_mapa_de_exucucao_contrato.md' WHERE id_item = 10021;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_mapa_de_exucucao_financeira.md' WHERE id_item = 10020;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_movimentacoes_acordos.md' WHERE id_item = 8585;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_movimentacoes_financeira.md' WHERE id_item = 9636;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/contratos/#!relatorios_reimpressao_do_acordo.md' WHERE id_item = 9679;
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
            UPDATE db_itensmenu SET help = 'Incluir uma paralisação' WHERE id_item = 9921;
            UPDATE db_itensmenu SET help = 'Alteração de Acordotipo' WHERE id_item = 8263;
            UPDATE db_itensmenu SET help = 'Exclusão' WHERE id_item = 9846;
            UPDATE db_itensmenu SET help = 'Excluir paralisação' WHERE id_item = 9923;
            UPDATE db_itensmenu SET help = 'Incluir uma reativação' WHERE id_item = 9926;
            UPDATE db_itensmenu SET help = 'Alterar paralisação' WHERE id_item = 9922;
            UPDATE db_itensmenu SET help = 'Relatorio de Extrato de Contratos' WHERE id_item = 9939;
            UPDATE db_itensmenu SET help = 'Cancelar uma reativação' WHERE id_item = 9927;
            UPDATE db_itensmenu SET help = 'Alteração de Acordocomissao' WHERE id_item = 8277;
            UPDATE db_itensmenu SET help = 'Exclusão de Acordocomissao' WHERE id_item = 8278;
            UPDATE db_itensmenu SET help = 'Inclusão de Penalidade' WHERE id_item = 8282;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordogarantia' WHERE id_item = 8318;
            UPDATE db_itensmenu SET help = 'Alteração de Acordogarantia' WHERE id_item = 8319;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 8417;
            UPDATE db_itensmenu SET help = 'Cancelar Cancelamento' WHERE id_item = 8422;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 8424;
            UPDATE db_itensmenu SET help = 'Cancelar Cancelamento' WHERE id_item = 8426;
            UPDATE db_itensmenu SET help = 'Alteração de Acordonatureza' WHERE id_item = 8259;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordotipo' WHERE id_item = 8262;
            UPDATE db_itensmenu SET help = 'Exclusão de Acordonatureza' WHERE id_item = 8260;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 8410;
            UPDATE db_itensmenu SET help = 'Exclusão de Acordotipo' WHERE id_item = 8264;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordogrupo' WHERE id_item = 8266;
            UPDATE db_itensmenu SET help = 'Alteração de Acordogrupo' WHERE id_item = 8267;
            UPDATE db_itensmenu SET help = 'Exclusão de Acordogrupo' WHERE id_item = 8268;
            UPDATE db_itensmenu SET help = 'Exclusão de Penalidade' WHERE id_item = 8284;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordo' WHERE id_item = 8290;
            UPDATE db_itensmenu SET help = 'Alteração de Acordo' WHERE id_item = 8291;
            UPDATE db_itensmenu SET help = 'Visualizar a execução financeira do acordo' WHERE id_item = 10020;
            UPDATE db_itensmenu SET help = 'Cancelamento' WHERE id_item = 8411;
            UPDATE db_itensmenu SET help = 'Visualizar os dados com base na execução do contrato' WHERE id_item = 10021;
            UPDATE db_itensmenu SET help = 'Exclusão de Acordogarantia' WHERE id_item = 8320;
            UPDATE db_itensmenu SET help = 'Execução do itens do contrato' WHERE id_item = 8459;
            UPDATE db_itensmenu SET help = 'Cancelamento' WHERE id_item = 8418;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 8420;
            UPDATE db_itensmenu SET help = 'Cancelamento' WHERE id_item = 8421;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordonatureza' WHERE id_item = 8258;
            UPDATE db_itensmenu SET help = 'Vincular Contrato com Empenhos' WHERE id_item = 10221;
            UPDATE db_itensmenu SET help = 'Exclusão de Acordocategoria' WHERE id_item = 9678;
            UPDATE db_itensmenu SET help = 'Consulta Acordos' WHERE id_item = 8564;
            UPDATE db_itensmenu SET help = 'Gerar Autorizacoes' WHERE id_item = 8563;
            UPDATE db_itensmenu SET help = 'Aditamentos de Quantidade/Valor' WHERE id_item = 8573;
            UPDATE db_itensmenu SET help = 'Reequilibro Financeiro' WHERE id_item = 8569;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordocategoria' WHERE id_item = 9676;
            UPDATE db_itensmenu SET help = 'Relatório dos Acordos' WHERE id_item = 8585;
            UPDATE db_itensmenu SET help = 'Reimpressão Acordo' WHERE id_item = 9679;
            UPDATE db_itensmenu SET help = 'Acordos a Vencer' WHERE id_item = 8596;
            UPDATE db_itensmenu SET help = 'Configurar Cronograma Execução' WHERE id_item = 8812;
            UPDATE db_itensmenu SET help = 'Aditamentos de Prazo' WHERE id_item = 8588;
            UPDATE db_itensmenu SET help = 'Renovação' WHERE id_item = 8589;
            UPDATE db_itensmenu SET help = 'Cancelar Autorização' WHERE id_item = 8567;
            UPDATE db_itensmenu SET help = 'Anexar arquivos ao contrato' WHERE id_item = 9606;
            UPDATE db_itensmenu SET help = 'Alteração de Acordocategoria' WHERE id_item = 9677;
            UPDATE db_itensmenu SET help = 'Movimentações Financeiras do Contrato' WHERE id_item = 9636;
            UPDATE db_itensmenu SET help = 'Alteração de Penalidade' WHERE id_item = 8283;
            UPDATE db_itensmenu SET help = 'Implantação de Contratos em Execução' WHERE id_item = 10418;
            UPDATE db_itensmenu SET help = 'Programação' WHERE id_item = 8580;
            UPDATE db_itensmenu SET help = 'Eventos dos acordos' WHERE id_item = 10225;
            UPDATE db_itensmenu SET help = 'Supressão de Quantidade/Valor' WHERE id_item = 10227;
            UPDATE db_itensmenu SET help = 'Contratos sem Programação' WHERE id_item = 10464;
            UPDATE db_itensmenu SET help = 'Cancelamento' WHERE id_item = 8425;
            UPDATE db_itensmenu SET help = 'Inclusão de Acordocomissao' WHERE id_item = 8276;
            UPDATE db_itensmenu SET help = 'con4_acordohomologacao001.php' WHERE id_item = 228399;
SQL
        );
    }
}
