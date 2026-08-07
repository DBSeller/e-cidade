<?php

namespace App\Domain\Educacao\Secretaria\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Educacao\Secretaria\Models\TipoBase;
use App\Domain\Educacao\Secretaria\Services\TipoBaseService;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ECidade\Enum\Educacao\Secretaria\ComposicaoItinerarioFormativoIntegradoEnum;
use ECidade\Enum\Educacao\Secretaria\EstruturaCurricularEnum;
use ECidade\Enum\Educacao\Secretaria\TipoItinerarioFormativoEnum;
use ECidade\Enum\Educacao\Secretaria\TiposCursoItinFormacaoTecnicaProfissionalEnum;

class TiposBaseController extends Controller
{
    public function getEstruturasCurriculares()
    {
        $estruturas = EstruturaCurricularEnum::getAll();
        return new DBJsonResponse($estruturas);
    }

    public function getTiposItinerarioFormativo()
    {
        $itinerarios = TipoItinerarioFormativoEnum::getAll();
        return new DBJsonResponse($itinerarios);
    }

    public function getComposicaoItinerarioFormativoIntegrado()
    {
        $composicao = ComposicaoItinerarioFormativoIntegradoEnum::getAll();
        return new DBJsonResponse($composicao);
    }

    public function getTiposCursoItinFormacaoTecnicaProfissional()
    {
        $tipos = TiposCursoItinFormacaoTecnicaProfissionalEnum::getAll();
        return new DBJsonResponse($tipos);
    }

    public function salvar(Request $request)
    {
        try {
            $service = new TipoBaseService();
            $service->salvar($request->all());
            return new DBJsonResponse('', 'Tipo de base salvo com sucesso!');
        } catch (Exception $exception) {
            throw new Exception('Falha ao salvar tipo de base!');
        }
    }

    public function getTiposBase()
    {
        $service = new TipoBaseService();
        
        return new DBJsonResponse($service->getTiposBase());
    }

    public function excluir(Request $request)
    {
        try {
            TipoBase::where('ed182_id', $request->get('id'))->delete();
            return new DBJsonResponse('', 'Tipo de base excluido com sucesso!');
        } catch (Exception $exception) {
            if ($exception->getCode() == 23503) {
                throw new Exception('Esse tipo de base não pode ser excluida pois já está vinculado
                em alguma disiciplina');
            } else {
                throw new Exception('Falha ao excluir tipo de base!');
            }
        }
    }
}
