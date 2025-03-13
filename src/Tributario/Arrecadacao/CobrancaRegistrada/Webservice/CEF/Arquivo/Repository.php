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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo;

use _db_fields;
use BusinessException;
use db_utils;
use DBException;
use DBString;
use ECidade\Tributario\Arrecadacao\Convenio;
use stdClass;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

/**
 * Repository para os dados que serão incluidos do arquivo
 * de requisição ao Webservice da CEF
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class Repository
{

    /**
     * Define qual cgm sera utilizado
     *
     * @param $param
     * @param $iNumpre
     * @return _db_fields|stdClass
     * @throws DBException
     */
    private function defineCgm($param, $iNumpre)
    {
        $oDaoRecibopaga = new \cl_recibopaga();
        $iCgm = 0;
        $tipo = key($param);

        switch ($tipo) {
            case 'cgm':
                $iCgm = $param[$tipo];

                break;

            case 'matricula':
                $oInstit = \db_stdClass::getDadosInstit();

                $sPrincipal = "false";

                if ($oInstit->db21_regracgmiptu) {
                    $sPrincipal = "true";
                }

                $sSqlCgm = $oDaoRecibopaga->sql_query_cgm_webservice_caixa(
                    $sPrincipal,
                    $oInstit->db21_regracgmiptu,
                    'M',
                    $param[$tipo]
                );

                $rsCgmProp   = $oDaoRecibopaga->sql_record($sSqlCgm);

                $oCgmPro = db_utils::fieldsMemory($rsCgmProp, 0);

                $iCgm    = $oCgmPro->rinumcgm;
                break;

            case 'inscricao':
                $where   = " k00_numnov = {$iNumpre}";
                $sSqlCgm = $oDaoRecibopaga->sql_query_file(null, " k00_numcgm as cgm ", null, $where);
                $rsCgm   = $oDaoRecibopaga->sql_record($sSqlCgm);
                $oCgm    = db_utils::fieldsMemory($rsCgm, 0);

                $iCgm    = $oCgm->cgm;

                break;

            default:
                $sCampos = "z01_nome, z01_cgccpf";
                $sSqlCgm = $oDaoRecibopaga->sql_query_cgm($sCampos, $iNumpre);
                $rsCgm   = $oDaoRecibopaga->sql_record($sSqlCgm);

                if (!$rsCgm) {
                    throw new DBException(
                        "Erro ao buscar o CGM vinculado ao recibo para realizar o registro bancário."
                    );
                }

                $oCgm = db_utils::fieldsMemory($rsCgm, 0);
                return $oCgm;
                break;
        }

        $sSqlCgm = $oDaoRecibopaga->sql_query_info_cgm($iCgm);
        $rsCgm   = $oDaoRecibopaga->sql_record($sSqlCgm);
        $oCgm = db_utils::fieldsMemory($rsCgm, 0);

        return  $oCgm;
    }

    /**
     * @param $iNumpre
     * @param $iConvenio
     * @param $nValor
     * @param array $aEmitirPor
     * @return stdClass
     * @throws DBException
     * @throws BusinessException
     */
    public function getDadosIncluiBoleto($iNumpre, $iConvenio, $nValor, $aEmitirPor = array())
    {
        $oRegistro = new stdClass();
        $oRegistro->tipoEspecie        = "02";
        $oRegistro->flagAceite         = "S";
        $oRegistro->tipo               = "ISENTO";
        $oRegistro->acao               = "DEVOLVER";
        $oRegistro->numeroDias         = "00";
        $oRegistro->codigoMoeda        = "09";
        $oRegistro->flagRegistro       = "S";

        $sSqlMsgRecibo = "select k00_msgrecibo
                          from recibopaga inner join arrecad  on arrecad.k00_numpre = recibopaga.k00_numpre
                                                             and arrecad.k00_numpar = recibopaga.k00_numpar
                                                             and arrecad.k00_receit = recibopaga.k00_receit
                                          inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                          where k00_numnov = {$iNumpre} limit 1
                         ";

        $rsMsgRecibo = db_query($sSqlMsgRecibo);

        if (!$rsMsgRecibo) {
            throw new DBException("Erro ao buscar a mensagem do tipo de débito.");
        }

        $oRegistro->mensagemRecibo = null;
        if (pg_num_rows($rsMsgRecibo) > 0) {
            $oRegistro->mensagemRecibo = db_utils::fieldsMemory($rsMsgRecibo, 0)->k00_msgrecibo;
        }

        $oConvenio = new Convenio($iConvenio);

        /**
         * Dados do Recibo
         */
        $oDaoRecibopaga = new \cl_recibopaga();
        $sSqlRecibopaga = $oDaoRecibopaga->sql_query_dadosRecibo($iNumpre);
        $rsRecibo       = db_query($sSqlRecibopaga);

        if (!$rsRecibo) {
            throw new DBException("Erro ao buscar os dados do recibo para realizar o registro bancário.");
        }

        if (pg_num_rows($rsRecibo) == 0) {

            /**
             * Dados Recibo Avulso
             */
            $oDaoReciboavulso =  new \cl_recibo();
            $sSqlReciboavulso =  $oDaoReciboavulso->sql_query_dadosReciboAvulso($iNumpre);
            $rsRecibo         =  $oDaoReciboavulso->sql_record($sSqlReciboavulso);

            if (!$rsRecibo) {
                throw new DBException("Erro ao buscar os dados do recibo avulso para realizar o registro bancário.");
            }
        }

        $oRecibo = db_utils::fieldsMemory($rsRecibo, 0);

        $oRegistro->codigoBeneficiario = $oConvenio->getCedente();
        $oRegistro->nossoNumero        = $oRecibo->nosso_numero;
        $oRegistro->numeroDocumento    = $iNumpre . "000";
        $oRegistro->dataVencimento     = $oRecibo->data_vencimento;
        $oRegistro->valor              = (string) db_formatar($nValor, 'p', ' ', strlen($nValor));
        $oRegistro->valor              = str_pad($oRegistro->valor, 16, '0', STR_PAD_LEFT);
        $oRegistro->valorJuros         = (string) db_formatar(0, 'p', ' ', strlen('0'));
        $oRegistro->valorJuros         = str_pad($oRegistro->valorJuros, 16, '0', STR_PAD_LEFT);
        $oRegistro->data               = $oRecibo->data_emissao;

        /**
         * Dados do CGM
         */
        if (!isset($oRecibo->numpre_debito)) {
            $oRecibo->numpre_debito = $iNumpre;
        }

        if (key($aEmitirPor) == 'inscricao') {
            $oCgm = $this->defineCgm($aEmitirPor, $iNumpre);
        } else {
            $oCgm = $this->defineCgm($aEmitirPor, $oRecibo->numpre_debito);
        }

        if (!DBString::isCNPJ($oCgm->z01_cgccpf) && !DBString::isCPF($oCgm->z01_cgccpf)) {
            throw new DBException("Erro ao processar a solicitação, CPF ou CNPJ inválido para esse CGM.");
        }

        $oRegistro->cpfcnpj = $oCgm->z01_cgccpf;
        $oRegistro->nome = DBString::removerCaracteresEspeciais($oCgm->z01_nome);

        /**
         * Dados do CGM
         */
        $oDaoDbConfig  = new \cl_db_config();
        $oSqlDbConfig  = $oDaoDbConfig->sql_query(db_getsession("DB_instit"));
        $rsInstituicao = $oDaoDbConfig->sql_record($oSqlDbConfig);

        if (!$rsInstituicao) {
            throw new DBException("Erro ao buscar os dados da Instituição.");
        }

        $oInstituicao = db_utils::fieldsMemory($rsInstituicao, 0);

        if (empty($oInstituicao->cgc)) {
            throw new BusinessException("Instituição com o cnpj inválido.");
        }

        /**
         * Hash de Autenticação
         */
        $oAutenticacao = new Autenticacao(
            $oRegistro->codigoBeneficiario,
            $oRegistro->nossoNumero,
            $oRegistro->dataVencimento,
            $oRegistro->valor,
            $oInstituicao->cgc
        );

        $oRegistro->autenticacao = $oAutenticacao->getHash();

        $cobrancaRegistrada = new CobrancaRegistrada();

        $oConvenio = $cobrancaRegistrada->getConvenio($iConvenio);
        $oBanco    = $cobrancaRegistrada->getBancoConvenio($iConvenio);

        /**
         * Usuário do webservice
         */
        $oDaoParametro = new \cl_parametroscobrancaregistrada();
        $sSqlParametro = $oDaoParametro->sql_query("*", "", "", "ar28_codban = '{$oBanco->db89_db_bancos}'");
        $rsParametro   = $oDaoParametro->sql_record($sSqlParametro);

        if (!$rsParametro) {
            throw new DBException("Não foi encontrado o usuário do webservice da CEF.");
        }

        $oParametro = db_utils::fieldsMemory($rsParametro, 0);

        $oRegistro->usuarioServico = $oParametro->ar28_usuario;

        return $oRegistro;
    }
}
