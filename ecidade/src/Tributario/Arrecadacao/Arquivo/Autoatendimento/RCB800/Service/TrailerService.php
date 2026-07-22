<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter\TrailerConverter;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Layout\Trailer as LayoutTrailer;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Trailer as TrailerEntity;
use ECidade\Tributario\Arrecadacao\Repository\Convenio as ConvenioRepository;

final class TrailerService extends Service
{
    private $dataBase;

    public function __construct(DataBase $dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function execute($quantidade)
    {
        
        $dataGeracao      = new DateTime();
        $layoutTrailer    = new LayoutTrailer();
        $trailerConverter = new TrailerConverter($layoutTrailer);
        $trailer          = new TrailerEntity();

        $trailer->setSequencial($quantidade + 2);
        $trailer->setQuantidade($quantidade);

        return $trailerConverter->build($trailer);        
    }
}
