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
use App\Domain\Financeiro\Planejamento\Requests\SalvarAreaResultadoRequest;
use App\Domain\Financeiro\Planejamento\Services\PlanejamentoService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class AreaResultadoController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class AreaResultadoController extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function buscar(Request $request)
    {
        $areas = AreaResultado::orderBy('pl4_titulo')
            ->when($request->has('planejamento'), function ($query) use ($request) {
                $query->where('pl4_planejamento', '=', $request->get('planejamento'));
            })
            ->get();

        return new DBJsonResponse($areas, 'Áreas de Resultado encontradas.');
    }


    public function salvar(SalvarAreaResultadoRequest $request, PlanejamentoService $service)
    {
        $msg = 'Área de Resultado adicionada ao planejamento.';
        if ($request->has('pl4_codigo') && $request->get('pl4_codigo') != '') {
            $msg = 'Alterações efetuada com sucesso.';
        }

        return new DBJsonResponse($service->salvarAreaResultado($request)->toArray(), $msg);
    }

    /**
     * Remove a área de resultado
     * @param Request $request
     * @param PlanejamentoService $service
     * @return DBJsonResponse
     * @throws Exception
     */
    public function delete(Request $request, PlanejamentoService $service)
    {
        if ($request->has('pl4_codigo') && $request->get('pl4_codigo') === '') {
            throw new Exception("Informe o código da Área que deseja excluir.", 406);
        }
        $service->removerAreaResultado($request->get('pl4_codigo'));
        return new DBJsonResponse([], "Área de Resultado removido com sucesso.");
    }
}
