<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20612AdicaoHelpMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastro_de_divisoes.md' where id_item = 5153;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastro_de_divisoes.md' where id_item = 5154;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastro_de_divisoes.md' where id_item = 5155;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_apolices.md' where id_item = 3615;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_apolices.md' where id_item = 3616;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_apolices.md' where id_item = 3617;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bens_global.md' where id_item = 3840;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bens_global.md' where id_item = 5151;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bens_individual.md' where id_item = 3648;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bens_individual.md' where id_item = 3649;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_classificacao_dos_bens.md' where id_item = 3623;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_classificacao_dos_bens.md' where id_item = 3624;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_classificacao_dos_bens.md' where id_item = 3625;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_comissoes.md' where id_item = 3603;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_comissoes.md' where id_item = 3604;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_comissoes.md' where id_item = 3605;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_convenios.md' where id_item = 7833;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_convenios.md' where id_item = 7834;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_convenios.md' where id_item = 7835;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_dispensa_tombamento.md' where id_item = 9830;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_dispensa_tombamento.md' where id_item = 9831;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_marca.md' where id_item = 7330;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_marca.md' where id_item = 7331;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_marca.md' where id_item = 7332;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_medidas.md' where id_item = 7334;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_medidas.md' where id_item = 7335;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_medidas.md' where id_item = 7336;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_modelo.md' where id_item = 7326;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_modelo.md' where id_item = 7327;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_modelo.md' where id_item = 7328;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_seguradoras.md' where id_item = 3611;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_seguradoras.md' where id_item = 3612;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_seguradoras.md' where id_item = 3613;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_situacao_dos_bens.md' where id_item = 3619;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_situacao_dos_bens.md' where id_item = 3620;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_situacao_dos_bens.md' where id_item = 3621;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipo_de_aquisicao.md' where id_item = 9037;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipo_de_aquisicao.md' where id_item = 9038;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipo_de_aquisicao.md' where id_item = 9039;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_baixa.md' where id_item = 3595;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_baixa.md' where id_item = 3596;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_baixa.md' where id_item = 3597;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_guarda.md' where id_item = 5170;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_guarda.md' where id_item = 5171;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_guarda.md' where id_item = 5172;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_unifica_notas.md' where id_item = 10168;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!consultas_bens.md' where id_item = 9152;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!consultas_historico_dos_bens.md' where id_item = 3771;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_alterar_placa.md' where id_item = 5146;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_alterar_situacao.md' where id_item = 5240;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_alterar_situacao.md' where id_item = 5241;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_baixa_de_bens.md' where id_item = 9134;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_baixa_de_bens.md' where id_item = 9135;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_baixa_de_bens.md' where id_item = 9912;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_configuracao_de_etiquetas.md' where id_item = 8117;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_configuracao_de_etiquetas.md' where id_item = 8118;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_configuracao_de_etiquetas.md' where id_item = 8119;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_confirmacao_de_transferencia.md' where id_item = 3636;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_desprocessar_incorporacao_bens.md' where id_item = 10540;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_devolucao_termo_de_guarda.md' where id_item = 5177;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_emite_etiquetas.md' where id_item = 8136;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_exclusao_historico_placa.md' where id_item = 228569;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_implantacao_depreciacao.md' where id_item = 9096;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_incorporacao_bens.md' where id_item = 10536;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9446;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9447;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9448;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9459;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9460;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9465;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_inventario.md' where id_item = 9466;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_levantamento_patrimonial.md' where id_item = 10153;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_parametros.md' where id_item = 3944;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_parametros.md' where id_item = 5684;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_parametros.md' where id_item = 9122;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_processamento_depreciacao.md' where id_item = 9124;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_processamento_depreciacao.md' where id_item = 9125;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_processamento_depreciacao.md' where id_item = 9140;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_termo_de_guarda.md' where id_item = 5174;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_termo_de_guarda.md' where id_item = 5175;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_termo_de_guarda.md' where id_item = 5176;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_externa.md' where id_item = 3627;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_externa.md' where id_item = 3628;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_externa.md' where id_item = 3629;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_interna.md' where id_item = 3954;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_interna.md' where id_item = 3955;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_interna.md' where id_item = 3956;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_lote.md' where id_item = 7302;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_lote.md' where id_item = 7303;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!procedimentos_transferencia_de_bens_lote.md' where id_item = 7304;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_apolices.md' where id_item = 3826;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_baixa_de_bens.md' where id_item = 3783;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_bens_por_classificacao.md' where id_item = 5203;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_bens_por_departamento_novo.md' where id_item = 9133;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_bens_por_departamento.md' where id_item = 3776;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_bens_sem_depreciacao.md' where id_item = 9159;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_cadastrais_tipos_de_baixa.md' where id_item = 3725;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_classificacao_dos_bens.md' where id_item = 3731;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_depreciacoes_processadas.md' where id_item = 9127;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_depreciacoes.md' where id_item = 9161;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_divisoes_por_departamento.md' where id_item = 5271;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_emissao_termo_de_guarda.md' where id_item = 5200;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_etiquetas_emitidas.md' where id_item = 8120;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_ficha_de_bens.md' where id_item = 3779;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_financeiro_patrimonial.md' where id_item = 587071;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_geral_de_bens.md' where id_item = 5571;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_historico_dos_bens.md' where id_item = 3782;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_inventarios.md' where id_item = 9471;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_levantamento_patrimonial.md' where id_item = 10154;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_manutencao_de_inventario.md' where id_item = 9468;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_modeloxi.md' where id_item = 9341;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_modeloxii.md' where id_item = 9342;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_modeloxvii.md' where id_item = 10047;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_reemite_ficha_de_transferencia.md' where id_item = 3739;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_reimpressao_devolucao_termo_de_guarda.md' where id_item = 9507;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_reimpressao_termo_de_guarda.md' where id_item = 9506;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_seguradoras.md' where id_item = 3729;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_termo_de_responsabilidade.md' where id_item = 4395;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!relatorios_transferencias_bens_em_aberto.md' where id_item = 228599;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_depreciacao.md' where id_item = 9041;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_depreciacao.md' where id_item = 9042;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_tipos_de_depreciacao.md' where id_item = 9043;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!consultas_bens_baixados.md' where id_item = 3785;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bem_tipos.md' where id_item = 8591;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bem_tipos.md' where id_item = 8592;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/patrimonio/#!cadastros_bem_tipos.md' where id_item = 8593;

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
update db_itensmenu set help = 'Inclusão de Bensmotbaixa' where id_item = 3595;
update db_itensmenu set help = 'Alteração de Bensmotbaixa' where id_item = 3596;
update db_itensmenu set help = 'Exclusão de Bensmotbaixa' where id_item = 3597;
update db_itensmenu set help = 'Inclusão da Comissão de Bens' where id_item = 3603;
update db_itensmenu set help = 'Alteração da Comissão de Bens' where id_item = 3604;
update db_itensmenu set help = 'Exclusão da Comissão de Bens' where id_item = 3605;
update db_itensmenu set help = 'Inclusão de Seguradoras' where id_item = 3611;
update db_itensmenu set help = 'Alteração de Seguradoras' where id_item = 3612;
update db_itensmenu set help = 'Exclusão de Seguradoras' where id_item = 3613;
update db_itensmenu set help = 'Inclusão de Apolice' where id_item = 3615;
update db_itensmenu set help = 'Alteração de Apolice' where id_item = 3616;
update db_itensmenu set help = 'Exclusão de Apolice' where id_item = 3617;
update db_itensmenu set help = 'Inclusão de Situabens' where id_item = 3619;
update db_itensmenu set help = 'Alteração de Situabens' where id_item = 3620;
update db_itensmenu set help = 'Exclusão de Situabens' where id_item = 3621;
update db_itensmenu set help = 'Inclusão de Clabens' where id_item = 3623;
update db_itensmenu set help = 'Alteração de Clabens' where id_item = 3624;
update db_itensmenu set help = 'Exclusão de Clabens' where id_item = 3625;
update db_itensmenu set help = 'Inclusão de Benstransf' where id_item = 3627;
update db_itensmenu set help = 'Alteração de Benstransf' where id_item = 3628;
update db_itensmenu set help = 'Exclusão de Benstransf' where id_item = 3629;
update db_itensmenu set help = 'Confirmação de transferência' where id_item = 3636;
update db_itensmenu set help = 'Inclusão de Bens' where id_item = 3648;
update db_itensmenu set help = 'Alteração de Bens' where id_item = 3649;
update db_itensmenu set help = 'Relatório dos tipos de baixa' where id_item = 3725;
update db_itensmenu set help = 'Relatório das seguradoras' where id_item = 3729;
update db_itensmenu set help = 'Relatório de classificação dos bens' where id_item = 3731;
update db_itensmenu set help = 'Reemite ficha de tranferência' where id_item = 3739;
update db_itensmenu set help = 'Consulta Histórico dos bens' where id_item = 3771;
update db_itensmenu set help = 'Bens por departamento' where id_item = 3776;
update db_itensmenu set help = 'Relatório de bens' where id_item = 3779;
update db_itensmenu set help = 'Relatório do histórico do bem' where id_item = 3782;
update db_itensmenu set help = 'Relatório da baixa de bens' where id_item = 3783;
update db_itensmenu set help = 'Relatório de apólices' where id_item = 3826;
update db_itensmenu set help = 'Inclusão global' where id_item = 3840;
update db_itensmenu set help = 'Parâmetros Globais' where id_item = 3944;
update db_itensmenu set help = 'Inclusão' where id_item = 3954;
update db_itensmenu set help = 'Alteração' where id_item = 3955;
update db_itensmenu set help = 'Exclusão' where id_item = 3956;
update db_itensmenu set help = 'Termo de responsabilidade' where id_item = 4395;
update db_itensmenu set help = 'Alterar placa do bem' where id_item = 5146;
update db_itensmenu set help = 'Alteração Global de Bens' where id_item = 5151;
update db_itensmenu set help = 'Inclusão de Departdiv' where id_item = 5153;
update db_itensmenu set help = 'Alteração de Departdiv' where id_item = 5154;
update db_itensmenu set help = 'Exclusão de Departdiv' where id_item = 5155;
update db_itensmenu set help = 'Inclusão de Benstipoguarda' where id_item = 5170;
update db_itensmenu set help = 'Alteração de Benstipoguarda' where id_item = 5171;
update db_itensmenu set help = 'Exclusão de Benstipoguarda' where id_item = 5172;
update db_itensmenu set help = 'Inclusão de Bensguarda' where id_item = 5174;
update db_itensmenu set help = 'Alteração de Bensguarda' where id_item = 5175;
update db_itensmenu set help = 'Exclusão de Bensguarda' where id_item = 5176;
update db_itensmenu set help = 'Devolução de Guarda' where id_item = 5177;
update db_itensmenu set help = 'Emissão Termo de Guarda' where id_item = 5200;
update db_itensmenu set help = 'Relatório de bens por classificação' where id_item = 5203;
update db_itensmenu set help = 'Alterar situação do bem individual' where id_item = 5240;
update db_itensmenu set help = 'Alterar situação do bem por lote(global).' where id_item = 5241;
update db_itensmenu set help = 'Divisões por Departamento' where id_item = 5271;
update db_itensmenu set help = 'Relatório Geral de bens' where id_item = 5571;
update db_itensmenu set help = 'Parâmetros de placa' where id_item = 5684;
update db_itensmenu set help = 'Inclusão' where id_item = 7302;
update db_itensmenu set help = 'Alteração' where id_item = 7303;
update db_itensmenu set help = 'Cancela Transferência' where id_item = 7304;
update db_itensmenu set help = 'Inclusão de Bensmodelo' where id_item = 7326;
update db_itensmenu set help = 'Alteração de Bensmodelo' where id_item = 7327;
update db_itensmenu set help = 'Exclusão de Bensmodelo' where id_item = 7328;
update db_itensmenu set help = 'Inclusão de Bensmarca' where id_item = 7330;
update db_itensmenu set help = 'Alteração de Bensmarca' where id_item = 7331;
update db_itensmenu set help = 'Exclusão de Bensmarca' where id_item = 7332;
update db_itensmenu set help = 'Inclusão de Bensmedida' where id_item = 7334;
update db_itensmenu set help = 'Alteração de Bensmedida' where id_item = 7335;
update db_itensmenu set help = 'Exclusão de Bensmedida' where id_item = 7336;
update db_itensmenu set help = 'Inclusão de Benscadcedente' where id_item = 7833;
update db_itensmenu set help = 'Alteração de Benscadcedente' where id_item = 7834;
update db_itensmenu set help = 'Exclusão de Benscadcedente' where id_item = 7835;
update db_itensmenu set help = 'Incluir modelo de etiqueta do bem' where id_item = 8117;
update db_itensmenu set help = 'Alterar os  parâmetros da etiqueta do bem' where id_item = 8118;
update db_itensmenu set help = 'Exclui modelo de etiqueta do bem' where id_item = 8119;
update db_itensmenu set help = 'Relatorio de etiquetas emitidas' where id_item = 8120;
update db_itensmenu set help = 'Emiti etiquetas patrimonio' where id_item = 8136;
update db_itensmenu set help = 'Inclusão de Benstipoaquisicao' where id_item = 9037;
update db_itensmenu set help = 'Alteração de Benstipoaquisicao' where id_item = 9038;
update db_itensmenu set help = 'Exclusão de Benstipoaquisicao' where id_item = 9039;
update db_itensmenu set help = 'Implantação Depreciação' where id_item = 9096;
update db_itensmenu set help = 'Parâmetros da Instituição' where id_item = 9122;
update db_itensmenu set help = 'Processamento Automático da Depreciação' where id_item = 9124;
update db_itensmenu set help = 'Processamento Manual da Depreciação' where id_item = 9125;
update db_itensmenu set help = 'Relatório de Depreciação Processada' where id_item = 9127;
update db_itensmenu set help = 'Novo relatório de bens por departamento' where id_item = 9133;
update db_itensmenu set help = 'inclusão da baixa de bens individual' where id_item = 9134;
update db_itensmenu set help = 'Cancelamento da Baixa' where id_item = 9135;
update db_itensmenu set help = 'Realiza o desprocessamento da depreciaçao' where id_item = 9140;
update db_itensmenu set help = 'Nova consulta de bens' where id_item = 9152;
update db_itensmenu set help = '' where id_item = 9159;
update db_itensmenu set help = 'Depreciações do Bem' where id_item = 9161;
update db_itensmenu set help = 'Modelo XI - Bens Patrimoniais - Arrolamento das Existências' where id_item = 9341;
update db_itensmenu set help = 'Modelo XII - Demonstrativo da Movimentação' where id_item = 9342;
update db_itensmenu set help = 'Abertura de um inventário' where id_item = 9446;
update db_itensmenu set help = 'Inclusão de abertura de inventário' where id_item = 9447;
update db_itensmenu set help = 'Anulação de um inventário' where id_item = 9448;
update db_itensmenu set help = 'Procedimento Manutenção inventário' where id_item = 9459;
update db_itensmenu set help = 'Desvincular bem' where id_item = 9460;
update db_itensmenu set help = 'Processamento do inventário' where id_item = 9465;
update db_itensmenu set help = 'Desprocessar Inventário' where id_item = 9466;
update db_itensmenu set help = 'Relatório Manutenção de Inventário' where id_item = 9468;
update db_itensmenu set help = 'Relatório de Inventário' where id_item = 9471;
update db_itensmenu set help = 'Termo de Guarda' where id_item = 9506;
update db_itensmenu set help = 'Reimpressão da Devolução Termo de Guarda' where id_item = 9507;
update db_itensmenu set help = 'Inclusão de dispensa de tombamento' where id_item = 9830;
update db_itensmenu set help = 'Estorno de baixa de tombamento' where id_item = 9831;
update db_itensmenu set help = 'Inclusão da baixa de bens por lote' where id_item = 9912;
update db_itensmenu set help = 'Modelo XVII - Termo de Baixa Definitiva' where id_item = 10047;
update db_itensmenu set help = 'Levantamento Patrimonial' where id_item = 10153;
update db_itensmenu set help = 'Levantamento Patrimonial' where id_item = 10154;
update db_itensmenu set help = 'Unificar / Desmembrar Notas Pendentes' where id_item = 10168;
update db_itensmenu set help = 'Incorporação de Bens' where id_item = 10536;
update db_itensmenu set help = 'Desprocessar Incorporação de Bens' where id_item = 10540;
update db_itensmenu set help = 'Exclui histórico de placa ' where id_item = 228569;
update db_itensmenu set help = 'Relatório Transferência de bens em aberto' where id_item = 228599;
update db_itensmenu set help = 'Financeiro Patrimonial' where id_item = 587071;
update db_itensmenu set help = 'Inclusão de Benstipodepreciacao' where id_item = 9041;
update db_itensmenu set help = 'Alteração de Benstipodepreciacao' where id_item = 9042;
update db_itensmenu set help = 'Exclusão de Benstipodepreciacao' where id_item = 9043;
update db_itensmenu set help = 'Consulta de bens baixados' where id_item = 3785;
update db_itensmenu set help = 'Inclusão de Bemtipos' where id_item = 8591;
update db_itensmenu set help = 'Alteração de Bemtipos' where id_item = 8592;
update db_itensmenu set help = 'Exclusão de Bemtipos' where id_item = 8593;

SQL
        );
    }
}
