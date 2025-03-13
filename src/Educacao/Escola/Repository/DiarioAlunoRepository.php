<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Educacao\Escola\Repository;

use Aluno;
use cl_diarioaluno;
use ECidade\Educacao\Escola\Model\DiarioAluno;
use Etapa;
use Exception;
use Turma;

/**
 * Class DiarioAlunoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class DiarioAlunoRepository extends Repository
{
    /**
     * @param DiarioAluno $diarioAluno
     * @throws Exception
     */
    private static function buscarResultado(DiarioAluno $diarioAluno)
    {
        $repository = new DiarioAlunoResultadoFinalRepository();
        $resultadoFinal = $repository->scopeDiarioAluno($diarioAluno)->first();
        if (!is_null($resultadoFinal)) {
            $diarioAluno->setResultadoFinal($resultadoFinal);
        }
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @throws Exception
     */
    private static function buscarDiarioArea(DiarioAluno $diarioAluno)
    {
        $repository = new DiarioAreaRepository();
        $diarioAluno->setDiarioAreasConhecimento($repository->scopeDiarioAluno($diarioAluno)->get());
    }

    /**
     * @return DiarioAluno[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_diarioaluno();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar diário do aluno.");
        }

        $diarios = [];

        while ($state = pg_fetch_array($rs)) {
            $diarioAluno = DiarioAluno::fromState($state);
            self::buscarDiarioArea($diarioAluno);
            self::buscarResultado($diarioAluno);
            $diarios[] = $diarioAluno;
        }

        return $diarios;
    }

    /**
     * @return DiarioAluno|null
     * @throws Exception
     */
    public function first()
    {
        $diarios = $this->get();
        if (empty($diarios)) {
            return null;
        }

        return array_shift($diarios);
    }

    /**
     * @param $key
     * @return DiarioAluno
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new cl_diarioaluno();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar diário do aluno.");
        }

        $diarioAluno = DiarioAluno::fromState(pg_fetch_array($rs));
        self::buscarDiarioArea($diarioAluno);
        self::buscarResultado($diarioAluno);

        return $diarioAluno;
    }

    /**
     * @param Aluno $aluno
     * @return $this]
     */
    public function scopeAluno(Aluno $aluno)
    {
        $this->scopes['aluno'] = "ed161_aluno = {$aluno->getCodigoAluno()}";
        return $this;
    }

    /**
     * @param Turma $aturma
     * @return $this
     */
    public function scopeTurma(Turma $aturma)
    {
        $this->scopes['turma'] = "ed161_turma = {$aturma->getCodigo()}";
        return $this;
    }

    /**
     * @param Etapa $etapa
     * @return $this
     */
    public function scopeEtapa(Etapa $etapa)
    {
        $this->scopes['etapa'] = "ed161_serie = {$etapa->getCodigo()}";
        return $this;
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @return DiarioAluno
     * @throws Exception
     */
    public function salvar(DiarioAluno $diarioAluno)
    {
        $dao = new cl_diarioaluno();
        $dao->ed161_codigo = $diarioAluno->getCodigo();
        $dao->ed161_aluno = $diarioAluno->getAluno()->getCodigoAluno();
        $dao->ed161_turma = $diarioAluno->getTurma()->getCodigo();
        $dao->ed161_serie = $diarioAluno->getEtapa()->getCodigo();
        $dao->ed161_encerrado = $diarioAluno->isEncerrado() ? 'true' : 'false';

        if (empty($dao->ed161_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed161_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar o diário do aluno.");
        }

        $diarioAluno->setCodigo($dao->ed161_codigo);

        return $diarioAluno;
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @return bool
     * @throws Exception
     */
    public function excluir(DiarioAluno $diarioAluno)
    {
        $dao = new cl_diarioaluno();
        $dao->excluir($diarioAluno->getCodigo());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao remover o diario do aluno.");
        }

        return true;
    }
}
