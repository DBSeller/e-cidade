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

namespace ECidade\Tributario\Arrecadacao\CGF\Repository;

use \cl_inicial;
use \cl_certid;

/**
 * Repository para operações com certidões na geral financeira.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Certidao
{
    /**
     * Buscamos as cdas e suas origens por inicial
     *
     * @param $inicial
     *
     * @return array
     *
     * @throws \DBException
     */
    public static function getCdaByInicial($inicial)
    {
        $daoInicial = new cl_inicial();
        $sql = $daoInicial->sql_query_FiltrarCdaPorInicial($inicial);
        $record = \db_query($sql);

        if (empty($record)) {
            throw new \DBException("Erro ao buscar CDA(s) da inicial " . $inicial . ".");
        }

        $retorno = \db_utils::makeCollectionFromRecord($record, function ($object) {
            return $object;
        });

        return $retorno;
    }

    /**
     * Retorna situação da CDA.
     *
     * @param $certidao
     *
     * @return mixed
     *
     * @throws \DBException
     */
    public static function getSituacaoCda($certidao)
    {
        $daoCertid = new cl_certid();

        $sql = $daoCertid->sql_queryConsultaCertidao("certidao = $certidao", $certidao);
        $sqlSituacao = $sql;

        if (self::isAtivoPluginCRA()) {
            $sql = $daoCertid->sql_queryBuscarOcorrenciasCda($certidao);
        }

        $rsOcorrencia = \db_query($sql);

        if (pg_num_rows($rsOcorrencia) == 0) {
            $rsOcorrencia = \db_query($sqlSituacao);
        }

        if (!$rsOcorrencia) {
            throw new \DBException("Erro ao buscar a situação da CDA " . $certidao . ".");
        }

        // Caso tiver 2 linhas, CDA será Ativa e Anulada, ou seja manter somente como ativa
        if (pg_num_rows($rsOcorrencia) == 2) {
            return "Ativa";
        } else {
            return \db_utils::fieldsMemory($rsOcorrencia, 0)->dl_situacao;
        }
    }

    /**
     * Retorna situação do débito da CDA.
     *
     * @param $certidao
     *
     * @return mixed
     *
     * @throws \DBException
     */
    public static function getSituacaoDebito($certidao)
    {
        $daoCertid = new cl_certid();

        $sql = $daoCertid->sql_queryConsultaCertidaoSituacaoDebito("certidao = $certidao", $certidao);
        
        $rsSituacaoDebito = \db_query($sql);

        if (!$rsSituacaoDebito) {
            throw new \DBException("Erro ao buscar a situação do débito da CDA " . $certidao . ".");
        }

        return \db_utils::fieldsMemory($rsSituacaoDebito, 0)->situacao;
    }

    /**
     * Retorna se plugin do CRA está ativo.
     *
     * @return bool
     *
     * @throws \DBException
     */
    private static function isAtivoPluginCRA()
    {
        $daoPlugin = new \cl_db_plugin();
        $sql = $daoPlugin->sql_query_file(null, "db145_situacao", null, "db145_nome = 'IntegracaoCRA'");
        $rsPlugin = \db_query($sql);

        if (!$rsPlugin) {
            throw new \DBException("Erro ao buscar situação do plugin: Integracao CRA. ");
        }

        if (pg_num_rows($rsPlugin) == 0) {
            return false;
        }

        $situacao = \db_utils::fieldsMemory($rsPlugin, 0)->db145_situacao;

        if ($situacao == 'f') {
            return false;
        }

        return true;
    }
}
