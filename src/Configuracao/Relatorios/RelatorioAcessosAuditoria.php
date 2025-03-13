<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Configuracao\Relatorios;

use Exception;
use FpdfMultiCellBorder;
use stdClass;
use db_stdClass;
use rotulocampo;
use DBException;
use cl_db_auditoria;
use Instituicao;

class RelatorioAcessosAuditoria extends FpdfMultiCellBorder
{
    /**
     * @var String
     */
    private $sCaminhoMensagens;

    /**
     * @var Array
     */
    private $aParametros;

    public function __construct($aParametros)
    {
        parent::__construct();

        $this->sCaminhoMensagens    = "configuracao.configuracao.con2_consultaacesso";
        $this->aParametros = $aParametros;

        $oInstituicao = new Instituicao(db_getsession('DB_instit'));

        global $head2, $head4, $head6;

        $head2  = "Relatório de Auditorias do e-cidade";
        $head4  = "Período de " . db_formatar($aParametros["dDataInicio"], "d");
        $head4 .= " a " . db_formatar($aParametros["dDataFim"], "d");
        $head6  = "Instituição: " . $oInstituicao->getCodigo();
        $head6 .= " - " . $oInstituicao->getDescricao();
    }

    protected function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(8, 8, 8);
        $this->Open();
        $this->SetAutoPageBreak(true, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', 'B', 9);
        $this->exibeHeader(true);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/acessos_auditoria_' . time() . '.pdf';
        $this->Output($fileName, false, true);
        return [
            "name" => utf8_encode("Relatório de Auditorias do e-cidade"),
            "path" => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    public function emitirPdf()
    {
        $this->initPdf();
        $this->AddPage('L');

        $oDaoDBAuditoria      = new cl_db_auditoria();
        $sCamposBuscaAcessos  = "distinct db_logsacessa.*, db_usuarios.login, db_usuarios.nome";
        $sCamposBuscaAcessos .= ", fc_montamenu(db_logsacessa.id_item) as path_menu";
        $sSqlBuscaAcessos     = $oDaoDBAuditoria->sql_query_acessos($sCamposBuscaAcessos, null, $this->aParametros);

        $rsRetornoAcessos     = db_query($sSqlBuscaAcessos);
  
        if (!$rsRetornoAcessos) {
            throw new DBException(_M("{$this->sCaminhoMensagens}.registros_nao_encontrados"));
        }
  
        if (pg_num_rows($rsRetornoAcessos) == 0) {
            throw new DBException("Não há modificações encontradas para impressão.");
        }

        $troca = 1;
        $alt = 4;
        $total = 0;

        while ($aRetornoAcessos = pg_fetch_object($rsRetornoAcessos)) {
            $aParametrosModif             = array();
            $sParametroEsquema = db_stdClass::normalizeStringJsonEscapeString($this->aParametros['sEsquema']);
            $aParametrosModif['sEsquema'] = $sParametroEsquema;
            $aParametrosModif['sCampo'] = db_stdClass::normalizeStringJsonEscapeString($this->aParametros['sCampo']);
            $aParametrosModif['mValor'] = db_stdClass::normalizeStringJsonEscapeString($this->aParametros['mValor']);
            $aParametrosModif['sTabela'] = db_stdClass::normalizeStringJsonEscapeString($this->aParametros['sTabela']);
            /**
             * Removido variavel de hora nos parametros pois alguns logsacessa foram migrados,
             * e os horários não estavam batendo.
             */
            $aParametrosModif['sDataHoraInicial'] = $aRetornoAcessos->data . ' 00:00:00.000000';
            $aParametrosModif['sDataHoraFim']     = $aRetornoAcessos->data . ' 23:59:59.999999';
            $aParametrosModif['sUsuario']         = $this->aParametros['sUsuario'];
            $aParametrosModif['iCodigoAcesso']    = $aRetornoAcessos->codsequen;
            $aParametrosModif['iInstituicao']     = $aRetornoAcessos->instit;

            $sSqlBuscaModificacoes = $oDaoDBAuditoria->sql_query_modificacoes($aParametrosModif);

            $rsBuscaModificacoes   = $oDaoDBAuditoria->sql_record($sSqlBuscaModificacoes);

            if (!$rsBuscaModificacoes) {
                throw new DBException(_M("{$this->sCaminhoMensagens}.registros_nao_encontrados"));
            }

            if ($this->gety() > $this->h -12 || $troca != 0) {
                $this->imprimirCabecalhoRetorno();
                $troca = 0;
            }

            $this->setfont('arial', '', 6);
            $this->cell(15, $alt, $aRetornoAcessos->codsequen, "T", 0, "C", 0);
            $this->cell(19, $alt, $aRetornoAcessos->ip, "T", 0, "C", 0);
            $this->cell(17, $alt, db_formatar($aRetornoAcessos->data, "d"), "T", 0, "C", 0);
            $this->cell(17, $alt, $aRetornoAcessos->hora, "T", 0, "C", 0);
            $this->cell(32, $alt, $aRetornoAcessos->id_usuario . ' - ' . $aRetornoAcessos->login, "T", 0, "L", 0);
            $this->cell(60, $alt, $aRetornoAcessos->nome, "T", 0, "L", 0);
            $sItemMenu = $aRetornoAcessos->id_item .' - '. $aRetornoAcessos->path_menu;
            $this->cell(120, $alt, $sItemMenu, "T", 1, "L", 0);

            if ($oDaoDBAuditoria->numrows > 0) {
                $troca2 = 1;
                while ($aRetornoMoficacoes = pg_fetch_object($rsBuscaModificacoes)) {
                    if ($this->gety() > $this->h -12 || $troca2 != 0) {
                        $this->imprimirCabecalhoModificacoes();
                        $troca2 = 0;
                    }
 
                    $sCodNomeTabela = $aRetornoMoficacoes->codarq . " - " . $aRetornoMoficacoes->tabela;
 
                    $this->setfont('arial', '', 6);
                    $this->cell(55, $alt, $sCodNomeTabela, "T", 0, "L", 0);
                    $this->cell(25, $alt, $aRetornoMoficacoes->nome_campo, "T", 0, "L", 0);
                    $this->cell(25, $alt, $aRetornoMoficacoes->descricao_operacao, "T", 0, "C", 0);
                    $this->cell(60, $alt, substr($aRetornoMoficacoes->valor_antigo, 0, 45), "T", 0, "L", 0);
                    $this->cell(60, $alt, substr($aRetornoMoficacoes->valor_novo, 0, 45), "T", 0, "L", 0);
                    $this->cell(30, $alt, $aRetornoMoficacoes->datahora_servidor, "T", 1, "L", 0);
                }
                $this->imprimirCabecalhoRetorno();
            }

            $total++;
        }

        $this->setfont('arial', 'b', 6);
        $this->cell(280, $alt, "Total de registros impressos: ".$total, 1, 0, "L", 1);
        $this->setfont('arial', 'b', 8);

        return $this->imprimir();
    }

    private function imprimirCabecalhoModificacoes()
    {

        $alt = 4;
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 6);

        $this->cell(55, $alt, "Tabela", 1, 0, "C", 1);
        $this->cell(25, $alt, "Nome Campo", 1, 0, "C", 1);
        $this->cell(25, $alt, "Operação", 1, 0, "C", 1);
        $this->cell(60, $alt, "Valor Antigo", 1, 0, "C", 1);
        $this->cell(60, $alt, "Valor Novo", 1, 0, "C", 1);
        $this->cell(30, $alt, "Data Hora Servidor", 1, 1, "C", 1);
    }

    private function imprimirCabecalhoRetorno()
    {

        $alt = 5;
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 7);

        $this->cell(15, $alt, 'Logacessa', 1, 0, "C", 1);
        $this->cell(19, $alt, "IP computador", 1, 0, "C", 1);
        $this->cell(17, $alt, "Data Acesso", 1, 0, "C", 1);
        $this->cell(17, $alt, "Hora Acesso", 1, 0, "C", 1);
        $this->cell(32, $alt, "Login", 1, 0, "C", 1);
        $this->cell(60, $alt, "Usuário", 1, 0, "C", 1);
        $this->cell(120, $alt, "Menu Acessado", 1, 1, "C", 1);
    }
}
