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

namespace ECidade\Financeiro\Contabilidade\Encerramento;

use BaseClassRepository;
use cl_condataconf;
use Closure;
use DBException;

/**
 * Class Repository
 * @package ECidade\Financeiro\Contabilidade\Encerramento
 */
class Repository extends BaseClassRepository
{
    /**
     * @var Repository
     */
    protected static $oInstance;

    /**
     * @param $sequencialInstituicao
     * @param $data
     * @param $codigoUsuario
     * @param $ano
     * @throws DBException
     */
    public function encerrarContabilidadeByPeriodo($sequencialInstituicao, $data, $codigoUsuario, $ano)
    {
        $daoConDataConf = new cl_condataconf();
        $daoConDataConf->c99_data = $data;
        $daoConDataConf->c99_usuario = $codigoUsuario;
        $daoConDataConf->c99_anousu = $ano;
        $daoConDataConf->c99_instit = $sequencialInstituicao;

        $resultado = $daoConDataConf->alterar($ano, $sequencialInstituicao);

        if ($resultado && $daoConDataConf->numrows_alterar == 0) {
            $resultado = $daoConDataConf->incluir($ano, $sequencialInstituicao);
        }

        if (!$resultado) {
            throw new DBException("Ocorreu um erro ao encerrar o período contábil.");
        }
    }

    /**
     * @param $sequencialInstituicao
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function cancelarEncerramento($sequencialInstituicao, $data)
    {
        $where = implode(" and ", array(
            "c99_instit = {$sequencialInstituicao}",
            "c99_data = '{$data}'",
        ));
        $daoConDataConf = new cl_condataconf();
        $daoConDataConf->excluir(null, null, $where);
        if ($daoConDataConf->erro_status === "0") {
            throw new \Exception("Não foi possível excluir o fechamento do período contábil.");
        }
        return true;
    }

    public function getDataUltimoEncerramento($sequencialInstituicao, $ano)
    {
        $daoConDataConf = new cl_condataconf();
        $sqlConsulta = $daoConDataConf->sql_query_file($ano, $sequencialInstituicao, "c99_data as data");
        $recordSet = db_query($sqlConsulta);

        if (!$recordSet) {
            throw new DBException("Ocorreu um erro ao verificar a data do último encerramento contábil.");
        }

        $data = \db_utils::makeFromRecord($recordSet, function ($retorno) {

            if (is_object($retorno)) {
                return $retorno->data;
            }

            return null;
        });

        return $data;
    }
}
