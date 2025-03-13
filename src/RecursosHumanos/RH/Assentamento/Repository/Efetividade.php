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

namespace ECidade\RecursosHumanos\RH\Assentamento\Repository;

/**
 * Class Efetividade
 * @package Ecidade\RecursosHumanos\RH\Assentamento\Repository
 */
class Efetividade extends \BaseClassRepository
{
    /**
     * @param Integer $codigoInstit
     * @param String $dataInicial
     * @param String|null $dataFinal
     */
    public static function efetividadeProcessada($codigoInstit, $dataInicial, $dataFinal = null)
    {
        $dao = new \cl_configuracoesdatasefetividade();
        $where = "rh186_processado is true AND rh186_instituicao = " . $codigoInstit;

        if (empty($dataFinal)) {
            $where .= " and '{$dataInicial}' between "
                . "rh186_datainicioefetividade and rh186_datafechamentoefetividade";
        } else {
            $where .= " and (('{$dataInicial}' between "
                . "rh186_datainicioefetividade and rh186_datafechamentoefetividade) "
                . "or ('{$dataFinal}' between rh186_datainicioefetividade "
                . "and rh186_datafechamentoefetividade))";
        }
        $sql = $dao->sql_query_file(
            null,
            "*",
            null,
            $where
        );
        $rs = db_query($sql);
        if (!$rs) {
            throw new \DBException("Erro ao buscar informações da efetividade Aberta no período.");
        }

        if (pg_num_rows($rs) > 0) {
            return true;
        }
        return false;
    }
}
