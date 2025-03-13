<?php

namespace App\Domain\Financeiro\Empenho\Repositories;

use App\Domain\Financeiro\Empenho\Models\TipoServicoObra;
use App\Domain\Core\Base\Repository\BaseRepository;

class TipoServicoObraRepository extends BaseRepository
{
    protected $modelClass = TipoServicoObra::class;

    /**
     * Persiste Model no Banco de Dados
     *
     * Caso possua um id nas propriedade sera realizado
     * um Update
     *
     * @param TipoServicoObra $tipoServicoObra
     * @return boolean
     */
    public function persist(TipoServicoObra $tipoServicoObra)
    {
        return $tipoServicoObra->save();
    }

    /**
     * Retorna uma instancia da Model
     *
     * @param string|null $id
     * @return TipoServicoObra
     */
    public function getModelInstance($id = null)
    {
        if (is_numeric($id)) {
            return TipoServicoObra::find($id);
        }

        return new TipoServicoObra;
    }

    public static function findByEmpenho($numemp)
    {
        return TipoServicoObra::where('e154_numemp', '=', $numemp)->first();
    }

    /**
     * Retorna o(s) label(s) da constante TIPO_LABELS da model
     *
     * Caso o tipo seja nulo será retornado todos os labels
     *
     * @param mixed $tipo
     * @return array|string
     */
    public static function labels($tipo = null)
    {
        $labels = TipoServicoObra::TIPO_LABELS;

        if (!is_null($tipo)) {
            return $labels[$tipo];
        }

        return $labels;
    }
}
