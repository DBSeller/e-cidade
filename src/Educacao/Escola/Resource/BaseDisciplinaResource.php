<?php


namespace ECidade\Educacao\Escola\Resource;

use ECidade\Educacao\Escola\Model\BaseCurricularDisciplina;
use ECidade\Educacao\Escola\Repository\BaseCurricularDisciplinaRepository;

class BaseDisciplinaResource
{
    /**
     * @param BaseCurricularDisciplina[] $baseDisciplinas
     * @return array
     */
    public static function toArray(array $baseDisciplinas)
    {
        $dados = [];
        foreach ($baseDisciplinas as $baseDisciplina) {
            $procedimento = '';
            if (!empty($baseDisciplina->getProcedimento())) {
                $procedimento = (object)[
                    'codigo' => $baseDisciplina->getProcedimento()->getCodigo(),
                    'descricao' => $baseDisciplina->getProcedimento()->getDescricao()
                ];
            }

            $dados[] = (object)[
                'codigo' => $baseDisciplina->getCodigo(),
                'disciplina' => (object)[
                    'codigo' => $baseDisciplina->getDisciplina()->getCodigoDisciplina(),
                    'nome' => $baseDisciplina->getDisciplina()->getNomeDisciplina(),
                    'nomeAbreviado' => $baseDisciplina->getDisciplina()->getAbreviatura()
                ],
                'procedimento' => $procedimento
            ];
        }

        return $dados;
    }
}
