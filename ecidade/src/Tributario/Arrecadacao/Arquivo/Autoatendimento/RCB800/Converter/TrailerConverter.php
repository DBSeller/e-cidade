<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Trailer;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Enum\TipoRegistro;
use ECidade\Tributario\Library\Entity;

final class TrailerConverter extends Converter
{
    const IDENTIFICACAO_ARQUIVO = "RCB800";

    /**
     * @param Entity $entity
     * @return string
     */
    public function build(Entity $entity)
    {
        $size   = $this->layout->getSize(Trailer::TIPO_REGISTRO_TRAILER);
        $header = TipoRegistro::TRAILER;
        
        $size = $this->layout->getSize(Trailer::QUANTIDADE_REGISTROS);
        $header .= substr(str_pad($entity->getQuantidade(),                $size, '0', STR_PAD_LEFT), ($size * -1));

        //Espaços em branco
        $size         = $this->layout->getSize(Trailer::RESERVADO);
        $defaultValue = $this->layout->getDefault(Trailer::RESERVADO);
        $header      .= str_repeat($defaultValue, $size);
       
        $size         = $this->layout->getSize(Trailer::SEQUENCIAL);
        $header      .= substr(str_pad($entity->getSequencial(),           $size, '0', STR_PAD_LEFT), ($size * -1));

        return $header;
    }
}
