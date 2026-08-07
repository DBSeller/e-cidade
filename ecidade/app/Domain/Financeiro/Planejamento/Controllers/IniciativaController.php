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
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\ExcluirAbrangenciaIniciativaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\ExcluirIniciativaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\ExcluirRegionalizacaoIniciativaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarAbrangenciaIniciativa;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarIniciativaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarRegionalizacaoIniciativaRequest;
use App\Domain\Financeiro\Planejamento\Services\IniciativaService;
use Exception;
use Illuminate\Http\Request;

/**
 * Class IniciativasController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class IniciativaController
{
    /**
     * @var IniciativaService
     */
    private $service;

    /**
     * IniciativaController constructor.
     * @param IniciativaService $service
     */
    public function __construct(IniciativaService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function buscar(Request $request)
    {
        $dados = Iniciativa::orderBy('pl12_orcprojativ')
            ->when($request->has('pl12_programaestrategico'), function ($query) use ($request) {
                $query->where('pl12_programaestrategico', '=', $request->get('pl12_programaestrategico'));
            })
            ->get();
        return new DBJsonResponse($dados, 'Iniciativas cadastradas no programa estratégicos.');
    }

    /**
     * @param $id
     * @return DBJsonResponse
     */
    public function getRegionalizacoes($id)
    {
        $iniciativa = Iniciativa::find($id);
        return new DBJsonResponse($iniciativa->regionalizacoes, 'Regionalizações da Iniciativa.');
    }

    /**
     * @param SalvarIniciativaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvar(SalvarIniciativaRequest $request)
    {
        $iniciativa = $this->service->salvarFromStdClass((object)$request->all());
        return new DBJsonResponse($iniciativa, 'Iniciativas salva com sucesso.');
    }

    /**
     * @param ExcluirIniciativaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function delete(ExcluirIniciativaRequest $request)
    {
        $this->service->delete($request->get('pl12_codigo'));
        return new DBJsonResponse([], 'Iniciativa removido com sucesso.');
    }

    /**
     * Retorna a iniciativa
     * @param $id
     * @return DBJsonResponse
     */
    public function show($id)
    {
        return new DBJsonResponse($this->service->find($id), 'Iniciativa encontrada.');
    }

    /**
     * Vincula as regionalizações a iniciativa
     * @param SalvarRegionalizacaoIniciativaRequest $request
     * @return DBJsonResponse
     */
    public function salvarRegionalizacoes(SalvarRegionalizacaoIniciativaRequest $request)
    {
        $regionalizacoes = $this->service->saveRegionalizacaoToObject((object)$request->all());
        return new DBJsonResponse($regionalizacoes, 'Regionalizações salvas com sucesso.');
    }

    /**
     * @param ExcluirRegionalizacaoIniciativaRequest $request
     * @return DBJsonResponse
     */
    public function excluirRegionalizacoes(ExcluirRegionalizacaoIniciativaRequest $request)
    {
        $this->service->excluirRegionalizacoes($request->get('pl12_codigo'));
        return new DBJsonResponse([], 'Regionalizações excluídas.');
    }

    /**
     * Vincula as abrangências a iniciativa
     * @param SalvarAbrangenciaIniciativa $request
     * @return DBJsonResponse
     */
    public function salvarAbrangencias(SalvarAbrangenciaIniciativa $request)
    {
        $abrangencias = $this->service->saveAbrangenciaToObject((object)$request->all());
        return new DBJsonResponse($abrangencias, 'Abrangência salvas com sucesso.');
    }

    /**
     * @param ExcluirAbrangenciaIniciativaRequest $request
     * @return DBJsonResponse
     */
    public function excluirAbrangencias(ExcluirAbrangenciaIniciativaRequest $request)
    {
        $this->service->excluirAbrangencias($request->get('pl12_codigo'));
        return new DBJsonResponse([], 'Abrangências excluídas.');
    }
}
