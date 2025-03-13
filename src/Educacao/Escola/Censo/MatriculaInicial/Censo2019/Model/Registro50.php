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

class Registro50
{
    const TIPO_REGISTRO = '50';
    protected $tipoRegistro = self::TIPO_REGISTRO;
    protected $codigoInepEscola;
    protected $codigoPessoa;
    protected $codigoInep;
    protected $codigoTurma;
    protected $codigoTurmaInep;
    protected $funcaoExerce;
    protected $regimeContratacao;
    protected $codigo1;
    protected $codigo2;
    protected $codigo3;
    protected $codigo4;
    protected $codigo5;
    protected $codigo6;
    protected $codigo7;
    protected $codigo8;
    protected $codigo9;
    protected $codigo10;
    protected $codigo11;
    protected $codigo12;
    protected $codigo13;
    protected $codigo14;
    protected $codigo15;
    private $eletivas = null;
    private $librasUnidadeCurricular = null;
    private $lingaIndigena = null;
    private $linguaEspanhol = null;
    private $linguaFrances = null;
    private $linguaOutra = null;
    private $projetoVida = null;
    private $trilhaAprofundamento = null;

    /**
     * @return string
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * @param string $tipoRegistro
     * @return Registro50
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;
        return $this;
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
     * @return Registro50
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
     * @return Registro50
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
     * @return Registro50
     */
    public function setCodigoInep($codigoInep)
    {
        $this->codigoInep = $codigoInep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoTurma()
    {
        return $this->codigoTurma;
    }

    /**
     * @param mixed $codigoTurma
     * @return Registro50
     */
    public function setCodigoTurma($codigoTurma)
    {
        $this->codigoTurma = $codigoTurma;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoTurmaInep()
    {
        return $this->codigoTurmaInep;
    }

    /**
     * @param mixed $codigoTurmaInep
     * @return Registro50
     */
    public function setCodigoTurmaInep($codigoTurmaInep)
    {
        $this->codigoTurmaInep = $codigoTurmaInep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFuncaoExerce()
    {
        return $this->funcaoExerce;
    }

    /**
     * @param mixed $funcaoExerce
     * @return Registro50
     */
    public function setFuncaoExerce($funcaoExerce)
    {
        $this->funcaoExerce = $funcaoExerce;
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
     * @return Registro50
     */
    public function setRegimeContratacao($regimeContratacao)
    {
        $this->regimeContratacao = $regimeContratacao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo1()
    {
        return $this->codigo1;
    }

    /**
     * @param mixed $codigo1
     * @return Registro50
     */
    public function setCodigo1($codigo1)
    {
        $this->codigo1 = $codigo1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo2()
    {
        return $this->codigo2;
    }

    /**
     * @param mixed $codigo2
     * @return Registro50
     */
    public function setCodigo2($codigo2)
    {
        $this->codigo2 = $codigo2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo3()
    {
        return $this->codigo3;
    }

    /**
     * @param mixed $codigo3
     * @return Registro50
     */
    public function setCodigo3($codigo3)
    {
        $this->codigo3 = $codigo3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo4()
    {
        return $this->codigo4;
    }

    /**
     * @param mixed $codigo4
     * @return Registro50
     */
    public function setCodigo4($codigo4)
    {
        $this->codigo4 = $codigo4;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo5()
    {
        return $this->codigo5;
    }

    /**
     * @param mixed $codigo5
     * @return Registro50
     */
    public function setCodigo5($codigo5)
    {
        $this->codigo5 = $codigo5;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo6()
    {
        return $this->codigo6;
    }

    /**
     * @param mixed $codigo6
     * @return Registro50
     */
    public function setCodigo6($codigo6)
    {
        $this->codigo6 = $codigo6;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo7()
    {
        return $this->codigo7;
    }

    /**
     * @param mixed $codigo7
     * @return Registro50
     */
    public function setCodigo7($codigo7)
    {
        $this->codigo7 = $codigo7;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo8()
    {
        return $this->codigo8;
    }

    /**
     * @param mixed $codigo8
     * @return Registro50
     */
    public function setCodigo8($codigo8)
    {
        $this->codigo8 = $codigo8;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo9()
    {
        return $this->codigo9;
    }

    /**
     * @param mixed $codigo9
     * @return Registro50
     */
    public function setCodigo9($codigo9)
    {
        $this->codigo9 = $codigo9;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo10()
    {
        return $this->codigo10;
    }

    /**
     * @param mixed $codigo10
     * @return Registro50
     */
    public function setCodigo10($codigo10)
    {
        $this->codigo10 = $codigo10;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo11()
    {
        return $this->codigo11;
    }

    /**
     * @param mixed $codigo11
     * @return Registro50
     */
    public function setCodigo11($codigo11)
    {
        $this->codigo11 = $codigo11;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo12()
    {
        return $this->codigo12;
    }

    /**
     * @param mixed $codigo12
     * @return Registro50
     */
    public function setCodigo12($codigo12)
    {
        $this->codigo12 = $codigo12;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo13()
    {
        return $this->codigo13;
    }

    /**
     * @param mixed $codigo13
     * @return Registro50
     */
    public function setCodigo13($codigo13)
    {
        $this->codigo13 = $codigo13;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo14()
    {
        return $this->codigo14;
    }

    /**
     * @param mixed $codigo14
     * @return Registro50
     */
    public function setCodigo14($codigo14)
    {
        $this->codigo14 = $codigo14;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigo15()
    {
        return $this->codigo15;
    }

    /**
     * @param mixed $codigo15
     * @return Registro50
     */
    public function setCodigo15($codigo15)
    {
        $this->codigo15 = $codigo15;
        return $this;
    }

    public function toArray()
    {
        return array(
        "tipoRegistro" => $this->getTipoRegistro(),
        "codigoInepEscola" => $this->getCodigoInepEscola(),
        "codigoPessoa" => $this->getCodigoPessoa(),
        "codigoInep" => $this->getCodigoInep(),
        "codigoTurma" => $this->getCodigoTurma(),
        "codigoTurmaInep" => $this->getCodigoTurmaInep(),
        "funcaoExerce" => $this->getFuncaoExerce(),
        "regimeContratacao" => $this->getRegimeContratacao(),
        "codigo1" => $this->getCodigo1(),
        "codigo2" => $this->getCodigo2(),
        "codigo3" => $this->getCodigo3(),
        "codigo4" => $this->getCodigo4(),
        "codigo5" => $this->getCodigo5(),
        "codigo6" => $this->getCodigo6(),
        "codigo7" => $this->getCodigo7(),
        "codigo8" => $this->getCodigo8(),
        "codigo9" => $this->getCodigo9(),
        "codigo10" => $this->getCodigo10(),
        "codigo11" => $this->getCodigo11(),
        "codigo12" => $this->getCodigo12(),
        "codigo13" => $this->getCodigo13(),
        "codigo14" => $this->getCodigo14(),
        "codigo15" => $this->getCodigo15(),
        "eletivas" => null,
        "librasUnidadeCurricular" => null,
        "lingaIndigena" => null,
        "linguaEspanhol" => null,
        "linguaFrances" => null,
        "linguaOutra" => null,
        "projetoVida" => null,
        "trilhaAprofundamento" => null
        );
    }
}
