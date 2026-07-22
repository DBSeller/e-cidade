<?php


namespace App\Domain\Financeiro\Orcamento\Factories;

use App\Domain\Configuracao\Instituicao\Model\DBConfig;
use App\Domain\Financeiro\Orcamento\Models\Recurso;
use ECidade\Lib\Session\DefaultSession;

/**
 * Class CodigoRecurso, a partir de 2022 os recursos são padronizados
 * @deprecated
 * @package App\Domain\Financeiro\Orcamento\Factories
 */
class CodigoRecurso
{

    /**
     * @param Recurso $recurso
     * @return int|string
     */
    public static function build(Recurso $recurso)
    {
        $defaultSession = DefaultSession::getInstance();
        $municipio = DBConfig::find($defaultSession->get(DefaultSession::DB_INSTIT))->munic;

        $ano = $defaultSession->get(DefaultSession::DB_ANOUSU);

        switch ($municipio) {
            case ($ano < 2019):
                return $recurso->getRecurso();
            case 'NITEROI':
                return $recurso->getLoaIdentificadorUso() .
                    $recurso->getLoaTipo() .
                    $recurso->getLoaGrupo() .
                    $recurso->getLoaEspecificacao();
            default:
                return $recurso->getLoaEspecificacao();
        }
    }
}
