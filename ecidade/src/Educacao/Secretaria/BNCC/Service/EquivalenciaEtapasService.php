<?php

namespace ECidade\Educacao\Secretaria\BNCC\Service;

use ECidade\Educacao\Secretaria\BNCC\Model\Etapa as EtapaBNCC;
use ECidade\Educacao\Secretaria\BNCC\Model\EtapasEquivalente;
use ECidade\Educacao\Secretaria\BNCC\Repository\EtapaRepository;
use ECidade\Educacao\Secretaria\BNCC\Repository\EtapasEquivalenteRepository;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use Exception;

/**
 * Class EquivalenciaEtapasService
 * @package ECidade\Educacao\Secretaria\BNCC\Service
 */
class EquivalenciaEtapasService
{
    /**
     * @param EnsinoEnum $ensinoEnum
     * @return EtapaBNCC[]
     * @throws Exception
     */
    public function getEtapasBNCC(EnsinoEnum $ensinoEnum)
    {
        $etapaRepository = new EtapaRepository();
        return $etapaRepository->scopeEnsino($ensinoEnum)->get();
    }

    /**
     * @param EnsinoEnum $ensinoEnum
     * @param EtapaBNCC $etapaBNCC
     * @return array
     * @throws Exception
     */
    public function getEtapasEquivalente(EnsinoEnum $ensinoEnum, EtapaBNCC $etapaBNCC)
    {
        $tipoEnsino = $ensinoEnum->getTipoEnsino();

        $dao = new \cl_seriebnccetapas();
        $sql = $dao->sqlEquivalencia($tipoEnsino, $etapaBNCC);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar etapas.");
        }

        $ensinos = [];
        while ($state = pg_fetch_object($rs)) {
            $etapa = (object) $state;
            $etapa->equivalente = $etapa->equivalente === 't';

            if (!array_key_exists($etapa->codigo_ensino, $ensinos)) {
                $ensino = clone $etapa;
                $ensino->etapas = [];
                unset($ensino->codigo_etapa);
                unset($ensino->etapa);
                unset($ensino->equivalente);
                $ensinos[$etapa->codigo_ensino] = $ensino;
            }

            $ensinos[$etapa->codigo_ensino]->etapas[] = $etapa;
        }

        return $ensinos;
    }

    /**
     * @param EtapaBNCC $etapa
     * @param $etapas
     * @return bool
     * @throws Exception
     */
    public function salvarEquivalencia(EtapaBNCC $etapa, $etapas)
    {
        $etapasEquivalente = new EtapasEquivalente();
        $etapasEquivalente->setBnccEtapa($etapa);

        $repository = new EtapasEquivalenteRepository();
        $repository->removerEquivalencia($etapasEquivalente);

        foreach ($etapas as $codigoEtapa) {
            $etapasEquivalente = new EtapasEquivalente();
            $etapasEquivalente->setBnccEtapa($etapa);
            $etapasEquivalente->setEtapaEcidade(\EtapaRepository::getEtapaByCodigo($codigoEtapa));
            $repository->salvar($etapasEquivalente);
        }

        return true;
    }
}
