<?php

if (!function_exists('filtrosDespesa')) {
    function filtrosDespesa($filtro)
    {
        $where = [];

        if (empty($filtro)) {
            return $where;
        }

        if (!empty($filtro->orgao->aOrgaos)) {
            $operador = $filtro->orgao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->orgao->aOrgaos);
            $where[] = "o58_orgao {$operador} ({$data})";
        }

        if (!empty($filtro->unidade->aUnidades)) {
            $operador = $filtro->unidade->operador === 'notin' ? 'not in' : 'in';
            $filtroUnidades = [];
            foreach ($filtro->unidade->aUnidades as $unidade) {
                $data = explode('-', $unidade);
                $filtroUnidades[] = sprintf(
                    '(o58_orgao %s (%s) and o58_unidade %s (%s))',
                    $operador,
                    $data[0],
                    $operador,
                    $data[1]
                );
            }
            $where[] = '(' . implode(' or ', $filtroUnidades) . ')';
        }
        if (!empty($filtro->funcao->aFuncoes)) {
            $operador = $filtro->funcao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->funcao->aFuncoes);
            $where[] = "o58_funcao {$operador} ({$data})";
        }

        if (!empty($filtro->subfuncao->aSubFuncoes)) {
            $operador = $filtro->subfuncao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->subfuncao->aSubFuncoes);
            $where[] = "o58_subfuncao {$operador} ({$data})";
        }

        if (!empty($filtro->programa->aProgramas)) {
            $operador = $filtro->programa->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->programa->aProgramas);
            $where[] = "o58_programa {$operador} ({$data})";
        }

        if (!empty($filtro->projativ->aProjAtiv)) {
            $operador = $filtro->projativ->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->projativ->aProjAtiv);
            $where[] = "o58_projativ {$operador} ({$data})";
        }

        if (!empty($filtro->elemento->aElementos)) {
            $operador = $filtro->elemento->operador === 'notin' ? 'not exists' : 'exists';
            $data = implode("', '", $filtro->elemento->aElementos);
            $where[] = " {$operador} (select 1 from orcamento.orcelemento
                where o56_anousu = o58_anousu
                  and o56_codele = o58_codele
                  and o56_elemento in ('{$data}')
                )";
        }

        if (!empty($filtro->recurso->aRecursos)) {
            $operador = $filtro->recurso->operador === 'notin' ? 'not in' : 'in';
            $data = implode(", ", $filtro->recurso->aRecursos);
            $where[] = "recurso {$operador} ({$data})";
        }

        return $where;
    }
}

if (!function_exists('filtrosConfiguracaoLinhaRelatorioLegal')) {
    function filtrosConfiguracaoLinhaRelatorioLegal($filtro)
    {
        $where = [];

        if (empty($filtro)) {
            return $where;
        }

        if (!empty($filtro->orgao->valor)) {
            $operador = $filtro->orgao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->orgao->valor);
            $where[] = "o58_orgao {$operador} ({$data})";
        }

        if (!empty($filtro->unidade->valor)) {
            $operador = $filtro->unidade->operador === 'notin' ? 'not in' : 'in';
            $filtroUnidades = [];
            foreach ($filtro->unidade->valor as $unidade) {
                $data = explode('-', $unidade);
                $filtroUnidades[] = sprintf(
                    '(o58_orgao %s (%s) and o58_unidade %s (%s))',
                    $operador,
                    $data[0],
                    $operador,
                    $data[1]
                );
            }
            $where[] = '(' . implode(' or ', $filtroUnidades) . ')';
        }
        if (!empty($filtro->funcao->valor)) {
            $operador = $filtro->funcao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->funcao->valor);
            $where[] = "o58_funcao {$operador} ({$data})";
        }

        if (!empty($filtro->subfuncao->valor)) {
            $operador = $filtro->subfuncao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->subfuncao->valor);
            $where[] = "o58_subfuncao {$operador} ({$data})";
        }

        if (!empty($filtro->programa->valor)) {
            $operador = $filtro->programa->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->programa->valor);
            $where[] = "o58_programa {$operador} ({$data})";
        }

        if (!empty($filtro->projativ->valor)) {
            $operador = $filtro->projativ->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->projativ->valor);
            $where[] = "o58_projativ {$operador} ({$data})";
        }

        if (!empty($filtro->recurso->valor)) {
            $operador = $filtro->recurso->operador === 'notin' ? 'not in' : 'in';
            $data = implode(", ", $filtro->recurso->valor);
            $where[] = "o15_codigo {$operador} ({$data})";
        }

        if (!empty($filtro->fonterecurso->valor)) {
            $operador = $filtro->fonterecurso->operador === 'notin' ? 'not in' : 'in';
            $data = implode(", ", $filtro->fonterecurso->valor);
            $where[] = "o15_recurso {$operador} ({$data})";
        }

        return $where;
    }
}
