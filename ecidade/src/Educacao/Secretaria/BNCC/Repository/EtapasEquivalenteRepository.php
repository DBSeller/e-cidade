<?php

namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_seriebnccetapas;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\EtapasEquivalente;
use Exception;

/**
 * Class EtapasEquivalentesRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class EtapasEquivalenteRepository extends Repository
{

    /**
     * @return EtapasEquivalente[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_seriebnccetapas();
        $sql = $dao->sql_query(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possivel buscar Etapas Equivalentes.");
        }
        $equivalencias = [];
        while ($state = pg_fetch_array($rs)) {
            $equivalencias[] = EtapasEquivalente::fromState($state);
        }

        return $equivalencias;
    }
    /**
     * @param EtapasEquivalente $etapasEquivalente
     * @return EtapasEquivalente
     * @throws Exception
     */
    public function salvar(EtapasEquivalente $etapasEquivalente)
    {
        $dao = new cl_seriebnccetapas();
        $dao->ed154_sequencial = $etapasEquivalente->getCodigo();
        $dao->ed154_bnccetapa = $etapasEquivalente->getBnccEtapa()->getCodigo();
        $dao->ed154_serie = $etapasEquivalente->getEtapaEcidade()->getCodigo();

        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar equivalência." . $dao->erro_msg);
        }

        $etapasEquivalente->setCodigo($dao->ed154_sequencial);
        return $etapasEquivalente;
    }

    /**
     * @param EtapasEquivalente $etapasEquivalente
     * @return bool
     * @throws Exception
     */
    public function removerEquivalencia(EtapasEquivalente $etapasEquivalente)
    {
        $where = "ed154_bnccetapa = {$etapasEquivalente->getBnccEtapa()->getCodigo()}";
        $dao = new cl_seriebnccetapas();
        $dao->excluir(null, $where);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir.");
        }

        return true;
    }

    /**
     * @param $codigoEtapa
     * @return EtapasEquivalenteRepository
     */
    public function scopeEtapaEcidade($codigoEtapa)
    {
        $this->scopes['etapa'] = "ed154_serie = $codigoEtapa";
        return $this;
    }
}
