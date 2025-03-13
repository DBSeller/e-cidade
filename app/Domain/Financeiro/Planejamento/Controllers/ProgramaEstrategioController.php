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
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\ProgramaEstrategicoIdRequest;
use App\Domain\Financeiro\Planejamento\Services\ProgramaEstrategicoService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class ProgramaEstrategioController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class ProgramaEstrategioController extends Controller
{

    /**
     * @var ProgramaEstrategicoService
     */
    private $service;

    public function __construct(ProgramaEstrategicoService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        return $this->service->buscar($request);
    }

    /**
     * Busca os programas estratégicos para a view de manutenção com possibilidade de executar filtros
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function buscar(Request $request)
    {
        $programas = $this->service->buscar($request->all());
        return new DBJsonResponse($programas, 'Programas Estratégicos cadastrados.');
    }

    public function show($id, ProgramaEstrategicoService $service)
    {
        return new DBJsonResponse($service->find($id), 'Dados do Programa Estratégico');
    }

    public function delete(ProgramaEstrategicoIdRequest $request, ProgramaEstrategicoService $service)
    {
        $service->remover($request->get('pl9_codigo'));
        return new DBJsonResponse([], 'Programa Estratégico removido com sucesso.');
    }

    public function salvar(Request $request, ProgramaEstrategicoService $service)
    {
        $programaEstrategico = $service->saveFromReques($request);

        return new DBJsonResponse($programaEstrategico, 'Programa salvo com sucesso.');
    }

    /**
     * Calcula o saldo do programa estratégico com base nas iniciativas vinculadas a ele
     * @param ProgramaEstrategicoIdRequest $request
     * @param ProgramaEstrategicoService $service
     * @return DBJsonResponse
     */
    public function calculaSaldoIniciativa(ProgramaEstrategicoIdRequest $request, ProgramaEstrategicoService $service)
    {
        $idIniciativa = $request->has('pl12_codigo') ? $request->get('pl12_codigo') : null;
        $saldos = $service->calcularSaldoIniciativa($request->get('pl9_codigo'), $idIniciativa);
        return new DBJsonResponse($saldos, 'Saldo do programa estratégico.');
    }
}
