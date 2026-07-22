<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Planejamento\Models\AreaResultado;
use App\Domain\Financeiro\Planejamento\Models\Iniciativa;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Vinculos\BuscarProgramaPorAreaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Vinculos\VincularProgramaPorArea;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProgramaPorAreaController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class ProgramaPorAreaController extends Controller
{
    /**
     * Busca todos os programas que não possuem vínculo com as áreas de resultado assim como
     * as que possui com a área com a área informada na request
     * @param BuscarProgramaPorAreaRequest $request
     * @return DBJsonResponse
     */
    public function buscar(BuscarProgramaPorAreaRequest $request)
    {
        $programas = ProgramaEstrategico::orderBy('pl9_orcprograma')
            ->where('pl9_planejamento', '=', $request->get('planejamento'))
            ->with('areasResultado')
            ->get()
            ->filter(function (ProgramaEstrategico $programaEstrategico) use ($request) {

                if ($programaEstrategico->areasResultado->isEmpty()) {
                    return $programaEstrategico;
                }

                // valida se a área vinculada no programa é a mesma
                $areaVinculada = $programaEstrategico
                    ->areasResultado
                    ->filter(function (AreaResultado $areaResultado) use ($request) {
                        if ((int)$areaResultado->pl4_codigo === (int)$request->get('area')) {
                            return true;
                        }
                    })->isEmpty();

                if (!$areaVinculada) {
                    return $programaEstrategico;
                }
            });

        // sei que não faz sentido, mas se tirar o array_values, hora retorna uma array, hora retorna um object
        // não remover array_values
        $programas = array_values($programas->toArray());
        return new DBJsonResponse($programas, 'Programas encontrados.');
    }

    /**
     * @param VincularProgramaPorArea $request
     * @return DBJsonResponse
     */
    public function vincular(VincularProgramaPorArea $request)
    {
        $area = AreaResultado::find($request->get('area'));
        $area->programas()->sync($request->get('programas'));
        return new DBJsonResponse([], 'Programas vinculados.');
    }
}
