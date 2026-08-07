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
class ColetaItem
{
    /**
     * Código da tabela
     * @var integer
     */
    private $codigo;

    /**
     * Codigo do usuário
     * @var integer
     */
    private $usuario;

    /**
     * Codigo do item
     * @var integer
     */
    private $item;

    /**
     * Data
     * @var DBDate|null
     */
    private $data;

    /**
     * Hora
     * @var string
     */
    private $hora;

    /**
     * Avisa paciente
     * @var integer
     */
    private $avisaPaciente;

    /**
     * Hora de entrega
     * @var string
     */
    private $horaEntrega;

    /**
     * Data de entrega
     * @var DBDate|null
     */
    private $dataEntrega;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_coletaitem_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Get the value of codigo
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get the value of usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get the value of item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set the value of item
     *
     * @return  self
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of hora
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set the value of hora
     *
     * @return  self
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get the value of avisaPaciente
     */
    public function getAvisaPaciente()
    {
        return $this->avisaPaciente;
    }

    /**
     * Set the value of avisaPaciente
     *
     * @return  self
     */
    public function setAvisaPaciente($avisaPaciente)
    {
        $this->avisaPaciente = $avisaPaciente;

        return $this;
    }

    /**
     * Get the value of horaEntrega
     */
    public function getHoraEntrega()
    {
        return $this->horaEntrega;
    }

    /**
     * Set the value of horaEntrega
     *
     * @return  self
     */
    public function setHoraEntrega($horaEntrega)
    {
        $this->horaEntrega = $horaEntrega;

        return $this;
    }

    /**
     * Get the value of dataEntrega
     */
    public function getDataEntrega()
    {
        return $this->dataEntrega;
    }

    /**
     * Set the value of dataEntrega
     *
     * @return  self
     */
    public function setDataEntrega($dataEntrega)
    {
        $this->dataEntrega = $dataEntrega;

        return $this;
    }

    /**
     * @param array $state
     * @return ColetaItem
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $coletaItem = new self();

        if (array_key_exists('la32_i_codigo', $state)) {
            $coletaItem->setCodigo((int)$state['la32_i_codigo']);
        }

        if (array_key_exists('la32_i_usuario', $state)) {
            $coletaItem->setUsuario(((int)$state['la32_i_usuario']));
        }

        if (array_key_exists('la32_i_requiitem', $state)) {
            $coletaItem->setItem((int)$state['la32_i_requiitem']);
        }

        if (array_key_exists('la32_d_data', $state)) {
            $coletaItem->setData(new Date($state['la32_d_data']));
        }

        if (array_key_exists('la32_c_hora', $state)) {
            $coletaItem->setHora($state['la32_c_hora']);
        }

        if (array_key_exists('la32_i_avisapaciente', $state)) {
            $coletaItem->setAvisaPaciente((int)$state['la32_i_avisapaciente']);
        }

        if (array_key_exists('la32_c_horaentrega', $state)) {
            $coletaItem->setHoraEntrega((int)$state['la32_c_horaentrega']);
        }

        if (array_key_exists('la32_d_entrega', $state)) {
            $coletaItem->setDataEntrega($state['la32_d_entrega']);
        }

        return $coletaItem;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la32_i_codigo' => $this->getCodigo(),
            'la32_i_usuario' => $this->getUsuario(),
            'la32_i_requiitem' => $this->getItem(),
            'la32_d_data' => $this->getData(),
            'la32_c_hora' => $this->getHora(),
            'la32_i_avisapaciente' => $this->getAvisaPaciente(),
            'la32_c_horaentrega' => $this->getHoraEntrega(),
            'la32_d_entrega' => $this->getDataEntrega()
        );

        return $retorno;
    }
}
