<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/02/19
 * Time: 17:41
 */

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Enum;

abstract class TipoRegistro
{
    const HEADER  = 'A';
    const DETALHE = '2';
    const TRAILER = 'Z';
}