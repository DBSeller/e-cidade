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

namespace ECidade\Saude\Laboratorio\Model;

/**
 * Classe para controle dos dados do Laboratório
 * @author Fernando de Oliveira Neto   fernando.neto@dbseller.com.br
 * @package Laboratorio
 */
class Laboratorio
{

    /**
     * Código do laboratório
     * @var integer
     */
    private $codigo;

    /**
     * Tipo do laboratório
     * @var integer
     */
    private $tipo;

    /**
     * Descrição do laboratório
     * @var string
     */
    private $descricao;

    /**
     * Alvara do laboratório
     * @var integer
     */
    private $alvara;

    /**
     * Cnes do laboratório
     * @var double
     */
    private $cnes;

    /**
     * Endereço do laboratório
     * @var string
     */
    private $endereco;

    /**
     * Telefone do laboratório
     * @var double
     */
    private $telefone;

    /**
     * Numero do laboratório
     * @var string
     */
    private $numero;

    /**
     * Turno de atendimento do laboratório
     * @var integer
     */
    private $turnoAtendimento;

    /**
     * Verificar se o laboratório é interfaceado
     * @var bool
     */
    private $interfaceado;

    /**
     * @param string $codigo
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $dao = db_utils::getDao("db_lab_laboratorio_classe");
            $sql = $dao->sql_query_file($codigo);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Retorna o codigo do laboratório
     * @return int|null
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Seta o código do laboratório
     *
     * @param  integer  $codigo Código do laboratório
     *
     * @return  self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Retorna o tipo do laboratório
     * @return int|null
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Seta o tipo do laboratório
     *
     * @param  integer  $tipo Tipo do laboratório
     *
     * @return  self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Retorna a descrição do laboratório
     * @return int|null
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Seta o descrição do laboratório
     *
     * @param  integer  $descricao Descrição do laboratório
     *
     * @return  self
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Retorna a alvará do laboratório
     * @return int|null
     */
    public function getAlvara()
    {
        return $this->alvara;
    }

    /**
     * Seta o alvará do laboratório
     *
     * @param  integer  $alvara Alvará do laboratório
     *
     * @return  self
     */
    public function setAlvara($alvara)
    {
        $this->alvara = $alvara;

        return $this;
    }

    /**
     * Retorna a cnes do laboratório
     * @return int|null
     */
    public function getCnes()
    {
        return $this->cnes;
    }

    /**
     * Seta o cnes do laboratório
     *
     * @param  integer  $cnes Cnes do laboratório
     *
     * @return  self
     */
    public function setCnes($cnes)
    {
        $this->cnes = $cnes;

        return $this;
    }

    /**
     * Retorna a endereco do laboratório
     * @return int|null
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Seta o endereco do laboratório
     *
     * @param  integer  $endereco Endereco do laboratório
     *
     * @return  self
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Retorna a telefone do laboratório
     * @return int|null
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Seta o telefone do laboratório
     *
     * @param  integer  $telefone Telefone do laboratório
     *
     * @return  self
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Retorna a número do laboratório
     * @return int|null
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Seta o número do laboratório
     *
     * @param  integer  $numero Número do laboratório
     *
     * @return  self
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Retorna o turno de atendimento do laboratório
     * @return int|null
     */
    public function getTurnoAtendimento()
    {
        return $this->turnoAtendimento;
    }

    /**
     * Seta o turno de atendimento do laboratório
     *
     * @param  integer  $turnoAtendimento turno de atendimento do laboratório
     *
     * @return  self
     */
    public function setTurnoAtendimento($turnoAtendimento)
    {
        $this->turnoAtendimento = $turnoAtendimento;

        return $this;
    }

    /**
     * Retorna se o laboratório é interfaceado
     * @return int|null
     */
    public function getInterfaceado()
    {
        return $this->interfaceado;
    }

    /**
     * Seta se o laboratório é interfaceado
     *
     * @param  integer  $interfaceado Se o laboratório é interfaceado
     *
     * @return  self
     */
    public function setInterfaceado($interfaceado)
    {
        $this->interfaceado = $interfaceado;

        return $this;
    }

        /**
     * @param array $state
     * @return Laboratorio
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $laboratorio = new self();

        if (array_key_exists('la02_i_codigo', $state)) {
            $laboratorio->setCodigo((int)$state['la02_i_codigo']);
        }

        if (array_key_exists('la02_i_tipo', $state)) {
            $laboratorio->setTipo(((int)$state['la02_i_tipo']));
        }

        if (array_key_exists('la02_c_descr', $state)) {
            $laboratorio->setDescricao($state['la02_c_descr']);
        }

        if (array_key_exists('la02_i_alvara', $state)) {
            $laboratorio->setAlvara((int)$state['la02_i_alvara']);
        }

        if (array_key_exists('la02_i_cnes', $state)) {
            $laboratorio->setCnes((double)$state['la02_i_cnes']);
        }

        if (array_key_exists('la02_c_endereco', $state)) {
            $laboratorio->setEndereco($state['la02_c_endereco']);
        }

        if (array_key_exists('la02_i_telefone', $state)) {
            $laboratorio->setTelefone((double)$state['la02_i_telefone']);
        }

        if (array_key_exists('la02_c_numero', $state)) {
            $laboratorio->setNumero($state['la02_c_numero']);
        }

        if (array_key_exists('la02_i_turnoatend', $state)) {
            $laboratorio->setTurnoAtendimento((int)$state['la02_i_turnoatend']);
        }

        if (array_key_exists('la02_interfaceado', $state)) {
            $laboratorio->setInterfaceado((bool)$state['la02_interfaceado']);
        }

        return $laboratorio;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'la02_i_codigo' => $this->getCodigo(),
            'la02_i_tipo'  => $this->getTipo(),
            'la02_c_descr' => $this->getDescricao(),
            'la02_i_alvara' => $this->getAlvara(),
            'la02_i_cnes' => $this->getCnes(),
            'la02_c_endereco' => $this->getEndereco(),
            'la02_i_telefone' => $this->getTelefone(),
            'la02_c_numero' => $this->getNumero(),
            'la02_i_turnoatend' => $this->getTurnoAtendimento(),
            'la02_interfaceado' => $this->getInterfaceado()
        );

        return $retorno;
    }
}
