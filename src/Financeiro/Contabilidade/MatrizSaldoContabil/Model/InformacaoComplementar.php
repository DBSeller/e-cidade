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

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model;

use \cl_infocomplementarvalor;

/**
 * Classe para processamento dos atributos
 * Class InformacaoComplementar
 * @package Ecidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model
 */
class InformacaoComplementar
{
    const INFO_COMP_TIPO_PO = 'PO';
    const INFO_COMP_TIPO_FP = 'FP';
    const INFO_COMP_TIPO_DC = 'DC';
    const INFO_COMP_TIPO_FR = 'FR';
    const INFO_COMP_TIPO_NR = 'NR';
    const INFO_COMP_TIPO_ND = 'ND';
    const INFO_COMP_TIPO_FS = 'FS';
    const INFO_COMP_TIPO_ES = 'ES';
    const INFO_COMP_TIPO_AI = 'AI';
    const INFO_COMP_TIPO_CF = 'CF';
    const INFO_COMP_TIPO_CO = 'CO';

    const INFO_COMP_CODIGO_ES = 50;

    const INFO_COMP_CODIGO_AI = 51;

    const INFO_COMP_CODIGO_CF = 53;

    const INFO_COMP_CODIGO_FP = 2;

    /**
     * @var string $sigla
     */
    private $sigla;

    /**
     * @var string $descricao
     */
    private $descricao;

    /**
     * @var $conta integer
     */
    private $conta;

    /**
     * @var int $anousu
     */
    private $anousu;

    /**
     * @var string $contaEstrutura
     */
    private $contaEstrutura;

    /**
     * @var int $contaReduzida
     */
    private $contaReduzida;

    /**
     * @var integer $codigoLancamento
     */
    private $codigoLancamento;

    /**
     * @var int $codigoInstituicao
     */
    private $codigoInstituicao;

    /**
     * @var int $codigoConplanoAtributos
     */
    private $codigoConplanoAtributos;

    private $codigoInformacaoComplementar;

    private $codigoSistema;

    /**
     * @var string $valor
     */
    private $valor;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    /**
     * @param int $codigoInstituicao
     */
    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    /**
     * @return int
     */
    public function getContaReduzida()
    {
        return $this->contaReduzida;
    }

    /**
     * @param int $contaReduzida
     */
    public function setContaReduzida($contaReduzida)
    {
        $this->contaReduzida = $contaReduzida;
    }

    /**
     * @return integer
     */
    public function getCodigoLancamento()
    {
        return $this->codigoLancamento;
    }

    /**
     * @param integer $codigoLancamento
     */
    public function setCodigoLancamento($codigoLancamento)
    {
        $this->codigoLancamento = $codigoLancamento;
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return int
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param int $conta
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
    }

    /**
     * @return int
     */
    public function getAnousu()
    {
        return $this->anousu;
    }

    /**
     * @param int $anousu
     */
    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    /**
     * @return string
     */
    public function getContaEstrutura()
    {
        return $this->contaEstrutura;
    }

    /**
     * @param string $contaEstrutura
     */
    public function setContaEstrutura($contaEstrutura)
    {
        $this->contaEstrutura = $contaEstrutura;
    }

    /**
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getCodigoConplanoAtributos()
    {
        return $this->codigoConplanoAtributos;
    }

    /**
     * @param int $codigoConplanoAtributos
     * @return InformacaoComplementar
     */
    public function setCodigoConplanoAtributos($codigoConplanoAtributos)
    {
        $this->codigoConplanoAtributos = $codigoConplanoAtributos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoInformacaoComplementar()
    {
        return $this->codigoInformacaoComplementar;
    }

    /**
     * @param mixed $codigoInformacaoComplementar
     */
    public function setCodigoInformacaoComplementar($codigoInformacaoComplementar)
    {
        $this->codigoInformacaoComplementar = $codigoInformacaoComplementar;
    }

    /**
     * @return mixed
     */
    public function getCodigoSistema()
    {
        return $this->codigoSistema;
    }

    /**
     * @param mixed $codigoSistema
     */
    public function setCodigoSistema($codigoSistema)
    {
        $this->codigoSistema = $codigoSistema;
    }


    /**
     * @param bool $lancamentoManual
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function atualizarValor($lancamentoManual = false)
    {
        if ($lancamentoManual) {
            switch ($this->sigla) {
                case self::INFO_COMP_TIPO_NR:
                    $this->setValor('999999999999999');
                    return true;
                case self::INFO_COMP_TIPO_ND:
                    $this->setValor('999999999999998');
                    return true;
                case self::INFO_COMP_TIPO_FS:
                    $this->setValor('04122');
                    return true;
            }
        }

        $oDaoInfoComplementarValor = new cl_infocomplementarvalor();

        switch ($this->sigla) {
            case self::INFO_COMP_TIPO_PO:
                $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_po_by_lancamento(
                    $this->getCodigoInstituicao()
                );
                break;
            case self::INFO_COMP_TIPO_FP:
                $hash = "atributo_fp{$this->getContaReduzida()}#{$this->getAnousu()}#{$this->getCodigoInstituicao()}";
                if (!\DBRegistry::has($hash)) {
                    $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_fp_by_reduzido(
                        $this->getContaReduzida(),
                        $this->getAnousu(),
                        $this->getCodigoInstituicao()
                    );
                    $rsInfoComplementarValor = \db_query($sql);

                    if (!$rsInfoComplementarValor) {
                        $msg  = "Não foi possível processar a informação complementar {$this->getDescricao()}";
                        $msg .= " para o lançamento {$this->getCodigoLancamento()}.";
                        throw new \Exception($msg);
                    }

                    $valor = \db_utils::fieldsMemory($rsInfoComplementarValor, 0)->infocomplementar_valor;
                    \DBRegistry::add($hash, $valor);
                }
                $this->setValor(\DBRegistry::get($hash));
                return true;

                break;
            case self::INFO_COMP_TIPO_DC:
                $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_dc_by_estrutural(
                    $this->getConta(),
                    $this->getAnousu()
                );
                break;
            case self::INFO_COMP_TIPO_FR:
                $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_fr_by_lancamento(
                    $this->getCodigoLancamento(),
                    $this->getContaReduzida(),
                    $this->getAnousu()
                );
                break;
            case self::INFO_COMP_TIPO_NR:
                $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_nr_by_lancamento(
                    $this->getCodigoLancamento()
                );
                break;
            case self::INFO_COMP_TIPO_ND:
                $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_nd_by_lancamento(
                    $this->getCodigoLancamento()
                );
                break;
            case self::INFO_COMP_TIPO_FS:
                $sql = $oDaoInfoComplementarValor->sql_query_infocomplementar_fs_by_lancamento(
                    $this->getCodigoLancamento()
                );
                break;
            case InformacaoComplementar::INFO_COMP_TIPO_AI:
            case InformacaoComplementar::INFO_COMP_TIPO_CF:
            case InformacaoComplementar::INFO_COMP_TIPO_CO:
            case InformacaoComplementar::INFO_COMP_TIPO_ES:
                $sql = 'select null as infocomplementar_valor';
                break;
            default:
                throw new \Exception('Informação complementar inválida.');
        }

        $rsInfoComplementarValor = \db_query($sql);

        if (!$rsInfoComplementarValor) {
            throw new \Exception("Não foi possível processar a informação complementar {$this->getDescricao()}
             para o lançamento {$this->getCodigoLancamento()}.");
        }

        $valor = \db_utils::fieldsMemory($rsInfoComplementarValor, 0)->infocomplementar_valor;
        $this->setValor($valor);

        return true;
    }
}
