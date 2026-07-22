<?php

namespace App\Domain\Saude\Ambulatorial\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Ambulatorial\Models\Microarea;

class FamiliaMicroareaController
{
    public function getMicroareas()
    {
        return new DBJsonResponse(
            Microarea::orderBy('sd34_v_descricao')->get(['sd34_i_codigo as id', 'sd34_v_descricao as descricao'])
        );
    }
}
