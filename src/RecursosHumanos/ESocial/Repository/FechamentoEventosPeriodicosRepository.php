<?php
/**
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
namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_avaliacaogruporespostaesocials1299;
use db_utils;
use DBException;
use ParameterException;

/**
 * Class FechamentoEventosPeriodicosRepository
 */
class FechamentoEventosPeriodicosRepository
{
    /**
     * @param $codigoCgmEmpregador
     * @param $ano
     * @param null $mes
     * @return mixed
     * @throws DBException
     * @throws ParameterException
     */
    public static function buscarReferenciaPorCompetenciaEmpregador($codigoCgmEmpregador, $ano, $mes = null)
    {
        if(empty($codigoCgmEmpregador)) {
            throw new ParameterException("CGM do empregador não informado.");
        }

        if(empty($ano)) {
            throw new ParameterException("Ano do período de apuração não informado.");
        }

        $dao = new cl_avaliacaogruporespostaesocials1299();
        $periodo = $ano;

        if (!empty($mes)) {
            $periodo .= "-{$mes}";
        }

        $where = " eso33_empregador = {$codigoCgmEmpregador} ";
        $where .= " AND eso33_periodo = '{$periodo}' ";
        $sql = $dao->sql_query_file(null, "eso33_sequencial", null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException("Não foi possível consultar a resposta do fechamento para a competência {$periodo}.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new DBException("Não foi encontrado fechamento para o período {$periodo}.");
        }

        return db_utils::fieldsMemory($rs, 0)->eso33_sequencial;
    }
}
