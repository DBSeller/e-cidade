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

use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ProcessoCompraTaxa;
use ECidade\Patrimonial\Licitacao\Licitacon\Campo\ResultadoHabilitacao;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\ItemProp as Regra;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Proposta as RegraProposta;

/**
 * Class ItemPropLicitaCon
 */
class ItemPropLicitaCon extends ArquivoLicitaCon
{
    /**
     * @var string
     */
    const NOME_ARQUIVO = 'ITEM_PROP';
    /**
     * @var string
     */
    const TP_OBJETO_OBRAS_SERVICO_ENGENHARIA = 'OSE';

    /**
     * ItemPropLicitaCon constructor.
     * @param CabecalhoLicitaCon $oCabecalho
     */
    public function __construct(CabecalhoLicitaCon $oCabecalho)
    {
        parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
        $this->sNomeArquivo = self::NOME_ARQUIVO;
        $this->iCodigoLayout = $this->oRegra->getCodigoLayout();
    }

    /**
     * @return array|stdClass[]
     * @throws DBException
     * @throws Exception
     */
    public function getDados()
    {
        $aSituacoes = array(
          SituacaoLicitacao::SITUACAO_JULGADA,
          SituacaoLicitacao::SITUACAO_ADJUDICADA,
          SituacaoLicitacao::SITUACAO_HOMOLOGADA
        );

        $oDaoLicitacao = new cl_liclicita;

        $sTipos = implode(',', array(
          licitacao::TIPO_JULGAMENTO_POR_ITEM,
          licitacao::TIPO_JULGAMENTO_GLOBAL,
        ));
        $aCampos = array(
          'l20_codigo',
          'z01_numcgm',
          'l20_numero AS nr_licitacao',
          'l20_anousu AS ano_licitacao',
          'l20_tipojulg AS tipo_julgamento',
          'l44_sigla AS cd_tipo_modalidade',
          "CASE
                WHEN l44_sigla IN ('CPC', 'MAI', 'RPO', 'PRD', 'PRI') OR l20_tipojulg <> " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " THEN NULL
                WHEN pc32_orcamitem IS NOT NULL THEN 'D'
                WHEN pc23_vlrun IS NULL OR pc23_vlrun = 0 THEN 'D'
                ELSE 'C'
            END AS tp_resultado_proposta",
          'l16_cadattdinamicovalorgrupo',
          'COALESCE(pc23_bdi, 0) AS pc23_bdi',
          'COALESCE(pc23_encargossociais, 0) AS pc23_encargossociais',
          'l21_ordem AS nr_item',
          "CASE
                WHEN l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " AND l44_sigla IN ('MDE') THEN pc23_percentualdesconto
                ELSE NULL
            END AS pc_desconto",
          'ROUND(COALESCE(pc23_vlrun * pc23_quant, 0), 2) AS vl_total_item',
          "COALESCE(pc23_vlrun, 0) AS VL_UNITARIO",
          "CASE
                WHEN l44_sigla IN ('MCA', 'MOQ', 'MOT', 'MPP', 'MTC', 'MTO', 'MTT', 'TPR') AND l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " THEN pc23_notatecnica
                ELSE NULL
            END AS vl_nota_tecnica",
          "MIN(COALESCE(CASE WHEN l20_tipojulg IN ({$sTipos}) THEN 1 ELSE l04_codigo END, 1)) AS NR_LOTE",
          'l04_descricao AS lote',
          "CASE
                WHEN l20_tipojulg = " . licitacao::TIPO_JULGAMENTO_POR_ITEM . " AND l44_sigla = 'CPC' THEN TO_CHAR(MAX(l11_data), 'DD/MM/YYYY')
            END AS dt_homologacao",
          'l20_tipojulg AS tp_nivel_julgamento',
          'pc21_orcamforne',
          'pc23_orcamforne',
          'pc22_orcamitem'
        );

        $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(),
          $this->oCabecalho->getDataGeracao());
        $aWhere[] = 'l20_licsituacao IN (' . implode(', ', $aSituacoes) . ' ) ';
        $aWhere[] = "l44_sigla NOT IN ('RPO', 'PRD', 'PRI')";

        $sWhereProposta = implode(' and ', $aWhere);
        $sGroupBy = 'l20_codigo, l21_codliclicita, l21_codigo, l44_sequencial, z01_numcgm, pc24_pontuacao, pc22_orcamitem, pc23_orcamitem, pc21_orcamforne, pc23_orcamforne, l04_descricao, pc32_orcamitem, l16_cadattdinamicovalorgrupo, l21_ordem';
        $sSqlLotes = $oDaoLicitacao->sql_query_propostas(implode(', ', $aCampos), $sWhereProposta,
          $sGroupBy . ' order by l20_codigo, l21_codigo');

        $rsLotes = db_query($sSqlLotes);

        if (!$rsLotes) {
            $sMsgErro = "Não foi possível buscar informações para o arquivo {$this->sNomeArquivo} no LicitaCon.";
            throw new DBException($sMsgErro);
        }

        $aLicitacoes = array();
        $iTotalLotes = pg_num_rows($rsLotes);
        for ($iLinha = 0; $iLinha < $iTotalLotes; $iLinha++) {
            $oLinha = db_utils::fieldsMemory($rsLotes, $iLinha);

            if($oLinha->vl_total_item === '0.00') {
                continue;
            }

            if ($oLinha->vl_nota_tecnica) {
                $oLinha->vl_nota_tecnica = number_format($oLinha->vl_nota_tecnica, 2, ',', '');
            }

            $oLinha->vl_total_item = (float)$oLinha->vl_total_item;
            $oLinha->vl_unitario = (float)$oLinha->vl_unitario;

            $lJulgamentoPorItem = $oLinha->tipo_julgamento == licitacao::TIPO_JULGAMENTO_POR_ITEM;

            $oLicitacao = LicitacaoRepository::getByCodigo($oLinha->l20_codigo);

            $oRegra = new RegraProposta($this->oCabecalho->getDataGeracao());
            $oRegra->setLicitacao($oLicitacao);
            $oRegra->setFornecedor(new OrcamentoFornecedor($oLinha->pc21_orcamforne));
            $oRegra->setItem(new ItemOrcamento($oLinha->pc22_orcamitem));
            $sResultadoProposta = $oRegra->getResultadoLicitacaoPorItem();

            $processoCompraTaxa = new ProcessoCompraTaxa(
              $oLicitacao->getCodigo(),
              licitacao::TIPO_JULGAMENTO_POR_ITEM,
              $oLinha->pc22_orcamitem,
              $oLinha->pc21_orcamforne
            );

            $resultadoHabilitacao = new ResultadoHabilitacao(
              $oLinha->pc21_orcamforne,
              $oLicitacao,
              licitacao::TIPO_JULGAMENTO_POR_ITEM,
              $this->oRegra->getVersao()
            );

            $sNomeLote = $oLinha->lote;

            if ($oLinha->tipo_julgamento != licitacao::TIPO_JULGAMENTO_POR_LOTE) {
                $sNomeLote = 1;
            }

            $sSqlNrLote = "select min(l04_codigo) as nr_lote from liclicita
                                   inner join liclicitem ON liclicitem.l21_codliclicita = liclicita.l20_codigo
                                   inner join liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                                   where liclicitemlote.l04_descricao ilike '{$sNomeLote}' and liclicita.l20_codigo = {$oLinha->l20_codigo}";
            $rsNrLote = db_query($sSqlNrLote);
            $nrLote = db_utils::fieldsMemory($rsNrLote, 0);

            $oStdItem = new stdClass;
            $oStdItem->NR_LICITACAO = $oLinha->nr_licitacao;
            $oStdItem->ANO_LICITACAO = $oLinha->ano_licitacao;
            $oStdItem->CD_TIPO_MODALIDADE = $oLinha->cd_tipo_modalidade;
            $oStdItem->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($oLinha->z01_numcgm);
            $oStdItem->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oLinha->z01_numcgm);
            $oStdItem->NR_LOTE = $oLinha->nr_lote != '1' ? $nrLote->nr_lote : $oLinha->nr_lote;
            $oStdItem->NR_ITEM = $oLinha->nr_item;
            $oStdItem->PC_DESCONTO = $oLinha->pc_desconto ? number_format($oLinha->pc_desconto, 2, ',', '') : '';
            $oStdItem->VL_TOTAL_ITEM = number_format($oLinha->vl_total_item, 2, ',', '');
            $oStdItem->VL_UNITARIO = number_format($oLinha->vl_unitario, 4, ',', '');

            if ($sResultadoProposta == "") {
                $sResultadoProposta = $oRegra->getResultadoLicitacaoPorLote();
            }

            $oStdItem->VL_NOTA_TECNICA = $oLinha->vl_nota_tecnica;
            $oStdItem->TP_RESULTADO_PROPOSTA = $sResultadoProposta;
            $oStdItem->DT_HOMOLOGACAO = null;
            $oStdItem->TP_NIVEL_JULGAMENTO = $oLinha->tp_nivel_julgamento;
            $oStdItem->lote = $oLinha->lote;
            $oStdItem->PC_BDI = '0,00';
            $oStdItem->PC_ENCARGOS_SOCIAIS = null;
            $oStdItem->pc21_orcamforne = $oLinha->pc21_orcamforne;
            $oStdItem->PC_TX = $processoCompraTaxa->obterValorHomologado();
            $oStdItem->TP_RESULTADO_HABILITACAO = $resultadoHabilitacao->obterValor();

            $sCampos = " pc20_codorc ";
            $sWhere = " l20_codigo = {$oLinha->l20_codigo} ";

            $oDaoOrcamItemLic = new cl_pcorcamitemlic();
            $sSqlOrcLicitacao = $oDaoOrcamItemLic->sql_query(null, $sCampos, null, $sWhere);

            $sCampos = " pc31_orcamforne as codigo, z01_nome as nome, l17_situacao as situacao ";
            $sWhere = " pc21_codorc in ($sSqlOrcLicitacao) and z01_numcgm = {$oLinha->z01_numcgm}";

            $oDaoFornecedoresLicitacao = new cl_pcorcamfornelic();
            $sSqlFornecedoresLicitacao = $oDaoFornecedoresLicitacao->sql_query(null, $sCampos, null, $sWhere);

            $result = db_query($sSqlFornecedoresLicitacao);

            $oHabilitacao = db_utils::fieldsMemory($result, 0);

            // INABILITADO
            if ($oHabilitacao->situacao == 2) {
                $oStdItem->TP_RESULTADO_HABILITACAO = '';

                if ($oLinha->tipo_julgamento == licitacao::TIPO_JULGAMENTO_GLOBAL) {
                    continue;
                }

                if ($oLinha->tipo_julgamento == licitacao::TIPO_JULGAMENTO_POR_ITEM) {
                    $oStdItem->TP_RESULTADO_HABILITACAO = 'I';
                }
            }

            if ($oLinha->cd_tipo_modalidade == 'CNV' && LicitanteLicitaCon::getTipoCondicaoFornecedor($oHabilitacao->codigo) == 'CNP') {
                continue;
            }

            if ($lJulgamentoPorItem && $oLinha->cd_tipo_modalidade == licitacao::MODALIDADE_CHAMAMENTO_PUBLICO_CREDENCIAMENTO) {
                $oStdItem->DT_HOMOLOGACAO = $oLinha->dt_homologacao;
            }

            $oAtributosDinamicos = $this->getAtributosDinamicos($oLinha->l16_cadattdinamicovalorgrupo);
            if ($oAtributosDinamicos->sTipoObjeto == self::TP_OBJETO_OBRAS_SERVICO_ENGENHARIA) {
                $oStdItem->PC_BDI = empty($oLinha->pc23_bdi) && $oLinha->pc23_bdi != '0,00' ? 
                    '0,00' : 
                    number_format($oLinha->pc23_bdi, 2, ',', '');
                    
                $oStdItem->PC_ENCARGOS_SOCIAIS = $oLinha->pc23_encargossociais ? number_format($oLinha->pc23_encargossociais,
                  2, ',', '') : null;
            }

            if ($oStdItem->TP_NIVEL_JULGAMENTO == licitacao::TIPO_JULGAMENTO_POR_LOTE || $oStdItem->TP_NIVEL_JULGAMENTO == licitacao::TIPO_JULGAMENTO_GLOBAL) {
                if (!empty($aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote][0])) {
                    $oAux = $aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote][0];

                    foreach ($aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote] as $oItem) {
                        if ($oStdItem->NR_LOTE < $oItem->NR_LOTE) {
                            $oItem->NR_LOTE = $oStdItem->NR_LOTE;
                        } else {
                            $oStdItem->NR_LOTE = $oItem->NR_LOTE;
                        }

                        if ($oStdItem->TP_RESULTADO_PROPOSTA == 'D' || $oAux->TP_RESULTADO_PROPOSTA == 'D') {
                            $oItem->TP_RESULTADO_PROPOSTA = $oStdItem->TP_RESULTADO_PROPOSTA = 'D';
                        }
                    }
                }
            }
            $aLicitacoes[$oLinha->l20_codigo][$oLinha->z01_numcgm][$oLinha->lote][] = $oStdItem;
        }

        $aItens = array();
        foreach ($aLicitacoes as $iCodigoLicitacao => $aLicitante) {
            foreach ($aLicitante as $aLote) {
                foreach ($aLote as $sDescricaoLote => $aItem) {
                    foreach ($aItem as $oItem) {
                        $aItens[] = $oItem;
                    }
                }
            }
        }

        return $aItens;
    }

    /**
     * @param $iAtributoDinamicoValorGrupo
     * @return stdClass
     */
    private function getAtributosDinamicos($iAtributoDinamicoValorGrupo)
    {
        $oStdAtributosDinamicos = new stdClass;
        $oStdAtributosDinamicos->sTipoObjeto = null;
        $oStdAtributosDinamicos->sTipoOrcamento = null;

        if (empty($iAtributoDinamicoValorGrupo)) {
            return $oStdAtributosDinamicos;
        }

        $aValoresAtributosDinamicos = DBAttDinamicoValor::getValores($iAtributoDinamicoValorGrupo);

        foreach ($aValoresAtributosDinamicos as $oValor) {
            switch ($oValor->getAtributo()->getNome()) {
                case 'tipoobjeto':
                    $oStdAtributosDinamicos->sTipoObjeto = $oValor->getValor();
                    break;
            }
        }

        if ($oStdAtributosDinamicos->sTipoObjeto != self::TP_OBJETO_OBRAS_SERVICO_ENGENHARIA) {
            $oStdAtributosDinamicos->sTipoOrcamento = null;
        }

        return $oStdAtributosDinamicos;
    }
}
