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

/**
 * Atributo de um conta Corrente
 * Class Atributo
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Model
 */
class Atributo
{


    /**
     * Codigo da atributo
     * @var  integer
     *
     */
    protected $codigo;

    /**
     * Nome do Atributo
     * @var string
     */
    protected $nome;

    /**
     * sigla do Atributo
     * @var string
     */
    protected $sigla;

    /**
     * Texto de ajuda do atributo
     * @var string
     */
    protected $ajuda;

    /**
     * Valor Padrao do Atributo
     * @var string
     */
    protected $valorPadrao;

    /**
     * regra para aquisicao dos valores
     * @var string
     */
    protected $regra;

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
    public function getAjuda()
    {
        return $this->ajuda;
    }

    /**
     * @param string $ajuda
     */
    public function setAjuda($ajuda)
    {
        $this->ajuda = $ajuda;
    }

    /**
     * @return string
     */
    public function getValorPadrao()
    {
        return $this->valorPadrao;
    }

    /**
     * @param string $valorPadrao
     */
    public function setValorPadrao($valorPadrao)
    {
        $this->valorPadrao = $valorPadrao;
    }

    /**
     * @return string
     */
    public function getRegra()
    {
        return $this->regra;
    }

    /**
     * @param string $regra
     */
    public function setRegra($regra)
    {
        $this->regra = $regra;
    }

}