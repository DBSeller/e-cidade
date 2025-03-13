<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 09:04
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model;


class DiretorCensoVO
{
    /**
     * @var integer
     */
    protected $criterioAcessoFuncao;

    /**
     * @var string
     */
    protected $especificacaoCriterioOutros;

    /**
     * @return integer
     */
    public function getCriterioAcessoFuncao()
    {
        return $this->criterioAcessoFuncao;
    }

    /**
     * @param integer $criterioAcessoFuncao
     * @return DiretorCensoVO
     */
    public function setCriterioAcessoFuncao($criterioAcessoFuncao)
    {
        $this->criterioAcessoFuncao = $criterioAcessoFuncao;
        return $this;
    }

    /**
     * @return string
     */
    public function getEspecificacaoCriterioOutros()
    {
        return $this->especificacaoCriterioOutros;
    }

    /**
     * @param string $especificacaoCriterioOutros
     * @return DiretorCensoVO
     */
    public function setEspecificacaoCriterioOutros($especificacaoCriterioOutros)
    {
        $this->especificacaoCriterioOutros = $especificacaoCriterioOutros;
        return $this;
    }

    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('ed254_criterioacessofuncao', $state)) {
            $self->setCriterioAcessoFuncao($state['ed254_criterioacessofuncao']);
        }
        if (array_key_exists('ed254_especificacaocriteriooutros', $state)) {
            $self->setEspecificacaoCriterioOutros($state['ed254_especificacaocriteriooutros']);
        }

        return $self;
    }
}
