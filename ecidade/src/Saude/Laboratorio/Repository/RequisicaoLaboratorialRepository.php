<?php


namespace ECidade\Saude\Laboratorio\Repository;

use Exception;

class RequisicaoLaboratorialRepository
{
    /**
     * @var \cl_lab_requisicao
     */
    private $dao;

    /**
     * RequisicaoLaboratorialRepository constructor.
     * @param \cl_lab_requisicao $dao
     */
    public function __construct(\cl_lab_requisicao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param string $columns
     * @return bool|\RequisicaoLaboratorial|null
     * @throws Exception
     */
    public function find($id, $columns = '*')
    {
        $sql = $this->dao->sql_query($id, $columns);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a Requisição.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $state = pg_fetch_array($rs);
        $requisicao = null;

        try {
            $requisicao = \RequisicaoLaboratorial::fromState($state);
        } catch (\BusinessException $e) {
            return null;
        }

        return $requisicao
            ->withRequisicaoExame();
    }

    /**
     * @param $codigoRequisicao
     * @param \cl_lab_requiitem $daoRequisicaoItem
     * @return array|bool
     */
    public function getMateriaisPorRequisicao($codigoRequisicao, \cl_lab_requiitem $daoRequisicaoItem)
    {
        $sql = $daoRequisicaoItem->sql_query_materiais_exame_requisicao(
            $codigoRequisicao,
            'la15_i_codigo, la15_c_descr',
            ''
        );

        $rs = db_query($sql);

        if (!$rs) {
            return false;
        }

        return pg_fetch_all($rs);
    }

    public function getExamesPorMaterialRequisicao(
        $codigoRequisicao,
        $codigoMaterial,
        \cl_lab_requiitem $daoRequisicaoItem
    ) {

        $sql = $daoRequisicaoItem->sql_query_materiais_exame_requisicao(
            $codigoRequisicao,
            'la08_i_codigo, la08_c_descr, la21_c_situacao, la21_i_codigo, la22_i_cgs, la21_observacao,
            la21_d_data, la21_d_entrega, la21_c_hora, la21_i_quantidade, la21_i_requisicao, la21_motivonovacoleta',
            "AND la15_i_codigo = {$codigoMaterial}"
        );

        $rs = db_query($sql);

        if (!$rs) {
            return false;
        }

        return pg_fetch_all($rs);
    }

    public function getExamesPorRequisicao(
        $codigoRequisicao,
        \cl_lab_requiitem $daoRequisicaoItem
    ) {

        $sql = $daoRequisicaoItem->sql_query_exame_requisicao(
            $codigoRequisicao,
            'la08_i_codigo, la08_c_descr, la21_c_situacao, la21_i_codigo, la22_i_cgs, la21_observacao,
            la21_d_data, la21_d_entrega, la21_c_hora, la21_i_quantidade, la21_i_requisicao, la21_motivonovacoleta'
        );

        $rs = db_query($sql);

        if (!$rs) {
            return false;
        }

        return pg_fetch_all($rs);
    }

    /**
     * @param $codigoRequisicao
     * @return object
     * @throws Exception
     */
    public function getSolicitanteRequisicao($codigoRequisicao)
    {
        $sql = $this->dao->sql_query_responsavel_requisicao($codigoRequisicao);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o solicitante da requisição {$codigoRequisicao}.");
        }

        if (pg_num_rows($rs) === 0) {
            throw new Exception("Solicitante não encontrado para a requisição {$codigoRequisicao}");
        }

        return pg_fetch_object($rs);
    }
}
