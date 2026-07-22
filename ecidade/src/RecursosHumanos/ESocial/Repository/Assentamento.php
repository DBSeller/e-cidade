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

use ECidade\RecursosHumanos\ESocial\Service\AfastamentoTemporarioService;

/**
 * Class Assentamento
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class Assentamento
{
    public function __construct()
    {
    }

    /**
     * Verifica se o assentamento do E-Cidade esta vinculado com um afastamento do eSocial
     * @param \Assentamento $assentamentoEcidade
     * @return bool
     * @throws \DBException
     */
    public static function possuiVinculoComESocial(\Assentamento $assentamentoEcidade)
    {
        $daoAssentamento = new \cl_afastamentosesocial();
        $buscaVinculo = $daoAssentamento->sql_query_file(
            null,
            "*",
            null,
            "eso08_tipoasse = {$assentamentoEcidade->getInstanciaTipoAssentamento()->getSequencial()}"
        );
        $buscaVinculo  = db_query($buscaVinculo);
        if (!$buscaVinculo) {
            $mensagem = "Não foi possível consultar a existência do vínculo entre o Assentamento do E-Cidade com"
                . " o ESocial.";
            throw new \DBException($mensagem);
        }
        return pg_num_rows($buscaVinculo) > 0;
    }

    /**
     * @param \Assentamento $assentamento
     * @param \DBDate $dataAtual
     * @throws \DBException
     * @throws \ParameterException
     * @return boolean
     */
    public static function salvarFormulario(\Assentamento $assentamento, \DBDate $dataAtual)
    {

        $dataImplantacaoEsocial = new \ECidade\RecursosHumanos\ESocial\Configuracao\S2230();
        $dataEnvio = new \DBDate($dataImplantacaoEsocial->getPropriedade('data_envio'));
        if ($dataAtual->getTimeStamp() >= $dataEnvio->getTimeStamp() && self::possuiVinculoComESocial($assentamento)) {
            $afastamento = new AfastamentoTemporarioService(
                $assentamento->getServidor()->getMatricula(),
                $assentamento->getCodigo()
            );
            $afastamento->preencherFormulario();
            return true;
        }
        return false;
    }

    /**
     * Exclui os vinculos do assentamento com o formulário
     * @param $assentamento
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public static function excluirFormulario($assentamento)
    {
        if (self::possuiVinculoComESocial($assentamento)) {
            $afastamento = new AfastamentoTemporarioService(
                $assentamento->getServidor()->getMatricula(),
                $assentamento->getCodigo()
            );
            $afastamento->excluirFormulario();
        }
    }
}
