<?php

namespace App\Domain\Configuracao\Configuracao\Controllers;

use App\Domain\Configuracao\Configuracao\Model\Organograma;
use App\Domain\Configuracao\Configuracao\Requests\SalvarOrganogramaRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Configuracao\Configuracao\Services\OrganogramaService;
use ECidade\Configuracao\Instituicao\Repository\InstituicaoRepository;
use Exception;

class OrganogramaController extends Controller
{
    public function get($instit, $departamento = null)
    {
        $repository = new InstituicaoRepository();
        $instituicao = $repository->find($instit);
        
        if ($instituicao->getCodigoDepartamentoPrincipal() == null) {
            throw new Exception('Departamento Principal não cadastrado na instituição.');
        }

        $service = new OrganogramaService();
        $organograma = $service->getOrganograma(
            $instituicao->getDescricaoDepartamentoAbreviado(),
            $instituicao->getCodigoDepartamentoPrincipal(),
            0,
            false,
            $departamento
        );

        return new DBJsonResponse($organograma);
    }

    public function salvar(SalvarOrganogramaRequest $request)
    {
        $service = new OrganogramaService();
        $service->salvar($request);

        return new DBJsonResponse([], 'Organograma salvo com sucesso.');
    }

    public function getByDepartamento(Request $request)
    {
        $organograma = Organograma::query()
            ->where('db122_departfilho', '=', $request->get('id'))
            ->first();

        if (!$organograma instanceof Organograma) {
            throw new Exception('Departamento não encontrado.');
        }
        
        $service = new OrganogramaService();
        $organograma = $service->getOrganograma(
            $organograma->db122_descricao,
            $organograma->db122_departfilho,
            0,
            $organograma->db122_associado
        );
        
        return new DBJsonResponse($organograma);
    }
}
