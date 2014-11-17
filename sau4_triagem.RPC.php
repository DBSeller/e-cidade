<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$oRetorno->dtAtual = date( 'd/m/Y', db_getsession( "DB_datausu" ) );
$oRetorno->sMedico = '';
$oRetorno->iMedico = '';
$iDepartamento     = db_getsession( "DB_coddepto" );

$oErro = new stdClass();

buscaProfissionalSaude( $oRetorno );

define("ARQUIVO_MENSAGEM", "saude.ambulatorial.sau4_triagem.");

try {

  db_inicio_transacao();

	switch($oParam->exec) {

		/**
		 * Retorna os procedimentos de triagem configurados
		 * @return array $oRetorno->aProcedimentos
		 */
		case 'getProcedimentosConfigurados':
			
			$oRetorno->aProcedimentos   = array();
			$oDaoProcedimentoTriagem    = db_utils::getDao("parametroprocedimentotriagem");
			$sCamposProcedimentoTriagem = "s166_sau_procedimento, sd63_c_procedimento, sd63_c_nome";
			$sSqlProcedimentoTriagem    = $oDaoProcedimentoTriagem->sql_query(
				                                                               	null, 
				                                                               	$sCamposProcedimentoTriagem
				                                                               );
			$rsProcedimentoTriagem      = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
			$iTotalProcedimentoTriagem  = $oDaoProcedimentoTriagem->numrows;
			
			if ( $iTotalProcedimentoTriagem > 0 ) {
				
				for ( $iContador = 0; $iContador < $iTotalProcedimentoTriagem; $iContador++ ) {
					
					$oDadosProcedimentosTriagem                 = db_utils::fieldsMemory($rsProcedimentoTriagem, $iContador);
					$oRetornoProcedimentoTriagem                = new stdClass();
					$oRetornoProcedimentoTriagem->iCodigo       = $oDadosProcedimentosTriagem->s166_sau_procedimento;
					$oRetornoProcedimentoTriagem->iProcedimento = urlencode($oDadosProcedimentosTriagem->sd63_c_procedimento);
					$oRetornoProcedimentoTriagem->sDescricao    = urlencode($oDadosProcedimentosTriagem->sd63_c_nome);
					$oRetorno->aProcedimentos[]                 = $oRetornoProcedimentoTriagem;
					unset($oRetornoProcedimentoTriagem);
				}
			}
			break;
			
	  /**
	   * Salva os procedimentos de triagem configurados
	   * @param integer $oParam->iProcedimento
	   */
		case 'salvarProcedimentos':
			
      $oDaoProcedimentoTriagem   = new cl_parametroprocedimentotriagem();
			$sWhereProcedimentoTriagem = "s166_sau_procedimento = {$oParam->iProcedimento}";
			$sSqlProcedimentoTriagem   = $oDaoProcedimentoTriagem->sql_query(
				                                                              	null, 
				                                                              	"s166_sequencial, sd63_c_nome",
				                                                              	null,
				                                                              	$sWhereProcedimentoTriagem
				                                                              );
			$rsProcedimentoTriagem     = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
      $oRetorno->message         = urlencode( _M(ARQUIVO_MENSAGEM . 'procedimento_salvo_parametros') );
			
			if ( $oDaoProcedimentoTriagem->numrows > 0 ) {
				
				$oDadosProcedimentosTriagem = db_utils::fieldsMemory($rsProcedimentoTriagem, 0);
				$oRetorno->status           = 2;
				$oErro                      = new stdClass();
				$oErro->sErro               = $oDadosProcedimentosTriagem->sd63_c_nome;
        $oRetorno->message          = urlencode( _M(ARQUIVO_MENSAGEM . 'procedimento_ja_cadastrado', $oErro) );
				unset($oDadosProcedimentosTriagem);
			} else {
				
				$oDaoProcedimentoTriagem->s166_sau_procedimento = $oParam->iProcedimento;
				$oDaoProcedimentoTriagem->incluir(null);
				
				if ( $oDaoProcedimentoTriagem->erro_status == "0" ) {
					throw new DBException($oDaoProcedimentoTriagem->erro_msg);
				}
			}
			
			break;
			
		/**
		 * Exclui um ou mais procedimentos de triagem
		 * @param array $oParam->aProcedimentos
		 */
		case 'excluirProcedimentos':
			
			if ( isset($oParam->aProcedimentos) && count($oParam->aProcedimentos) > 0 ) {

				$oDaoProcedimentoTriagem   = new cl_parametroprocedimentotriagem();
				$sWhereProcedimentoTriagem = "s166_sau_procedimento in (" . implode(", ", $oParam->aProcedimentos) . ")";
					
				$oDaoProcedimentoTriagem->excluir(null, $sWhereProcedimentoTriagem);
					
				if ( $oDaoProcedimentoTriagem->erro_status == "0" ) {
					throw new DBException($oDaoProcedimentoTriagem->erro_msg);
				}

        $oRetorno->message = urlencode( _M(ARQUIVO_MENSAGEM . 'procedimento_excluido') );
			}

			break;

		/**
		 * Salva ou altera uma Triagem
		 */
		case 'salvarTriagem':

			validaDados( $oParam );
			buscaProfissionalSaude( $oRetorno );

			$iCbosProfissional = verificaCbosProfissional( $oParam );
			$oDataSistema      = new DBDate( date("Y-m-d", db_getSession("DB_datausu")) );

			$oTriagemAvulsa = new TriagemAvulsa( $oParam->iTriagem );
			$oTriagemAvulsa->setCboProfissional( $iCbosProfissional );
			$oTriagemAvulsa->setCgsUnd( $oParam->iCgsUnd );
			$oTriagemAvulsa->setLogin( db_getSession("DB_id_usuario") );
			$oTriagemAvulsa->setPressaoSistolica( $oParam->iPressaoSistolica);
			$oTriagemAvulsa->setPressaoDiastolica( $oParam->iPressaoDiastolica);
			$oTriagemAvulsa->setCintura( $oParam->iCintura );
			$oTriagemAvulsa->setPeso( $oParam->nPeso );
			$oTriagemAvulsa->setAltura( $oParam->iAltura );
			$oTriagemAvulsa->setGlicemia( $oParam->iGlicemia == '' ? '0' : $oParam->iGlicemia );
      $iAlimentacaoExameGlicose = $oParam->iAlimentacaoExameGlicose == '' ? '0' : $oParam->iAlimentacaoExameGlicose;
			$oTriagemAvulsa->setAlimentacaoExameGlicose( $iAlimentacaoExameGlicose );
			$oTriagemAvulsa->setDataConsulta( new DBDate( $oParam->dtDataConsulta ) );
			$oTriagemAvulsa->setDataSistema( $oDataSistema );
			$oTriagemAvulsa->setHoraSistema( date("H:i") );
			$oTriagemAvulsa->setTemperatura( $oParam->nTemperatura );
			$oTriagemAvulsa->salvar();

			$oRetorno->iTriagemAvulsa = $oTriagemAvulsa->getCodigo();
			$oRetorno->message        = _M(ARQUIVO_MENSAGEM . 'triagem_salva');
			break;

		/**
		 * Verifica se o CGS tem triagem mas ainda não consultou e retornar os dados da triagem
		 */
		case 'buscaTriagemValida':

      $oRetorno->lTemTriagem     = false;
      $oRetorno->lSomenteTriagem = false;

			if ( !isset($oParam->iCgsUnd) || $oParam->iCgsUnd == "" ) {
				throw new DBException( _M( ARQUIVO_MENSAGEM . "informe_cgs") );
			}

			$oCgs                 = new Cgs( $oParam->iCgsUnd );
			$oRetorno->sSexo      = $oCgs->getSexo();
			$oRetorno->iCgsUnd    = $oCgs->getCodigo();

      $sCartaoSus = isset( $oParam->iCartaoSus ) && !empty( $oParam->iCartaoSus ) ? $oParam->iCartaoSus : $oCgs->getCartaoSus();
			$oRetorno->sCartaoSus = $sCartaoSus;

			$iUsuarioLogado = db_getSession("DB_id_usuario");
			$dtDataSistema  = date("Y-m-d", db_getSession("DB_datausu"));

			$oDaoTriagemAvulsa    = new cl_sau_triagemavulsa();
      $sCamposTriagem       = "s152_i_codigo, s155_i_codigo, sd29_i_codigo";
			$sOrderBy             = "1 desc limit 1";
			$sWhereTriagem        = "     s152_i_cgsund      = {$oParam->iCgsUnd} ";
      $sWhereTriagem       .= " and s152_i_login       = {$iUsuarioLogado} ";
      $sWhereTriagem       .= " and s152_d_datasistema = '{$dtDataSistema}' ";

      if ( isset($oParam->iProntuario) && !empty($oParam->iProntuario)) {
        $sWhereTriagem = " s155_i_prontuario = {$oParam->iProntuario}";
      }

			$sSqlTriagemConsulta  = $oDaoTriagemAvulsa->sql_query_consulta(null, $sCamposTriagem, $sOrderBy, $sWhereTriagem);
			$rsTriagemConsulta    = db_query( $sSqlTriagemConsulta );

			if ( !$rsTriagemConsulta ) {

				$oErro = new stdClass();
				$oErro->sErro = $oDaoTriagemAvulsa->erro_msg;
				throw new DBException( _M( ARQUIVO_MENSAGEM . "erro_buscar_triagem", $oErro) );
			}

			if ( pg_num_rows($rsTriagemConsulta) > 0 ) {

        $oRetorno->lTemTriagem = true;
				$oDadosRetorno         = db_utils::fieldsMemory($rsTriagemConsulta, 0);
				$oTriagemAvulsa        = new TriagemAvulsa( $oDadosRetorno->s152_i_codigo );

				$oRetorno->iCodigo                  = $oTriagemAvulsa->getCodigo();
				$oRetorno->iCbosProfissional        = $oTriagemAvulsa->getCboProfissional();
				$oRetorno->iLogin                   = $oTriagemAvulsa->getLogin();
				$oRetorno->iPressaoSistolica        = $oTriagemAvulsa->getPressaoSistolica();
				$oRetorno->iPressaoDiastolica       = $oTriagemAvulsa->getPressaoDiastolica();
				$oRetorno->iCintura                 = $oTriagemAvulsa->getCintura();
				$oRetorno->nPeso                    = intval ($oTriagemAvulsa->getPeso() );
				$oRetorno->iAltura                  = $oTriagemAvulsa->getAltura();
				$oRetorno->iGlicemia                = $oTriagemAvulsa->getGlicemia() == 0 ? '' : $oTriagemAvulsa->getGlicemia();
				$oRetorno->iAlimentacaoExameGlicose = $oTriagemAvulsa->getAlimentacaoExameGlicose();
				$oRetorno->dtDataConsulta           = urlencode( $oTriagemAvulsa->getDataConsulta() );
				$oRetorno->dtDataSistema            = urlencode( $oTriagemAvulsa->getDataSistema() );
				$oRetorno->dtHoraSistema            = urlencode( $oTriagemAvulsa->getHoraSistema() );

        $nTemperatura = $oTriagemAvulsa->getTemperatura() == '' ? $oTriagemAvulsa->getTemperatura() : intval ($oTriagemAvulsa->getTemperatura() );
				$oRetorno->nTemperatura = $nTemperatura;

				$oMedico           = $oTriagemAvulsa->getMedico();
				$oRetorno->iMedico = $oMedico->getCodigo();
				$oRetorno->sMedico = urlencode($oMedico->getNome());

        if( empty( $oDadosRetorno->s155_i_codigo ) ) {
          $oRetorno->lSomenteTriagem = true;
        }
			}

			break;

    case 'buscaCBOS':

      $oRetorno->aCbos = array();
      $oDaoFarCbos     = new cl_far_cbos();
      $sSqlFarCbos     = $oDaoFarCbos->sql_query_file();
      $rsFarCbos       = db_query( $sSqlFarCbos );

      if( !$rsFarCbos ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_last_error( $rsFarCbos );
        throw new DBException( _M( 'ARQUIVO_MENSAGEM' . 'erro_buscar_cbos', $oMensagem ) );
      }

      if( pg_num_rows( $rsFarCbos ) > 0 ) {

        $iTotalLinhas = pg_num_rows( $rsFarCbos );
        for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

          $oDadosRetorno           = db_utils::fieldsMemory( $rsFarCbos, $iContador );
          $oDadosCbos              = new stdClass();
          $oDadosCbos->iCbos       = $oDadosRetorno->fa53_i_codigo;
          $oDadosCbos->sCbos       = urlencode( $oDadosRetorno->fa53_c_descr );
          $oDadosCbos->sEstrutural = urlencode( $oDadosRetorno->fa53_c_estrutural );
          $oRetorno->aCbos[]       = $oDadosCbos;
        }
      }

      break;

    case 'dadosDepartamento':

      $oDepartamento           = new DBDepartamento( $iDepartamento );
      $oRetorno->iDepartamento = $oDepartamento->getCodigo();
      $oRetorno->sDepartamento = urlencode( $oDepartamento->getNomeDepartamento() );

      break;

    case 'dadosProfissional':

      if( !isset( $oParam->iMedico ) || empty( $oParam->iMedico ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . 'medico_nao_informado' ) );
      }

      $oRetorno->iUnidadeMedicos = '';
      $oRetorno->iCbos           = '';

      $oDaoUnidadeMedicos    = new cl_unidademedicos();
      $sWhereUnidadeMedicos  = "sd04_i_unidade = {$iDepartamento} and sd04_i_medico = {$oParam->iMedico}";
      $sCamposUnidadeMedicos = "distinct sd04_i_codigo, fa54_i_cbos";
      $sSqlUnidadeMedicos    = $oDaoUnidadeMedicos->sql_query_cbos(
                                                                    null,
                                                                    $sCamposUnidadeMedicos,
                                                                    null,
                                                                    $sWhereUnidadeMedicos
                                                                  );
      $rsUnidadeMedicos = db_query( $sSqlUnidadeMedicos );

      if( !$rsUnidadeMedicos ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error( $rsUnidadeMedicos );
        throw new DBException( _M( ARQUIVO_MENSAGEM . 'erro_buscar_cbos', $oErro ) );
      }

      if( pg_num_rows( $rsUnidadeMedicos ) > 0 ) {

        $oDadosCbos                = db_utils::fieldsMemory( $rsUnidadeMedicos, 0 );
        $oRetorno->iUnidadeMedicos = $oDadosCbos->sd04_i_codigo;
        $oRetorno->iCbos           = $oDadosCbos->fa54_i_cbos;
      }

      break;

    /**
    * Buscamos todos os procedimentos de triagem configurados, para incluir um novo registro para cada na tabela
    * prontproced, e armazenamos em um array com os codigos
    */
    case 'buscaProcedimentosTriagem':

      $oRetorno->aProcedimentosTriagem = array();
      $oDaoProcedimentoTriagem         = new cl_parametroprocedimentotriagem();
      $sSqlProcedimentoTriagem         = $oDaoProcedimentoTriagem->sql_query(null, "s166_sau_procedimento");
      $rsProcedimentoTriagem           = db_query( $sSqlProcedimentoTriagem );
      $iTotalProcedimentoTriagem       = pg_num_rows( $rsProcedimentoTriagem );

      if ($iTotalProcedimentoTriagem  > 0 ) {

        for ( $iContador = 0; $iContador < $iTotalProcedimentoTriagem; $iContador++ ) {
          $oRetorno->aProcedimentosTriagem[] = db_utils::fieldsMemory($rsProcedimentoTriagem, $iContador)->s166_sau_procedimento;
        }
      }

      break;

    case 'salvarEspecialidadeProcedimentos':

      $oDaoEspecmedico    = new cl_especmedico();
      $sWhereEspecMedico  = "     sd27_i_rhcbo   = {$oParam->iEspecialidade}";
      $sWhereEspecMedico .= " and sd04_i_unidade = {$iDepartamento}";
      $sWhereEspecMedico .= " and sd04_i_medico  = {$oRetorno->iMedico}";
      $sSqlEspecMedico    = $oDaoEspecmedico->sql_query(null, 'sd27_i_codigo', null, $sWhereEspecMedico);
      $rsEspecMedico      = db_query($sSqlEspecMedico);

      if ( !$rsEspecMedico ) {

        $oErro->sErro = pg_last_error( $rsEspecMedico );
        throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_buscar_especialidade_medico", $oErro) );
      }

      if( pg_num_rows( $rsEspecMedico ) > 0 ) {

        $iEspecialidadeMedico = db_utils::fieldsmemory( $rsEspecMedico, 0 )->sd27_i_codigo;
        $oDaoProntproced      = new cl_prontproced();

        foreach ( $oParam->aProcedimentosTriagem as $iProcedimento ) {

          $oDaoProntproced->sd29_i_prontuario   = $oParam->iProntuario;
          $oDaoProntproced->sd29_i_procedimento = $iProcedimento;
          $oDaoProntproced->sd29_i_profissional = $iEspecialidadeMedico;
          $oDaoProntproced->sd29_i_usuario      = DB_getsession("DB_id_usuario");
          $oDaoProntproced->sd29_d_cadastro     = date("Y-m-d",db_getsession("DB_datausu"));
          $oDaoProntproced->sd29_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
          $oDaoProntproced->sd29_c_hora         = date('H:i');
          $oDaoProntproced->sd29_c_cadastro     = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
          $oDaoProntproced->sd29_t_diagnostico  = '';
          $oDaoProntproced->incluir( null );

          if( $oDaoProntproced->erro_status == '0' ) {

            $oErro->sErro = $oDaoSauTriagemAvulsa->erro_msg;
            throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_incluir_procedimento", $oErro) );
          }
        }

        $oRetorno->message = _M( ARQUIVO_MENSAGEM . "procedimento_salvo" );
      }

      break;

    case 'buscaCgs':

      if( !isset( $oParam->iCgs ) || empty( $oParam->iCgs ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "informe_cgs" ) );
      }

      $oCgs           = new Cgs( $oParam->iCgs );
      $oRetorno->iCgs = $oCgs->getCodigo();
      $oRetorno->sCgs = urlencode( $oCgs->getNome() );
      break;

    case 'salvarTriagemProntuario':

      if( !isset( $oParam->iTriagem ) || empty( $oParam->iTriagem ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "triagem_nao_encontrado" ) );
      }

      if( !isset( $oParam->iProntuario ) || empty( $oParam->iProntuario ) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "prontuario_nao_encontrado" ) );
      }

      $oDaoTriagemProntuario                       = new cl_sau_triagemavulsaprontuario();
      $oDaoTriagemProntuario->s155_i_triagemavulsa = $oParam->iTriagem;
      $oDaoTriagemProntuario->s155_i_prontuario    = $oParam->iProntuario;
      $oDaoTriagemProntuario->incluir( null );

      if ( $oDaoTriagemProntuario->erro_status == '0' ) {

        $oErro->sErro = $oDaoTriagemProntuario->erro_msg;
        throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_incluir_triagem_prontuario", $oErro) );
      }

      break;

    case 'buscaEspecialidade' :

      if ( !isset($oParam->iProntuario) || empty($oParam->iProntuario) ) {
        throw new ParameterException( _M( ARQUIVO_MENSAGEM . "prontuario_nao_encontrado" ) );
      }

      $oRetorno->iEspecialidade = null;
      $oRetorno->sEspecialidade = '';
      $oDaoProntProced          = new cl_prontproced();
      $sCamposProntProced       = "rh70_sequencial, rh70_descr";
      $sWhereProntProced        = "sd29_i_prontuario = {$oParam->iProntuario}";
      $sSqlProntProced          = $oDaoProntProced->sql_query_especialidade( null, $sCamposProntProced, null, $sWhereProntProced );
      $rsProntProced            = db_query( $sSqlProntProced );

      if ( !$rsProntProced ) {

        $oErro->sErro = pg_last_error( $rsProntProced );
        throw new DBException( _M(ARQUIVO_MENSAGEM . "erro_buscar_especialidade_medico", $oErro) );
      }

      if( pg_num_rows( $rsProntProced ) > 0 ) {

        $oDadosEspecialidade      = db_utils::fieldsMemory( $rsProntProced, 0);
        $oRetorno->iEspecialidade = $oDadosEspecialidade->rh70_sequencial;
        $oRetorno->sEspecialidade = urlencode( $oDadosEspecialidade->rh70_descr );
      }

      break;
	}

  db_fim_transacao();
} catch ( Exception $oErro ) {

	db_fim_transacao(true);
	$oRetorno->status  = 2;
	$oRetorno->message = urlencode($oErro->getMessage());
}

/**
 * Valida os dados enviados por parâmetros
 * @param  Object $oParam 
 */
function validaDados( $oParam ) {

	if ( !isset($oParam->iCgsUnd) || $oParam->iCgsUnd == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_cgs") );
	}

	if ( !isset($oParam->iPressaoSistolica) || $oParam->iPressaoSistolica == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_pressao_sistolica") );
	}

	if ( !isset($oParam->iPressaoDiastolica) || $oParam->iPressaoDiastolica == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_pressao_diastolica") );	
	}

	if ( !isset($oParam->iCintura) || $oParam->iCintura == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_cintura") );
	}

	if ( !isset($oParam->nPeso) || $oParam->nPeso == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_peso") );
	}

	/**
	 * Valida quantidade de números decimais do peso informado
	 */
	$aPeso = explode(".", $oParam->nPeso);
	if ( count($aPeso) == 2 ) {

		if ( count($aPeso[1]) > 3) {
			throw new Exception( _M( ARQUIVO_MENSAGEM . "peso_acima_casa_decimais") );		
		}
	}

	if ( $oParam->nPeso > 999.999 ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "peso_superior") );		
	}

	if ( !isset($oParam->iAltura) || $oParam->iAltura == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_altura") );			
	}

	if ( $oParam->iAltura > 250 ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "altura_superior") );			
	}

	if ( $oParam->iGlicemia != "" && $oParam->iGlicemia > 0 && $oParam->iAlimentacaoExameGlicose == '') {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "selecione_forma_alimentacao") );
	}

	if ( !isset($oParam->iProfissional) || $oParam->iProfissional == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "selecione_profissional") );	
	}

	if ( !isset($oParam->iCbos) || $oParam->iCbos == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "selecione_cbos" ) );
	}

	if ( !isset($oParam->dtDataConsulta) || $oParam->dtDataConsulta == '' ) {
		throw new Exception( _M( ARQUIVO_MENSAGEM . "informe_data_consulta" ) );
	}
}

/**
 * Verifica se profissional logado é um profissional da saude e retorna o código, o nome do médico e uma flag
 * dizendo que é um profissional da saude
 * @param stdClass $oRetorno 
 */
function buscaProfissionalSaude( $oRetorno ) {

	$oRetorno->lProfissionalSaude = false;

	$oDaoMedicos     = new cl_medicos();
	$sCamposMedicos  = "z01_nome, sd03_i_codigo";
	$sWhereMedicos   = " sd02_i_codigo = ".db_getsession("DB_coddepto");
	$sWhereMedicos  .= " and db_usuacgm.id_usuario = ".db_getsession("DB_id_usuario");
	$sSqlMedicos     = $oDaoMedicos->sql_query_profissional_saude(null, $sCamposMedicos, null, $sWhereMedicos);
	$rsMedicos       = db_query( $sSqlMedicos );

	if ( !$rsMedicos ) {

		$oErro        = new stdClass();
		$oErro->sErro = $oDaoMedicos->erro_msg;
		throw new Exception( _M( ARQUIVO_MENSAGEM . "erro_buscar_medico", $oErro ) );
	}

	if ( pg_num_rows($rsMedicos) > 0 ) {

  	$oProfissional     = db_utils::fieldsmemory($rsMedicos, 0);
  	$oRetorno->sMedico = urlencode($oProfissional->z01_nome);
  	$oRetorno->iMedico = $oProfissional->sd03_i_codigo;
  	$oRetorno->lProfissionalSaude = true;
  }

  return $oRetorno;
}

/**
 * Verifica se existe CBO Profissional através da unidademedicos e cbos informados. 
 * Caso não haja, inclui um novo CBO Profissional.
 * Retorna o código do CBO Profissional.
 * @param  Object $oParam
 * @return integer
 */
function verificaCbosProfissional( $oParam ) {

	$oDaoCbosProfissional    = new cl_far_cbosprofissional();
	$sWhereCbosProfissional  = "     fa54_i_unidademedico = {$oParam->iUnidadeMedicos}";
	$sWhereCbosProfissional .= " and fa54_i_cbos = {$oParam->iCbos}";
	$sSqlCbosProfissional    = $oDaoCbosProfissional->sql_query_file(null, "fa54_i_codigo", null, $sWhereCbosProfissional);
	$rsCbosProfissional      = db_query( $sSqlCbosProfissional );

	if ( !$rsCbosProfissional ) {

		$oErro        = new stdClass();
		$oErro->sErro = $oDaoCbosProfissional->erro_msg;
		throw new Exception( _M( ARQUIVO_MENSAGEM . "erro_buscar_cbos_profissional", $oErro ) );
	}

	if ( pg_num_rows($rsCbosProfissional) > 0 ) {
		$iCbosProfissional = db_utils::fieldsmemory($rsCbosProfissional, 0)->fa54_i_codigo;
	} else {

		$oDaoCbosProfissional->fa54_i_unidademedico = $oParam->iUnidadeMedicos;
		$oDaoCbosProfissional->fa54_i_cbos          = $oParam->iCbos;
		$oDaoCbosProfissional->incluir(null);
		
		if ( $oDaoCbosProfissional->erro_status == '0' ) {

				$oErro        = new stdClass();
				$oErro->sErro = $oDaoCbosProfissional->erro_msg;
				throw new Exception( _M( ARQUIVO_MENSAGEM . "erro_incluir_cbos_profissional", $oErro ) );
		}

		$iCbosProfissional = $oDaoCbosProfissional->fa54_i_codigo;
	}

	return $iCbosProfissional;
}

echo $oJson->encode($oRetorno);
?>