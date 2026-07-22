<?php

namespace ECidade\Financeiro\Orcamento\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\FonteRecurso;
use Exception;

class FonteRecursoSiconfiRepository extends Repository
{

    public function scopeCodigoRecurso($codigo)
    {
        $this->scopes['orctiporec_id'] = "orctiporec_id = {$codigo}";
        return $this;
    }

    public function scopeExercicio($exercicio)
    {
        $this->scopes['exercicio'] = "exercicio = {$exercicio}";
        return $this;
    }


    public function get()
    {
        $where = '';
        if (!empty($this->scopes)) {
            $where = " where " . implode(' and ', $this->scopes);
        }
        $sql = sprintf("select * from fonterecurso %s order by gestao", $where);
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception('Erro ao executar sql.');
        }

        $fontes = [];
        while ($state = pg_fetch_array($rs)) {
            $fontes[] = FonteRecurso::fromState($state);
        }

        return $fontes;
    }

    /**
     * @return FonteRecurso|null
     * @throws Exception
     */
    public function first()
    {
        $fontes = $this->get();
        if (empty($fontes)) {
            return null;
        }

        return $fontes[0];
    }

    public function salvar(FonteRecurso $fonteRecurso)
    {
        $dao = new \cl_fonterecurso();
        $dao->id = $fonteRecurso->getId();
        $dao->orctiporec_id = $fonteRecurso->getOrctiporecId();
        $dao->exercicio = $fonteRecurso->getExercicio();
        $dao->codigo_siconfi = $fonteRecurso->getCodigoSiconfi();
        $dao->gestao = $fonteRecurso->getGestao();
        $dao->classificacaofr_id = $fonteRecurso->getClassificacaofrId();
        $dao->tipo_detalhamento = $fonteRecurso->getTipoDetalhamento();
        $dao->descricao = $fonteRecurso->getDescricao();

        if (empty($dao->id)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->id);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar fonte de recurso.");
        }
        $fonteRecurso->setId($dao->id);

        return $fonteRecurso;
    }

    public function deleteByScope()
    {
        $where = '';
        if (!empty($this->scopes)) {
            $where = implode(' and ', $this->scopes);
        }

        $dao = new \cl_fonterecurso();
        $dao->excluir(null, $where);
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Fonte de recurso");
        }

        unset($especificacao);
        return true;
    }
}
