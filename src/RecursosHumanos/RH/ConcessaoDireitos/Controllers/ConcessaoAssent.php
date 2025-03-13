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

namespace ECidade\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use AfastaAssenta;
use Afastamento;
use AfastamentoRepository;
use Assentamento;
use AssentamentoFuncional;
use AssentamentoFuncionalRepository;
use AssentamentoRepository;
use AssentamentoSubstituicao;
use BusinessException;
use cl_assenta;
use cl_assentadb_cadattdinamicovalorgrupo;
use cl_portaria;
use cl_portariaassenta;
use cl_portariatipo;
use cl_portariatipodoccoletiva;
use cl_portariatipodocindividual;
use cl_rhparam;
use db_utils;
use DBDate;
use DBPessoal;
use Exception;
use InformacoesExternasTipoAssentamento;
use Ponto;
use ProporcionalizacaoPontoSalario;
use ServidorRepository;
use stdClass;

class ConcessaoAssent
{
    private static function ine()
    {
        require_once(modification("libs/db_stdlib.php"));
        require_once(modification("libs/db_conecta.php"));
        require_once(modification("libs/db_sessoes.php"));
        require_once(modification("libs/db_usuariosonline.php"));
        require_once(modification("libs/db_utils.php"));
        require_once(modification("dbforms/db_funcoes.php"));
    }

    public static function save(
        $h31_sequencial,
        $h31_portariatipo,
        $h31_usuario,
        $h31_numero,
        $h31_anousu,
        $h31_dtportaria,
        $h31_dtinicio,
        $h31_dtlanc,
        $h31_amparolegal,
        $h16_regist,
        $h16_dtconc,
        $h16_dtterm,
        $h16_atofic
    ) {
        self::ine();
        $clrhparam         = new cl_rhparam;
        $clportaria        = new cl_portaria;
        $classenta         = new cl_assenta;
        $clportariaassenta = new cl_portariaassenta;
        $clportariatipo    = new cl_portariatipo;
        $clportariatipodoccoletiva   = new cl_portariatipodoccoletiva();
        $clportariatipodocindividual = new cl_portariatipodocindividual();

        $sOpcaoAssentamento = 2;

        //VARIAVEIS PORTARIA
        $clportaria->h31_portariatipo = (string)$h31_portariatipo;
        $clportaria->h31_usuario = (string)$h31_usuario;
        $clportaria->h31_anousu = (string) $h31_anousu;
        $dtportaria = explode('-', $h31_dtportaria);
        $clportaria->h31_dtportaria_dia = (string)$dtportaria[2];
        $clportaria->h31_dtportaria_mes = (string)$dtportaria[1];
        $clportaria->h31_dtportaria_ano = (string)$dtportaria[0];
        $clportaria->h31_dtportaria = (string)$h31_dtportaria;
        $dtportaria = explode('-', $h31_dtinicio);
        $clportaria->h31_dtinicio_dia = (string)$dtportaria[2];
        $clportaria->h31_dtinicio_mes = (string)$dtportaria[1];
        $clportaria->h31_dtinicio_ano = (string)$dtportaria[0];
        $clportaria->h31_dtinicio = (string)$h31_dtinicio;
        $dtportaria = explode('-', $h31_dtlanc);
        $clportaria->h31_dtlanc_dia = (string)$dtportaria[2];
        $clportaria->h31_dtlanc_mes = (string)$dtportaria[1];
        $clportaria->h31_dtlanc_ano = (string)$dtportaria[0];
        $clportaria->h31_dtlanc = (string)$h31_dtlanc;
        $clportaria->h31_amparolegal = (string)$h31_amparolegal;
        $clportaria->h31_portariaassinatura = "";

        db_inicio_transacao();
        /**
         * Pesquisa parametro da numeracao da portaria, caso encontre pega proxima numeracao, nextval()
         */
        $sWhereRhParam  = " h36_ultimaportaria > 0 and h36_instit = " . db_getsession("DB_instit");
        $sSqlRhParam    = $clrhparam->sql_query_file(null, "h36_ultimaportaria", null, $sWhereRhParam);
        $rsDadosRhParam = $clrhparam->sql_record($sSqlRhParam);
        $lSeqAutomatico = false;

        if ($clrhparam->numrows > 0) {
            $lSeqAutomatico = true;
        }
        $iNroPort = $h31_numero;

        if ($lSeqAutomatico) {
            $sSqlSequence       = " select nextval('rhparam_h36_ultimaportaria_seq') as seq ";
            $rsConsultaSequence = db_query($sSqlSequence);
            $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence, 0);
            $iNroPort           = $oSeqPortaria->seq;
        }

        /**
         * Inclui portaria
         */
        if (isset($h31_sequencial) && trim(@$h31_sequencial) == "") {
            $clportaria->h31_numero = (string)$iNroPort;
            $_POST = null;
            $clportaria->incluir($h31_sequencial);

            if ($clportaria->erro_status == "0") {
                $sqlerro          = true;
                $erro_msg         = $clportaria->erro_msg;
            } else {
                $h31_sequencial   = $clportaria->h31_sequencial;
                $h31_portariatipo = $clportaria->h31_portariatipo;
                $h31_numero       = $clportaria->h31_numero;
            }
        }

        /**
         * Inclui assentamento
         */

        $rsPortariaTipo = db_query($clportariatipo->sql_query_file($h31_portariatipo, "h30_tipoasse", null));
        $oPortariaTipo  = db_utils::fieldsMemory($rsPortariaTipo, 0);

        $oAssentamento = new Assentamento();
        /**
         * Quando for assentamento funcional salvamos na tabela plugins.assentamentofuncional
         */

        if (isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {
            $oAssentamento = new AssentamentoFuncional();
        }
        $oAssentamento->setMatricula((string)$h16_regist);
        $oAssentamento->setTipoAssentamento($oPortariaTipo->h30_tipoasse);
        $oAssentamento->setDataConcessao(new DBDate($h16_dtconc));
        $oAssentamento->setHistorico('');
        $oAssentamento->setCodigoPortaria($iNroPort);
        $oAssentamento->setDescricaoAto($h16_atofic);
        $oAssentamento->setDias("1");
        $oAssentamento->setPercentual("0");

        if (isset($h16_dtterm) && trim($h16_dtterm) != "") {
            $oAssentamento->setDataTermino(new DBDate($h16_dtterm));
        }

        if (!isset($h16_anoato)) {
            $h16_anoato = '';
        }

        $oAssentamento->setSegundoHistorico('');
        $oAssentamento->setLoginUsuario((string)db_getsession("DB_id_usuario"));
        $oAssentamento->setDataLancamento(date("Y-m-d", db_getsession("DB_datausu")));
        $oAssentamento->setConvertido("false");
        $oAssentamento->setAnoPortaria($h16_anoato);

        if (isset($sOpcaoAssentamento) &&  $sOpcaoAssentamento == 2) {
            $oAssentamentoSalvo = AssentamentoFuncionalRepository::persist($oAssentamento->persist());
        } else {
            $oAssentamentoSalvo = AssentamentoRepository::persist($oAssentamento->persist());
        }
        if (!$oAssentamentoSalvo instanceof Assentamento) {
            throw new BusinessException($oAssentamentoSalvo);
        }
        /**
         * Incluimos na tabela assenta e criamos uma relação entre os assentamentos do pessoal e do rh
         * incluendo as chaves na tabela afastaassenta
         */

        $aListaInformacoesExternas = InformacoesExternasTipoAssentamento::getTipoAssentamentoConfiguradosPorCompetencia(
            DBPessoal::getCompetenciaFolha()
        );
        if (is_array($aListaInformacoesExternas)) {
            $aTiposAssentamentoConfigurados = array();
            foreach ($aListaInformacoesExternas as $oInformacoesExternas) {
                $aTiposAssentamentoConfigurados[] = $oInformacoesExternas->getTipoAssentamento()->getCodigo();
            }

            if (in_array($oAssentamento->getTipoAssentamento(), $aTiposAssentamentoConfigurados)) {
                $oServidor    = ServidorRepository::getInstanciaByCodigo(
                    $oAssentamento->getMatricula(),
                    $oInformacoesExternas->getCompetencia()->getAno(),
                    $oInformacoesExternas->getCompetencia()->getMes()
                );

                $oAfastamento = new Afastamento();

                $oAfastamento->setCompetencia($oInformacoesExternas->getCompetencia());
                $oAfastamento->setServidor($oServidor);
                $oAfastamento->setDataAfastamento($oAssentamento->getDataConcessao());
                $oAfastamento->setDataRetorno($oAssentamento->getDataTermino());
                $oAfastamento->setCodigoSituacao($oInformacoesExternas->getSituacaoAfastamento());
                $oAfastamento->setDataLancamento($oAssentamento->getDataLancamento());
                $oAfastamento->setCodigoAfastamentoSefip($oInformacoesExternas->getSefip());
                $oAfastamento->setCodigoRetornoSefip($oInformacoesExternas->getCodigoRetorno());
                $oAfastamento->setObservacao($oAssentamento->getHistorico());

                $oAfastamentoSalvo = AfastamentoRepository::persist($oAfastamento);

                if (!$oAfastamentoSalvo instanceof Afastamento) {
                    throw new BusinessException("Erro ao salvar afastamento na base de dados.");
                }

                $oAfastaAssenta      = new AfastaAssenta($oAssentamento, $oAfastamento);
                $oAfastaAssentaSalvo = $oAfastaAssenta->persist();

                if (!$oAfastaAssentaSalvo instanceof AfastaAssenta) {
                    throw new BusinessException("Erro ao salvar vínculo entre assentamento e afastamento.");
                }

                /**
                 * Realiza a proporcionalização no ponto
                 */
                $oProporcionalizacaoPontoSalario = new ProporcionalizacaoPontoSalario(
                    $oServidor->getPonto(Ponto::SALARIO),
                    $oInformacoesExternas->getSituacaoAfastamento(),
                    $oAssentamento->getDataTermino()
                );
                $oProporcionalizacaoPontoSalario->processar();
            }
        }
        $sqlerro = false;
        if ($classenta->erro_status == "0") {
            $sqlerro    = true;
            $erro_msg   = $classenta->erro_msg;
            $clportaria->erro_msg = $erro_msg;
        }

        if (!$sqlerro) {
            $h16_codigo = $oAssentamento->getCodigo();
            $clportariaassenta->h33_portaria = $h31_sequencial;
            $clportariaassenta->h33_assenta  = $h16_codigo;
            $clportariaassenta->incluir(null);

            if ($clportariaassenta->erro_status == "0") {
                $sqlerro  = true;
                $erro_msg = $clportariaassenta->erro_msg;
                $clportaria->erro_msg = $erro_msg;
            }
        }

        if (!$sqlerro && !empty($h80_db_cadattdinamicovalorgrupo)) {
            $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
            $oDaoAssentaAttr->h80_assenta                     = $h16_codigo;
            $oDaoAssentaAttr->h80_db_cadattdinamicovalorgrupo = $h80_db_cadattdinamicovalorgrupo;
            $oDaoAssentaAttr->incluir($h16_codigo, $h80_db_cadattdinamicovalorgrupo);

            if ($oDaoAssentaAttr->erro_status == "0") {
                $sqlerro  = true;
                $erro_msg = $oDaoAssentaAttr->erro_msg;
            }
        }

        /**
         * Altera parametro(h36_ultimaportaria) com numero da ultima portaria
         * - caso sequencial for automatico
         * - caso nao exitir erro
         */
        if (!$sqlerro && $lSeqAutomatico) {
            $sSqlSequence       = " select last_value as seq from rhparam_h36_ultimaportaria_seq";
            $rsConsultaSequence = db_query($sSqlSequence);
            $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence, 0);

            $clrhparam->h36_ultimaportaria = $oSeqPortaria->seq;
            $clrhparam->h36_instit         = db_getsession("DB_instit");
            $clrhparam->alterar(db_getsession("DB_instit"));

            if ($clrhparam->erro_status == "0") {
                $sqlerro  = true;
                $erro_msg = $clrhparam->erro_msg;
                $clportaria->erro_msg = $erro_msg;
            }
        }
        if (!$sqlerro) {
            if (isset($rh161_regist)) {
                if (empty($rh161_regist)) {
                    $sqlerro  = true;
                    $erro_msg = 'Informe a matrícula do servidor substituído';
                    db_redireciona("");
                }

                $oAssentamentoSubstituicao = new AssentamentoSubstituicao($oAssentamento->getCodigo());
                $oAssentamentoSubstituicao->setSubstituido(
                    ServidorRepository::getInstanciaByCodigo(
                        $rh161_regist,
                        DBPessoal::getAnoFolha(),
                        DBPessoal::getMesFolha()
                    )
                );

                $mResponse = $oAssentamentoSubstituicao->persist();

                if ($mResponse !== true) {
                    $sqlerro  = true;
                    $erro_msg = $mResponse;
                }
            }
        }
        db_fim_transacao($sqlerro);

        // // Consulta Portaria /////////////////////////////////

        if (isset($h31_anousu) && trim($h31_anousu) != "") {
            $iAnoUsu = $h31_anousu;
        } else {
            $iAnoUsu = db_getsession("DB_anousu");
        }

        $sWhere  = " cast( h31_numero as integer ) between {$h31_numero} and {$h31_numero} ";
        if ($h31_numero == $h31_numero) {
            $sWhere  = " h31_numero = '$h31_numero' ";
        }

        $sWhere .= " and h31_anousu = {$iAnoUsu} ";
        $sWhereVerificaLotacao = " and h16_regist in (select distinct rh02_regist from rhpessoalmov
                                               where rh02_anousu = " . DBPessoal::getAnoFolha() . "
                                                 and rh02_mesusu = " . DBPessoal::getMesFolha() . "
                                                 and rh02_lota in (select rh157_lotacao
                                                 from db_usuariosrhlota
                                                 where rh157_usuario = " . db_getsession("DB_id_usuario") . "))";

        $rsConsultaPortarias = $clportaria->sql_record(
            $clportaria->sql_query_assentamento_funcional(
                null,
                "*",
                null,
                $sWhere,
                $sWhereVerificaLotacao,
                2
            )
        );
        $iNroLinhasPort        = $clportaria->numrows;

        if ($iNroLinhasPort > 0) {
            for ($i = 0; $i < $iNroLinhasPort; $i++) {
                $oPortaria = db_utils::fieldsMemory($rsConsultaPortarias, $i);
                $aPortarias[] = $oPortaria->h31_numero;
            }

            $oVariavelPortaria = new stdClass();
            $oVariavelPortaria->sNome  = '$portaria';
            $sValor                = implode("','", $aPortarias);
            $oVariavelPortaria->sValor = "'{$sValor}'";
            $aRetornoParametros[] = $oVariavelPortaria;


            $oVariavelAno = new stdClass();
            $oVariavelAno->sNome  = '$ano';
            $oVariavelAno->sValor = $iAnoUsu;
            $aRetornoParametros[] = $oVariavelAno;


            $rsConsultaModParam = $clrhparam->sql_record(
                $clrhparam->sql_query_file(
                    null,
                    "*",
                    null,
                    " h36_instit = " . db_getsession("DB_instit")
                )
            );
            $iNroLinhasParam    = $clrhparam->numrows;

            if ($iNroLinhasParam > 0) {
                $oModParam = db_utils::fieldsMemory($rsConsultaModParam, 0);
                $sRetornoTipoIndividual = $oModParam->h36_modportariaindividual;
                $sRetornoTipoColetiva   = $oModParam->h36_modportariacoletiva;
            } else {
                $sRetornoTipoIndividual = "";
                $sRetornoTipoColetiva   = "";
            }

            $rsConsultaTipo = $clportaria->sql_record(
                $clportaria->sql_query_assentamento_funcional(
                    null,
                    "h31_portariatipo",
                    null,
                    $sWhere,
                    $sWhereVerificaLotacao,
                    2
                )
            );

            $oTipo = db_utils::fieldsMemory($rsConsultaTipo, 0);

            $rsConsultaTipoIndividual = $clportariatipodocindividual->sql_record(
                $clportariatipodocindividual->sql_query_file(
                    null,
                    "h37_modportariaindividual",
                    null,
                    "h37_portariatipo = {$oTipo->h31_portariatipo}"
                )
            );
            $iNroLinhasTipoIndividual = $clportariatipodocindividual->numrows;

            if ($iNroLinhasTipoIndividual > 0) {
                $oTipoIndividual           = db_utils::fieldsMemory($rsConsultaTipoIndividual, 0);
                $sRetornoTipoIndividual = $oTipoIndividual->h37_modportariaindividual;
            }

            $rsConsultaTipoColetiva = $clportariatipodoccoletiva->sql_record(
                $clportariatipodoccoletiva->sql_query_file(
                    null,
                    "h38_modportariacoletiva",
                    null,
                    "h38_portariatipo = {$oTipo->h31_portariatipo}"
                )
            );
            $iNroLinhasTipoColetiva = $clportariatipodoccoletiva->numrows;

            if ($iNroLinhasTipoColetiva > 0) {
                $oTipoColetiva         = db_utils::fieldsMemory($rsConsultaTipoColetiva, 0);
                $sRetornoTipoColetiva = $oTipoColetiva->h38_modportariacoletiva;
            }


            $aRetorno = array(
                "aParametros"   => $aRetornoParametros,
                "iModIndividual" => $sRetornoTipoIndividual,
                "iModColetiva"  => $sRetornoTipoColetiva,
                "erro"          => false
            );
        } else {
            $aRetorno = array("msg" => urlencode("Nenhuma portaria cadastrada!"), "erro" => true);
        }

        //Fim da Consulta/////////////////////////////////

        //Gera Arquivo /////////////////
        $retorno = new stdClass();
        $retorno->erro = false;
        try {
            if ($aRetorno['iModIndividual']) {
                $iModelo = $aRetorno['iModIndividual'];
            } else {
                $iModelo = $aRetorno['iModColetiva'];
            }

            $p1 = new stdClass();
            $p1->sNome = '$portaria';
            $p1->sValor = $clportaria->h31_numero;
            $p2 = new stdClass();
            $p2->sNome = '$ano';
            $p2->sValor = $clportaria->h31_anousu;

            $arrayP = [$p1, $p2];

            $parametros = new stdClass();
            $parametros->_path = "w/14/rh_processaassinaturadigital.RPC.php";
            $parametros->exec = 'gerarArquivo';
            $parametros->aParametros = json_encode($arrayP);
            $parametros->iCodigoPortaria = $clportaria->h31_sequencial;
            $parametros->iCodRelatorio = $iModelo;

            //2000585
            db_putsession("DB_itemmenu_acessado", 228699);

            db_putsession("DB_user", $_ENV['DB_USERNAME']);
            db_putsession("DB_senha", $_ENV['DB_PASSWORD']);
            db_putsession("DB_servidor", $_ENV['DB_HOST']);
            db_putsession("DB_porta", $_ENV['DB_PORT']);

            try {
                $retorno = new Processassinaturadigital();
                $retorno = $retorno->gerarArquivo($parametros);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } catch (Exception $exception) {
            if (db_utils::inTransaction()) {
                db_fim_transacao(true);
            }
            $retorno->mensagem = $exception->getMessage();
            $retorno->erro = true;
        }

        return $oAssentamento->getCodigo();
    }
}
