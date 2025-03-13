<?php

namespace ECidade\Educacao\Escola\Repository;

use cl_areahistmpsdisc;
use cl_historicompsarea;
use DisciplinaHistoricoRede;
use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaHistoricoRede;
use Exception;
use HistoricoEtapaRede;

class AreaHistoricoRedeRepository extends Repository
{
    public function find($codigo)
    {
        $resultados = $this->scopeCodigo($codigo)->get();
        if (empty($resultados)) {
            return null;
        }

        return array_shift($resultados);
    }
    /**
     * @return AreaHistoricoRede[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_historicompsarea();
        $sql = $dao->sql_query_file(null, "*", null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Areas de Conhecimento do Histórico por Etapa.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $areaHistoricoRede = AreaHistoricoRede::fromState($state);

            $daoDisciplina = new \cl_areahistmpsdisc();
            $sqlDisciplina = $daoDisciplina->sql_query(
                null,
                "*",
                null,
                " ed171_historicompsarea = {$areaHistoricoRede->getCodigo()}"
            );

            $rsDisciplinaArea = db_query($sqlDisciplina);
            while ($ln = pg_fetch_array($rsDisciplinaArea)) {
                $areaHistoricoRede->addDisciplinaHistoricoRede(new DisciplinaHistoricoRede($ln['ed171_histmpsdisc']));
            }

            $dados[] = $areaHistoricoRede;
        }

        return $dados;
    }

    public function first()
    {
        $resultados = $this->get();
        if (empty($resultados)) {
            return null;
        }

        return array_shift($resultados);
    }

    /**
     * @param AreaHistoricoRede $areaHistoricoRede
     * @return AreaHistoricoRede
     * @throws Exception
     */
    public function salvar(AreaHistoricoRede $areaHistoricoRede)
    {
        $dao = new cl_historicompsarea();
        $dao->ed170_codigo = $areaHistoricoRede->getCodigo();
        $dao->ed170_historicomps = $areaHistoricoRede->getHistoricoEtapaRede()->getCodigoEtapa();
        $dao->ed170_areaconhecimento = $areaHistoricoRede->getAreaConhecimento()->getCodigo();
        $dao->ed170_resultadoobtido = $areaHistoricoRede->getResultadoObtido();
        $dao->ed170_resultadofinal = $areaHistoricoRede->getResultadoFinal();

        if (empty($dao->ed170_codigo)) {
            $exists = $this->scopeHistoricoEtapaRede($areaHistoricoRede->getHistoricoEtapaRede())
                ->scopeAreaConhecimento($areaHistoricoRede->getAreaConhecimento())
                ->first();

            if (!is_null($exists)) {
                throw new Exception("Já existe essa Área de Conhecimento nessa Etapa do Histórico");
            }

            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed170_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao inserir Area de Conhecimento na Etapa do Histórico");
        }

        $areaHistoricoRede->setCodigo($dao->ed170_codigo);
        return $areaHistoricoRede;
    }

    /**
     * @param AreaHistoricoRede|null $areaHistoricoRede
     * @param DisciplinaHistoricoRede $disciplinaHistoricoRede
     * @throws Exception
     */
    public function salvarAreaDisciplina(
        AreaHistoricoRede $areaHistoricoRede = null,
        DisciplinaHistoricoRede $disciplinaHistoricoRede
    ) {
        $codigo = $disciplinaHistoricoRede->getCodigo();
        db_query("delete from areahistmpsdisc where ed171_histmpsdisc = {$codigo}");

        if (is_null($areaHistoricoRede)) {
            return;
        }
        $daoDisciplina = new cl_areahistmpsdisc();
        $daoDisciplina->ed171_histmpsdisc = $codigo;
        $daoDisciplina->ed171_historicompsarea = $areaHistoricoRede->getCodigo();
        $daoDisciplina->incluir(null);

        if ($daoDisciplina->erro_status == 0) {
            throw new Exception("Erro ao vincular Area de Conhecimento com Disciplina do Histórico");
        }
    }

    /**
     * @param AreaHistoricoRede $areaHistoricoRede
     * @throws Exception
     */
    public function excluir(AreaHistoricoRede $areaHistoricoRede)
    {
        $dao = new cl_historicompsarea();
        $dao->ed170_codigo = $areaHistoricoRede->getCodigo();
        $dao->excluir($dao->ed170_codigo);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Área de Conhecimento da Etapa do Histórico");
        }
    }

    /**
     * @param $codigo
     * @return $this
     */
    public function scopeCodigo($codigo)
    {
        $this->scopes['codigo'] = "ed170_codigo = {$codigo}";
        return $this;
    }

    /**
     * @param HistoricoEtapaRede $historicoEtapaRede
     * @return $this
     */
    public function scopeHistoricoEtapaRede(HistoricoEtapaRede $historicoEtapaRede)
    {
        $this->scopes['historicoEtapa'] = "ed170_historicomps = {$historicoEtapaRede->getCodigoEtapa()}";
        return $this;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return AreaHistoricoRedeRepository
     */
    public function scopeAreaConhecimento(AreaConhecimento $areaConhecimento)
    {
        $this->scopes['areaConhecimento'] = "ed170_areaconhecimento = {$areaConhecimento->getCodigo()}";
        return $this;
    }
}
