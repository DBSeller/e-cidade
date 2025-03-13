<?php

namespace ECidade\Saude\Ambulatorial\Model;

class CgsAuditoria
{

    private $sequencial;
    private $cgs;
    private $usuario;

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function getCgs()
    {
        return $this->cgs;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setCgs($cgs)
    {
        $this->cgs = $cgs;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }


    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('z18_cgs', $state)) {
            $self->setCgs($state['z18_cgs']);
        }
        if (array_key_exists('z18_usuario', $state)) {
            $self->setUsuario($state['z18_usuario']);
        }

        return $self;
    }
}
