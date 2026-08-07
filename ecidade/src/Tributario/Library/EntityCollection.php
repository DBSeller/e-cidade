<?php

namespace ECidade\Tributario\Library;

use ECidade\Tributario\Library\Collection;
use ECidade\Tributario\Library\ModelCollection;

abstract class EntityCollection extends Collection
{
    protected $modelCollection;

    public function __construct(ModelCollection $modelCollection)
    {
        parent::__construct();        

        $this->size = $modelCollection->count();
        $this->modelCollection = $modelCollection;
    }
}
