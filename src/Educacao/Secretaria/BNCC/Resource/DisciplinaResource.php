<?php


namespace ECidade\Educacao\Secretaria\BNCC\Resource;

use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;

/**
 * Class DisiciplinaResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class DisciplinaResource
{
    /**
     * @param Disciplina[] $disciplinas
     * @return array
     */
    public static function toArray(array $disciplinas)
    {
        $data = array();

        foreach ($disciplinas as $disciplina) {
            $data[] = (object) array(
                "codigo" => $disciplina->getCodigo(),
                "nome" => $disciplina->getNome(),
                "sigla" => $disciplina->getSigla(),
                "area_conhecimento" => $disciplina->getAreaConhecimento(),
                "ensino" => $disciplina->getEnsino(),
            );
        }
        return $data;
    }
}
