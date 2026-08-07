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

use ECidade\RecursosHumanos\ESocial\Repository\ServidorMatriculas;

/**
 * Class ComprovanteRendimentoRepository
 */
class ComprovanteRendimentoRepository
{
    /**
     * Array com instancias dos comprovantes
     *
     * @static
     * @var Array
     * @access private
     */
    private static $aColecao = array();

    /**
     * Representa a instancia a classe
     *
     * @var ComprovanteRendimentoRepository
     * @access private
     */
    private static $oInstance;

    /**
     * Previne a criação do objeto externamente
     *
     */
    private function __construct()
    {
        return;
    }

    /**
     * @param \Servidor $matricula
     * @param integer $ano
     * @return \ComprovanteRendimento
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function getPorMatriculaNoAno(Servidor $matricula, $ano, $instituicao)
    {
        if (isset(self::$aColecao[$matricula->getCgm()->getCodigo()])) {
            return self::$aColecao[$matricula->getCgm()->getCodigo()];
        }

        $oDaoGeracaoDrif = new cl_rhdirfgeracao();
        $oDaoRubricas = new cl_rhrubricas();
        $oCompetencia = new DBCompetencia($ano, 12);
        $where = "rh96_numcgm = {$matricula->getCgm()->getCodigo()}";
        $where .= "  and rh95_ano = {$ano} ";
        $sSqlDadosDirf = $oDaoGeracaoDrif->sql_query_comprovante_rendimentos($oCompetencia, $where, null, $instituicao);
        $rsDadosDirf = db_query($sSqlDadosDirf);
        if (!$rsDadosDirf) {
            throw new DBException('Erro ao realizar pesquisa do comprovante de rendimentos');
        }
        $iTotalLinhas = pg_num_rows($rsDadosDirf);
        if ($iTotalLinhas == 0) {
            throw new BusinessException(
                "Sem comprovante de rendimentos para o servidor {$matricula->getMatricula()} em {$ano}."
            );
        }
        $oDadosComprovante = db_utils::fieldsMemory($rsDadosDirf, 0);

        $servidorMatriculas = new ServidorMatriculas(
            $matricula->getCgm()->getCodigo(),
            $instituicao
        );

        $sSqlRubricasBase = $oDaoRubricas->sql_query_comprovante_rendimento_rubricas_base(
            db_anofolha(),
            db_mesfolha(),
            null,
            null
        );
        $rsRubricasBase = db_query($sSqlRubricasBase);
        $aRubricas = array();
        if (pg_num_rows($rsRubricasBase) > 0) {
            $sRubricas = db_utils::fieldsMemory($rsRubricasBase, 0)->rubricas;
            if (!empty($sRubricas)) {
                $sSqlRubricasValores = $oDaoGeracaoDrif->sql_query_comprovante_rendimentos_rubricas_valores(
                    $oCompetencia,
                    $where,
                    null,
                    $servidorMatriculas->getMatriculas(),
                    $sRubricas
                );
                $rsRubricasValores = db_query($sSqlRubricasValores);
                $iTotalLinhasRubricas = pg_num_rows($rsRubricasValores);
                if ($iTotalLinhasRubricas > 0) {
                    for ($i = 0; $i < $iTotalLinhasRubricas; $i++) {
                        $oRubricasValores = db_utils::fieldsMemory($rsRubricasValores, $i);
                        $oRubrica = new \stdClass();
                        $oRubrica->rubrica = $oRubricasValores->descricao;
                        $oRubrica->valor = $oRubricasValores->valor;
                        $aRubricas[$i] = $oRubrica;
                    }
                }
            }
        }

        $oDadosComprovante->matricula = $matricula;
        $oDadosComprovante->ano = $ano;
        $oDadosComprovante->detalhamento_rubricas = $aRubricas;
        $oComprovante = self::make($oDadosComprovante);
        self::add($oComprovante);

        return $oComprovante;
    }

    /**
     * @param $oDados
     * @return \ComprovanteRendimento
     */
    public static function make($oDados)
    {
        $oDirf = new Dirf2012($oDados->ano, '023');
        $outrasInformacoes = $oDirf->getInformacoesComplementares($oDados->matricula->getCgm()->getCodigo());

        $oComprovante = new ComprovanteRendimento();
        $oComprovante->setMatriculas(array($oDados->matricula));
        $oComprovante->setCgm($oDados->matricula->getCgm());
        $oComprovante->setFontePagadora($oDados->rh95_fontepagadora);
        $oComprovante->setNomeFontePagadora($oDados->nome_fonte);
        $oComprovante->setLotacao($oDados->r70_estrut);
        $oComprovante->setQuantidadeDeMeses($oDados->rra_quantidade_meses);
        $oComprovante->setValorAbono($oDados->abono);
        $oComprovante->setValorDependentes($oDados->depend);
        $oComprovante->setValorDependentesDecimoTerceiro($oDados->depend_13);
        $oComprovante->setValorDescontoAposentado($oDados->aposentadoria_65);
        $oComprovante->setValorDescontoAposentadoDecimoTerceiro($oDados->aposentadoria_65_13);
        $oComprovante->setValorDescontoMolestiaGraveInativos($oDados->molestia_grave_inativos);
        $oComprovante->setValorDescontoMolestiaGraveInativosDecimoTerceiro($oDados->molestia_grave_inativos_13);
        $oComprovante->setValorDespesaDaAcao($oDados->rra_despesa_acao);
        $oComprovante->setValorDiarias($oDados->diaria);
        $oComprovante->setValorIndenizacaoRescisao($oDados->ind_rescisao);
        $oComprovante->setValorIsencaoSobreRRA($oDados->rra_isentos);
        $oComprovante->setValorIRRFSobreRRA($oDados->rra_irrf);
        $oComprovante->setValorMolestiaGraveAtivos($oDados->molestia_grave_ativos);
        $oComprovante->setValorMolestiaGraveAtivosDecimoTerceiro($oDados->molestia_grave_ativos_13);
        $oComprovante->setValorOutrosRendimentos($oDados->outros5);
        $oComprovante->setValorPagoEmPensao($oDados->pensao);
        $oComprovante->setValorPagoEmPensaoDecimoTerceiro($oDados->pensao_13);
        $oComprovante->setValorPagoIRRF($oDados->irrf);
        $oComprovante->setValorPagoIRRFDecimoTerceiro($oDados->irrf_13);
        $oComprovante->setValorPensaoSobreRRA($oDados->rra_pensao);
        $oComprovante->setValorPlanoSaude($oDados->plano_saude);
        $oComprovante->setValorTotalRendimentos($oDados->rendimento);
        $oComprovante->setValorTotalRendimentoDecimoTerceiro($oDados->rendimento_13);
        $oComprovante->setValorPrevidenciaOficial($oDados->prev_oficial);
        $oComprovante->setValorPrevidenciaOficialDecimoTerceiro($oDados->prev_oficial_13);
        $oComprovante->setValorPrevidenciaPrivada($oDados->prev_privada);
        $oComprovante->setValorPrevidenciaPrivadaDecimoTerceiro($oDados->prev_privada_13);
        $oComprovante->setValorPrevidenciaSobreRRA($oDados->rra_previdencia);
        $oComprovante->setOutrasInformacoes($outrasInformacoes);
        $oComprovante->setDetalhamentoRubricas($oDados->detalhamento_rubricas);
        $oComprovante->setValorRendimentosTributaveisSobreRRA($oDados->rra_rendimentos_tributaveis);

        return $oComprovante;
    }

    /**
     * Adiciona a coleção um comprovante
     *
     * @param ComprovanteRendimento $oComprovante
     */
    public static function add(ComprovanteRendimento $oComprovante)
    {
        $oRepository = self::getInstance();
        $oRepository::$aColecao[$oComprovante->getCgm()->getCodigo()] = $oComprovante;
    }

    /**
     * Retorna a instancia do repositório
     *
     * @return ComprovanteRendimentoRepository
     */
    public static function getInstance()
    {
        if (empty(self::$oInstance)) {
            self::$oInstance = new ComprovanteRendimentoRepository();
        }

        return self::$oInstance;
    }

    /**
     * Previne o clone
     *
     * @return void
     */
    private function __clone()
    {
        return;
    }
}
