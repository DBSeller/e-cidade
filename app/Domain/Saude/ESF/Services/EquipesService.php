<?php

namespace App\Domain\Saude\ESF\Services;

use App\Domain\Saude\ESF\Models\Equipe;
use App\Domain\Saude\Ambulatorial\Models\Unidade;

/**
 * @package App\Domain\Saude\ESF\Services
 */
class EquipesService
{
    /**
     * @throws \Exception
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function buscarUnidadesComEquipe($instituicao)
    {
        $unidades = Unidade::select('coddepto', 'descrdepto')
            ->join('plugins.psf_equipe', 'sd02_i_codigo', 'psf_cod_estabelecimento')
            ->join('db_depart', 'sd02_i_codigo', 'coddepto')
            ->groupBy('coddepto', 'descrdepto')
            ->where('instit', $instituicao)
            ->get();

        if ($unidades->isEmpty()) {
            throw new \Exception('Não foi encontrada nenhuma unidade com equipe cadastrada na instituição.');
        }

        return $unidades;
    }

    /**
     * @param integer $cgmProfissional
     * @param integer $idUnidade
     * @return Equipe
     */
    public static function getEquipeProfissional($cgmProfissional, $idUnidade)
    {
        return Equipe::profissional($cgmProfissional)->unidade($idUnidade)->profissionalAtivo()->first();
    }

    public static function getEquipesUnidade($idUnidade)
    {
        return Equipe::unidade($idUnidade)->get();
    }
}
