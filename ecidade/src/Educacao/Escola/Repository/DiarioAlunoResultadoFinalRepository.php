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

use cl_diarioalunoresultadofinal;
use ECidade\Educacao\Escola\Model\DiarioAluno;
use ECidade\Educacao\Escola\Model\DiarioAlunoResultadoFinal;
use Exception;

/**
 * Class DiarioAlunoResultadoFinalRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class DiarioAlunoResultadoFinalRepository extends Repository
{
    /**
     * @param $key
     * @return DiarioAlunoResultadoFinal
     * @throws Exception
     */
    private static function find($key)
    {
        $dao = new cl_diarioalunoresultadofinal();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o resultado.");
        }

        return DiarioAlunoResultadoFinal::fromState(pg_fetch_array($rs));
    }

    /**
     * @return DiarioAlunoResultadoFinal[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_diarioalunoresultadofinal();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os resultados.");
        }

        $avaliacoes = [];
        while ($state = pg_fetch_array($rs)) {
            $avaliacoes[] = DiarioAlunoResultadoFinal::fromState($state);
        }

        return $avaliacoes;
    }

    /**
     * @return DiarioAlunoResultadoFinal|null
     * @throws Exception
     */
    public function first()
    {
        $resultados = $this->get();
        if (empty($resultados)) {
            return null;
        }

        return array_shift($resultados);
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @return $this
     */
    public function scopeDiarioAluno(DiarioAluno $diarioAluno)
    {
        $this->scopes['diario_aluno'] = "ed165_diarioaluno = {$diarioAluno->getCodigo()}";
        return $this;
    }

    public function findOrCreate(DiarioAluno $diarioAluno)
    {
        $diarioResultadoFinal = $this->scopeDiarioAluno($diarioAluno)->first();
        if (is_null($diarioResultadoFinal)) {
            $diarioResultadoFinal = new DiarioAlunoResultadoFinal();
            $diarioResultadoFinal->setDiarioAluno($diarioAluno);

            $this->salvar($diarioResultadoFinal);
        }

        return $diarioResultadoFinal;
    }

    /**
     * @param DiarioAlunoResultadoFinal $diarioResultadoFinal
     * @return DiarioAlunoResultadoFinal
     * @throws Exception
     */
    public function salvar(DiarioAlunoResultadoFinal $diarioResultadoFinal)
    {
        $dao = new cl_diarioalunoresultadofinal();
        $dao->ed165_codigo = $diarioResultadoFinal->getCodigo();
        $dao->ed165_diarioaluno = $diarioResultadoFinal->getDiarioAluno()->getCodigo();
        $dao->ed165_resultado_final = $diarioResultadoFinal->getResultadoFinal();

        if (empty($dao->ed165_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed165_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao gerar diário final." . $dao->erro_msg);
        }

        $diarioResultadoFinal->setCodigo($dao->ed165_codigo);

        return $diarioResultadoFinal;
    }
}
