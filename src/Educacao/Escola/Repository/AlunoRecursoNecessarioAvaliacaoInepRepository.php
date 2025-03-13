<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 29/04/2019
 * Time: 15:23
 */

namespace ECidade\Educacao\Escola\Repository;


use cl_alunorecursosavaliacaoinep;
use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Model\AlunoRecursoNecessarioAvaliacaoInep;
use Exception;

class AlunoRecursoNecessarioAvaliacaoInepRepository extends Repository
{
    /**
     * @param string $campos
     * @return AlunoRecursoNecessarioAvaliacaoInep[]
     * @throws Exception
     */
    public function get($campos = "*")
    {
        $dao = new cl_alunorecursosavaliacaoinep();
        $sql = $dao->sql_query_file(null, $campos, null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os Recursos necessários para uso do(a) aluno(a) e para a participação em avaliações do Inep (Saeb)");
        }

        if (pg_num_rows($rs) === 0) {
            return array();
        }

        $recursos = array();
        while ($state = pg_fetch_array($rs)) {
            $recursos[] = AlunoRecursoNecessarioAvaliacaoInep::fromState($state);
        }

        return $recursos;
    }

    /**
     * @return $this
     */
    public function scopeExcluirNenhum()
    {
        $this->scopes['nenhum'] = "ed327_recursosavaliacaoinep != 110";
        return $this;
    }

    /**
     * @param Aluno $aluno
     * @param string $operador
     * @return $this
     */
    public function scopeAluno(Aluno $aluno, $operador = '=')
    {
        $this->scopes['aluno'] = "ed327_aluno {$operador} {$aluno->getCodigo()}";
        return $this;
    }
}
