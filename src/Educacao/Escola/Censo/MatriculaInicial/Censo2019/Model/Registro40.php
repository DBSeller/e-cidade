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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model;


class Registro40
{
    const TIPO_REGISTRO = '40';
    protected $tipoRegistro = self::TIPO_REGISTRO;

    protected $codigoInepEscola;
    protected $codigoPessoa;
    protected $codigoInep;
    protected $cargo = 2;
    protected $criterioAcesso;
    protected $especificacaoCriterioAcesso;
    protected $regimeContratacao;

    /**
     * @return string
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @return mixed
     */
    public function getCodigoInepEscola()
    {
        return $this->codigoInepEscola;
    }

    /**
     * @param mixed $codigoInepEscola
     * @return Registro40
     */
    public function setCodigoInepEscola($codigoInepEscola)
    {
        $this->codigoInepEscola = $codigoInepEscola;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoPessoa()
    {
        return $this->codigoPessoa;
    }

    /**
     * @param mixed $codigoPessoa
     * @return Registro40
     */
    public function setCodigoPessoa($codigoPessoa)
    {
        $this->codigoPessoa = $codigoPessoa;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoInep()
    {
        return $this->codigoInep;
    }

    /**
     * @param mixed $codigoInep
     * @return Registro40
     */
    public function setCodigoInep($codigoInep)
    {
        $this->codigoInep = $codigoInep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     * @return Registro40
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriterioAcesso()
    {
        return $this->criterioAcesso;
    }

    /**
     * @param mixed $criterioAcesso
     * @return Registro40
     */
    public function setCriterioAcesso($criterioAcesso)
    {
        $this->criterioAcesso = $criterioAcesso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEspecificacaoCriterioAcesso()
    {
        return $this->especificacaoCriterioAcesso;
    }

    /**
     * @param mixed $especificacaoCriterioAcesso
     * @return Registro40
     */
    public function setEspecificacaoCriterioAcesso($especificacaoCriterioAcesso)
    {
        $this->especificacaoCriterioAcesso = $especificacaoCriterioAcesso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegimeContratacao()
    {
        return $this->regimeContratacao;
    }

    /**
     * @param mixed $regimeContratacao
     * @return Registro40
     */
    public function setRegimeContratacao($regimeContratacao)
    {
        $this->regimeContratacao = $regimeContratacao;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            "tipoRegistro" => $this->getTipoRegistro(),
            "codigoInepEscola" => $this->getCodigoInepEscola(),
            "codigoPessoa" => $this->getCodigoPessoa(),
            "codigoInep" => $this->getCodigoInep(),
            "cargo" => $this->getCargo(),
            "criterioAcesso" => $this->getCriterioAcesso(),
            "especificacaoCriterioAcesso" => $this->getEspecificacaoCriterioAcesso(),
            "regimeContratacao" => $this->getRegimeContratacao(),
        );
    }
}
