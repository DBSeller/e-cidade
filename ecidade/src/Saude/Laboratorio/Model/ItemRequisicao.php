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
 *
 */

namespace ECidade\Saude\Laboratorio\Model;

use Cassandra\Date;

/**
 * Classe para controle dos dados dos Itens da Requisicao
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Laboratorio
 */
class ItemRequisicao
{
    /**
     * Código do Item
     * @var integer
     */
    private $codigo;

    /**
     * Codigo da requisição
     * @var integer
     */
    private $requisicao;

    /**
     * Data de entrega
     * @var DBDate|null
     */
    private $dataEntrega;

    /**
     * Data de cadastro
     * @var DBDate|null
     */
    private $dataCadastro;

    /**
     * Hora de cadastro
     * @var string
     */
    private $horaCadastro;

    /**
     * Código do setor
     * @var integer
     */
    private $codigoSetor;

    /**
     * Se for emergencia
     * @var integer
     */
    private $emergencia;

    /**
     * Situação do item
     * @var string
     */
    private $situacao;

    /**
     * Quantidade do Item
     * @var integer
     */
    private $quantidade;

    /**
     * Observacao do Item
     * @var string
     */
    private $observacao;

    /**
     * Motivo de nova coleta do Item
     * @var string
     */
    private $motivo;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_requiitem_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo do item
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set código do item
     *
     * @param  integer  $codigo  Código do item
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Retorna o codigo da requisição
     * @return int|null
     */
    public function getRequisicao()
    {
        return $this->requisicao;
    }

    /**
     * Set código da requisicao
     *
     * @param  integer  $codigo  Código da requisicao
     *
     * @return  self
     */
    public function setRequisicao($requisicao)
    {
        $this->requisicao = $requisicao;

        return $this;
    }

    /**
     * Retorna a data de entrega
     * @return DBDate|null
     */
    public function getDataEntrega()
    {
        return $this->dataEntrega;
    }

    /**
     * Set a data de entrega
     *
     * @param  DBDate  $dataEntrega  Data de entrega
     *
     * @return  self
     */
    public function setDataEntrega($dataEntrega)
    {
        $this->dataEntrega = $dataEntrega;

        return $this;
    }

    /**
     * Retorna a data de cadastro
     * @return DBDate|null
     */
    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    /**
     * Set a data de cadastro
     *
     * @param  DBDate  $dataCadastro  Data de cadastro
     *
     * @return  self
     */
    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $dataCadastro;

        return $this;
    }

    /**
     * Retorna a hora de cadastro
     * @return string|null
     */
    public function getHoraCadastro()
    {
        return $this->horaCadastro;
    }

    /**
     * Set a hora de cadastro
     *
     * @param  string  $horaCadastro  Hora de cadastro
     *
     * @return  self
     */
    public function setHoraCadastro($horaCadastro)
    {
        $this->horaCadastro = $horaCadastro;

        return $this;
    }

    /**
     * Retorna código do setor
     * @return integer|null
     */
    public function getCodigoSetor()
    {
        return $this->codigoSetor;
    }

    /**
     * Set o codigo do setor
     *
     * @param  integer  $codigoSetor  codigo do setor
     *
     * @return  self
     */
    public function setCodigoSetor($codigoSetor)
    {
        $this->codigoSetor = $codigoSetor;

        return $this;
    }

    /**
     * Retorna o código que indica emergencia
     * @return integer|null
     */
    public function getEmergencia()
    {
        return $this->emergencia;
    }

    /**
     * Set código que indica emergencia
     *
     * @param  integer  $emergencia indica emergencia.
     *
     * @return  self
     */
    public function setEmergencia($emergencia)
    {
        $this->emergencia = $emergencia;

        return $this;
    }

    /**
     * Retorna a situação
     * @return string|null
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * Set situacao
     *
     * @param  string  $situacao.
     *
     * @return  self
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;

        return $this;
    }

    /**
     * Retorna quantidade
     * @return integer|null
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set quantidade
     *
     * @param  integer  $quantidade.
     *
     * @return  self
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Retorna observacao
     * @return string|null
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Set observacao
     *
     * @param  string  $observacao.
     *
     * @return  self
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * Retorna motivo
     * @return string|null
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set motivo
     *
     * @param  string  $motivo.
     *
     * @return  self
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * @param array $state
     * @return ItemRequisicao
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $itemRequisicao = new self();

        if (array_key_exists('la21_i_codigo', $state)) {
            $itemRequisicao->setCodigo((int)$state['la21_i_codigo']);
        }

        if (array_key_exists('la21_i_requisicao', $state)) {
            $itemRequisicao->setRequisicao(((int)$state['la21_i_requisicao']));
        }

        if (array_key_exists('la21_d_entrega', $state)) {
            $itemRequisicao->setDataEntrega(new Date($state['la21_d_entrega']));
        }

        if (array_key_exists('la21_d_data', $state)) {
            $itemRequisicao->setDataCadastro(new Date($state['la21_d_data']));
        }

        if (array_key_exists('la21_c_hora', $state)) {
            $itemRequisicao->setHoraCadastro($state['la21_c_hora']);
        }

        if (array_key_exists('la21_i_setorexame', $state)) {
            $itemRequisicao->setCodigoSetor((int)$state['la21_i_setorexame']);
        }

        if (array_key_exists('la21_i_emergencia', $state)) {
            $itemRequisicao->setEmergencia((int)$state['la21_i_emergencia']);
        }

        if (array_key_exists('la21_c_situacao', $state)) {
            $itemRequisicao->setSituacao($state['la21_c_situacao']);
        }

        if (array_key_exists('la21_i_quantidade', $state)) {
            $itemRequisicao->setQuantidade((int)$state['la21_i_quantidade']);
        }

        if (array_key_exists('la21_observacao', $state)) {
            $itemRequisicao->setObservacao($state['la21_observacao']);
        }

        if (array_key_exists('la21_motivonovacoleta', $state)) {
            $itemRequisicao->setMotivo($state['la21_motivonovacoleta']);
        }

        return $itemRequisicao;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
        'la21_i_codigo' => $this->getCodigo(),
        'la21_i_requisicao' => $this->getRequisicao(),
        'la21_d_entrega' => $this->getDataEntrega(),
        'la21_d_data' => $this->getDataCadastro(),
        'la21_c_hora' => $this->getHoraCadastro(),
        'la21_i_setorexame' => $this->getCodigoSetor(),
        'la21_i_emergencia' => $this->getEmergencia(),
        'la21_c_situacao' => $this->getSituacao(),
        'la21_i_quantidade' => $this->getQuantidade(),
        'la21_observacao' => $this->getQuantidade(),
        'la21_motivonovacoleta' => $this->getMotivo()
        );

        return $retorno;
    }
}
