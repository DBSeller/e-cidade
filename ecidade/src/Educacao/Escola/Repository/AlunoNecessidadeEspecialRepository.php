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

use cl_alunonecessidade;
use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Model\AlunoNecessidadeEspecial;
use Escola;
use mysql_xdevapi\Exception;

class AlunoNecessidadeEspecialRepository extends Repository
{
    /**
     * @param string $campos
     * @return AlunoNecessidadeEspecial[]
     */
    public function get($campos = "*")
    {
        $dao = new cl_alunonecessidade();
        $sql = $dao->sql_query_file(null, $campos, null, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar deficiência(s) do aluno.");
        }

        if (pg_num_rows($rs) === 0) {
            return array();
        }

        $necessidades = array();
        while ($state = pg_fetch_array($rs)) {
            $necessidades[] = AlunoNecessidadeEspecial::fromState($state);
        }

        return $necessidades;
    }

    /**
     * @param Aluno $aluno
     * @param string $operador
     * @return AlunoNecessidadeEspecialRepository
     */
    public function scopeAluno(Aluno $aluno, $operador = '=')
    {
        $this->scopes['aluno'] = "ed214_i_aluno {$operador} {$aluno->getCodigo()}";
        return $this;
    }

    /**
     * @param Escola $escola
     * @param string $operador
     * @return AlunoNecessidadeEspecialRepository
     */
    public function scopeEscola(Escola $escola, $operador = '=')
    {
        $this->scopes['aluno'] = "ed214_i_escola {$operador} {$escola->getCodigo()}";
        return $this;
    }

    /**
     * @param array $codigos
     * @param string $operador
     * @return AlunoNecessidadeEspecialRepository
     */
    public function scopeNecessidade(array $codigos, $operador = ' in ')
    {
        $this->scopes['necessidades'] = " ed214_i_necessidade {$operador} ( " . implode(', ', $codigos) . ")";
        return $this;
    }
}
