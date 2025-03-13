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
namespace ECidade\Tributario\Arrecadacao;

/**
 * Classe responsavel por abastrair regras referente a proprietario
 *
 * @package ECidade\Tributario\Arrecadacao
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 */
class Proprietario
{

    /**
     * Metodo busca o proprietario atraves da matricula
     *
     * @param $matricula
     * @return _db_fields|stdClass
     */
    public static function getProprietarioByMatricula($matricula)
    {

        $oInstit = \db_stdClass::getDadosInstit();
        $oDaoRecibopaga = new \cl_recibopaga();
        $sPrincipal = "false";

        if ($oInstit->db21_regracgmiptu) {
            $sPrincipal = "true";
        }

        $sSqlCgm = $oDaoRecibopaga->sql_query_cgm_webservice_caixa(
            $sPrincipal,
            $oInstit->db21_regracgmiptu,
            'M',
            $matricula
        );

        $rsCgmProp = $oDaoRecibopaga->sql_record($sSqlCgm);

        $oCgmPro = \db_utils::fieldsMemory($rsCgmProp, 0);

        return  $oCgmPro;
    }

     /**
     * Metodo busca o proprietario atraves da inscricao
     *
     * @param $inscricao
     * @return _db_fields|stdClass
     */
    public static function getProprietarioByInscricao($inscricao)
    {

        $oInstit = \db_stdClass::getDadosInstit();
        $oDaoRecibopaga = new \cl_recibopaga();
        $sPrincipal = "false";

        if ($oInstit->db21_regracgmiptu) {
            $sPrincipal = "true";
        }

        $sSqlCgm = $oDaoRecibopaga->sql_query_cgm_webservice_caixa(
            $sPrincipal,
            $oInstit->db21_regracgmiptu,
            'I',
            $inscricao
        );

        $rsCgmProp = $oDaoRecibopaga->sql_record($sSqlCgm);

        $oCgmPro = \db_utils::fieldsMemory($rsCgmProp, 0);

        return  $oCgmPro;
    }
}
