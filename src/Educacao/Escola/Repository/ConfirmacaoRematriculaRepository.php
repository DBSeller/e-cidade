<?php

namespace ECidade\Educacao\Escola\Repository;

use Aluno;
use Calendario;
use cl_confirmacaorematricula;
use DateTime;
use ECidade\Educacao\Escola\Model\ConfirmacaoRematricula;
use Escola;
use Etapa;
use Exception;
use Turma;

/**
 * Class ConfirmacaoRematriculaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class ConfirmacaoRematriculaRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param Escola $escola
     * @return $this
     */
    public function scopeEscola(Escola $escola)
    {
        $this->scopes[] = "edu01_escola = {$escola->getCodigo()}";
        return $this;
    }

    /**
     * @param Calendario $calendario
     * @return $this
     */
    public function scopeCalendario(Calendario $calendario)
    {
        $this->scopes[] = "edu01_calendario = {$calendario->getCodigo()}";
        return $this;
    }

    /**
     * @param Turma $turma
     * @return $this
     */
    public function scopeTurma(Turma $turma)
    {
        $this->scopes[] = "edu01_turma = {$turma->getCodigo()}";
        return $this;
    }

    /**
     * @param Aluno $aluno
     * @return $this
     */
    public function scopeAluno(Aluno $aluno)
    {
        $this->scopes[] = "edu01_aluno = {$aluno->getCodigoAluno()}";
        return $this;
    }

    /**
     * @param $sequencial
     * @return bool|ConfirmacaoRematricula
     * @throws Exception
     */
    public static function find($sequencial)
    {
        $dao = new cl_confirmacaorematricula();
        $sql = $dao->sql_query($sequencial);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a confirmação de rematrícula {$sequencial}.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ConfirmacaoRematricula::fromState($resultado);
    }

    /**
     * @param ConfirmacaoRematricula $confirmacaoRematricula
     * @throws Exception
     */
    public function save(ConfirmacaoRematricula $confirmacaoRematricula)
    {
        $criadoEm = $confirmacaoRematricula->getCriadoEm();
        $criadoEm = $criadoEm instanceof DateTime ? $criadoEm : new DateTime();

        $dao = new cl_confirmacaorematricula();
        $dao->setEscola($confirmacaoRematricula->getEscola()->getCodigo());
        $dao->setCalendario($confirmacaoRematricula->getCalendario()->getCodigo());
        $dao->setTurma($confirmacaoRematricula->getTurma()->getCodigo());
        $dao->setAluno($confirmacaoRematricula->getAluno()->getCodigoAluno());
        $dao->setCriadoEm($criadoEm->format('Y-m-d H:i:s.u'));

        if ($sequencial = $confirmacaoRematricula->getSequencial()) {
            $dao->setSequencial($confirmacaoRematricula->getSequencial());
            $dao->alterar($sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar a confirmação de rematrícula do aluno {$confirmacaoRematricula->getAluno()->getNome()}.\nContate o suporte.");
        }
    }

    /**
     * @return ConfirmacaoRematricula[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_confirmacaorematricula();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as confirmações de rematrícula.\nContate o suporte.");
        }

        $confirmacoes = array();

        if (pg_num_rows($rs) === 0) {
            return $confirmacoes;
        }

        while ($confirmacao = pg_fetch_array($rs)) {
            $confirmacoes[] = ConfirmacaoRematricula::fromState($confirmacao);
        }

        return $confirmacoes;
    }

    /**
     * @param ConfirmacaoRematricula $confirmacaoRematricula
     * @throws Exception
     */
    public static function delete(ConfirmacaoRematricula $confirmacaoRematricula)
    {
        $dao = new cl_confirmacaorematricula();
        $dao->excluir($confirmacaoRematricula->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a confirmação de rematrícula do aluno {$confirmacaoRematricula->getAluno()->getNome()}.\nContate o suporte.");
        }
    }

    /**
     * @param array|int $ids
     * @return int
     * @throws Exception
     */
    public static function destroy($ids)
    {
        $count = 0;
        $ids = is_array($ids) ? $ids : func_get_args();

        foreach ($ids as $id) {
            static::delete(static::find($id));
            $count++;
        }

        return $count;
    }

    public static function countConfirmados(Etapa $etapa, Escola $escola, Calendario $calendario)
    {
        $sql = "
            SELECT count(confirmacaorematricula.edu01_sequencial)
            FROM confirmacaorematricula
                   INNER JOIN turma ON turma.ed57_i_codigo = confirmacaorematricula.edu01_turma
                   INNER JOIN turmaserieregimemat ON turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo
                   INNER JOIN serieregimemat ON serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat
                   INNER JOIN serie ON serie.ed11_i_codigo = serieregimemat.ed223_i_serie
            WHERE ed11_i_codigo = {$etapa->getCodigo()}
              AND edu01_escola = {$escola->getCodigo()}
              AND edu01_calendario = {$calendario->getCodigo()}
            GROUP BY ed11_i_codigo, ed11_c_descr;
        ";

        $rs = db_query($sql);

        return pg_num_rows($rs) === 0 ? 0 : pg_fetch_object($rs)->count;
    }
}
