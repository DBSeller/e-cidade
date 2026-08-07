<?php

namespace ECidade\Financeiro\Tesouraria\Models;

/**
 * Class LinhaTefProcessado
 * @package ECidade\Financeiro\Tesouraria\Models
 */
class LinhaTefProcessado
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $arquivoTefId;
    /**
     * @var integer
     */
    private $codigoLancamento;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return LinhaTefProcessado
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArquivoTefId()
    {
        return $this->arquivoTefId;
    }

    /**
     * @param mixed $arquivoTefId
     * @return LinhaTefProcessado
     */
    public function setArquivoTefId($arquivoTefId)
    {
        $this->arquivoTefId = $arquivoTefId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoLancamento()
    {
        return $this->codigoLancamento;
    }

    /**
     * @param mixed $codigoLancamento
     * @return LinhaTefProcessado
     */
    public function setCodigoLancamento($codigoLancamento)
    {
        $this->codigoLancamento = $codigoLancamento;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('id', $state)) {
            $self->setId($state['id']);
        }
        if (array_key_exists('arquivo_tef_id', $state)) {
            $self->setArquivoTefId($state['arquivo_tef_id']);
        }
        if (array_key_exists('conlancam_id', $state)) {
            $self->setCodigoLancamento($state['conlancam_id']);
        }

        return $self;
    }
}
