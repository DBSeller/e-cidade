<?php

namespace ECidade\Configuracao\Cadastro\Repository;

use cl_orcdotacao;

class OrcamentoDotacaoRepository
{
    private $scopes = [];

    public function get($campos = ['*'], $ordem = [])
    {
        $daoDotacao = new cl_orcdotacao();
        $sqlDotacao = $daoDotacao->sql_query(
            null,
            null,
            implode(',', $campos),
            implode(',', $ordem),
            implode(' AND ', $this->scopes)
        );
        $result = db_query($sqlDotacao);
        if (!$result) {
            throw new \Exception("Erro ao buscar Dotação");
        }
        if (pg_num_rows($result) == 0) {
            return [];
        }

        return pg_fetch_all($result);
    }

    public function scopeOrgao($orgao)
    {
        $this->scopes["orgao"] = "o58_orgao = {$orgao}";
    }

    public function scopeUnidade($unidade)
    {
        $this->scopes["unidade"] = "o58_unidade = {$unidade}";
    }

    public function scopeFuncao($funcao)
    {
        $this->scopes["funcao"] = "o58_funcao = {$funcao}";
    }

    public function scopeSubfuncao($subfuncao)
    {
        $this->scopes["subfuncao"] = "o58_subfuncao = {$subfuncao}";
    }

    public function scopePrograma($programa)
    {
        $this->scopes["programa"] = "o58_programa = {$programa}";
    }

    public function scopeProjetoAtividade($projetoAtividade)
    {
        $this->scopes["projetoAtividade"] = "o58_projativ = {$projetoAtividade}";
    }

    public function scopeElemento($elemento)
    {
        $this->scopes["elemento"] = "o56_elemento = '{$elemento}'";
    }

    public function scopeAnousu($anousu)
    {
        $this->scopes["anousu"] = "o58_anousu = {$anousu}";
    }
}
