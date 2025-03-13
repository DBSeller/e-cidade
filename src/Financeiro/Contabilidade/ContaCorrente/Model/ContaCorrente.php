<?php
/**
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
namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Model;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Atributo as AtributoModel;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Repository\Atributo;

/**
 * Class ContaCorrente
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Model
 */
class ContaCorrente
{

    /**
     * codigo dos atributos
     * @var integer
     */
    private $codigo;

    /**
     * NOme do atributo
     * @var string
     */
    private $nome;

    /**
     * @var Atributo[]
     */
    private $atributos = array();

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Retorna todos os atributos do conta corrente
     * @return AtributoModel[]
     */
    public function getAtributos()
    {

        if (count($this->atributos) == 0) {
            $this->atributos = Atributo::getByContaCorrente($this);
        }
        return $this->atributos;
    }


    /**
     * Retorna todos os atributos do conta corrente da MSC
     * @return AtributoModel[]
     */
    public function getAtributosMSC($conplano, $reduzido = "")
    {

        if (count($this->atributos) == 0) {
            $this->atributos = Atributo::getByConPlano($conplano, $reduzido);
        }
        return $this->atributos;
    }


    /**
     * @param Atributo[] $atributos
     */
    public function setAtributos($atributos)
    {
        $this->atributos = $atributos;
    }
}
