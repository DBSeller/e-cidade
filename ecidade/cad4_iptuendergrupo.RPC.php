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

require_once modification("libs/db_stdlib.php");
require_once modification("std/db_stdClass.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

db_app::import('exceptions.*');

/**
 * Carregamos na memória os valores enviados por ajax para o programa atual.
 */
$oJson  = new Services_JSON();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));

/**
 * Criamos o objeto de retorno que será utilizada fazer o retorno de cada CASE.
 */
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = null;

/**
 * Efetuamos um switch para distingüir qual será o comportamento do programa.
 */
switch ($oParam->sExec) {
	
	/**
	 * CASE que efetua a busca de imóveis para popular a grid do formulário.
	 * Verificamos o tipo de busca que o usuário selecionou (por CGM ou por matrícula) e definimos 
	 * o comportamento necessário.
	 */
	case "buscarMatriculas":
		
		/**
		 * Definimos as variáveis que são utilizadas pelos dois comportamentos.
		 */
		$oRetorno->aMatriculas = array();
    $sWhereMatriculas      = null;

		if ( $oParam->iDbOpcao == 3 ) {
			$sWhereMatriculas = 'j43_matric is not null';
		}

		/**
		 * -------------------------------------------------------------------------------------------------------
		 * Pesquisa por CGM - flag de consulta por CGM TRUE
		 * -------------------------------------------------------------------------------------------------------
	   */
		if ($oParam->buscaPorCgm) {
			
			/**
			 * Verificamos se o CGM informado é de alguma IMOBILIARIA.
			 */
			$oDaoImobil = db_utils::getDao('imobil');
			$sCampos    = "j01_matric, j43_ender, j43_numimo, j43_comple, j43_munic";
			$sSqlImobil = $oDaoImobil->sql_query_enderecoEntrega($oParam->iNumeroCgm, $sCampos, $sWhereMatriculas);
			$rsImobil   = $oDaoImobil->sql_record($sSqlImobil);

			if ($oDaoImobil->numrows > 0) {
				
				$aMatriculasImobiliaria = db_utils::getCollectionByRecord($rsImobil, false, false, true);
				foreach ($aMatriculasImobiliaria as $oMatricula) {

					$oDadosMatricula               = new stdClass();
					$oDadosMatricula->iMatricula   = $oMatricula->j01_matric;
					$oDadosMatricula->sEndereco    = $oMatricula->j43_ender;
					$oDadosMatricula->iNumero      = $oMatricula->j43_numimo;
					$oDadosMatricula->sComplemento = $oMatricula->j43_comple;
					$oDadosMatricula->sMunicipio   = $oMatricula->j43_munic;
					$oDadosMatricula->sTipoVinculo = urlencode("IMOBILIÁRIA");
					$oRetorno->aMatriculas[]       = $oDadosMatricula;
				}
			}
			
			/**
			 * Verificamos se o CGM informado é de algum promitente
			 */
			$oDaoPromitente = db_utils::getDao('promitente');
			$sCampos        = "j01_matric, j43_ender, j43_numimo, j43_comple, j43_munic";
			$sSqlPromitente = $oDaoPromitente->sql_query_enderecoEntrega($oParam->iNumeroCgm, $sCampos, $sWhereMatriculas);
			$rsPromitente   = $oDaoPromitente->sql_record($sSqlPromitente);
			
			if ($oDaoPromitente->numrows > 0) {
				
				$aMatriculasPromitente = db_utils::getCollectionByRecord($rsPromitente, false, false, true);
				foreach ($aMatriculasPromitente as $oMatricula) {
					
					$oDadosMatricula               = new stdClass();
					$oDadosMatricula->iMatricula   = $oMatricula->j01_matric;
					$oDadosMatricula->sEndereco    = $oMatricula->j43_ender;
					$oDadosMatricula->iNumero      = $oMatricula->j43_numimo;
					$oDadosMatricula->sComplemento = $oMatricula->j43_comple;
					$oDadosMatricula->sMunicipio   = $oMatricula->j43_munic;
					$oDadosMatricula->sTipoVinculo = urlencode("PROMITENTE");
					$oRetorno->aMatriculas[]       = $oDadosMatricula;
				}
			}
			
			/**
			 * Verificamos se o CGM é algum proprietário secundário
			 */
			$oDaoPropri = db_utils::getDao('propri');
			$sCampos    = "j01_matric, j43_ender, j43_numimo, j43_comple, j43_munic";
			$sSqlPropri = $oDaoPropri->sql_query_enderecoEntrega($oParam->iNumeroCgm, $sCampos, $sWhereMatriculas);
			$rsPropri   = $oDaoPropri->sql_record($sSqlPropri);

			if ($oDaoPropri->numrows > 0) {
				
				$aMatriculasProprietario = db_utils::getCollectionByRecord($rsPropri, false, false, true);

				foreach ($aMatriculasProprietario as $oMatricula) {
					
					$oDadosMatricula               = new stdClass();
					$oDadosMatricula->iMatricula   = $oMatricula->j01_matric;
					$oDadosMatricula->sEndereco    = $oMatricula->j43_ender;
					$oDadosMatricula->iNumero      = $oMatricula->j43_numimo;
					$oDadosMatricula->sComplemento = $oMatricula->j43_comple;
					$oDadosMatricula->sMunicipio   = $oMatricula->j43_munic;
					$oDadosMatricula->sTipoVinculo = urlencode("PROPRIETÁRIO");
					$oRetorno->aMatriculas[]       = $oDadosMatricula;
				}
			}
			
			/**
			 * Verificamos se o CGM é proprietário principal.
			 */
			$oDaoIptuBase = db_utils::getDao('iptubase');
			$sCampos      = "j01_matric, j43_ender, j43_numimo, j43_comple, j43_munic";
			$sSqlIptuBase = $oDaoIptuBase->sql_query_enderecoEntrega($oParam->iNumeroCgm, $sCampos, $sWhereMatriculas);
			$rsIptuBase   = $oDaoIptuBase->sql_record($sSqlIptuBase);
			
			if ($oDaoIptuBase->numrows > 0) {
				
				$aMatriculasProprietario = db_utils::getCollectionByRecord($rsIptuBase, false, false, true);
				foreach ($aMatriculasProprietario as $oMatricula) {
					
					$oDadosMatricula               = new stdClass();
					$oDadosMatricula->iMatricula   = $oMatricula->j01_matric;
					$oDadosMatricula->sEndereco    = $oMatricula->j43_ender;
					$oDadosMatricula->iNumero      = $oMatricula->j43_numimo;
					$oDadosMatricula->sComplemento = $oMatricula->j43_comple;
					$oDadosMatricula->sMunicipio   = $oMatricula->j43_munic;
					$oDadosMatricula->sTipoVinculo = urlencode("PROPRIETÁRIO");
					$oRetorno->aMatriculas[]       = $oDadosMatricula;
				}
			}

			/**
			 * Verifica se encontrou alguma matricula pelo CGM
			 */
			if (count($oRetorno->aMatriculas) == 0) {

				$oRetorno->iStatus  = 3;
				$oRetorno->sMessage = urlencode("Não foi encontrada nenhuma matrícula para o CGM {$oParam->iNumeroCgm}");
			}

			break;
		} 

		/**
		 * -------------------------------------------------------------------------------------------------------
		 * Pesquisa por MATRICULA - flag de consulta por CGM FALSE
		 * -------------------------------------------------------------------------------------------------------
		 */	 
		
		$oDaoIptuBase  = db_utils::getDao('iptubase');
		$oDaoIptuEnder = db_utils::getDao('iptuender');

		/**
		 * Pesquisa por MATRICULA
		 */
		$sCampos       = "j01_matric, j43_ender, j43_numimo, j43_comple, j43_munic, j43_iptuendergrupo";
		$sWhere        = "where j01_matric =  {$oParam->iNumeroMatricula}";

		if ( $oParam->iDbOpcao == 3 ) {
			$sWhere .= ' and '.$sWhereMatriculas;
		}
		
		$sSqlIptuBase = $oDaoIptuBase->sql_query_enderecoEntrega(null, $sCampos, $sWhere);
		$rsIptuBase   = $oDaoIptuBase->sql_record($sSqlIptuBase);
		
		if ($oDaoIptuBase->numrows > 0) {
			
			$oMatricula								     = db_utils::fieldsMemory($rsIptuBase, 0, false, false, true);
			$oDadosMatricula               = new stdClass();
			$oDadosMatricula->iMatricula   = $oMatricula->j01_matric;
			$oDadosMatricula->sEndereco    = $oMatricula->j43_ender;
			$oDadosMatricula->iNumero      = $oMatricula->j43_numimo;
			$oDadosMatricula->sComplemento = $oMatricula->j43_comple;
			$oDadosMatricula->sMunicipio   = $oMatricula->j43_munic;
			$oDadosMatricula->sTipoVinculo = isset($oParam->lLancarMatricula) ? urlEncode('Endereço') : "";
			$oRetorno->aMatriculas[]       = $oDadosMatricula;

			/**
			 * Pesquisa todas as matriculas que possuem o mesmo grupo
			 *  - Quando existir parametro lLancarMatricula não pesquisar pelo grupo de endereço
			 */	 
			if ( !isset($oParam->lLancarMatricula) && $oMatricula->j43_iptuendergrupo > 0 ) {

				$sWhereMatriculasAgrupadas = "j43_matric <> {$oMatricula->j01_matric}";
				$sSqlMatriculasAgrupadas   = $oDaoIptuEnder->sql_queryMatriculasAgrupadas($oMatricula->j43_iptuendergrupo, '*', $sWhereMatriculasAgrupadas);
				$rsMatriculasAgrupadas     = $oDaoIptuEnder->sql_record($sSqlMatriculasAgrupadas);
				
				if ($oDaoIptuEnder->numrows > 0) {

					$aMatriculasAgrupadas = db_utils::getCollectionByRecord($rsMatriculasAgrupadas, false, false, true);

					foreach ($aMatriculasAgrupadas as $oMatriculasAgrupadas) {
					
						$oDadosMatriculasAgrupadas               = new stdClass();
						$oDadosMatriculasAgrupadas->iMatricula   = $oMatriculasAgrupadas->j43_matric;
						$oDadosMatriculasAgrupadas->sEndereco    = $oMatriculasAgrupadas->j43_ender;
						$oDadosMatriculasAgrupadas->iNumero      = $oMatriculasAgrupadas->j43_numimo;
						$oDadosMatriculasAgrupadas->sComplemento = $oMatriculasAgrupadas->j43_comple;
						$oDadosMatriculasAgrupadas->sMunicipio   = $oMatriculasAgrupadas->j43_munic;
						$oDadosMatriculasAgrupadas->sTipoVinculo = urlEncode("Endereço"); 
						$oRetorno->aMatriculas[]                 = $oDadosMatriculasAgrupadas;
					}
				}
			} 
		}

		/**
		 * Verifica se encontrou alguma matricula e se nao existir o parametro lLancarMatricula
		 */
		if (count($oRetorno->aMatriculas) == 0 && !isset($oParam->lLancarMatricula) ) {
				
			$oRetorno->iStatus  = 3;
			$oRetorno->sMessage = urlEncode("Matrícula sem endereço de entrega cadastrado");
		}
			
	break;
	
	/**
	 * CASE que salva o novo endereço de entrega para as matrículas selecionadas.
	 *
	 * - Verificamos se as matrículas informadas ainda não estão em nenhum grupo (campo j43_iptuendergrupo da  tabela iptuender)
	 *   em caso positivo informamos ao usuário com um confirm perguntando se ele deseja retirar a
	 *   matrícula do grupo e abortamos a operação.
   *
	 * - Após a verificação descrita acima percorremos as matrículas alterando/incluindo seus dados com as que foram informadas no formulário. 
	 */
	case "salvarEndereco":
		
		$oDaoIptuEnder   = db_utils::getDao('iptuender');

		/**
		 * String com as matriculas selecionadas na grid
		 */
		$sInMatriculas   = implode(", ", $oParam->aMatriculasSelecionadas);

		/**
		 * Monta sql para buscar os grupos das matriculas selecionadas 
		 */
		$sWhereIptuEnder = " j43_matric in ({$sInMatriculas}) and j43_iptuendergrupo is not null ";
		$sSqlIptuEnder   = $oDaoIptuEnder->sql_query_file(null, "distinct j43_iptuendergrupo", null, $sWhereIptuEnder);
		$rsIptuEnder     = $oDaoIptuEnder->sql_record($sSqlIptuEnder);

		/**
		 * Flag para verificar se deve gerar novo grupo de endereço 
		 */
		$lMantemGrupo = false;

		/**
		 * Verifica se das matriculas selecionadas existe mais de um grupo
		 * Verificamos se a flag que indica para sobreescrever os grupos de endereço está habilitada ou não.
		 * - Retorna para usuario se usuario clicar em ok a fla lDesvinculaMatriculas ser true e continuara o script
		 */
		if ( $oDaoIptuEnder->numrows > 1 && $oParam->lDesvinculaMatriculas == false ) {

			$sMensagemErro      = "Existem matrículas selectionadas que pertencem a outro grupo de endereços de entrega.";
			$oRetorno->iStatus  = 3;
			$oRetorno->sMessage = urlencode($sMensagemErro);

			break;
		}

		/**
		 * Se numrows da busca dos grupos for 1, então todas as matriculas selecionadas tem o mesmo grupo
		 *  - Depois Verifica se a quantidade de matriculas selecionadas é o mesmo do total das matriculas desse grupo de endereço
		 *  - Se for diferente gera um novo grupo
		 */
		if ( $oDaoIptuEnder->numrows == 1 ) {

			$oIptuEnder          = db_utils::fieldsMemory($rsIptuEnder, 0);	
			$iCodigoGrupoEndereco = $oIptuEnder->j43_iptuendergrupo;

			$sWhereVerificaGrupo  = "j43_iptuendergrupo = {$iCodigoGrupoEndereco}";
			$sCamposVerificaGrupo = "count(j43_iptuendergrupo) as total_matriculas_agrupadas";
			$sSqlVerificaGrupo    = $oDaoIptuEnder->sql_query_file(null, $sCamposVerificaGrupo, null, $sWhereVerificaGrupo);
			$rsVerificaGrupo      = $oDaoIptuEnder->sql_record($sSqlVerificaGrupo);
			$oVerificaGrupo       = db_utils::fieldsMemory($rsVerificaGrupo, 0);

			$iTotalMatriculasSelecionadas = count( $oParam->aMatriculasSelecionadas );
			$iTotalMatriculasAgrupadas    = $oVerificaGrupo->total_matriculas_agrupadas;

			if ($iTotalMatriculasAgrupadas == $iTotalMatriculasSelecionadas) {
				$lMantemGrupo = true;
			}
		}
			
		try {

			db_inicio_transacao();
			
			/**
			 * Array com as matriculas que já possuem iptuender 
			 */
			$aMatriculasAlteradas = array();

			$oDaoIptuEnderGrupo = db_utils::getDao('iptuendergrupo');
			$oDaoIptuEnder      = db_utils::getDao('iptuender');

			/**
			 * Verifica se deve criar um grupo 
			 */
			if ( !$lMantemGrupo )  {

				/**
				 * Gera um grupo para os endereços
				 */
				$oDaoIptuEnderGrupo->j135_observacao = ' ';
				$oDaoIptuEnderGrupo->incluir(null);

				if ($oDaoIptuEnderGrupo->erro_status == 0) {
					throw new DBException('Erro na inclusão do grupo dos endereços \n\n'.$oDaoIptuEnderGrupo->erro_msg);
				}

				$iCodigoGrupoEndereco = $oDaoIptuEnderGrupo->j135_sequencial;
			}
			
			/**
			 * Busca os dados das matriculas que já possuem endereço 
			 */
			$sWhereIptuEnder = " j43_matric in ({$sInMatriculas}) ";
			$sSqlIptuEnder   = $oDaoIptuEnder->sql_query_file(null, "*", null, $sWhereIptuEnder);
			$rsIptuEnder     = $oDaoIptuEnder->sql_record($sSqlIptuEnder);

			/**
			 * Define os valores a serem usados na alteração e na inclusão das matriculas  
			 */
			$oDaoIptuEnder->j43_dest           = db_stdClass::normalizeStringJson($oParam->sNomeDestinatario);
			$oDaoIptuEnder->j43_ender          = db_stdClass::normalizeStringJson($oParam->sLogradouro);
			$oDaoIptuEnder->j43_numimo         = db_stdClass::normalizeStringJson($oParam->iNumero);
			$oDaoIptuEnder->j43_comple         = db_stdClass::normalizeStringJson($oParam->sComplemento);
			$oDaoIptuEnder->j43_bairro         = db_stdClass::normalizeStringJson($oParam->sBairro);
			$oDaoIptuEnder->j43_munic          = db_stdClass::normalizeStringJson($oParam->sMunicipio);
			$oDaoIptuEnder->j43_uf             = db_stdClass::normalizeStringJson($oParam->sUF);
			$oDaoIptuEnder->j43_cep            = db_stdClass::normalizeStringJson($oParam->sCEP);
			$oDaoIptuEnder->j43_cxpost         = db_stdClass::normalizeStringJson($oParam->sCaixaPostal);
			$oDaoIptuEnder->j43_iptuendergrupo = $iCodigoGrupoEndereco;			
			
			/**
			 * Verifica se existem matriculas com endereço e altera (iptuender)
			 */
			if ($oDaoIptuEnder->numrows > 0) {
				
				$aMatriculas = db_utils::getCollectionByRecord($rsIptuEnder);
				foreach ($aMatriculas as $oMatricula) {
					
					$aMatriculasAlteradas[]    = $oMatricula->j43_matric;
					$oDaoIptuEnder->j43_matric = $oMatricula->j43_matric;
					
					$oDaoIptuEnder->alterar($oMatricula->j43_matric);

					if ($oDaoIptuEnder->erro_status == 0) {
						throw new DBException('Erro na alteração \n\n'.$oDaoIptuEnder->erro_msg);
					}
				}
			}
      
			/**
			 * Verifica se existe matriculas sem endereço e inclui (iptuender)
			 */
			if ( ( isset($aMatriculas) && count($aMatriculas) <> count($oParam->aMatriculasSelecionadas) ) || !isset($aMatriculas) )  {

				/**
				 * Array com as matriculas que NÃO possuem iptuender 
				 */
				$aMatriculasNovas = array_diff(array_unique($oParam->aMatriculasSelecionadas), $aMatriculasAlteradas );

				/**
				 * Incluir endereço para as matriculas selecinadas 
				 */
				foreach ($aMatriculasNovas as $iMatricula) {

					$oDaoIptuEnder->j43_matric = $iMatricula;
					$oDaoIptuEnder->incluir(null);

					if ($oDaoIptuEnder->erro_status == 0) {
						throw new DBException('Erro na inclusão \n\n'.$oDaoIptuEnder->erro_msg);
					}
				}

			}
			
			/**
			 * Deleta os grupos que não estão sendo usados no cadastro de endereço (tabela iptuender)
			 */
			$sSqlIptuEnderGrupo = $oDaoIptuEnderGrupo->sql_queryGruposNaoUsados();
			$rsIptuEnderGrupo   = $oDaoIptuEnderGrupo->sql_record($sSqlIptuEnderGrupo);

			if ($oDaoIptuEnderGrupo->numrows > 0) {

				$aGruposEnderecoParaDeletar = db_utils::getCollectionByRecord($rsIptuEnderGrupo);

				foreach ($aGruposEnderecoParaDeletar as $oGrupoEndereco) {

					$oDaoIptuEnderGrupo->j135_sequencial = $oGrupoEndereco->j135_sequencial;
					$oDaoIptuEnderGrupo->excluir($oGrupoEndereco->j135_sequencial);

					if ($oDaoIptuEnderGrupo->erro_status == 0) {
						throw new DBException('Erro na exclusão dos grupos de endereços \n\n'.$oDaoIptuEnderGrupo->erro_msg);
					}
				}
			}

			db_fim_transacao(false);

		} catch (DBException $eException) {
			
			db_fim_transacao(true);
			$oRetorno->iStatus  = 2;
			$oRetorno->sMessage = urlencode($eException->getMessage());
		}
		
	break;
	
	/**
	 * CASE que exclui os endereços de entrega das matrículas selecionadas no formulário.
	 * Após a exclusão dos mesmos verificamos se os grupos de endereço dos endereços de entrega excluídos ainda
	 * possuem algum registro na tabela iptuender  
	 */
	case "excluirEnderecos":
	
		$oDaoIptuEnder      = db_utils::getDao('iptuender');
		$oDaoIptuEnderGrupo = db_utils::getDao('iptuendergrupo');

		/**
		 * String com as matriculas selecionadas 
		 */
		$sInMatriculas      = implode(", ", $oParam->aMatriculasSelecionadas);

		$sWhereIptuEnder    = " j43_matric in ({$sInMatriculas}) ";
		$sSqlIptuEnder      = $oDaoIptuEnder->sql_query_file(null, "*", null, $sWhereIptuEnder);
		$rsIptuEnder        = $oDaoIptuEnder->sql_record($sSqlIptuEnder);

		if ($oDaoIptuEnder->numrows > 0) {
			
			try {

				db_inicio_transacao();

				$oDaoIptuEnderGrupo = db_utils::getDao('iptuendergrupo');
				$aMatriculas        = db_utils::getCollectionByRecord($rsIptuEnder);

				foreach ($aMatriculas as $oMatricula) {

					$oDaoIptuEnder = db_utils::getDao('iptuender');
					$oDaoIptuEnder->j43_matric = $oMatricula->j43_matric;
					$oDaoIptuEnder->excluir($oMatricula->j43_matric);

					if ($oDaoIptuEnder->erro_status == 0) {
						throw new DBException('Erro na exclusão dos endereços \n\n'.$oDaoIptuEnder->erro_msg);
					}
				}

				/**
				 * Deleta os grupos que não estão sendo usados no cadastro de endereço (tabela iptuender)
				 */
				$sSqlIptuEnderGrupo = $oDaoIptuEnderGrupo->sql_queryGruposNaoUsados();
				$rsIptuEnderGrupo   = $oDaoIptuEnderGrupo->sql_record($sSqlIptuEnderGrupo);

				if ($oDaoIptuEnderGrupo->numrows > 0) {

					$aGruposEnderecoParaDeletar = db_utils::getCollectionByRecord($rsIptuEnderGrupo);

					foreach ($aGruposEnderecoParaDeletar as $oGrupoEndereco) {

						$oDaoIptuEnderGrupo->j135_sequencial = $oGrupoEndereco->j135_sequencial;
						$oDaoIptuEnderGrupo->excluir($oGrupoEndereco->j135_sequencial);

						if ($oDaoIptuEnderGrupo->erro_status == 0) {
							throw new DBException('Erro na exclusão dos grupos de endereços \n\n'.$oDaoIptuEnderGrupo->erro_msg);
						}
					}
				}

				db_fim_transacao(false);

			} catch (DBException $oException) {
				
				db_fim_transacao(true);
				$oRetorno->iStatus  = 2;
				$oRetorno->sMessage = urlencode($oException->getMessage());
			}

		} else {
			
			$oRetorno->iStatus  = 2;
			$oRetorno->sMessage = urlencode("As matrículas informadas não possuem endereço de entrega cadastrado.");
		}

	break;

}

/**
 * Retornamos o objeto de retorno.
 */ 
echo $oJson->encode($oRetorno);