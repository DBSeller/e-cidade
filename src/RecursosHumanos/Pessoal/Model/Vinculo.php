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


use Instituicao;
use InstituicaoRepository;

class Vinculo
{
    private static $tipoVinculo = array(
        "A" => "Ativo",
        "I" => "Inativo",
        "P" => "Pensionista",
    );

    private static $regimes = array(
        "1" => "Estatutário",
        "2" => "CLT",
        "3" => "Extra Quadro",
    );

    private $codigo;
    private $descricao;
    private $codigoRegime;
    private $vinculo;

    /**
     * @var Instituicao
     */
    private $instituicao;
    private $naturezaRegime;

    /**
     * @param array $state
     * @return Vinculo
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('rh30_codreg', $state)) {
            $self->setCodigo($state['rh30_codreg']);
        }
        if (array_key_exists('rh30_descr', $state)) {
            $self->setDescricao($state['rh30_descr']);
        }
        if (array_key_exists('rh30_regime', $state)) {
            $self->setCodigoRegime($state['rh30_regime']);
        }
        if (array_key_exists('rh30_vinculo', $state)) {
            $self->setVinculo($state['rh30_vinculo']);
        }
        if (array_key_exists('rh30_instit', $state)) {
            $self->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($state['rh30_instit']));
        }
        if (array_key_exists('rh30_naturezaregime', $state)) {
            $self->setNaturezaRegime($state['rh30_naturezaregime']);
        }

        return $self;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return Vinculo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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
     * @return Vinculo
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoRegime()
    {
        return $this->codigoRegime;
    }

    /**
     * @param mixed $codigoRegime
     * @return Vinculo
     */
    public function setCodigoRegime($codigoRegime)
    {
        $this->codigoRegime = $codigoRegime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVinculo()
    {
        return $this->vinculo;
    }

    /**
     * @param mixed $vinculo
     * @return Vinculo
     */
    public function setVinculo($vinculo)
    {
        $this->vinculo = $vinculo;
        return $this;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     * @return Vinculo
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNaturezaRegime()
    {
        return $this->naturezaRegime;
    }

    /**
     * @param mixed $naturezaRegime
     * @return Vinculo
     */
    public function setNaturezaRegime($naturezaRegime)
    {
        $this->naturezaRegime = $naturezaRegime;
        return $this;
    }

}