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

namespace ECidade\Patrimonial\Patrimonio\Incorporacao\Model;

class MaterialPendenteIncorporacaoModel
{
    /**
     * t12_sequencial
     * @var integer
     */
    private $codigo;

    /**
     * t12_matestoqueinimei
     * @var integer
     */
    private $vinculoEstoque;

    /**
     * t12_servico
     * @var bool
     */
    private $servico = false;

    /**
     * t12_valorunitario
     * @var float
     */
    private $valorUnitario;

    /**
     * quantidade disponivel para baixa
     * @var float
     */
    private $quantidade;

    /**
     * Descrição do material
     * @var string
     */
    private $descricao;

    /**
     * código do material no estoque (matmater)
     * @var integer
     */
    private $codigoMaterial;

    /**
     * Código do departamento de entrada (m70_coddepto)
     * @var integer
     */
    private $codigoDepartamento;

    /**
     * @var integer
     */
    private $empenho;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return int
     */
    public function getVinculoEstoque()
    {
        return $this->vinculoEstoque;
    }

    /**
     * @param int $vinculoEstoque
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setVinculoEstoque($vinculoEstoque)
    {
        $this->vinculoEstoque = $vinculoEstoque;

        return $this;
    }

    /**
     * @return bool
     */
    public function isServico()
    {
        return $this->servico;
    }

    /**
     * @param bool $servico
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setServico($servico)
    {
        $this->servico = $servico;

        return $this;
    }

    /**
     * @return float
     */
    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }

    /**
     * @param float $valorUnitario
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param mixed $quantidade
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoMaterial()
    {
        return $this->codigoMaterial;
    }

    /**
     * @param int $codigoMaterial
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setCodigoMaterial($codigoMaterial)
    {
        $this->codigoMaterial = $codigoMaterial;

        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoDepartamento()
    {
        return $this->codigoDepartamento;
    }

    /**
     * @param int $codigoDepartamento
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setCodigoDepartamento($codigoDepartamento)
    {
        $this->codigoDepartamento = $codigoDepartamento;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEmpenho()
    {
        return $this->empenho;
    }

    /**
     * @param integer $empenho
     * @return MaterialPendenteIncorporacaoModel
     */
    public function setEmpenho($empenho)
    {
        $this->empenho = $empenho;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function __clone()
    {
        $this->codigo = null;
    }
}