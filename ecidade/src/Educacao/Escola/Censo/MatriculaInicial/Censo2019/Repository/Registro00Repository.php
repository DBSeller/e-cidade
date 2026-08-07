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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository;

use cl_escola;
use ECidade\Educacao\Escola\Repository\Repository;
use Escola;
use Exception;

class Registro00Repository extends Repository
{
    /**
     * @param Escola $escola
     * @return array
     * @throws Exception
     */
    public function getDadosCenso(Escola $escola)
    {
        $where = array(
            "ed18_i_codigo = {$escola->getCodigo()}",
        );
        
        $dao = new cl_escola();
        $sql = $dao->sql_censo($where);
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            throw new Exception("Não foi possível buscar os dados da escola.");
        }

        return pg_fetch_array($rs);
    }

    /**
     * @param Escola $escola
     * @param string $operator
     * @return $this
     */
    public function scopeEscola(Escola $escola, $operator = '=')
    {
        $this->scopes['escola'] = "ed38_i_escola {$operator} {$escola->getCodigo()}";
        return $this;
    }

    public function buscaDatasCalendarioEscolar(Escola $escola, $ano)
    {
        $dao = new cl_escola();
        $sql = $dao->sqlDatasCalendario($escola->getCodigo(), $ano);
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            throw new Exception("Não foi possível buscar as datas do calendario academico.");
        }

        return pg_fetch_array($rs);
    }
}
