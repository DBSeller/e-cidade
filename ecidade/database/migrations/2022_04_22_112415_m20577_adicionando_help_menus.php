<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20577AdicionandoHelpMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_autorizacao_de_empenho.md' where id_item =  2568;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_autorizacao_de_empenho.md' where id_item =  2569;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_solicitacao_de_compras.md' where id_item =  3486;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_solicitacao_de_compras.md' where id_item =  3487;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_solicitacao_de_compras.md' where id_item =  3488;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_subgrupos_material_servico.md' where id_item =  3547;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_subgrupos_material_servico.md' where id_item =  3548;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_subgrupos_material_servico.md' where id_item =  3549;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_grupos_de_material_servico.md' where id_item =  3555;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_grupos_de_material_servico.md' where id_item =  3556;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_grupos_de_material_servico.md' where id_item =  3557;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_material_servicos.md' where id_item =  3567;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_material_servicos.md' where id_item =  3568;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_material_servicos.md' where id_item =  3569;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_emite_autorizacao_de_empenho.md' where id_item =  3670;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_grupos_materiais_servicos.md' where id_item =  3735;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_subgrupos_materiais_servicos.md' where id_item =  3736;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_tipos_de_compra.md' where id_item =  3738;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_materiais_servicos.md' where id_item =  3741;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_ordem_de_compra.md' where id_item =  3917;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_ordem_de_compra.md' where id_item =  3918;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_emite_ordem_de_compra.md' where id_item =  3940;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_fornecedores.md' where id_item =  3963;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_fornecedores.md' where id_item =  3964;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_fornecedores.md' where id_item =  3965;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_unidades.md' where id_item =  3978;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_unidades.md' where id_item =  3979;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_unidades.md' where id_item =  3980;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_ordem_de_compra.md' where id_item =  3991;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_reemissao_solicitacao_de_compras.md' where id_item =  3998;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_solicitacao_de_compras.md' where id_item =  4028;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_de_solicitacao.md' where id_item =  4032;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_de_solicitacao.md' where id_item =  4033;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_de_solicitacao.md' where id_item =  4034;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_reemissao_orcamento_da_solicitacao.md' where id_item =  4045;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_de_solicitacao.md' where id_item =  4048;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_liberar_solicitacao.md' where id_item =  4097;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_processo_de_compras.md' where id_item =  4103;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_processo_de_compras.md' where id_item =  4104;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_processo_de_compras.md' where id_item =  4105;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_processo_de_compras.md' where id_item =  4108;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_de_solicitacao.md' where id_item =  4111;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_orcamento_processo_de_compras.md' where id_item =  4112;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_autorizacao_de_empenho.md' where id_item =  4122;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_reemissao_orcamento_de_pc.md' where id_item =  4144;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_configuracao_texto_ordem_de_compra.md' where id_item =  4174;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_configuracao_texto_ordem_de_compra.md' where id_item =  4175;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_solicitacoes_em_processo.md' where id_item =  4188;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_processo_de_compras.md' where id_item =  4200;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_processo_de_compras.md' where id_item =  4201;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_ordem_de_compra.md' where id_item =  4207;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_tipos_de_certificados.md' where id_item =  4709;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_tipos_de_certificados.md' where id_item =  4710;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_tipos_de_certificados.md' where id_item =  4711;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_documentos.md' where id_item =  4713;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_documentos.md' where id_item =  4714;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_documentos.md' where id_item =  4715;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!cadastro_documentos_tipo_certificado.md' where id_item =  4781;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_reemissao_de_certificado.md' where id_item =  4782;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_valores_a_suplementar.md' where id_item =  4801;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_gera_certificado.md' where id_item =  4835;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_gera_certificado.md' where id_item =  4836;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_motivos_de_troca.md' where id_item =  4953;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_fornecedor.md' where id_item =  5001;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_processo_de_compras.md' where id_item =  5025;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_item.md' where id_item =  5314;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_certificado_de_fornecedores.md' where id_item =  5732;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  7944;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  7945;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  7950;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  7951;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_registro_de_preco.md' where id_item =  7954;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_registro_de_preco.md' where id_item =  7955;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  7963;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  7964;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_processamento_registro_de_preco.md' where id_item =  7968;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_processamento_registro_de_preco.md' where id_item =  7969;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_registro_de_preco.md' where id_item =  7984;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_registro_de_preco.md' where id_item =  7997;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_configuracao_desdobramento_oc.md' where id_item =  8018;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_libera_fornecedor.md' where id_item =  8094;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_libera_fornecedor.md' where id_item =  8095;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_libera_fornecedor.md' where id_item =  8096;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_cotacoes_de_preco.md' where id_item =  8126;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  8558;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  8559;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_quantidade.md' where id_item =  8560;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_abertura_registro_de_preco.md' where id_item =  8561;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_parametros_registro_de_preco.md' where id_item =  8668;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_libera_fornecedor.md' where id_item =  8714;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_reemite_notificacao_bloqueio_fornecedor.md' where id_item =  8715;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_cedencia_registro_de_preco.md' where id_item =  8955;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_manutencao_reserva_de_saldo.md' where id_item =  9030;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_manutencao_reserva_de_saldo.md' where id_item =  9031;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_autorizacao_de_empenho.md' where id_item =  9087;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_solicitacao_de_compras.md' where id_item =  9093;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_processo_de_compras.md' where id_item =  9143;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_reemissao_processo_de_compras.md' where id_item =  9147;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_processo_de_compras.md' where id_item =  9151;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_solicitacao_de_compras.md' where id_item =  9158;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_solicitacoes_liberadas.md' where id_item =  9160;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_pendencia_de_solicitacao.md' where id_item =  9229;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_processo_de_compras_autorizadas.md' where id_item =  9230;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_emissao_aut_processo_de_compras.md' where id_item =  9235;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_emite_nota_de_bloqueio.md' where id_item =  9358;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_posicao_ordem_de_compra.md' where id_item =  9381;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_gera_lista_de_itens_txt.md' where id_item =  9660;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_vigencia_registro_de_preco.md' where id_item =  9682;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_registro_de_preco.md' where id_item =  9731;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!consulta_ordens_de_compra.md' where id_item =  9897;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_mapa_das_propostas_do_orcamento.md' where id_item =  9984;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_mapa_das_propostas_do_orcamento.md' where id_item =  9985;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!relatorios_registro_de_preco.md' where id_item =  9992;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10009;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10010;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10011;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10012;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10013;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10014;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10015;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_registro_de_preco_por_valor.md' where id_item =  10016;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_solicitacao_de_compras.md' where id_item =  10023;
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
        update db_itensmenu set help = 'Inclusão de autorização de empenho' where id_item = 2568;
        update db_itensmenu set help = 'Alteração de autorização de empenho' where id_item = 2569;
        update db_itensmenu set help = 'Inclusão de Solicita' where id_item = 3486;
        update db_itensmenu set help = 'Alteração de Solicita' where id_item = 3487;
        update db_itensmenu set help = 'Inclusão de Pcsubgrupo' where id_item = 3547;
        update db_itensmenu set help = 'Alteração de Pcsubgrupo' where id_item = 3548;
        update db_itensmenu set help = 'Consulta de ordem de compra' where id_item = 9897;
        update db_itensmenu set help = 'Configuração de Desdobramentos p/OC' where id_item = 8018;
        update db_itensmenu set help = 'Emite Abertura' where id_item = 7955;
        update db_itensmenu set help = 'Alteração de Liberafornecedor' where id_item = 8095;
        update db_itensmenu set help = 'compilação do registro de preço controlado por valor' where id_item = 0009;
        update db_itensmenu set help = 'Anulação da Compilação' where id_item = 0010;
        update db_itensmenu set help = 'Abertura de um registor de preço por valor' where id_item = 0011;
        update db_itensmenu set help = 'Altera a abertura de um registro de preço' where id_item = 0012;
        update db_itensmenu set help = 'Anula abertura de um registro de preço' where id_item = 0013;
        update db_itensmenu set help = 'Alteração da Compilação' where id_item = 7964;
        update db_itensmenu set help = 'Inclusão de Registro de Preço por Valor' where id_item = 0014;
        update db_itensmenu set help = 'Alteração de Registro de Preço por Valor' where id_item = 0015;
        update db_itensmenu set help = 'Anulação de Estimativa de Registro de Preço por Valor' where id_item = 0016;
        update db_itensmenu set help = 'Documentos por Tipo de Certificado' where id_item = 4781;
        update db_itensmenu set help = 'Incluir Abertura' where id_item = 7944;
        update db_itensmenu set help = 'Cancelamento de Liberação de Fornecedor' where id_item = 8096;
        update db_itensmenu set help = 'Imprimir o mapa de proposta do orçamento por item' where id_item = 9984;
        update db_itensmenu set help = 'Imprimir o mapa de proposta do orçamento por lote' where id_item = 9985;
        update db_itensmenu set help = 'Relatório de atas vigentes' where id_item = 9992;
        update db_itensmenu set help = 'Emite Estimativa' where id_item = 7954;
        update db_itensmenu set help = 'Emite Compilação' where id_item = 7984;
        update db_itensmenu set help = 'Alterar Abertura' where id_item = 7945;
        update db_itensmenu set help = '' where id_item = 7950;
        update db_itensmenu set help = 'Alterar Estimativa' where id_item = 7951;
        update db_itensmenu set help = 'Inclusão de Liberafornecedor' where id_item = 8094;
        update db_itensmenu set help = 'Processamento da Compilação' where id_item = 7968;
        update db_itensmenu set help = 'Cancela o processamento da Compilacao' where id_item = 7969;
        update db_itensmenu set help = 'Cotações de Preços' where id_item = 8126;
        update db_itensmenu set help = 'Inclusão da Compilação' where id_item = 7963;
        update db_itensmenu set help = 'Posição do Registro de Preço' where id_item = 7997;
        update db_itensmenu set help = 'Anula a solicitação de compras' where id_item = 0023;
        update db_itensmenu set help = 'Anulação de Ordem de Compra' where id_item = 3918;
        update db_itensmenu set help = 'Reemissão do Certificado' where id_item = 4782;
        update db_itensmenu set help = 'Anula Registro de Preço' where id_item = 8558;
        update db_itensmenu set help = 'Anula Estimativa Registro de Preço' where id_item = 8559;
        update db_itensmenu set help = 'Anula Compilação Registro de Preço' where id_item = 8560;
        update db_itensmenu set help = 'Emissão de Notas de Bloqueio' where id_item = 9358;
        update db_itensmenu set help = 'Cedência de material entre departamentos.' where id_item = 8955;
        update db_itensmenu set help = 'Consulta Item' where id_item = 5314;
        update db_itensmenu set help = 'Alteração do resumo do processo de compra' where id_item = 5025;
        update db_itensmenu set help = 'Consulta Fornecedor' where id_item = 5001;
        update db_itensmenu set help = 'Motivos de troca' where id_item = 4953;
        update db_itensmenu set help = 'Inclusão de Certificado para Fornecedor' where id_item = 4835;
        update db_itensmenu set help = 'Alteração de Certificado do Fornecedor' where id_item = 4836;
        update db_itensmenu set help = 'Valores a suplementar' where id_item = 4801;
        update db_itensmenu set help = 'Inclusão de Pctipocertif' where id_item = 4709;
        update db_itensmenu set help = 'Alteração de Pctipocertif' where id_item = 4710;
        update db_itensmenu set help = 'Exclusão de Pctipocertif' where id_item = 4711;
        update db_itensmenu set help = 'Inclusão de Pcdoccertif' where id_item = 4713;
        update db_itensmenu set help = 'Alteração de Pcdoccertif' where id_item = 4714;
        update db_itensmenu set help = 'Exclusão de Pcdoccertif' where id_item = 4715;
        update db_itensmenu set help = 'Solicitações em processo' where id_item = 4188;
        update db_itensmenu set help = 'Cabecalho 2' where id_item = 4175;
        update db_itensmenu set help = 'Cabecalho 1' where id_item = 4174;
        update db_itensmenu set help = 'inclusão de ordens de compra' where id_item = 3917;
        update db_itensmenu set help = 'Exclusão' where id_item = 4201;
        update db_itensmenu set help = 'Lançar valores' where id_item = 4048;
        update db_itensmenu set help = 'Lançar valores' where id_item = 4108;
        update db_itensmenu set help = 'Anular autorização' where id_item = 4122;
        update db_itensmenu set help = 'Julgar orçamento' where id_item = 4112;
        update db_itensmenu set help = 'Reemissão de orçamento de PC' where id_item = 4144;
        update db_itensmenu set help = 'Inclusão' where id_item = 4032;
        update db_itensmenu set help = 'Alteração' where id_item = 4033;
        update db_itensmenu set help = 'Exclusão' where id_item = 4034;
        update db_itensmenu set help = 'Reemissão do orçamento da solicitação' where id_item = 4045;
        update db_itensmenu set help = 'Solicitação de compras' where id_item = 4028;
        update db_itensmenu set help = 'Parâmetros' where id_item = 8668;
        update db_itensmenu set help = 'Julgar orçamento' where id_item = 4111;
        update db_itensmenu set help = 'Liberar solicitação' where id_item = 4097;
        update db_itensmenu set help = 'Alteração de Matunid' where id_item = 3979;
        update db_itensmenu set help = 'Exclusão de Matunid' where id_item = 3980;
        update db_itensmenu set help = 'Alteração da  ordem de compra' where id_item = 3991;
        update db_itensmenu set help = 'Ordem de Compra' where id_item = 3940;
        update db_itensmenu set help = 'Inclusão de Pcforne' where id_item = 3963;
        update db_itensmenu set help = 'Alteração de Pcforne' where id_item = 3964;
        update db_itensmenu set help = 'Exclusão de Pcforne' where id_item = 3965;
        update db_itensmenu set help = 'Inclusão de Matunid' where id_item = 3978;
        update db_itensmenu set help = 'Tipos de compra' where id_item = 3738;
        update db_itensmenu set help = 'Grupos de materiais/serviços' where id_item = 3735;
        update db_itensmenu set help = 'Subgrupos de materiais/serviços' where id_item = 3736;
        update db_itensmenu set help = 'Emite autorização de empenho' where id_item = 3670;
        update db_itensmenu set help = 'Exclusão de Pcsubgrupo' where id_item = 3549;
        update db_itensmenu set help = 'Inclusão de Pctipo' where id_item = 3555;
        update db_itensmenu set help = 'Alteração de Pctipo' where id_item = 3556;
        update db_itensmenu set help = 'Exclusão de Pctipo' where id_item = 3557;
        update db_itensmenu set help = 'Alteração de Pcmater' where id_item = 3568;
        update db_itensmenu set help = 'Exclusão de Pcmater' where id_item = 3569;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/compras/#!procedimentos_solicitacao_de_compras.md' where id_item = 3488;
        update db_itensmenu set help = 'Materiais/serviços' where id_item = 3741;
        update db_itensmenu set help = 'Alteração' where id_item = 4104;
        update db_itensmenu set help = 'Inclusão' where id_item = 4103;
        update db_itensmenu set help = 'Exclusão' where id_item = 4105;
        update db_itensmenu set help = 'Gerar Lista de Itens em TXT' where id_item = 9660;
        update db_itensmenu set help = 'Manutenção de reserva de saldo' where id_item = 9030;
        update db_itensmenu set help = 'Manutenção de reserva de saldo > Autorização' where id_item = 9031;
        update db_itensmenu set help = 'Alterar dotações de Solicitação de Compras' where id_item = 9093;
        update db_itensmenu set help = 'LIberação do Processo de Compras' where id_item = 9143;
        update db_itensmenu set help = 'Reemite Notificação Bloqueio Fornecedor' where id_item = 8715;
        update db_itensmenu set help = 'Relatório de solicitações liberadas' where id_item = 9160;
        update db_itensmenu set help = 'Processo de Compras Autorizadas' where id_item = 9230;
        update db_itensmenu set help = '' where id_item = 9682;
        update db_itensmenu set help = 'Posição Ordem de Compra' where id_item = 9381;
        update db_itensmenu set help = 'Inclusão' where id_item = 4200;
        update db_itensmenu set help = 'Inclusão de Pcmater' where id_item = 3567;
        update db_itensmenu set help = 'Reemite Documento de Processo de Compras' where id_item = 9147;
        update db_itensmenu set help = 'Inclusão de varias ordens compra' where id_item = 4207;
        update db_itensmenu set help = 'Relatório de Certificado de Fornecedores' where id_item = 5732;
        update db_itensmenu set help = 'Notificação' where id_item = 8714;
        update db_itensmenu set help = 'Manutenção nos registros de pendência das solicitações de compras' where id_item = 9229;
        update db_itensmenu set help = 'Lista de estimativas.' where id_item = 9731;
        update db_itensmenu set help = 'Reemissão de solicitação de compras' where id_item = 3998;
        update db_itensmenu set help = 'Gera autorização de Empenho' where id_item = 9087;
        update db_itensmenu set help = 'Novo cadastro de solicitações' where id_item = 9158;
        update db_itensmenu set help = 'Consulta Abertura de registro de preço' where id_item = 8561;
        update db_itensmenu set help = 'Emissão Aut. do Processo de Compras' where id_item = 9235;
        update db_itensmenu set help = 'Consulta de Processo de Compras' where id_item = 9151;
SQL
        );
    }
}
