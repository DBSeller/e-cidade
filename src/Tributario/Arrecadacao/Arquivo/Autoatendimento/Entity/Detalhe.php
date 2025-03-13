<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Entity;

use ECidade\Tributario\Arrecadacao\Entity\Contribuinte;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Configuracao\Entity\Instituicao;
use ECidade\Tributario\Library\Entity;

class Detalhe extends Entity
{
    const TIPO_REGISTRO_DETALHE    = 'TIPOREGISTRO';
    const RESPONSAVEL_DEBITO       = 'RESPONSAVELDEBITO';
    const TIPO_ATUALIZACAO         = 'TIPOATUALIZACAO';
    const IDENTIFICACAO_DEVEDOR    = 'IDENTIFICACAODEVEDOR';
    const IDENTIFICADOR_DEBITO     = 'IDENTIFICADORDEBITO';
    const REFERENCIA_DEBITO        = 'REFERENCIADEBITO';
    const DETALHAMENTO_DEBITO      = 'DETALHAMENTO_DEBITO';
    const VENCIMENTO_CODIGO_BARRAS = 'VENCIMENTOCODIGOBARRAS';
    const CODIGO_BARRAS            = 'CODIGOBARRAS';
    const VALOR_DEBITO             = 'VALORDEBITO';
    const TIPO_DEBITO              = 'TIPODEBITO';
    const NUMERO_PARCELA           = 'NUMEROPARCELA';
    const CHASSI_VEICULO           = 'CHASSIVEICULO';
    const VALOR_VENAL_IMOVEL       = 'VALORVENALIMOVEL';
    const CODIGO_BARRAS_AGRUPADOR  = 'CODIGOBARRASAGRUPADOR';
    const TIPO_DE_PESSOA           = 'TIPODEPESSOA';
    const RESERVADO                = 'RESERVADO';
    const SEQUENCIAL               = 'SEQUENCIAL';
    const RCB800                   = 'RCB800';

    private $instituicao;
    private $tipoAtualizacao;
    private $contribuinte;
    private $recibo;
    private $tipoDebito;
    private $numeroParcela;
    private $anoReferencia;
    private $sequencial;
    private $codigoBarrasAgrupador;

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return mixed
     */
    public function getTipoAtualizacao()
    {
        return $this->tipoAtualizacao;
    }

    /**
     * @param mixed $tipoAtualizacao
     */
    public function setTipoAtualizacao($tipoAtualizacao)
    {
        $this->tipoAtualizacao = $tipoAtualizacao;
    }

    /**
     * @return Contribuinte
     */
    public function getContribuinte()
    {
        return $this->contribuinte;
    }

    /**
     * @param Contribuinte $contribuinte
     */
    public function setContribuinte(Contribuinte $contribuinte)
    {
        $this->contribuinte = $contribuinte;
    }

    /**
     * @return Recibo
     */
    public function getRecibo()
    {
        return $this->recibo;
    }

    /**
     * @param Recibo $recibo
     */
    public function setRecibo(Recibo $recibo)
    {
        $this->recibo = $recibo;
    }

    public function getTipoDebito()
    {
        return $this->tipoDebito;
    }

    public function setTipoDebito($tipoDebito)
    {
        $this->tipoDebito = $tipoDebito;
    }

    public function getNomeTipoDebito()
    {
        return $this->nomeTipoDebito;
    }

    public function setNomeTipoDebito($nomeTipoDebito)
    {
        $this->nomeTipoDebito = $nomeTipoDebito;
    }

    public function getNumeroParcela()
    {
        return $this->numeroParcela;
    }

    public function setNumeroParcela($numeroParcela)
    {
        $this->numeroParcela = $numeroParcela;
    }

    public function getCodigoBarrasAgrupador()
    {
        return $this->codigoBarrasAgrupador;
    }

    public function setCodigoBarrasAgrupador($codigoBarrasAgrupador)
    {
        $this->codigoBarrasAgrupador = $codigoBarrasAgrupador;
    }

    /**
     * @return integer
     */
    public function getAnoReferencia()
    {
        return $this->anoReferencia;
    }

    /**
     * @param integer $anoReferencia
     */
    public function setAnoReferencia($anoReferencia)
    {
        $this->anoReferencia = $anoReferencia;
    }

    /**
     * @return integer
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param integer $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return string
     */
    public function getTipoArquivo()
    {
        return self::RCB800;
    }
}
