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

use Bem;
use DBDate;

class MaterialIncorporadoModel
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var Bem
     */
    private $bem;

    /**
     * @var MaterialPendenteIncorporacaoModel
     */
    private $materialPendenteIncorporado;

    /**
     * @var DBDate
     */
    private $data;

    /**
     * @var boolean
     */
    private $reavaliar = false;

    /**
     * @var float
     */
    private $quantidade;

    private $ativo = true;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return MaterialIncorporadoModel
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return Bem
     */
    public function getBem()
    {
        return $this->bem;
    }

    /**
     * @param Bem $bem
     * @return MaterialIncorporadoModel
     */
    public function setBem(Bem $bem)
    {
        $this->bem = $bem;

        return $this;
    }

    /**
     * @return MaterialPendenteIncorporacaoModel
     */
    public function getMaterialPendenteIncorporado()
    {
        return $this->materialPendenteIncorporado;
    }

    /**
     * @param MaterialPendenteIncorporacaoModel $bemPendenteIncorporado
     * @return MaterialIncorporadoModel
     */
    public function setMaterialPendenteIncorporacao($bemPendenteIncorporado)
    {
        $this->materialPendenteIncorporado = $bemPendenteIncorporado;

        return $this;
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
     * @return MaterialIncorporadoModel
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReavaliar()
    {
        return $this->reavaliar;
    }

    /**
     * @param bool $reavaliar
     * @return MaterialIncorporadoModel
     */
    public function setReavaliar($reavaliar)
    {
        $this->reavaliar = $reavaliar;

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
     * @param float $quantidade
     * @return MaterialIncorporadoModel
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return float
     */
    public function getValorTotal()
    {
        return round(($this->quantidade * $this->materialPendenteIncorporado->getValorUnitario()), 2);
    }
}