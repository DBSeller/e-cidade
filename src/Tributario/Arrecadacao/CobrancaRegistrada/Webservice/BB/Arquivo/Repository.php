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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\BB\Arquivo;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

use _db_fields;
use BusinessException;
use db_utils;
use DBException;
use DBString;
use ECidade\Tributario\Arrecadacao\Convenio;
use stdClass;

/**
 * Repository para os dados que serão incluidos do arquivo
 * de requisição ao Webservice do BB
 *
 * @author Natanael Giacomini <natanael.giacomini@dbseller.com.br>
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
            throw new DBException("Não foi encontrado o ClientID e o Cliente Secret do webservice do BB.");
        }

        $oParametro = db_utils::fieldsMemory($rsParametro, 0);

        $oRegistro->clientId = $oParametro->ar28_clientid;
        $oRegistro->clientSecret = $oParametro->ar28_clientsecret;

        /**
         * Hash de Autenticação
         */
        $oAutenticacao = new Autenticacao(
            $oRegistro->clientId,
            $oRegistro->clientSecret
        );

        $oRegistro->codigoTipoInscricaoPagador = 2;

        if (strlen($oCgm->z01_cgccpf) == 11) {
            $oRegistro->codigoTipoInscricaoPagador = 1;
        }

        $sqlArrebanco = "SELECT k00_numbco
                           FROM arrebanco
                          WHERE k00_numpre = {$iNumpre}";

        $result = db_query($sqlArrebanco);

        if (!$result) {
            throw new DBException("Não foi possivel buscar o sequencial da tabela arrebanco.");
        }

        $resultArrebanco = db_utils::fieldsMemory($result, 0);

        $oRegistro->numeroConvenio = $oConvenio->ar13_convenio;
        $oRegistro->numeroCarteira = $oConvenio->ar13_carteira;
        $oRegistro->numeroVariacaoCarteira = $oConvenio->ar13_variacao;
        $oRegistro->codigoModalidadeTitulo = 1;
        $oRegistro->dataEmissaoTitulo = date("d.m.Y", strtotime($oRecibo->data_emissao));
        $oRegistro->dataVencimentoTitulo = date("d.m.Y", strtotime($oRecibo->data_vencimento));
        $oRegistro->valorOriginalTitulo = (string) db_formatar($nValor, 'p', ' ', strlen($nValor));
        $oRegistro->codigoTipoDesconto = 0;
        $oRegistro->codigoTipoJuroMora = 3;
        $oRegistro->codigoTipoMulta = 0;
        $oRegistro->codigoAceiteTitulo = "N";
        $oRegistro->codigoTipoTitulo = 25;
        $oRegistro->indicadorPermissaoRecebimentoParcial = "N";

        $oRegistro->textoNumeroTituloCliente = strval("000{$resultArrebanco->k00_numbco}");

        $oRegistro->numeroInscricaoPagador = substr($oCgm->z01_cgccpf, 0, 15);
        $oRegistro->nomePagador = substr(utf8_encode($oCgm->z01_nome), 0, 60);
        $oRegistro->textoEnderecoPagador = substr(utf8_encode($oCgm->z01_ender), 0, 60);
        $oRegistro->numeroCepPagador = substr($oCgm->z01_cep, 0, 8);
        $oRegistro->nomeMunicipioPagador = substr(utf8_encode($oCgm->z01_munic), 0, 20);
        $oRegistro->nomeBairroPagador = substr(utf8_encode($oCgm->z01_bairro), 0, 20);
        $oRegistro->siglaUfPagador = substr($oCgm->z01_uf, 0, 2);
        $oRegistro->codigoChaveUsuario = $oParametro->ar28_chavej;
        $oRegistro->codigoTipoCanalSolicitacao = 5;
        $oRegistro->autenticacao = $oAutenticacao->getHash();

        return $oRegistro;
    }
}
