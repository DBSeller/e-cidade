<?php

namespace ECidade\Configuracao\Api\Models;

use DateTime;
use Exception;

class ApiCliente
{
    /**
     * @var integer
     */
    private $sequencial;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $chave;
    /**
     * @var DateTime
     */
    private $ultimaUtilizacao;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
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
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return string
     */
    public function getChave()
    {
        return $this->chave;
    }

    /**
     * @param string $chave
     */
    public function setChave($chave)
    {
        $this->chave = $chave;
    }

    /**
     * @return DateTime
     */
    public function getUltimaUtilizacao()
    {
        return $this->ultimaUtilizacao;
    }

    /**
     * @param DateTime $ultimaUtilizacao
     */
    public function setUltimaUtilizacao($ultimaUtilizacao)
    {
        $this->ultimaUtilizacao = $ultimaUtilizacao;
    }

    /**
     * @param array $state
     * @return ApiCliente
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('db172_sequencial', $state)) {
            $self->setSequencial($state['db172_sequencial']);
        }
        if (array_key_exists('db172_nome', $state)) {
            $self->setNome($state['db172_nome']);
        }
        if (array_key_exists('db172_descricao', $state)) {
            $self->setDescricao($state['db172_descricao']);
        }
        if (array_key_exists('db172_chave', $state)) {
            $self->setChave($state['db172_chave']);
        }
        if (array_key_exists('db172_ultima_utilizacao', $state)) {
            $timestamp = $state['db172_ultima_utilizacao'];
            if (!is_null($timestamp)) {
                $ultimaAtualizacao = new DateTime($timestamp);
                $self->setUltimaUtilizacao($ultimaAtualizacao);
            }
        }
        return $self;
    }
}
