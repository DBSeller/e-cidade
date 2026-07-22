<?php

namespace ECidade\Educacao\Secretaria\BNCC\Interfaces;

/**
 * Interface PlanilhaHabilidadeInterface
 * @package ECidade\Educacao\Secretaria\BNCC\Interfaces
 */
interface PlanilhaHabilidadeInterface
{
    public function setLinhas(array $linhas);
    public function processarLinhas();
    public function getFileDump();
}
