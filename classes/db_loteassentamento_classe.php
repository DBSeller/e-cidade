<?php

/**
 * Class cl_loteassentamento
 * @property int h24_sequencial
 * @property int h24_lotelancamento
 * @property int h24_assenta
 */
class cl_loteassentamento extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("recursoshumanos.loteassentamento");
    }
}
