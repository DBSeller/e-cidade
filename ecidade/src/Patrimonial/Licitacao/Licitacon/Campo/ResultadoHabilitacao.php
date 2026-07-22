<?php

namespace ECidade\Patrimonial\Licitacao\Licitacon\Campo;

use cl_pcorcamfornelichabilitacao;
use db_utils;
use DBException;
use licitacao as Licitacao;

/**
 * Class ResultadoHabilitacao
 * @package ECidade\Patrimonial\Licitacao\Licitacon\Campo
 */
class ResultadoHabilitacao
{
    /**
     * @var
     */
    private $codigoFornecedor;
    /**
     * @var
     */
    private $licitacao;
    /**
     * @var
     */
    private $arquivo;
    /**
     * @var null
     */
    private $versao;

    /**
     * ResultadoHabilitacao constructor.
     * @param $iCodigoFornecedor
     * @param Licitacao $oLicitacao
     * @param $sArquivo
     * @param null $sVersao
     */
    public function __construct($iCodigoFornecedor, Licitacao $oLicitacao, $sArquivo, $sVersao = null)
    {
        $this->setCodigoFornecedor($iCodigoFornecedor)
            ->setLicitacao($oLicitacao)
            ->setArquivo($sArquivo);
        $this->versao = $sVersao;
    }

    /**
     * @return mixed
     */
    public function getCodigoFornecedor()
    {
        return $this->codigoFornecedor;
    }

    /**
     * @return mixed
     */
    public function getLicitacao()
    {
        return $this->licitacao;
    }

    /**
     * @return mixed
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * @return mixed|string
     * @throws DBException
     */
    public function obterValor()
    {
        if (isset($this->versao) && $this->versao < 1.4) {
            return $this->buscarValor();
        }

        if ($this->getCodigoFornecedor() == '') {
            return '';
        }

        $sArquivo = $this->getArquivo();
        $sTipoNivelJulgamento = $this->getLicitacao()->obterNivelJulgamento();

        $bJulgamentoItem = $sTipoNivelJulgamento == 'I';
        $bJulgamentoLote = $sTipoNivelJulgamento == 'L';
        $bJulgamentoGlobal = $sTipoNivelJulgamento == 'G';
        $bArquivoGlobal = $sArquivo == Licitacao::TIPO_JULGAMENTO_GLOBAL;
        $bArquivoLote = $sArquivo == Licitacao::TIPO_JULGAMENTO_POR_LOTE;
        $bAquivoItem = $sArquivo == Licitacao::TIPO_JULGAMENTO_POR_ITEM;

        if ($bJulgamentoItem && $bArquivoGlobal) {
            return '';
        }

        if ($bJulgamentoItem && $bArquivoLote) {
            return '';
        }

        if ($bJulgamentoLote && $bArquivoGlobal) {
            return '';
        }

        if ($bJulgamentoLote && $bAquivoItem) {
            return '';
        }

        if ($bJulgamentoGlobal && $bAquivoItem) {
            return '';
        }

        if ($bJulgamentoGlobal && $bArquivoLote) {
            return '';
        }

        return $this->buscarValor();
    }

    /**
     * @param $iCodigoFornecedor
     * @return $this
     */
    public function setCodigoFornecedor($iCodigoFornecedor)
    {
        $this->codigoFornecedor = $iCodigoFornecedor;
        return $this;
    }

    /**
     * @param Licitacao $licitacao
     * @return $this
     */
    public function setLicitacao(Licitacao $licitacao)
    {
        $this->licitacao = $licitacao;
        return $this;
    }

    /**
     * @param $arquivo
     * @return $this
     */
    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    /**
     * @return mixed|string
     * @throws DBException
     */
    public function buscarValor()
    {
        if (!$this->getCodigoFornecedor()) {
            return '';
        }

        $sFiltro = "l17_pcorcamfornelic = {$this->getCodigoFornecedor()}";

        $oClOrcamfornelichabilitacao = new cl_pcorcamfornelichabilitacao;
        $sConsulta = $oClOrcamfornelichabilitacao->sql_query_file(null, 'l17_situacao', null, $sFiltro);
        $rResultado = db_query($sConsulta);

        if (!$rResultado) {
            throw new DBException('Não foi possível buscar o tipo de habilitação do fornecedor.');
        }

        $iSituacao = db_utils::fieldsMemory($rResultado, 0)->l17_situacao;
        $aSituacoes = array(1 => 'H', 2 => 'I', 3 => 'N');

        return pg_num_rows($rResultado) ? $aSituacoes[$iSituacao] : '';
    }
}
