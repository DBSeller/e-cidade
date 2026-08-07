<?php

namespace ECidade\RecursosHumanos\ESocial\Entity;

use Avaliacao;
use Exception;

class AdmissaoPreliminar
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $cgm;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var int
     */
    private $avaliacaoGrupoResposta;

    /**
     * @var Avaliacao
     */
    private $avaliacao;

    /**
     * @param array $state
     * @return AdmissaoPreliminar
     */
    public static function fromState(array $state)
    {
        $admissaoPreliminar = new self();

        if (array_key_exists('eso18_sequencial', $state)) {
            $admissaoPreliminar->setSequencial($state['eso18_sequencial']);
        }
        if (array_key_exists('eso18_avaliacaogruporesposta', $state)) {
            $admissaoPreliminar->setAvaliacaoGrupoResposta($state['eso18_avaliacaogruporesposta']);
        }
        if (array_key_exists('eso18_cgm', $state)) {
            $admissaoPreliminar->setCgm($state['eso18_cgm']);
        }
        if (array_key_exists('eso18_cpf', $state)) {
            $admissaoPreliminar->setCpf($state['eso18_cpf']);
        }

        return $admissaoPreliminar;
    }

    /**
     * Carrega a avaliação associada a esse registro
     *
     * @return $this
     */
    public function withAvaliacao()
    {
        /** @todo Carregar dados da tabela avaliacaogruporesposta */
        throw new Exception('AdmissaoPreliminar::withAvaliacao() não implementado!');

        return $this;
    }

    /**
     * @return  int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param   int  $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return  int
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param   int  $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return  string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param   string  $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return  int
     */
    private function getAvaliacaoGrupoResposta()
    {
        return $this->avaliacaoGrupoResposta;
    }

    /**
     * @param   int  $avaliacaoGrupoResposta
     */
    private function setAvaliacaoGrupoResposta($avaliacaoGrupoResposta)
    {
        $this->avaliacaoGrupoResposta = $avaliacaoGrupoResposta;
    }

    /**
     * @return  Avaliacao
     */
    public function getAvaliacao()
    {
        return $this->avaliacao;
    }

    /**
     * @param   Avaliacao  $avaliacao
     */
    public function setAvaliacao(Avaliacao $avaliacao)
    {
        $this->setAvaliacaoGrupoResposta($avaliacao->getCodigo());

        $this->avaliacao = $avaliacao;
    }
}
