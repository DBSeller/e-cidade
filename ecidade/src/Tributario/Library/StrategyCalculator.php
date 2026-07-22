<?php 

namespace ECidade\Tributario\Library;

use ECidade\Tributario\Library\Entity;

interface StrategyCalculator
{
    public function calculate(Entity $entity);
}
