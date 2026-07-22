<?php
namespace ECidade\Tributario\Arrecadacao\Entity;

class Inscricao extends Contribuinte 
{
    public function getInscricao()
    {
        return parent::getIdentificador();
    }

    public function getTipo()
    {
        return self::INSCRICAO;
    }
}