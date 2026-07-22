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

require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

/**
 * Model que executa os lancamentos auxiliares para Abertura de Exercicio Orcamento
 * @author     Bruno Silva
 * @package    contabilidade
 * @subpackage lancamento
 * @version    1.0 $
 */
class LancamentoAuxiliarPagamentoLimiteSaque extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

    /**
     * @var int
     */
    private $historico = 10100;

    /**
     * @var
     */
    private $valor;

    /**
     * @var string
     */
    private $complemento = "Baixa de Obrigação de Pagamento por Limite de Saque";

    /**
     * @var Recurso
     */
    private $recurso;

    /**
     * @var integer
     */
    private $contaDebito;

    /**
     * @var integer
     */
    private $contaCredito;

    /**
     * @param float $valor
     */
    public function setValorTotal($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return float
     */
    public function getValorTotal()
    {
        return $this->valor;
    }

    /**
     * @param int $iHistorico
     */
    public function setHistorico($iHistorico)
    {
        $this->historico = $iHistorico;
    }

    /**
     * @return int
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param string $sObservacaoHistorico
     */
    public function setObservacaoHistorico($sObservacaoHistorico)
    {
        $this->complemento = $sObservacaoHistorico;
    }

    /**
     * @return string
     */
    public function getObservacaoHistorico()
    {
        return $this->complemento;
    }

    /**
     * @param int $iCodigoLancamento
     * @param string $dtLancamento
     * @return bool
     * @throws BusinessException
     */
    public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

        parent::setCodigoLancamento($iCodigoLancamento);
        parent::setDataLancamento($dtLancamento);
        parent::salvarVinculoComplemento();
        return true;
    }

    /**
     * @param Recurso $recurso
     */
    public function setRecurso(Recurso $recurso)
    {
        $this->recurso = $recurso;
    }

    /**
     * @return Recurso
     */
    public function getRecurso()
    {
        return $this->recurso;
    }

    public function setContaDebito($contaDebito)
    {
        $this->contaDebito = $contaDebito;
    }

    public function setContaCredito($contaCredito)
    {
        $this->contaCredito = $contaCredito;
    }

    /**
     * @return int
     */
    public function getContaCredito()
    {
        return $this->contaCredito;
    }

    /**
     * @return int
     */
    public function getContaDebito()
    {
        return $this->contaDebito;
    }

    public function isEstorno()
    {
        return false;
    }

}