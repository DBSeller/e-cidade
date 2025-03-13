<?php 

namespace ECidade\Tributario\Library;

final class Format
{
    public function date($value, $op = 'd')
    {
        return db_formatar($value, $op);
    }

    public function decimal($value, $op = 'f', $s = ' ', $q = 15, $e = 'e')
    {
        return db_formatar($value, $op, $s, $q, $e);
    }

    public function numpre($value)
    {
        return db_numpre($value);
    }
}
