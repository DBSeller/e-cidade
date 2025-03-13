<?php

namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Valor
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl10_codigo
 * @property $pl10_origem
 * @property $pl10_chave
 * @property $pl10_ano
 * @property $pl10_valor
 * @property $pl10_editadomanual
 */
class Valor extends Model
{
    const ORIGEM_PROGRAMA = 'PROGRAMA ESTRATEGICO';
    const ORIGEM_INICIATIVA = 'INICIATIVA'; // não é usada
    const ORIGEM_OBJETIVOS = 'OBJETIVOS';
    const ORIGEM_DETALHAMENTO_DESPESA = 'DETALHAMENTO INICIATIVA';
    const ORIGEM_RECEITA = 'RECEITA';
    const ORIGEM_META_OBJETIVO = 'META OBJETIVO';

    // origens de Niterói
    const ORIGEM_INDICADORES = 'INDICADORES';
    const ORIGEM_INDICADOR_AREA = 'INDICADOR AREA';
    const ORIGEM_INDICADOR_PROGRAMA = 'INDICADOR PROGRAMA';
    const ORIGEM_META_FISICA = 'META FISICA';
    const ORIGEM_META_FINANCEIRA = 'META FINANCEIRA';
    const ORIGEM_TETO_ORCAMENTARIO = 'TETO ORCAMENTARIO';
    const ORIGEM_PIB = 'PIB';

    protected $table = 'planejamento.valores';
    protected $primaryKey = 'pl10_codigo';
}
