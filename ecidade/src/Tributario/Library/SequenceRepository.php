<?php 

namespace ECidade\Tributario\Library;

use ECidade\Tributario\Library\DataBaseRepository;

abstract class SequenceRepository extends DataBaseRepository
{
    abstract function get();
    abstract function next();
}
