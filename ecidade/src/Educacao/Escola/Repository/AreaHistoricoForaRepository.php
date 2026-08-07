<?php

namespace ECidade\Educacao\Escola\Repository;

use cl_areahistmpsdiscfora;
use DisciplinaHistoricoForaRede;
use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Model\AreaHistoricoFora;
use Exception;
use HistoricoEtapaForaRede;

/**
 * Class AreaHistoricoForaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class AreaHistoricoForaRepository extends Repository
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
     * @return AreaHistoricoFora[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_historicompsforaarea();
        $sql = $dao->sql_query_file(null, "*", null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Areas de Conhecimento do Histórico por Etapa.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $areaHistoricoFora = AreaHistoricoFora::fromState($state);

            $daoDisciplina = new cl_areahistmpsdiscfora();
            $sqlDisciplina = $daoDisciplina->sql_query_file(
                null,
                "*",
                null,
                " ed173_historicompsforaarea = {$areaHistoricoFora->getCodigo()}"
            );
            $rsDisciplinaArea = db_query($sqlDisciplina);
            while ($ln = pg_fetch_array($rsDisciplinaArea)) {
                $areaHistoricoFora->addDisciplinaHistoricoForaRede(
                    new DisciplinaHistoricoForaRede($ln['ed173_histmpsdiscfora'])
                );
            }

            $dados[] = $areaHistoricoFora;
        }

        return $dados;
    }

    /**
     * @param AreaHistoricoFora $areaHistoricoFora
     * @return AreaHistoricoFora
     * @throws Exception
     */
    public function salvar(AreaHistoricoFora $areaHistoricoFora)
    {
        $dao = new \cl_historicompsforaarea();
        $dao->ed172_codigo = $areaHistoricoFora->getCodigo();
        $dao->ed172_historicompsfora = $areaHistoricoFora->getHistoricoEtapaForaRede()->getCodigoEtapa();
        $dao->ed172_areaconhecimento = $areaHistoricoFora->getAreaConhecimento()->getCodigo();
        $dao->ed172_resultadoobtido = $areaHistoricoFora->getResultadoObtido();
        $dao->ed172_resultadofinal = $areaHistoricoFora->getResultadoFinal();

        if (empty($dao->ed172_codigo)) {
            $exists = $this->scopeHistoricoEtapaForaRede($areaHistoricoFora->getHistoricoEtapaForaRede())
                ->scopeAreaConhecimento($areaHistoricoFora->getAreaConhecimento())->first();

            if (!is_null($exists)) {
                throw new Exception("Já existe essa Área de Conhecimento nessa Etapa do Histórico");
            }

            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed172_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao inserir Area de Conhecimento na Etapa do Histórico");
        }

        $areaHistoricoFora->setCodigo($dao->ed172_codigo);

        return $areaHistoricoFora;
    }

    /**
     * @param AreaHistoricoFora $areaHistoricoFora
     * @throws Exception
     */
    public function excluir(AreaHistoricoFora $areaHistoricoFora)
    {
        $dao = new \cl_historicompsforaarea();
        $dao->ed172_codigo = $areaHistoricoFora->getCodigo();
        $dao->excluir($dao->ed172_codigo);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Área de Conhecimento da Etapa do Histórico");
        }
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
     * @param $codigo
     * @return $this
     */
    public function scopeCodigo($codigo)
    {
        $this->scopes['codigo'] = "ed172_codigo = {$codigo}";
        return $this;
    }

    /**
     * @param HistoricoEtapaForaRede $historicoEtapaForaRede
     * @return AreaHistoricoForaRepository
     */
    public function scopeHistoricoEtapaForaRede(HistoricoEtapaForaRede $historicoEtapaForaRede)
    {
        $this->scopes['historicoEtapa'] = "ed172_historicompsfora = {$historicoEtapaForaRede->getCodigoEtapa()}";
        return $this;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return AreaHistoricoForaRepository
     */
    public function scopeAreaConhecimento(AreaConhecimento $areaConhecimento)
    {
        $this->scopes['areaConhecimento'] = "ed172_areaconhecimento = {$areaConhecimento->getCodigo()}";
        return $this;
    }

    /**
     * @param AreaHistoricoFora|null $areaHistoricoFora
     * @param DisciplinaHistoricoForaRede $disciplinaHistoricoFora
     * @throws Exception
     */
    public function salvarAreaDisciplina(
        AreaHistoricoFora $areaHistoricoFora = null,
        DisciplinaHistoricoForaRede $disciplinaHistoricoFora
    ) {
        $codigo = $disciplinaHistoricoFora->getCodigo();
        db_query("delete from areahistmpsdiscfora where ed173_histmpsdiscfora = {$codigo} ");

        if (is_null($areaHistoricoFora)) {
            return;
        }

        $daoDisciplina = new cl_areahistmpsdiscfora();
        $daoDisciplina->ed173_histmpsdiscfora = $codigo;
        $daoDisciplina->ed173_historicompsforaarea = $areaHistoricoFora->getCodigo();
        $daoDisciplina->incluir(null);

        if ($daoDisciplina->erro_status == 0) {
            throw new Exception("Erro ao vincular Area de Conhecimento com Disciplina do Histórico");
        }
    }
}
