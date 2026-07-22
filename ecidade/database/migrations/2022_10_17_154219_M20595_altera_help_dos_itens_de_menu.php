<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20595AlteraHelpDosItensDeMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_depositos.md' WHERE id_item = 7153;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_depositos.md' WHERE id_item = 7152;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_depositos.md' WHERE id_item = 7151;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_fabricantes.md' WHERE id_item = 6832;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_fabricantes.md' WHERE id_item = 6831;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_fabricantes.md' WHERE id_item = 6830;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_grupos_subgrupos.md' WHERE id_item = 8786;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_grupos_subgrupos.md' WHERE id_item = 8785;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_material.md' WHERE id_item = 3984;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_material.md' WHERE id_item = 3983;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_material.md' WHERE id_item = 3982;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_unidades.md' WHERE id_item = 3980;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_unidades.md' WHERE id_item = 3979;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_unidades.md' WHERE id_item = 3978;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_vinculos_tipo_grupo_subgrupo.md' WHERE id_item = 9346;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!cadastros_vinculos_tipo_grupo_subgrupo.md' WHERE id_item = 9345;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!consultas_arquivos_pit.md' WHERE id_item = 7831;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!consultas_controle_de_validade.md' WHERE id_item = 8069;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!consultas_material.md' WHERE id_item = 9226;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!consultas_ordem_de_compra.md' WHERE id_item = 9897;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!devolucao_de_materiais.md' WHERE id_item = 4320;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_atendimento_de_requisicao.md' WHERE id_item = 4299;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_configuracao_do_texto_da_oc.md' WHERE id_item = 4175;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_configuracao_do_texto_da_oc.md' WHERE id_item = 4174;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_entrada_de_ordem_de_compra.md' WHERE id_item = 587073;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_entrada_de_ordem_de_compra.md' WHERE id_item = 7775;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_entrada_de_ordem_de_compra.md' WHERE id_item = 3992;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_entrada_manual.md' WHERE id_item = 4311;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_entrada_manual.md' WHERE id_item = 4310;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_entrada_manual.md' WHERE id_item = 4309;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_fechamento_estoque.md' WHERE id_item = 9870;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_implantacao_estoque.md' WHERE id_item = 4304;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_implantacao_estoque.md' WHERE id_item = 4303;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_implantacao_estoque.md' WHERE id_item = 4302;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_parametros.md' WHERE id_item = 7820;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_parametros.md' WHERE id_item = 7581;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_parametros.md' WHERE id_item = 7580;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_pit.md' WHERE id_item = 7825;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_pit.md' WHERE id_item = 7824;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_pit.md' WHERE id_item = 7823;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_planilha_de_distribuicao.md' WHERE id_item = 10172;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_planilha_de_distribuicao.md' WHERE id_item = 10171;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_planilha_de_distribuicao.md' WHERE id_item = 10170;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_requisicao_de_saida_de_materiais.md' WHERE id_item = 8019;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_requisicao_de_saida_de_materiais.md' WHERE id_item = 4606;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_requisicao_de_saida_de_materiais.md' WHERE id_item = 4305;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_requisicao_de_saida_de_materiais.md' WHERE id_item = 4298;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_requisicao_de_saida_de_materiais.md' WHERE id_item = 4297;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_saida_manual.md' WHERE id_item = 4319;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_saida_manual.md' WHERE id_item = 4318;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_saida_manual.md' WHERE id_item = 4317;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 8480;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 8479;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 8478;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 8003;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 7996;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 4335;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 4333;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 4315;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!procedimentos_transferencia_entre_depositos.md' WHERE id_item = 4313;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_anulacoes_de_entrada_de_oc.md' WHERE id_item = 8024;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_arquivos.pit.md' WHERE id_item = 7830;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_cadastrais_cadastro_materiais.md' WHERE id_item = 4345;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_cadastrais_materiais_interligados.md' WHERE id_item = 4526;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_cadastrais_material_origem.md' WHERE id_item = 4499;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_cadastrais_tipo_de_lancamentos.md' WHERE id_item = 4366;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_cadastrais_unidades.md' WHERE id_item = 4344;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_conferencia_estoque_item_novo.md' WHERE id_item = 9228;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_conferencia_estoque_item.md' WHERE id_item = 5724;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_controle_de_estoque_novo.md' WHERE id_item = 9896;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_controle_de_estoque.md' WHERE id_item = 4805;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_controle_estoque_movimentacoes.md' WHERE id_item = 228615;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_controle_validade.md' WHERE id_item = 8068;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_devolucao_material_almoxarifado.md' WHERE id_item = 8031;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_documentos_reemissao_solicitacao.md' WHERE id_item = 8239;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_documentos_reimpressao_anulacao_requisicao.md' WHERE id_item = 8279;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_documentos_reimpressao_anulacao_solicitacao.md' WHERE id_item = 8280;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_documentos_requisica_de_saida_materiais.md' WHERE id_item = 4306;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_documentos_termo_transferencia.md' WHERE id_item = 4605;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_entrada_das_ordens_de_compra.md' WHERE id_item = 5212;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_entrada_materiais_por_departamento.md' WHERE id_item = 5334;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_estoque_ponto_de_pedido.md' WHERE id_item = 289578;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_fechamento_do_estoque.md' WHERE id_item = 9869;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_ficha_financeira.md' WHERE id_item = 9952;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_grupo_subgrupos.md' WHERE id_item = 8788;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_legais_modeloxx.md' WHERE id_item = 9347;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_legais_modeloxxi.md' WHERE id_item = 9991;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_movimentacao_de_estoque_regra_antiga.md' WHERE id_item = 228533;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_movimentacao_de_estoque.md' WHERE id_item = 9552;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_movimentacao_distribuicao.md' WHERE id_item = 8614;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_notas.md' WHERE id_item = 4012;
            UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_saida_material_por_departamento.md' WHERE id_item = 8692;
UPDATE db_itensmenu SET help = 'https://e-cidade.wiki.br/patrimonial/material/#!relatorios_transferencias_em_aberto.md' WHERE id_item = 9643;
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
            UPDATE db_itensmenu SET help = 'Controle de Estoque' WHERE id_item = 228615;
            UPDATE db_itensmenu SET help = 'Consulta de ordem de compra' WHERE id_item = 9897;
            UPDATE db_itensmenu SET help = 'Modelo XXI - Bens em Almoxarifado' WHERE id_item = 9991;
            UPDATE db_itensmenu SET help = 'Ficha financeira' WHERE id_item = 9952;
            UPDATE db_itensmenu SET help = 'Relatório do processamento do fechamento do estoque.' WHERE id_item = 9869;
            UPDATE db_itensmenu SET help = 'Entrada de Materiais por Departamentos' WHERE id_item = 5334;
            UPDATE db_itensmenu SET help = 'Processa o fechamento do estoque' WHERE id_item = 9870;
            UPDATE db_itensmenu SET help = 'Controle de Estoque' WHERE id_item = 9896;
            UPDATE db_itensmenu SET help = '""' WHERE id_item = 8019;
            UPDATE db_itensmenu SET help = 'Alteração de Dados de Notas Fiscais' WHERE id_item = 7775;
            UPDATE db_itensmenu SET help = 'Cadastro de Unidades' WHERE id_item = 4344;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 8480;
            UPDATE db_itensmenu SET help = 'Parâmetros globais do módulo material' WHERE id_item = 7580;
            UPDATE db_itensmenu SET help = 'Inclusão da entrada da ordem de compra' WHERE id_item = 3992;
            UPDATE db_itensmenu SET help = 'Controle de parametros  por instituição' WHERE id_item = 7820;
            UPDATE db_itensmenu SET help = 'Cadastro de Materiais' WHERE id_item = 4345;
            UPDATE db_itensmenu SET help = 'Processar Arquivo' WHERE id_item = 7824;
            UPDATE db_itensmenu SET help = 'Cancelar Arquivo' WHERE id_item = 7825;
            UPDATE db_itensmenu SET help = 'Ferar Arquivo para o PIT' WHERE id_item = 7823;
            UPDATE db_itensmenu SET help = 'Grupo / SubGrupo' WHERE id_item = 9345;
            UPDATE db_itensmenu SET help = '""' WHERE id_item = 7830;
            UPDATE db_itensmenu SET help = 'Controle de Validade' WHERE id_item = 8068;
            UPDATE db_itensmenu SET help = 'Controle de Validade' WHERE id_item = 8069;
            UPDATE db_itensmenu SET help = 'Consulta arquivos do pit' WHERE id_item = 7831;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 8478;
            UPDATE db_itensmenu SET help = 'Consulta' WHERE id_item = 7581;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 8786;
            UPDATE db_itensmenu SET help = 'Grupo/Sub Grupo' WHERE id_item = 8788;
            UPDATE db_itensmenu SET help = 'Anula a entrada de notas.' WHERE id_item = 587073;
            UPDATE db_itensmenu SET help = 'Atendimento da Transferência' WHERE id_item = 7996;
            UPDATE db_itensmenu SET help = 'Reimpressão Anulação da Requisição' WHERE id_item = 8279;
            UPDATE db_itensmenu SET help = 'Reimpressão Anulação da Solicitação' WHERE id_item = 8280;
            UPDATE db_itensmenu SET help = 'Entrada das ordens de compra' WHERE id_item = 5212;
            UPDATE db_itensmenu SET help = 'Inclusão de Matmater' WHERE id_item = 3982;
            UPDATE db_itensmenu SET help = 'Alteração de Matmater' WHERE id_item = 3983;
            UPDATE db_itensmenu SET help = 'Exclusão de Matmater' WHERE id_item = 3984;
            UPDATE db_itensmenu SET help = 'Requisição Automática' WHERE id_item = 4606;
            UPDATE db_itensmenu SET help = 'Termo de Transferência' WHERE id_item = 4605;
            UPDATE db_itensmenu SET help = 'Materiais e Origem do Compras' WHERE id_item = 4499;
            UPDATE db_itensmenu SET help = 'Materiasi Interligados' WHERE id_item = 4526;
            UPDATE db_itensmenu SET help = 'Requisição de saída de materiais' WHERE id_item = 4306;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 4303;
            UPDATE db_itensmenu SET help = 'Cancelar' WHERE id_item = 4304;
            UPDATE db_itensmenu SET help = 'Cancelar' WHERE id_item = 4311;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 4310;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 4309;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 4313;
            UPDATE db_itensmenu SET help = 'Tipo de Lançamentos' WHERE id_item = 4366;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 4319;
            UPDATE db_itensmenu SET help = 'Cancelar' WHERE id_item = 4315;
            UPDATE db_itensmenu SET help = 'Confirmar' WHERE id_item = 4333;
            UPDATE db_itensmenu SET help = 'Cancelar' WHERE id_item = 4317;
            UPDATE db_itensmenu SET help = 'Inclusão de Requisição ' WHERE id_item = 4297;
            UPDATE db_itensmenu SET help = 'Alteração da Requisição' WHERE id_item = 4298;
            UPDATE db_itensmenu SET help = 'Atendimento de Requisição' WHERE id_item = 4299;
            UPDATE db_itensmenu SET help = 'Inclusão da implantação de estoque' WHERE id_item = 4302;
            UPDATE db_itensmenu SET help = 'Cabecalho 2' WHERE id_item = 4175;
            UPDATE db_itensmenu SET help = 'Cabecalho 1' WHERE id_item = 4174;
            UPDATE db_itensmenu SET help = 'Alteração de Matunid' WHERE id_item = 3979;
            UPDATE db_itensmenu SET help = 'Exclusão de Matunid' WHERE id_item = 3980;
            UPDATE db_itensmenu SET help = 'Inclusão de Matunid' WHERE id_item = 3978;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 7151;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 7152;
            UPDATE db_itensmenu SET help = 'Exclusão' WHERE id_item = 7153;
            UPDATE db_itensmenu SET help = 'Exclusão de Matfabricante' WHERE id_item = 6832;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 4318;
            UPDATE db_itensmenu SET help = 'Relatório de Estoque de Ponto de Pedido' WHERE id_item = 289578;
            UPDATE db_itensmenu SET help = 'Devolução de Materiais' WHERE id_item = 4320;
            UPDATE db_itensmenu SET help = 'Anulação do Pedido de Transferência' WHERE id_item = 8003;
            UPDATE db_itensmenu SET help = 'Reemissão de Solicitação de Transferência' WHERE id_item = 8239;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 8785;
            UPDATE db_itensmenu SET help = 'Tipo / Grupo' WHERE id_item = 9346;
            UPDATE db_itensmenu SET help = 'Anulações de Entrada de Ordem de Compra' WHERE id_item = 8024;
            UPDATE db_itensmenu SET help = 'Devolução de Material ao Almoxarifado' WHERE id_item = 8031;
            UPDATE db_itensmenu SET help = 'Consulta material (novo)' WHERE id_item = 9226;
            UPDATE db_itensmenu SET help = 'Exclusão' WHERE id_item = 8479;
            UPDATE db_itensmenu SET help = 'Relatório das transferências em aberto' WHERE id_item = 9643;
            UPDATE db_itensmenu SET help = 'Movimentação de Estoque' WHERE id_item = 9552;
            UPDATE db_itensmenu SET help = 'Alteração de Matfabricante' WHERE id_item = 6831;
            UPDATE db_itensmenu SET help = 'Inclusão de Matfabricante' WHERE id_item = 6830;
            UPDATE db_itensmenu SET help = 'Distribuição' WHERE id_item = 8614;
            UPDATE db_itensmenu SET help = 'Saída de Material por departamento' WHERE id_item = 8692;
            UPDATE db_itensmenu SET help = 'Exclusão de Requisição' WHERE id_item = 4305;
            UPDATE db_itensmenu SET help = 'Estoque por Item (Novo)' WHERE id_item = 9228;
            UPDATE db_itensmenu SET help = 'Relatório das notas' WHERE id_item = 4012;
            UPDATE db_itensmenu SET help = 'Modelo XX - Bens em Almoxarifado' WHERE id_item = 9347;
            UPDATE db_itensmenu SET help = 'Importar' WHERE id_item = 10172;
            UPDATE db_itensmenu SET help = 'Inclusão' WHERE id_item = 10170;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 10171;
            UPDATE db_itensmenu SET help = 'Alteração' WHERE id_item = 4335;
            UPDATE db_itensmenu SET help = 'Movimentação de Estoque(Regra Antiga)' WHERE id_item = 228533;
            UPDATE db_itensmenu SET help = 'Relatório de Estoque por Item' WHERE id_item = 5724;
            UPDATE db_itensmenu SET help = 'Controle de Estoque (Desativado)' WHERE id_item = 4805;
SQL
        );
    }
}
