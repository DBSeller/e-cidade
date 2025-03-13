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

namespace ECidade\RecursosHumanos\Pessoal\Model;

use Exception;

class Sindicato
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $cnpj;

    /**
     * @var string
     */
    private $razaoSocial;

    /**
     * @var int
     */
    private $mesDataBase;

    /**
     * @param array $state
     * @return Sindicato
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $sindicato = new self();
        if (array_key_exists('rh116_sequencial', $state)) {
            $sindicato->setSequencial((int)$state['rh116_sequencial']);
        }

        if (array_key_exists('rh116_codigo', $state)) {
            $sindicato->setCodigo($state['rh116_codigo']);
        }

        if (array_key_exists('rh116_cnpj', $state)) {
            $sindicato->setCnpj($state['rh116_cnpj']);
        }

        if (array_key_exists('rh116_descricao', $state)) {
            $sindicato->setRazaoSocial($state['rh116_descricao']);
        }

        if (array_key_exists('rh116_mesdatabase', $state)) {
            $sindicato->setMesDataBase($state['rh116_mesdatabase']);
        }

        return $sindicato;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'sequencial' => $this->getSequencial(),
            'codigo' => $this->getCodigo(),
            'razaoSocial' => $this->getRazaoSocial(),
            'cnpj' => $this->getCnpj(),
            'mesDataBase' => $this->getMesDataBase()
        );

        return $retorno;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Sindicato
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return (string)$this->codigo;
    }

    /**
     * @param string $codigo
     * @return Sindicato
     */
    public function setCodigo($codigo)
    {
        $this->codigo = (string)$codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getRazaoSocial()
    {
        return (string)$this->razaoSocial;
    }

    /**
     * @param string $razaoSocial
     * @return Sindicato
     */
    public function setRazaoSocial($razaoSocial)
    {
        $this->razaoSocial = (string)$razaoSocial;
        return $this;
    }

    /**
     * @return string
     */
    public function getCnpj()
    {
        return (string)$this->cnpj;
    }

    /**
     * @param string $cnpj
     * @return Sindicato
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = (string)$cnpj;
        return $this;
    }

    /**
     * @return int
     */
    public function getMesDataBase()
    {
        return (int)$this->mesDataBase;
    }

    /**
     * @param int $mesDataBase
     */
    public function setMesDataBase($mesDataBase)
    {
        $this->mesDataBase = (int)$mesDataBase;
    }
}
