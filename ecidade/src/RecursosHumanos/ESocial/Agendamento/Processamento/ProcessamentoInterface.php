<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

/**
 * Class ProcessamentoInterface
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
interface ProcessamentoInterface
{
    /**
     * ProcessamentoInterface constructor.
     * @param $cgm
     */
    public function __construct($cgm);

    /**
     * @return mixed
     */
    public function processar();
}