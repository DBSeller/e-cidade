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

namespace ECidade\Patrimonial\Patrimonio\Bem\Model;

use DateTime;

class BemPlaca
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $idBem;

    /**
     * @var String
     */
    protected $placa;

    /**
     * @var integer
     */
    protected $placaSequencial;

    /**
     * @var String
     */
    protected $observacao;

    /**
     * @var DateTime
     */
    protected $data;

    /**
     * @var integer
     */
    protected $idUsuario;

    /**
     * Indica se histórico da placa foi excluído
     * @var boolean
     */
    protected $ativo;

    public function __construct($id = null)
    {
        if (!empty($id)) {
            $daoBensPlaca =  new \cl_bensplaca();

            $sql = $daoBensPlaca->sql_query_file($id);
            $postgresResource = $daoBensPlaca->sql_record($sql);

            if (pg_num_rows($postgresResource) > 0) {
                $bemPlaca = pg_fetch_assoc($postgresResource);

                $this->setId($id);
                $this->setIdBem($bemPlaca['t41_bem']);
                $this->setPlaca($bemPlaca['t41_placa']);
                $this->setPlacaSequencial($bemPlaca['t41_placaseq']);
                $this->setObservacao($bemPlaca['t41_obs']);
                $this->setData($bemPlaca['t41_data']);
                $this->setIdUsuario($bemPlaca['t41_usuario']);
                $this->setExcluido($bemPlaca['t41_excluido'] == 't' ? true : false);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdBem()
    {
        return $this->idBem;
    }

    public function setIdBem($idBem)
    {
        $this->idBem = $idBem;
    }

    public function getPlaca()
    {
        return $this->placa;
    }

    public function setPlaca($placa)
    {
        $this->placa = $placa;
    }

    public function getPlacaSequencial()
    {
        return $this->placaSequencial;
    }

    public function setPlacaSequencial($placaSequencial)
    {
        $this->placaSequencial = $placaSequencial;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }

    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function isExcluido()
    {
        return $this->ativo;
    }

    public function setExcluido($ativo)
    {
        $this->ativo = $ativo;
    }

    public static function fromState(array $state)
    {
        $bemPlaca = new self();

        if (array_key_exists('t41_codigo', $state)) {
            $bemPlaca->setId((int) $state['t41_codigo']);
        }

        if (array_key_exists('t41_bem', $state)) {
            $bemPlaca->setIdBem((int) $state['t41_bem']);
        }

        if (array_key_exists('t41_placa', $state)) {
            $bemPlaca->setPlaca($state['t41_placa']);
        }

        if (array_key_exists('t41_placaseq', $state)) {
            $bemPlaca->setPlacaSequencial((int) $state['t41_placaseq']);
        }

        if (array_key_exists('t41_obs', $state)) {
            $bemPlaca->setObservacao($state['t41_obs']);
        }

        if (array_key_exists('t41_data', $state)) {
            $bemPlaca->setData(new DateTime($state['t41_data']));
        }

        if (array_key_exists('t41_usuario', $state)) {
            $bemPlaca->setIdUsuario((int )$state['t41_usuario']);
        }

        if (array_key_exists('t41_excluido', $state)) {
            $bemPlaca->setExcluido($state['t41_excluido'] == 't' ? true : false);
        }

        return $bemPlaca;
    }

    public function toArray()
    {
        return [
            't41_codigo' => $this->getId(),
            't41_bem' => $this->getIdBem(),
            't41_placa' => $this->getPlaca(),
            't41_placaseq' => $this->getPlacaSequencial(),
            't41_obs' => $this->getObservacao(),
            't41_data' => !is_null($this->getData()) ? $this->getData()->format('Y-m-d') : null,
            't41_usuario' => $this->getIdUsuario(),
            't41_excluido' => $this->isExcluido() ? 't' : 'f'
        ];
    }
}
