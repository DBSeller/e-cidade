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

use cl_db_cadattdinamicoatributosvalor;
use db_utils;
use DBException;
use ECidade\RecursosHumanos\RH\Assentamento\Model\AtributoDinamico as Model;
use function db_query;

/**
 * Class Efetividade
 * @package Ecidade\RecursosHumanos\RH\Assentamento\Repository
 */
class AtributoDinamico extends \BaseClassRepository
{
    public static function incluir(Model $atributo)
    {
        $dao = new cl_db_cadattdinamicoatributosvalor();
        $dao->db110_cadattdinamicovalorgrupo = $atributo->getGrupo();
        $dao->db110_db_cadattdinamicoatributos = $atributo->getAtributo();
        $dao->db110_valor = $atributo->getValor();

        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception($dao->erro_msg);
        }
    }

    /**
     * @param $codigoGrupo
     * @return array
     */
    public static function getByGrupo($codigoGrupo)
    {
        $dao = new cl_db_cadattdinamicoatributosvalor();
        $campos = "db110_db_cadattdinamicoatributos as atributo, db110_valor as valor";
        $where = "db110_cadattdinamicovalorgrupo={$codigoGrupo}";

        $sql = $dao->sql_query_file(null, $campos, null, $where);
        $rs = db_query($sql);

        $grupoAtributos = db_utils::getCollectionByRecord($rs);
        $retorno = array();

        foreach ($grupoAtributos as $atributo) {
            $retorno[$atributo->atributo] = $atributo->valor;
        }

        return $retorno;
    }

    /**
     * @param $codigoGrupo
     * @return bool
     * @throws DBException
     */
    public static function deleteByGrupo($codigoGrupo)
    {
        $sql = "DELETE FROM db_cadattdinamicoatributosvalor WHERE db110_cadattdinamicovalorgrupo={$codigoGrupo}";
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException("Houve um erro ao excluir as informações dos formulario do assentamento.");
        }
        return true;
    }
}
