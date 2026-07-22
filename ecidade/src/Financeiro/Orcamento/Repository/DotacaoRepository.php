<?php


namespace ECidade\Financeiro\Orcamento\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\Dotacao;
use Exception;

class DotacaoRepository extends Repository
{
    /**
     * Retorna as dotações
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_orcdotacao();
        $ordem = "o58_anousu, o58_coddot";
        $sql = $dao->sql_query_file(null, null, '*', $ordem, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar dotação");
        }

        $dotacoes = array();
        while ($state = pg_fetch_array($rs)) {
            $dotacoes[] = Dotacao::fromState($state);
        }

        return $dotacoes;
    }

    /**
     * @return Dotacao|null
     * @throws Exception
     */
    public function first()
    {
        $data = $this->get();
        if (count($data)) {
            return array_shift($data);
        }
        return null;
    }

    /**
     * @param Dotacao $dotacao
     * @return Dotacao
     * @throws Exception
     */
    public function save(Dotacao $dotacao)
    {
        $dao = new \cl_orcdotacao();
        $dao->o58_anousu = $dotacao->getAno();
        $dao->o58_coddot = $dotacao->getCodigoDotacao();
        $dao->o58_orgao = $dotacao->getIdOrgao();
        $dao->o58_unidade = $dotacao->getIdUnidade();
        $dao->o58_subfuncao = $dotacao->getIdSubfuncao();
        $dao->o58_projativ = $dotacao->getIdProjeto();
        $dao->o58_codigo = $dotacao->getIdRecurso();
        $dao->o58_funcao = $dotacao->getIdFuncao();
        $dao->o58_programa = $dotacao->getIdPrograma();
        $dao->o58_codele = $dotacao->getIdElemento();
        $dao->o58_valor = $dotacao->getValor();
        $dao->o58_instit = $dotacao->getIdInstituicao();
        $dao->o58_localizadorgastos = $dotacao->getLocalizadorGastos();
        $dao->o58_datacriacao = $dotacao->getDataCriacao();
        $dao->o58_concarpeculiar = $dotacao->getCaracteristicaPeculiar();
        $dao->o58_esferaorcamentaria = $dotacao->getEsferaOrcamentaria();
        $dao->incluir($dotacao->getAno(), $dotacao->getCodigoDotacao());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao incluir Dotação.");
        }

        return $dotacao;
    }

    public function excluirByScope()
    {
        $dao = new \cl_orcdotacao();
        $dao->excluir(null, null, implode(' and ', $this->scopes));
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Dotações");
        }
    }

    public function scopeAno($ano)
    {
        $this->scopes['ano'] = " o58_anousu = {$ano}";
        return $this;
    }


    public function scopeCoddot($coddot)
    {
        $this->scopes['codigo'] = "o58_coddot = {$coddot}";
        return $this;
    }

    public function scopeOrgao($orgao)
    {
        $this->scopes['orgao'] = " o58_orgao = {$orgao}";
        return $this;
    }

    public function scopeUnidade($unidade)
    {
        $this->scopes['unidade'] = " o58_unidade = {$unidade}";
        return $this;
    }

    public function scopeSubfuncao($subfuncao)
    {
        $this->scopes['subfuncao'] = " o58_subfuncao = {$subfuncao}";
        return $this;
    }

    public function scopeProjeto($projativ)
    {
        $this->scopes['projeto'] = " o58_projativ = {$projativ}";
        return $this;
    }

    public function scopeRecurso($recurso)
    {
        $this->scopes['recurso'] = " o58_codigo = {$recurso}";
        return $this;
    }

    public function scopeFuncao($funcao)
    {
        $this->scopes['funcao'] = " o58_funcao = {$funcao}";
        return $this;
    }


    public function scopePrograma($programa)
    {
        $this->scopes['programa'] = " o58_programa = {$programa}";
        return $this;
    }

    public function scopeElemento($codele)
    {
        $this->scopes['elemento'] = " o58_codele = {$codele}";
        return $this;
    }

    public function scopeInstitituicao($instit)
    {
        $this->scopes['institituicao'] = " o58_instit = {$instit}";
        return $this;
    }

    public function scopeLocalizadorGastos($localizadorgastos)
    {
        $this->scopes['localizadorgastos'] = " o58_localizadorgastos = {$localizadorgastos}";
        return $this;
    }

    public function scopeCaracteristicaPeculiar($concarpeculiar)
    {
        $this->scopes['cp'] = " o58_concarpeculiar = {$concarpeculiar}";
        return $this;
    }

    public function scopeEsferaOrcamentaria($esferaorcamentaria)
    {
        $this->scopes['esferaorcamentaria'] = " o58_esferaorcamentaria = {$esferaorcamentaria}";
        return $this;
    }
}
