<?php

namespace App\Domain\Tributario\ISSQN\Model\AlvaraEventos;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MensagemPadrao
 *
 * @package App\Domain\Tributario\ISSQN\Model\AlvaraEventos
 * @property int q171_codigo
 * @property string q171_descricao
 * @property string q171_mensagem
 */
class MensagemPadrao extends Model
{
    protected $table = 'issqn.mensagempadraoalvaraevento';
    protected $primaryKey = 'q171_codigo';
    public $timestamps = false;

    /**
     * @param int $q171_codigo
     * @return this
     */
    public function setCodigo($q171_codigo)
    {
        $this->q171_codigo = $q171_codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->q171_codigo;
    }

    /**
     * @param string $q171_descricao
     * @return this
     */
    public function setDescricao($q171_descricao)
    {
        $this->q171_descricao = $q171_descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->q171_descricao;
    }

    /**
     * @param string $q171_mensagem
     * @return this
     */
    public function setMensagem($q171_mensagem)
    {
        $this->q171_mensagem = $q171_mensagem;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagem()
    {
        return $this->q171_mensagem;
    }
}
