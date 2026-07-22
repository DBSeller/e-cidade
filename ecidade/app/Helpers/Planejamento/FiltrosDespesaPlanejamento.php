<?php

use App\Domain\Financeiro\Planejamento\Models\DetalhamentoDespesa;
use App\Domain\Financeiro\Planejamento\Models\Iniciativa;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;

if (!function_exists('filtrosDespesaJsonToPlanejamento')) {
    /**
     * Através da string json dos filtros da despesa. Retorna um objeto para uso no planejamento
     * @param $stringJson
     * @return object
     */
    function filtrosDespesaJsonToPlanejamento($stringJson)
    {
        $filtroOrcamento = parseStringJson($stringJson);
        return filtrosDespesaToPlanejamento($filtroOrcamento);
    }
}

if (!function_exists('filtrosDespesaToPlanejamento')) {

    /**
     * Retorna um objeto stdClass com base nos filtros da despesa do orçamento
     * @param stdClass $filtroDespesa
     * @return object
     */
    function filtrosDespesaToPlanejamento(stdClass $filtroDespesa)
    {
        return (object) [
            "orgao" => (object) [
                "valores" => $filtroDespesa->orgao->aOrgaos,
                "contem" => $filtroDespesa->orgao->operador === 'in'
            ],
            "unidade" => (object) [
                "valores" => $filtroDespesa->unidade->aUnidades,
                "contem" => $filtroDespesa->unidade->operador === 'in'
            ],
            "funcao" => (object) [
                "valores" => $filtroDespesa->funcao->aFuncoes,
                "contem" => $filtroDespesa->funcao->operador === 'in'
            ],
            "subfuncao" => (object) [
                "valores" => $filtroDespesa->subfuncao->aSubFuncoes,
                "contem" => $filtroDespesa->subfuncao->operador === 'in'
            ],
            "programa" => (object) [
                "valores" => $filtroDespesa->programa->aProgramas,
                "contem" => $filtroDespesa->programa->operador === 'in'
            ],
            "iniciativa" => (object) [
                "valores" => $filtroDespesa->projativ->aProjAtiv,
                "contem" => $filtroDespesa->projativ->operador === 'in'
            ],
            "elemento" => (object) [
                "valores" => $filtroDespesa->elemento->aElementos,
                "contem" => $filtroDespesa->elemento->operador === 'in'
            ],
            "recurso" => (object) [
                "valores" => $filtroDespesa->recurso->aRecursos,
                "contem" => $filtroDespesa->recurso->operador === 'in'
            ]
        ];
    }
}

if (!function_exists('matchFiltros')) {

    /**
     * @param \stdClass $filtros
     * @param ProgramaEstrategico $programa
     * @param Iniciativa $iniciativa
     * @param DetalhamentoDespesa $detalhamento
     * @return bool
     */
    function matchFiltros(
        \stdClass           $filtros,
        ProgramaEstrategico $programa,
        Iniciativa          $iniciativa,
        DetalhamentoDespesa $detalhamento
    ) {
        if (!empty($filtros->programa->valores)) {
            $valores = $filtros->programa->valores;
            if ($filtros->orgao->contem && !in_array($programa->pl9_orcprograma, $valores)) {
                return false;
            }
            if (!$filtros->orgao->contem && in_array($programa->pl9_orcprograma, $valores)) {
                return false;
            }
        }

        if (!empty($filtros->orgao->valores)) {
            if ($filtros->orgao->contem && !in_array($detalhamento->pl20_orcorgao, $filtros->orgao->valores)) {
                return false;
            }
            if (!$filtros->orgao->contem && in_array($detalhamento->pl20_orcorgao, $filtros->orgao->valores)) {
                return false;
            }
        }

        if (!empty($filtros->iniciativa->valores)) {
            $iniciativas = $filtros->iniciativa->valores;
            if ($filtros->iniciativa->contem && !in_array($iniciativa->pl12_orcprojativ, $iniciativas)) {
                return false;
            }
            if (!$filtros->iniciativa->contem && in_array($iniciativa->pl12_orcprojativ, $iniciativas)) {
                return false;
            }
        }

        if (!empty($filtros->unidade->valores)) {
            $unidades = $filtros->unidade->valores;
            if ($filtros->unidade->contem && !in_array($detalhamento->pl20_orcunidade, $unidades)) {
                return false;
            }
            if (!$filtros->unidade->contem && in_array($detalhamento->pl20_orcunidade, $unidades)) {
                return false;
            }
        }

        if (!empty($filtros->funcao->valores)) {
            if ($filtros->funcao->contem && !in_array($detalhamento->pl20_orcfuncao, $filtros->funcao->valores)) {
                return false;
            }
            if (!$filtros->funcao->contem && in_array($detalhamento->pl20_orcfuncao, $filtros->funcao->valores)) {
                return false;
            }
        }

        if (!empty($filtros->subfuncao->valores)) {
            $subfuncoes = $filtros->subfuncao->valores;
            if ($filtros->subfuncao->contem && !in_array($detalhamento->pl20_orcsubfuncao, $subfuncoes)) {
                return false;
            }
            if (!$filtros->subfuncao->contem && in_array($detalhamento->pl20_orcsubfuncao, $subfuncoes)) {
                return false;
            }
        }

        if (!empty($filtros->elemento->valores)) {
            $elemento = $detalhamento->getNaturezaDespesa()->o56_elemento;
            if ($filtros->elemento->contem && !in_array($elemento, $filtros->elemento->valores)) {
                return false;
            }
            if (!$filtros->elemento->contem && in_array($elemento, $filtros->elemento->valores)) {
                return false;
            }
        }

        if (!empty($filtros->recurso->valores)) {
            if ($filtros->recurso->contem && !in_array($detalhamento->pl20_recurso, $filtros->recurso->valores)) {
                return false;
            }
            if (!$filtros->recurso->contem && in_array($detalhamento->pl20_recurso, $filtros->recurso->valores)) {
                return false;
            }
        }

        return true;
    }
}
