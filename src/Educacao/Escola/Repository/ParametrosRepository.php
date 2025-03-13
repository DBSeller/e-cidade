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

use cl_edu_parametros;
use ECidade\Educacao\Escola\Model\Parametros;
use Escola;

/**
 * Class ParametrosRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class ParametrosRepository extends Repository
{
    /**
     * @param Escola $escola
     * @param string $campos
     * @return Parametros
     */
    public static function getFromEscola(Escola $escola, $campos = '*')
    {
        $where = array("ed233_i_escola = {$escola->getCodigo()}");
        $dao = new cl_edu_parametros();
        $sql = $dao->sql_query_file(null, $campos, null, implode(' and ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar parâmetros.");
        }

        if (pg_num_rows($rs) === 0) {
            return array();
        }

        return Parametros::fromState(pg_fetch_array($rs));
    }
}
