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

use db_utils;
use DBException;
use ECidade\RecursosHumanos\RH\Assentamento\Model\AssentamentoAtributoDinamico as Model;
use Exception;

/**
 * Class Efetividade
 * @package Ecidade\RecursosHumanos\RH\Assentamento\Repository
 */
class AssentamentoAtributoDinamico extends \BaseClassRepository
{
    /**
     * @param Model $assentamentoAtributoDinamico
     * @throws Exception
     */
    public static function incluir(Model $assentamentoAtributoDinamico)
    {
        $dao = new \cl_assentadb_cadattdinamicovalorgrupo();
        $dao->incluir(
            $assentamentoAtributoDinamico->getCodigoAssentamento(),
            $assentamentoAtributoDinamico->getCodigoGrupo()
        );

        if ($dao->erro_status == "0") {
            throw new Exception($dao->erro_msg);
        }
    }

    /**
     * @param $codigoAssentamento
     * @return bool
     * @throws DBException | Exception
     */
    public static function deleteByAssentamento($codigoAssentamento)
    {
        if (empty($codigoAssentamento)) {
            return false;
        }
        $assentamentoGrupos = self::getByAssentamento($codigoAssentamento);
        foreach ($assentamentoGrupos as $grupo) {
            AtributoDinamico::deleteByGrupo($grupo->getCodigoGrupo());
            self::delete($grupo->getCodigoGrupo());
            AtributoDinamicoGrupo::delete($grupo->getCodigoGrupo());
        }
        return true;
    }

    /**
     * @param $codigoGrupo
     * @return bool
     * @throws DBException
     */
    public static function delete($codigoGrupo)
    {
        $sql = "delete from recursoshumanos.assentadb_cadattdinamicovalorgrupo "
            . " where h80_db_cadattdinamicovalorgrupo = {$codigoGrupo}";
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException("Houve um erro ao tentar excluir o formulário preenchido do assentamento.");
        }
        return true;
    }
    /**
     * @param $codigoAssentamento
     * @return array
     * @throws DBException
     */
    public static function getByAssentamento($codigoAssentamento)
    {
        $retorno = array();

        $sql = "select * from assentadb_cadattdinamicovalorgrupo where h80_assenta = $codigoAssentamento";
        $rs = db_query($sql);
        if (!$rs) {
            throw new DBException("erro pr daniel escrever");
        }
        for ($i = 0; $i < pg_num_rows($rs); $i++) {
            $registro = new Model();
            $reg = db_utils::fieldsMemory($rs, $i);
            $registro->setCodigoGrupo($reg->h80_db_cadattdinamicovalorgrupo);
            $registro->setCodigoAssentamento($codigoAssentamento);
            $retorno[] = $registro;
        }

        return $retorno;
    }
}
