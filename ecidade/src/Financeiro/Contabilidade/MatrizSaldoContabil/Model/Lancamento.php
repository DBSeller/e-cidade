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

namespace Ecidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model;

use Ecidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\InformacaoComplementar;
use \DBDate;

/**
 * Class Lancamento
 * @package Ecidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class Lancamento
{
    const TIPO_BEGINNING_BALANCE = 1;
    const TIPO_PERIOD_CHANGE = 2;
    const TIPO_ENDING_BALANCE = 3;

    /**
     * @var integer $sequencial
     */
    private $sequencial;

    /**
     * @var integer $codigoLancamento
     */
    private $codigoLancamento;

    /**
     * @var string $natureza
     */
    private $natureza;

    /**
     * @var integer $tipoLancamento
     */
    private $tipoLancamento;

    /**
     * @var float $valor
     */
    private $valor;

    /**
     * @var InformacaoComplementar[]
     */
    private $infoComplementares;

    /**
     * @var DBDate
     */
    private $data;

    /**
     * Código do sistema de conta corrente
     * @var integer
     */
    private $sistema;

    /**
     * @param integer|null $sequencial
     */
    public function __construct($sequencial = null)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return DBDate
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DBDate $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @param $codigo
     */
    public function setCodigoLancamento($codigo)
    {
        $this->codigoLancamento = $codigo;
    }

    /**
     * @param $natureza
     */
    public function setNatureza($natureza)
    {
        $this->natureza = $natureza;
    }

    /**
     * @param $tipo
     */
    public function setTipoLancamento($tipo)
    {
        $this->tipoLancamento = $tipo;
    }

    /**
     * @param $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @return integer
     */
    public function getCodigoLancamento()
    {
        return $this->codigoLancamento;
    }

    /**
     * @return string
     */
    public function getNatureza()
    {
        return $this->natureza;
    }

    /**
     * @return integer
     */
    public function getTipoLancamento()
    {
        return $this->tipoLancamento;
    }

    /**
     * Retorna a descrição do tipo de lancamento.
     * @return string
     */
    public function getDescricaoTipoLancamento()
    {
        $tipo = $this->getTipoLancamento();
        switch ($tipo) {
            case self::TIPO_BEGINNING_BALANCE:
                return 'beginning_balance';
                break;
            case self::TIPO_PERIOD_CHANGE:
                return 'period_change';
            case self::TIPO_ENDING_BALANCE:
                return 'ending_balance';
                break;
        }
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Retorna as informaçoes complementares.
     *
     * @return InformacaoComplementar[]
     */
    public function getInfoComplementares()
    {
        return $this->infoComplementares;
    }

    /**
     * Adiciona uma informaçao complementar.
     *
     * @param InformacaoComplementar $infoComplementares
     *
     * @return Lancamento
     */
    public function addInfoComplementares(InformacaoComplementar $infoComplementares)
    {
        $this->infoComplementares[] = $infoComplementares;
        return $this;
    }

    /**
     * Define as informaçoes complementares.
     *
     * @param InformacaoComplementar[] $infoComplementares
     *
     * @return Lancamento
     */
    public function setInfoComplementares(array $infoComplementares)
    {
        $this->infoComplementares = $infoComplementares;
        return $this;
    }

    /**
     * @return int
     */
    public function getSistema()
    {
        return $this->sistema;
    }

    /**
     * @param int $sistema
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;
    }


}
