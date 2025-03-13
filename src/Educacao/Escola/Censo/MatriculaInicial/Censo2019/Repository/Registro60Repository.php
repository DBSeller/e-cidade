<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 11:31
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository;

use cl_alunocensotipotransporte;
use cl_turmaacmatricula;
use ECidade\Educacao\Escola\Censo\Censo;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\MatriculaCensoVo;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\TurmaCensoVo;
use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Repository\Repository;
use Exception;

class Registro60Repository extends Repository
{

    public function scopeAluno(Aluno $aluno, $operador = "=")
    {
        $this->scopes['aluno'] = "ed47_i_codigo {$operador} {$aluno->getCodigo()}";
        return $this;
    }

    public function scopeTurma(TurmaCensoVo $turma)
    {
        if ($turma->isEscolarizacao()) {
            $this->scopes['turma'] = "ed57_i_codigo = {$turma->getCodigoTurma()}";
        } else {
            $this->scopes['turma'] = "ed268_i_codigo = {$turma->getCodigoTurma()}";
        }
    }

    /**
     * @param TurmaCensoVo $turma
     * @param Censo $censo
     * @return array
     * @throws Exception
     */
    public function getMatriculaTurmaRegular(TurmaCensoVo $turma, Censo $censo)
    {
        $this->scopes['turma'] = "ed57_i_codigo = {$turma->getCodigoTurma()}";
        $this->scopes['data_matricula'] = "ed60_d_datamatricula <= '{$censo->getDataCenso()->getDate()}'";
        $this->scopes['etapa_origem'] = "ed221_c_origem = 'S'";
        $this->scopes['situacao'] = "
            (   (ed60_c_situacao = 'MATRICULADO' AND ed60_d_datasaida IS NULL
                                    AND not exists (select 1 from matricula as mat
                                        where mat.ed60_i_turma = matricula.ed60_i_turmaant
                                            and mat.ed60_i_aluno = ed47_i_codigo
                                            and (mat.ed60_c_situacao = 'TROCA DE TURMA'
                                                    or mat.ed60_c_situacao = 'MATRICULA INDEVIDA')
                                            and mat.ed60_d_datasaida > '{$censo->getDataCenso()->getDate()}'))
             OR (ed60_c_situacao != 'MATRICULADO' AND ed60_d_datasaida > '{$censo->getDataCenso()->getDate()}')
            )
        ";

        $dao = new \cl_matricula();
        $sql = $dao->sql_query_excportacao_alunos_censo($this->scopes, array('codigo_turma_escola'));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar alunos matrículados na turma: {$turma->getCodigoTurma()}");
        }

        $matriculas = array();
        while ($state = pg_fetch_array($rs)) {
            $matricula = MatriculaCensoVo::fromState($state);
            $matricula->setTurma($turma);
            $matriculas[] = $matricula;
        }

        return $matriculas;
    }

    /**
     * @param TurmaCensoVo $turma
     * @param Censo $censo
     * @return MatriculaCensoVo[]
     * @throws Exception
     */
    public function getMatriculaTurmaEspecial(TurmaCensoVo $turma, Censo $censo)
    {
        $where = array(
            "ed268_i_codigo = {$turma->getCodigoTurma()}",
            "ed269_d_data <= '{$censo->getDataCenso()->getDate()}'",
            "ed52_i_ano = {$censo->getDataCenso()->getAno()}",
            "ed268_i_tipoatend in (4, 5)"
        );

        $campos = "
        distinct ed269_aluno as codigo_aluno_escola, ed269_i_codigo as  codigo_matricula, ed268_c_aee as atendimento
        ";

        $dao = new cl_turmaacmatricula();
        $sql = $dao->sql_query_turma(null, $campos, null, implode(' and ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar alunos matrículados na turma ac: {$turma->getCodigoTurma()}");
        }

        $matriculas = array();
        while ($state = pg_fetch_array($rs)) {
            $matricula = MatriculaCensoVo::fromState($state);
            $matricula->setTurma($turma);
            $matriculas[] = $matricula;
        }
        return $matriculas;
    }

    public function getTransporteEscolar(Aluno $aluno)
    {
        $where = "ed311_aluno = {$aluno->getCodigo()} ";
        $dao = new cl_alunocensotipotransporte();
        $sql = $dao->sql_query_file(null, "ed311_censotipotransporte", null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar dados de transporte escolar do aluno: {$aluno->getCodigo()}");
        }

        return pg_fetch_all_columns($rs);
    }
}
