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
use App\Domain\Financeiro\Planejamento\Models\Iniciativa;
use App\Domain\Financeiro\Planejamento\Models\ObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Vinculos\BuscarIniciativaPorObjetivoRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Vinculos\VincularIniciativaPorObjetivoRequest;
use App\Http\Controllers\Controller;

/**
 * Class IniciativaPorObjetivoController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class IniciativaPorObjetivoController extends Controller
{
    /**
     * retorna as iniciativas sem objetivos
     * @param BuscarIniciativaPorObjetivoRequest $request
     * @return DBJsonResponse
     */
    public function buscar(BuscarIniciativaPorObjetivoRequest $request)
    {
        $inciativas = Iniciativa::orderBy('pl12_orcprojativ')
            ->where('pl12_programaestrategico', '=', $request->get('programa'))
            ->with('objetivos')
            ->get()
            ->filter(function (Iniciativa $iniciativa) use ($request) {
                if ($iniciativa->objetivos->isEmpty()) {
                    return $iniciativa;
                }

                $has = !$iniciativa
                    ->objetivos->filter(function (ObjetivoProgramaEstrategico $objetivo) use ($request) {
                        if ((int)$objetivo->pl11_codigo === (int)$request->get('objetivo')) {
                            return $objetivo;
                        }
                    })->isEmpty();

                if ($has) {
                    return $iniciativa;
                }
            });
        $inciativas = array_values($inciativas->toArray());
        return new DBJsonResponse($inciativas, 'Iniciativas encontradas.');
    }

    /**
     * @param VincularIniciativaPorObjetivoRequest $request
     * @return DBJsonResponse
     */
    public function vincular(VincularIniciativaPorObjetivoRequest $request)
    {
        $objetivo = ObjetivoProgramaEstrategico::find($request->get('objetivo'));
        $objetivo->iniciativas()->sync($request->get('iniciativas'));
        return new DBJsonResponse([], 'Iniciativas vinculadas.');
    }
}
