<?php


namespace ECidade\Financeiro\Orcamento\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Mappers\DeComplementoParaFonteRecursos;
use ECidade\Financeiro\Orcamento\Mappers\DeRecursoParaFonteRecursos;
use ECidade\Financeiro\Orcamento\Model\Recurso;
use ECidade\V3\Extension\Registry;

class FonteRecursoRepository extends Repository
{
    public function get()
    {
        $dao = new \cl_orctiporec();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar recursos");
        }
        $complementos = [];
        while ($state = pg_fetch_array($rs)) {
            $complementos[] = Recurso::fromState($state);
        }

        return $complementos;
    }

    public function all()
    {
        $this->resetScopes();
        return $this->get();
    }

    public static function registraRecursos()
    {
        $repository = new FonteRecursoRepository();
        $recursos = $repository->all();

        Registry::set('deParaRecursos', DeRecursoParaFonteRecursos::create($recursos));
        Registry::set('deParaComplemento', DeComplementoParaFonteRecursos::create($recursos));
    }
}
