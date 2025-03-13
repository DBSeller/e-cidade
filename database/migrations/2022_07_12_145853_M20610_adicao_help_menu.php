<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20610AdicaoHelpMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_comissao.md' where id_item = 4797;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_comissao.md' where id_item = 4798;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_comissao.md' where id_item = 4799;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_fornecedores.md' where id_item = 3963;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_fornecedores.md' where id_item = 3964;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_fornecedores.md' where id_item = 3965;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_locais.md' where id_item = 4793;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_locais.md' where id_item = 4794;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_de_locais.md' where id_item = 4795;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_modalidades.md' where id_item = 4692;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_modalidades.md' where id_item = 4693;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_modalidades.md' where id_item = 4694;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_movimentacoes_de_registros.md' where id_item = 7959;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_movimentacoes_de_registros.md' where id_item = 7960;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_movimentacoes_de_registros.md' where id_item = 7961;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!cadastros_tipo_de_empresa.md' where id_item = 6950;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!consultas_cgm.md' where id_item = 9255;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!consultas_edital_download.md' where id_item = 5490;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!consultas_licitacao.md' where id_item = 9223;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_adjudicar.md' where id_item = 10207;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_anulacao_de_itens_cancelar.md' where id_item = 147887;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_anulacao_de_itens_cancelar.md' where id_item = 228030;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_ata.md' where id_item = 8132;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_ata.md' where id_item = 8133;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_ata.md' where id_item = 8134;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_autorizacao.md' where id_item = 4718;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_autorizacao.md' where id_item = 4719;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_cancelamento_julgamento.md' where id_item = 147886;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_configuracao_dos_editais.md' where id_item = 4689;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_credenciamentos_fornecedores.md' where id_item = 10406;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_deserta.md' where id_item = 7319;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_deserta.md' where id_item = 7320;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_deserta.md' where id_item = 9364;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_edital_web.md' where id_item = 5479;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_edital_web.md' where id_item = 5480;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_edital_web.md' where id_item = 5481;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_edital_web.md' where id_item = 5489;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_edital.md' where id_item = 8057;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_edital.md' where id_item = 8060;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_eventos.md' where id_item = 10209;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_exportacao_dados_licitacon.md' where id_item = 10213;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_exportacao_dados_licitacon.md' where id_item = 10214;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_exportacao_dados_licitacon.md' where id_item = 10246;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_exportacao_dados_licitacon.md' where id_item = 10462;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_fornecedores_da_licitacao.md' where id_item = 4685;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_fracassada.md' where id_item = 9361;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_fracassada.md' where id_item = 9362;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_fracassada.md' where id_item = 9363;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_habilitacao_de_fornecedores.md' where id_item = 10206;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_homologar.md' where id_item = 10208;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_lancar_propostas.md' where id_item = 4686;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_licitacao.md' where id_item = 4681;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_licitacao.md' where id_item = 4682;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_licitacao.md' where id_item = 4683;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_manutencao_licitacao.md' where id_item = 8984;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_minuta.md' where id_item = 8603;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_minuta.md' where id_item = 8604;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_minuta.md' where id_item = 8605;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_registro_de_preco.md' where id_item = 7987;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_registro_de_preco.md' where id_item = 7989;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_registro_de_preco.md' where id_item = 7990;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_registro_de_preco.md' where id_item = 7992;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_registro_de_preco.md' where id_item = 7993;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_reserva_de_cotas.md' where id_item = 10215;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_revoga.md' where id_item = 7339;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_revoga.md' where id_item = 7340;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_trocar_fornecedor.md' where id_item = 9401;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_adjudicacao_de_processo.md' where id_item = 4804;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_edital_download.md' where id_item = 5492;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_emite_autorizacao_empenho.md' where id_item = 3670;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_fornecedores_empatados_cotacoes_me_epp.md' where id_item = 6948;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_gera_lista_de_itens_txt.md' where id_item = 4779;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_historico_do_julgamento_da_licitacao.md' where id_item = 9232;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_homologacao_de_processo.md' where id_item = 4803;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_licitacao.md' where id_item = 4687;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_licitacoes_liberadas_web.md' where id_item = 5491;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_mapa_das_propostas.md' where id_item = 4688;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_resumido_da_licitacao.md' where id_item = 6876;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_situacoes_da_licitacao.md' where id_item = 289574;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!tutorial_portal_compras_publica.md' where id_item = 228554;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!tutorial_portal_compras_publica.md' where id_item = 228570;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!tutorial_portal_compras_publica.md' where id_item = 228571;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_parametros.md' where id_item = 6813;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!relatorios_itens_bloqueados.md' where id_item = 228595;
update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/licitacoes/#!procedimentos_anulacao.md' where id_item = 9853;

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
update db_itensmenu set help = 'Itens Bloqueados' where id_item = 228595;
update db_itensmenu set help = 'Integração' where id_item = 228570;
update db_itensmenu set help = 'Configuração' where id_item = 228571;
update db_itensmenu set help = 'Exclusão' where id_item = 7961;
update db_itensmenu set help = 'Cancelar Revogamento' where id_item = 7340;
update db_itensmenu set help = 'Inclusão' where id_item = 7959;
update db_itensmenu set help = 'Alteração' where id_item = 7960;
update db_itensmenu set help = 'Incluir Desistência' where id_item = 7989;
update db_itensmenu set help = 'Incluir Bloqueio' where id_item = 7992;
update db_itensmenu set help = 'Cancelar Bloqueio' where id_item = 7993;
update db_itensmenu set help = 'Configuração de Ata' where id_item = 8132;
update db_itensmenu set help = 'Geração de Editais' where id_item = 8057;
update db_itensmenu set help = 'Upload Atas' where id_item = 8133;
update db_itensmenu set help = 'Upload dos Editais' where id_item = 8060;
update db_itensmenu set help = 'Situações da Licitação' where id_item = 289574;
update db_itensmenu set help = 'Cancelar anulação de itens' where id_item = 228030;
update db_itensmenu set help = 'Cancela julgamento de licitação' where id_item = 147886;
update db_itensmenu set help = 'Anulação de itens de licitação' where id_item = 147887;
update db_itensmenu set help = 'revogar Licitacao' where id_item = 7339;
update db_itensmenu set help = 'Inclusão de Liclicitaweb' where id_item = 5479;
update db_itensmenu set help = 'Alteração de Liclicitaweb' where id_item = 5480;
update db_itensmenu set help = 'Exclusão de Liclicitaweb' where id_item = 5481;
update db_itensmenu set help = 'Exclusão de Liccomissao' where id_item = 4799;
update db_itensmenu set help = 'Inclusão de Liccomissao' where id_item = 4797;
update db_itensmenu set help = 'Alteração de Liccomissao' where id_item = 4798;
update db_itensmenu set help = 'Homologação de Processo' where id_item = 4803;
update db_itensmenu set help = 'Adjudicação de Processo' where id_item = 4804;
update db_itensmenu set help = 'Inclusão de Liclocal' where id_item = 4793;
update db_itensmenu set help = 'Alteração de Liclocal' where id_item = 4794;
update db_itensmenu set help = 'Exclusão de Liclocal' where id_item = 4795;
update db_itensmenu set help = 'Anula autorização de empenho gerada apartir de uma licitação' where id_item = 4719;
update db_itensmenu set help = 'Inclusão de Licitação' where id_item = 4681;
update db_itensmenu set help = 'Alteração da_Licitação' where id_item = 4682;
update db_itensmenu set help = 'Exclusão da Licitação' where id_item = 4683;
update db_itensmenu set help = 'Cadastro de Fornecedores da Licitação' where id_item = 4685;
update db_itensmenu set help = 'Relatório de Licitação' where id_item = 4687;
update db_itensmenu set help = 'Mapa das Propostas da Licitação' where id_item = 4688;
update db_itensmenu set help = 'Inclusão de Cflicita' where id_item = 4692;
update db_itensmenu set help = 'Alteração de Cflicita' where id_item = 4693;
update db_itensmenu set help = 'Exclusão de Cflicita' where id_item = 4694;
update db_itensmenu set help = 'Gera autorização de empenho através da licitação' where id_item = 4718;
update db_itensmenu set help = 'Credenciamento de Fornecedores da Licitação' where id_item = 10406;
update db_itensmenu set help = 'Inclusão de Pcforne' where id_item = 3963;
update db_itensmenu set help = 'Alteração de Pcforne' where id_item = 3964;
update db_itensmenu set help = 'Exclusão de Pcforne' where id_item = 3965;
update db_itensmenu set help = 'Emite autorização de empenho' where id_item = 3670;
update db_itensmenu set help = '' where id_item = 6876;
update db_itensmenu set help = 'Fornecedores Empatados e Cotações ME/EPP' where id_item = 6948;
update db_itensmenu set help = '' where id_item = 6950;
update db_itensmenu set help = 'Configuração dos Editais' where id_item = 4689;
update db_itensmenu set help = 'Geração' where id_item = 8603;
update db_itensmenu set help = 'Upload Minuta' where id_item = 8604;
update db_itensmenu set help = 'Configuração de Minuta' where id_item = 8605;
update db_itensmenu set help = 'Anexa Arquivo Edital' where id_item = 5489;
update db_itensmenu set help = 'Processo de Compras' where id_item = 8984;
update db_itensmenu set help = 'Histórico do Julgamento da Licitação' where id_item = 9232;
update db_itensmenu set help = 'Cancelar' where id_item = 7320;
update db_itensmenu set help = 'Edital(Download)' where id_item = 5490;
update db_itensmenu set help = 'Alterar Observação da Licitação Deserta' where id_item = 9364;
update db_itensmenu set help = 'Inclusão de Licitação Fracassada' where id_item = 9361;
update db_itensmenu set help = 'Exclusão de Licitação Fracassada' where id_item = 9363;
update db_itensmenu set help = 'Trocar de fonecedores' where id_item = 9401;
update db_itensmenu set help = 'Licitações Liberadas na Web' where id_item = 5491;
update db_itensmenu set help = 'Edital (Download)' where id_item = 5492;
update db_itensmenu set help = 'Gera Lista de Itens em TXT' where id_item = 4779;
update db_itensmenu set help = 'Incluir' where id_item = 7319;
update db_itensmenu set help = 'Geração' where id_item = 8134;
update db_itensmenu set help = 'Parâmetros' where id_item = 6813;
update db_itensmenu set help = 'Alteração de Licitação Fracassada' where id_item = 9362;
update db_itensmenu set help = 'Reequilíbrio' where id_item = 7987;
update db_itensmenu set help = 'Cancelar Desistência' where id_item = 7990;
update db_itensmenu set help = 'Consulta Licitação' where id_item = 9223;
update db_itensmenu set help = 'Nova consulta de CGM' where id_item = 9255;
update db_itensmenu set help = 'Habilitação de Fornecedores' where id_item = 10206;
update db_itensmenu set help = 'Lançar Propostas para Licitação' where id_item = 4686;
update db_itensmenu set help = 'Adjudicar Licitação' where id_item = 10207;
update db_itensmenu set help = 'Homologar Licitação' where id_item = 10208;
update db_itensmenu set help = 'Eventos da Licitação' where id_item = 10209;
update db_itensmenu set help = 'Confirmação de Envio' where id_item = 10214;
update db_itensmenu set help = 'Geração de Arquivos' where id_item = 10213;
update db_itensmenu set help = 'Reserva de Cotas' where id_item = 10215;
update db_itensmenu set help = 'Manuten??o de Licita?es Enviadas' where id_item = 10246;
update db_itensmenu set help = 'Documentos do Portal Transparência' where id_item = 10462;
update db_itensmenu set help = 'Portal Compras Públicas' where id_item = 228554;
update db_itensmenu set help = 'Anulação da Licitação' where id_item = 9853;

SQL
        );
    }
}
