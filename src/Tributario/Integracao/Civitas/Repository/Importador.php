<?php
/**
 *     E-cidade Software protectedo para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca protecteda Geral GNU, conforme
 *  protectedada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca protecteda Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca protecteda Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


namespace ECidade\Tributario\Integracao\Civitas\Repository;

use ECidade\Tributario\Integracao\Civitas\Model\Importador as ImportadorModel;

/**
* Repository do importador de arquivos do Civitas
* @author Alysson Zanette <alysson.zanette@dbseller.com.br>
*/
class Importador
{

    /**
     * @var CODIGO_PENDENTE
     */
    const CODIGO_PENDENTE = 3;

    /**
     * @var CODIGO_ERRO
     */
    const CODIGO_ERRO = 2;

    /**
     * @var CODIGO_SUCESSO
     */
    const CODIGO_SUCESSO  = 1;

    private static $log;

    /**
     * Retorna um Importador de arquivos do civitas
     * @param array $aArquivos
     * @return ImportadorModel
     */
    public static function getImportador($aArquivos = array())
    {
        return new ImportadorModel($aArquivos);
    }


    /**
     *
     * @param $situacao
     * @return mixed
     * @throws \DBException
     */
    public  static function getTipoSituacao($situacao)
    {
        $daoSituacao = new \cl_requisicaocivitassituacao();

        $querySituacao = $daoSituacao->sql_query_file(null, "rq02_sequencial", null, "rq02_codigo = $situacao");
        $recordSituacao = $daoSituacao->sql_record($querySituacao);


        if (empty($recordSituacao)) {
            throw new \DBException("Erro ao buscar a situação da requisição");
        }

        $resultadoSituacao = \db_utils::fieldsMemory($recordSituacao, 0);

        return $resultadoSituacao->rq02_sequencial;
    }


    /**
     * @param $situacao
     * @param null $sequecialRequisicao
     * @param $data
     * @return mixed|null
     * @throws \DBException
     */
    public static function atualizarSituacao($situacao, $sequecialRequisicao = null,  $data)
    {

        $daoRequisicao = new \cl_requisicaocivitas();

        $daoRequisicao->rq01_situacao  = self::getTipoSituacao($situacao);

        $logs = self::getLog();

        $daoRequisicao->rq01_descricao = (!empty($logs) ?  pg_escape_string($logs) : '');


        if (empty($sequecialRequisicao)) {

            if (empty($data) && !self::validateDate($data)) {
                throw new \DBException("Erro ao atualizar  a situacao data invalida.");
            }

            $daoRequisicao->rq01_dataenvio = $data;
            $resultado = $daoRequisicao->incluir();
            $operacao = "incluir";

        } else {

            $resultado = self::alteraSituacao($sequecialRequisicao, $situacao, $daoRequisicao->rq01_descricao);
            $operacao = "alterar";
        }

        if (empty($resultado)) {
            throw new \DBException("Erro ao $operacao dados da requisição.  ". $daoRequisicao->erro_msg);
        }

        return $daoRequisicao->rq01_sequencial;
    }


    public static function alteraSituacao($sequecialRequisicao, $situacao, $descricao = '')
    {

        $camposDesc = '';
        $where = "WHERE rq01_sequencial = " . $sequecialRequisicao;

        if (!empty($descricao)) {
            $camposDesc =  ", rq01_descricao = '". $descricao . "'";
        }

        $sSql = sprintf("UPDATE  requisicaocivitas SET rq01_situacao = %s  %s  %s", $situacao, $camposDesc, $where);

         $rsAlterar = db_query($sSql);

         if (!$rsAlterar) {
             return false;
         }

         return true;
    }


    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


    /**
     * @return mixed
     */
    public function getLog()
    {
        if (!empty(self::$log) && is_array(self::$log)) {

            $logs  = \DBString::utf8_encode_all(self::$log);

            return json_encode($logs);
        }

        return self::$log;
    }

    /**
     * @param $log
     */
    public static function setLog($log)
    {
       self::$log = $log;
    }

}
