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

namespace ECidade\Educacao\Escola\Mapper;

use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;

/**
 * Class AvaliacaoPorAreaConhecimetno
 * @package ECidade\Educacao\Escola\Mapper
 */
class AvaliacaoPorAreaConhecimento
{
    /**
     * @var AreaProcedimentoAvaliacao
     */
    private $areaProcedimentoAvaliacao;

    /**
     * @var mixed
     */
    private $avaliacao;
    /**
     * @var boolean
     */
    private $amparado = false;
    /**
     * @var string
     */
    private $resultadoAvaliacao;

    /**
     * @param AreaProcedimentoAvaliacao $avaliacao
     * @return $this
     */
    public function setAreaProcedimentoAvaliacao(AreaProcedimentoAvaliacao $avaliacao)
    {
        $this->areaProcedimentoAvaliacao = $avaliacao;
        return $this;
    }

    /**
     * @return AreaProcedimentoAvaliacao
     */
    public function getAreaProcedimentoAvaliacao()
    {
        return $this->areaProcedimentoAvaliacao;
    }

    /**
     * @return AvaliacaoPorAreaConhecimento
     */
    public function getAvaliacao()
    {
        return $this->avaliacao;
    }

    /**
     * Se avaliado por parecer ou nivel, é uma string, por nota, um int/float
     * @param mixed $avaliacao
     * @return AvaliacaoPorAreaConhecimento
     */
    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
        return $this;
    }

    /**
     * @param $amparado
     * @return AvaliacaoPorAreaConhecimento
     */
    public function setAmparado($amparado)
    {
        $this->amparado = $amparado;

        if ($this->amparado) {
            $this->resultadoAvaliacao = 'A';
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAmparado()
    {
        return $this->amparado;
    }

    /**
     * @return string
     */
    public function getResultadoAvaliacao()
    {
        return $this->resultadoAvaliacao;
    }

    /**
     * @param string $resultado
     * @return AvaliacaoPorAreaConhecimento
     */
    public function setResultadoAvaliacao($resultado)
    {
        $this->resultadoAvaliacao = $resultado;
        return $this;
    }
}
