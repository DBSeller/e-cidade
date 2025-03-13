<?php

namespace App\Domain\Educacao\Secretaria\Services;

use App\Domain\Educacao\Secretaria\Models\TipoBase;
use ECidade\Enum\Educacao\Secretaria\ComposicaoItinerarioFormativoIntegradoEnum;
use ECidade\Enum\Educacao\Secretaria\EstruturaCurricularEnum;
use ECidade\Enum\Educacao\Secretaria\TipoItinerarioFormativoEnum;
use ECidade\Enum\Educacao\Secretaria\TiposCursoItinFormacaoTecnicaProfissionalEnum;

/**
 * Class TurmaEspecialService
 * @package App\Domain\Educacao\Escola\Services
 */
class TipoBaseService
{

    public function salvar($dados)
    {
        unset($dados['_path']);
        $dados['ed182_compos_itinerario_integrado'] = isset($dados['ed182_compos_itinerario_integrado']) &&
            !empty($dados['ed182_compos_itinerario_integrado']) ?
            json_encode($dados['ed182_compos_itinerario_integrado']) : null;

        $dados['ed182_tipo_itinerario_informativo'] = isset($dados['ed182_tipo_itinerario_informativo']) &&
            !empty($dados['ed182_tipo_itinerario_informativo']) ?
            json_encode($dados['ed182_tipo_itinerario_informativo']) : null;
     
        $dados['ed182_tipo_curso_itinerario_tec_prof'] = isset($dados['ed182_tipo_curso_itinerario_tec_prof']) &&
            !empty($dados['ed182_tipo_curso_itinerario_tec_prof']) ?
            intval($dados['ed182_tipo_curso_itinerario_tec_prof']) : null;

        $dados['ed182_itinerario_concomitante'] = isset($dados['ed182_itinerario_concomitante']) &&
            !empty($dados['ed182_itinerario_concomitante']) ?
            intval($dados['ed182_itinerario_concomitante']) : null;

      
        if (!isset($dados['ed182_id']) || empty($dados['ed182_id'])) {
            unset($dados['ed182_id']);
            $tipoBase = TipoBase::create($dados);
            $tipoBase->ed182_ordem_historico = $tipoBase->ed182_id;
            $tipoBase->save();
        } else {
            TipoBase::updateOrCreate(['ed182_id' => $dados['ed182_id']], $dados);
        }
    }

    public function getTiposBase()
    {
        $tipos = TipoBase::all();
        foreach ($tipos as $tipo) {
            $aTiposItinerarioEnum = [];
            $aComposicaoItinerarioEnum = [];
            $aTipoCursoEnum = [];
            $aEstruturaCurricularEnum = [];


            $enum = new EstruturaCurricularEnum(intval($tipo->getAttribute('ed182_estrutura_curricular')));
            $aEstruturaCurricularEnum = ['id' => $enum->getValue(), 'descricao' => $enum->descricao()];
            
            if (!is_null($tipo->getAttribute('ed182_tipo_curso_itinerario_tec_prof'))) {
                $enum = new TiposCursoItinFormacaoTecnicaProfissionalEnum(
                    intval($tipo->getAttribute('ed182_tipo_curso_itinerario_tec_prof'))
                );
                $aTipoCursoEnum = ['id' => $enum->getValue(), 'descricao' => $enum->descricao()];
                $tipo->setAttribute('ed182_tipo_curso_itinerario_tec_prof', $aTipoCursoEnum);
            }

            if (!is_null($tipo->getAttribute('ed182_tipo_itinerario_informativo'))) {
                $aTiposItinerario = json_decode($tipo->getAttribute('ed182_tipo_itinerario_informativo'));
                foreach ($aTiposItinerario as $tp) {
                    $enum = new TipoItinerarioFormativoEnum(intval($tp));
                    $aTiposItinerarioEnum[] = ['id' => $enum->getValue(), 'descricao' => $enum->descricao()];
                }
                $tipo->setAttribute('ed182_tipo_itinerario_informativo', $aTiposItinerarioEnum);
            }

            if (!is_null($tipo->getAttribute('ed182_compos_itinerario_integrado'))) {
                $aComposicaoItinerio = json_decode($tipo->getAttribute('ed182_compos_itinerario_integrado'));
                foreach ($aComposicaoItinerio as $tp) {
                    $enum = new ComposicaoItinerarioFormativoIntegradoEnum(intval($tp));
                    $aComposicaoItinerarioEnum[] = ['id' => $enum->getValue(), 'descricao' => $enum->descricao()];
                }
                $tipo->setAttribute('ed182_compos_itinerario_integrado', $aComposicaoItinerarioEnum);
            }

            $tipo->setAttribute('ed182_estrutura_curricular', $aEstruturaCurricularEnum);
        }

        return $tipos;
    }
}
