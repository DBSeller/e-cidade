<?php


namespace ECidade\Educacao\Escola\Repository;

use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvida;
use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvidaReferencial;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeReferencialCurricularEstadual;
use Exception;

/**
 * Class HabilidadeDesenvolvidaReferencialRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class HabilidadeDesenvolvidaReferencialRepository extends Repository
{

    public function get()
    {
        $dao = new \cl_diario_classe_bncc_habilidade_referencial();
        $sql = $dao->sql_query_file(null, "*", null, implode(', ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Habilidade Desenvolvida do Referencial.");
        }

        $habilidadesReferencial = [];
        while ($state = pg_fetch_array($rs)) {
            $habilidadesReferencial[] = HabilidadeDesenvolvidaReferencial::fromState($state);
        }

        return $habilidadesReferencial;
    }

    public function salvar(HabilidadeDesenvolvidaReferencial $habilidadeReferencial)
    {
        $dao = new \cl_diario_classe_bncc_habilidade_referencial();
        $dao->ed169_codigo = null;
        $dao->ed169_diario_classe_bncc_habilidade = $habilidadeReferencial->getHabilidadeDesenvolvida()->getCodigo();
        $dao->ed169_bnccreferencial = $habilidadeReferencial->getReferencialCurricular()->getCodigo();
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Habilidade Desenvolvida Referencial." . $dao->erro_msg);
        }

        $habilidadeReferencial->setCodigo($dao->ed169_codigo);

        return $habilidadeReferencial;
    }

    /**
     * @param HabilidadeDesenvolvidaReferencial $habilidadeDesenvolidaReferencial
     * @throws Exception
     */
    public function excluir(HabilidadeDesenvolvidaReferencial $habilidadeDesenvolidaReferencial)
    {
        $dao = new \cl_diario_classe_bncc_habilidade_referencial();
        $dao->ed169_codigo = $habilidadeDesenvolidaReferencial->getCodigo();
        $dao->excluir($dao->ed169_codigo);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Habilidade Desenvolvida Referencial." . $dao->erro_msg);
        }

        unset($habilidadeDesenvolidaReferencial);
    }

    public function scopeHabilidadeDesenvolvida(HabilidadeDesenvolvida $habilidadeDesenvolvida)
    {
        $codigo = pg_escape_string($habilidadeDesenvolvida->getCodigo());
        $this->scopes['habilidadeDesenvolvida'] = "ed169_diario_classe_bncc_habilidade = '{$codigo}'";

        return $this;
    }

    public function scopeReferencial(HabilidadeReferencialCurricularEstadual $habilidadeReferencial)
    {
        $codigo = pg_escape_string($habilidadeReferencial->getCodigo());
        $this->scopes['referencial'] = "ed169_bnccreferencial = '{$codigo}'";

        return $this;
    }
}
