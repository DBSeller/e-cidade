<?php

namespace ECidade\Configuracao\Workflow\Interfaces;

interface Acao
{
    public function run();
    public function validate();
}
