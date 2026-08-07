<?php


namespace ECidade\Educacao\Secretaria\BNCC\Resource;

use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeReferencialCurricularEstadual;

/**
 * Class HabilidadeReferencialCurricularResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class HabilidadeReferencialCurricularResource
{
    /**
     * @param HabilidadeReferencialCurricularEstadual[] $referenciaisCurriculares
     * @return array
     */
    public static function toArray(array $referenciaisCurriculares)
    {
        $dados = [];
        foreach ($referenciaisCurriculares as $referencialCurricular) {
            $dados[] = static::toJson($referencialCurricular);
        }

        return $dados;
    }

    /**
     * @param HabilidadeReferencialCurricularEstadual $referencialCurricularEstadual
     * @return object
     */
    public static function toJson(HabilidadeReferencialCurricularEstadual $referencialCurricularEstadual)
    {
        return (object) [
           'id' => $referencialCurricularEstadual->getCodigo(),
           'codigoHabilidade' => $referencialCurricularEstadual->getCodigoHabilidade(),
           'codigo' => $referencialCurricularEstadual->getCodigoReferencial(),
           'codigoReferencial' => $referencialCurricularEstadual->getCodigoReferencial(),
           'habilidade' => $referencialCurricularEstadual->getHabilidade(),
           'nome' => $referencialCurricularEstadual->getHabilidade(),
           'etapa' => $referencialCurricularEstadual->getEtapa(),
           'ensino' => $referencialCurricularEstadual->getEnsino()->value(),
        ];
    }
}
