<?php


namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_bnccreferencial;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\Etapa;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeReferencialCurricularEstadual;
use Exception;

/**
 * Class HabilidadeReferencialCurricularEstadualRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class HabilidadeReferencialCurricularEstadualRepository extends Repository
{
    /**
     * @return HabilidadeReferencialCurricularEstadual[]
     * @throws Exception
     */
    public function get()
    {
        if (!isset($this->scopes['ano'])) {
            $this->scopeAno(date('Y'));
        }

        $dao = new cl_bnccreferencial();
        $sql = $dao->sql_query_file(null, "*", null, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Habilidades da BNCC Referencial.");
        }

        $habilidades = [];
        while ($habilidade = pg_fetch_array($rs)) {
            $habilidades[] = HabilidadeReferencialCurricularEstadual::fromState($habilidade);
        }

        return $habilidades;
    }

    /**
     * @return HabilidadeReferencialCurricularEstadual
     * @throws Exception
     */
    public function find($id = null)
    {
        if (!isset($this->scopes['ano']) && is_null($id)) {
            $this->scopeAno(date('Y'));
        }

        $dao = new cl_bnccreferencial();
        $sql = $dao->sql_query_file($id, "*", null, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Habilidades da BNCC Referencial.");
        }

        return HabilidadeReferencialCurricularEstadual::fromState(pg_fetch_array($rs));
    }

    /**
     * @param $codigo
     * @return $this
     */
    public function scopeCodigoHabilidadeBNCC($codigo)
    {
        $codigo = pg_escape_string($codigo);
        $this->scopes['codigoHabilidadeBNCC'] = "ed168_codigohabilidade = '{$codigo}'";
        return $this;
    }

    /**
     * @param $codigo
     * @return $this
     */
    public function scopeCodigoReferencial($codigo)
    {
        $codigo = pg_escape_string($codigo);
        $this->scopes['codigoReferencial'] = "ed168_codigoreferencial = '{$codigo}'";
        return $this;
    }

       /**
     * @param $objeto
     * @return $this
     */
    public function scopeObjetoConhecimento($objeto)
    {
        $objeto = pg_escape_string($objeto);
        $this->scopes['objetoConhecmento'] = "ed168_objeto_conhecimento = '{$objeto}'";
        return $this;
    }

    /**
     * @param $ano
     * @return $this
     */
    public function scopeAno($ano)
    {
        $this->scopes['ano'] = "ed168_ano = {$ano}";
        return $this;
    }

    /**
     * @param Etapa[] $etapas
     * @return $this
     */
    public function scopeEtapaBNCC(array $etapas)
    {
        $scopes = [];
        foreach ($etapas as $etapa) {
            $scopes[] = "trim(ed168_etapa) ilike '%{$etapa->getEtapa()}%'";
        }
        $this->scopes['etapa'] = '('.implode(' or ', $scopes) .')';
        return $this;
    }

    /**
     * @param HabilidadeReferencialCurricularEstadual $habilidadeReferencial
     * @throws Exception
     */
    public function excluir(HabilidadeReferencialCurricularEstadual $habilidadeReferencial)
    {
        $dao = new cl_bnccreferencial();
        $dao->ed168_codigo = $habilidadeReferencial->getCodigo();
        $dao->excluir($dao->ed168_codigo);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao Excluir Habilidade do Referencial: " . $dao->erro_msg);
        }

        unset($habilidadeReferencial);
    }

    public function salvar(HabilidadeReferencialCurricularEstadual $habilidadeReferencial)
    {
        $dao = new cl_bnccreferencial();
        $dao->ed168_codigo = pg_escape_string($habilidadeReferencial->getCodigo());
        $dao->ed168_ensino = pg_escape_string($habilidadeReferencial->getEnsino()->value());
        $dao->ed168_etapa = pg_escape_string($habilidadeReferencial->getEtapa());
        $dao->ed168_codigohabilidade = pg_escape_string($habilidadeReferencial->getCodigoHabilidade());
        $dao->ed168_codigoreferencial = pg_escape_string($habilidadeReferencial->getCodigoReferencial());
        $dao->ed168_habilidade = pg_escape_string($habilidadeReferencial->getHabilidade());
        $dao->ed168_ano = pg_escape_string($habilidadeReferencial->getAno());
        $dao->ed168_objeto_conhecimento = pg_escape_string($habilidadeReferencial->getObjetoConhecimento());

        if (!empty($dao->ed168_codigo)) {
            $dao->alterar($dao->ed168_codigo);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Habilidade Educação Infantil: ".$dao->erro_msg);
        }

        $habilidadeReferencial->setCodigo($dao->ed168_codigo);
        return $habilidadeReferencial;
    }
}
