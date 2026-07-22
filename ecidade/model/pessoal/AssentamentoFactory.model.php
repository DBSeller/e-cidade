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

/**
 * Factory para o gerenciar os Assentamentos do sistema, será
 * responsavém por tratar os assentamentos e retornar uma instância
 * de Assentamento ou de AssentamentoSubstituicao
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package Pessoal
 */

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoControleMedico;

class AssentamentoFactory
{

    /**
     * Arquivo de mensagens
     */
    const MENSAGEM = "recursoshumanos.pessoal.AssentamentoFactory.";

    /**
     * @var Assentamento
     */
    private static $listaAssentamentos = array();

    private function __construct()
    {
    }

    /**
     * Retorna uma instância de AssentamentoSubstituicao caso o assentamento informado
     * por parâmetro for da Natureza substituição, caso contrário retorna uma
     * instância de Assentamento.
     *
     * @param  integer $iCodigoAssentamento
     * @return Assentamento|AssentamentoSubstituicao|AssentamentoRRA
     *
     * @throws BusinessException
     * @throws DBException
     */
    public static function getByCodigo($iCodigoAssentamento)
    {

        if (empty($iCodigoAssentamento)) {
            throw new BusinessException (self::MENSAGEM . 'codigo_nao_informado');
        }
        if (!empty(self::$listaAssentamentos[$iCodigoAssentamento])) {
            return self::$listaAssentamentos[$iCodigoAssentamento];
        }

        /**
         * Montamos um objeto de assentamento para recuperar o seu tipo e consultar a natureza
         */
        $oAssentamento = new Assentamento($iCodigoAssentamento);
        $iTipoAssentamento = $oAssentamento->getTipoAssentamento();

        if (empty($iTipoAssentamento)) {
            throw new BusinessException (self::MENSAGEM . 'erro_buscar_tipo_assentamento');
        }

        /**
         * Verificamos se a natureza do assentamento é substituição.
         */
        $oDaoTipoAsse = new cl_tipoasse();
        $rsTipoAsse = $oDaoTipoAsse->sql_record($oDaoTipoAsse->sql_query_file($iTipoAssentamento, "h12_natureza"));

        if (!$rsTipoAsse) {
            throw new DBException(self::MENSAGEM . 'erro_buscar_assentamentos');
        }
        $oStdTipoAssentamento = db_utils::fieldsMemory($rsTipoAsse, 0);

        $oAssentamentoRetorno = null;
        switch ($oStdTipoAssentamento->h12_natureza) {

            case Assentamento::NATUREZA_SUBSTITUICAO:

                $oAssentamentoRetorno = new AssentamentoSubstituicao($iCodigoAssentamento);
                break;

            case Assentamento::NATUREZA_RRA:

                $oAssentamentoRetorno =  new AssentamentoRRA($iCodigoAssentamento);
                break;

            case Assentamento::NATUREZA_JUSTIFICATIVA:

                $oAssentamentoRetorno = new \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa($iCodigoAssentamento);
                break;

            case Assentamento::NATUREZA_HE_MANUAL:
                $oAssentamentoRetorno = new \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual($iCodigoAssentamento);
                break;

            case Assentamento::NATUREZA_ABONO_FALTA:
                $oAssentamentoRetorno = new \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta($iCodigoAssentamento);
                break;
            case Assentamento::NATUREZA_CONTROLE_MEDICO:
                $oAssentamentoRetorno = new AssentamentoControleMedico($iCodigoAssentamento);
                break;

            default:
                $oAssentamentoRetorno = new Assentamento($iCodigoAssentamento);
                break;
        }
        self::$listaAssentamentos[$iCodigoAssentamento] = $oAssentamentoRetorno;
        return $oAssentamentoRetorno;
    }

    public static function getInstanciaPorNatureza($iTipoAssentamento)
    {

        /**
         * Verificamos qual a natureza do assentamento
         */
        $oDaoTipoAsse = new cl_tipoasse();
        $rsTipoAsse = $oDaoTipoAsse->sql_record($oDaoTipoAsse->sql_query_file($iTipoAssentamento, "h12_natureza"));

        if (!$rsTipoAsse) {
            throw new DBException(self::MENSAGEM . 'erro_buscar_assentamentos');
        }
        $oStdTipoAssentamento = db_utils::fieldsMemory($rsTipoAsse, 0);

        switch ($oStdTipoAssentamento->h12_natureza) {

            case Assentamento::NATUREZA_SUBSTITUICAO:

                return new AssentamentoSubstituicao();
                break;

            case Assentamento::NATUREZA_RRA:

                return new AssentamentoRRA();
                break;

            case Assentamento::NATUREZA_JUSTIFICATIVA:

                return new \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa();
                break;

            case Assentamento::NATUREZA_HE_MANUAL:
                return new \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual();
                break;

            case Assentamento::NATUREZA_ABONO_FALTA:
                return new \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta();
                break;

            default:
                return new Assentamento();
                break;
        }
    }
}
