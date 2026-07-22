<?php


namespace ECidade\Financeiro\Orcamento\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\Receita;
use Exception;

class ReceitaRepository extends Repository
{
    /**
     * Retorna as receitas
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_orcreceita();
        $ordem = "o70_anousu, o70_codrec";
        $sql = $dao->sql_query_file(null, null, "*", $ordem, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar receitas");
        }

        $receitas = [];
        while ($state = pg_fetch_array($rs)) {
            $receitas[] = Receita::fromState($state);
        }

        return $receitas;
    }

    /**
     * @param Receita $receita
     * @return Receita
     * @throws Exception
     */
    public function save(Receita $receita)
    {
        $dao = new \cl_orcreceita();
        $dao->o70_anousu = $receita->getAno();
        $dao->o70_codrec = $receita->getReduzido();
        $dao->o70_codfon = $receita->getIdFonte();
        $dao->o70_codigo = $receita->getTipoRecurso();
        $dao->o70_valor = $receita->getValor();
        $dao->o70_reclan = $receita->getStatusReceita();
        $dao->o70_instit = $receita->getIdInstituicao();
        $dao->o70_concarpeculiar = $receita->getCaracteriscaPeculiar();
        $dao->o70_datacriacao = $receita->getDataCriacao();
        $dao->o70_orcorgao = $receita->getIdOrgao();
        $dao->o70_orcunidade = $receita->getIdUnidade();
        $dao->o70_esferaorcamentaria = $receita->getEsferaOrcamentaria();
        $dao->incluir($receita->getAno(), $receita->getReduzido());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao incluir Receita.");
        }

        $receita->setReduzido($dao->o70_codrec);

        return $receita;
    }

    public function excluirByScope()
    {
        $dao = new \cl_orcreceita();
        $dao->excluir(null, null, implode(' and ', $this->scopes));
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Receitas.");
        }
    }

    public function first()
    {
        $data = $this->get();
        if (count($data)) {
            return array_shift($data);
        }
        return null;
    }

    public function scopeAno($ano)
    {
        $this->scopes['ano'] = " o70_anousu = {$ano}";
        return $this;
    }

    public function scopeReduzido($idReceita)
    {
        $this->scopes['reduzido'] = " o70_codrec = {$idReceita}";
        return $this;
    }

    public function scopeFonte($idFonte)
    {
        $this->scopes['fonte'] = " o70_codfon = {$idFonte}";
        return $this;
    }

    public function scopeRecurso($tipoRecurso)
    {
        $this->scopes['recurso'] = " o70_codigo = {$tipoRecurso}";
        return $this;
    }

    public function scopeStatus($statusReceita)
    {
        $this->scopes['status'] = " o70_reclan = {$statusReceita}";
        return $this;
    }

    public function scopeInstituicao($idInstituicao)
    {
        $this->scopes['instituicao'] = " o70_instit = {$idInstituicao}";
        return $this;
    }

    public function scopeCaracteristicaPeculiar($caracteristicaPeculiar)
    {
        $this->scopes['caracteriscapeculiar'] = " o70_concarpeculiar = '{$caracteristicaPeculiar}'";
        return $this;
    }

    public function scopeOrgao($idOrgao)
    {
        $this->scopes['orgao'] = " o70_orcorgao = {$idOrgao}";
        return $this;
    }

    public function scopeUnidade($idUnidade)
    {
        $this->scopes['unidade'] = " o70_orcunidade = {$idUnidade}";
        return $this;
    }

    public function scopeEsferaOrcamentaria($esferaOrcamentaria)
    {
        $this->scopes['esferaorcamentaria'] = " o70_esferaorcamentaria = {$esferaOrcamentaria}";
        return $this;
    }

    public function update(Receita $receita)
    {
        $dao = new \cl_orcreceita();
        $dao->o70_anousu = $receita->getAno();
        $dao->o70_codrec = $receita->getReduzido();
        $dao->o70_codfon = $receita->getIdFonte();
        $dao->o70_codigo = $receita->getTipoRecurso();
        $dao->o70_valor = $receita->getValor();
        $dao->o70_reclan = $receita->getStatusReceita();
        $dao->o70_instit = $receita->getIdInstituicao();
        $dao->o70_concarpeculiar = $receita->getCaracteriscaPeculiar();
        $dao->o70_datacriacao = $receita->getDataCriacao();
        $dao->o70_orcorgao = $receita->getIdOrgao();
        $dao->o70_orcunidade = $receita->getIdUnidade();
        $dao->o70_esferaorcamentaria = $receita->getEsferaOrcamentaria();
        $dao->alterar($receita->getAno(), $receita->getReduzido());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao alterar Receita.");
        }

        return $receita;
    }
}
