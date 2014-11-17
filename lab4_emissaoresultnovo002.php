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
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');
require_once('fpdf151/FpdfMultiCellBorder.php');


$oRequisicaoLaboratorial = new RequisicaoLaboratorial( $requisicao );
$aAtributosDoExame       = array();

/**
 * Objeto com informações a serem utilizadas no relatório
 */
$oDadosEstrutura                 = new stdClass();
$oDadosEstrutura->iLarguraPadrao = 192;
$oDadosEstrutura->iAlturaPadrao  = 5;
$oDadosEstrutura->aSetor         = array();
$oDadosEstrutura->aExames        = array();
$oDadosEstrutura->iRequisicao    = $requisicao;

$oDadosEstrutura->oSolicitante          = new stdClass();
$oDadosEstrutura->oSolicitante->iCodigo = '';
$oDadosEstrutura->oSolicitante->sNome   = '';
$oDadosEstrutura->oSolicitante->sSexo   = '';
$oDadosEstrutura->oSolicitante->iIdade  = '';
$oDadosEstrutura->oSolicitante->sMedico = $oRequisicaoLaboratorial->getMedico();

/**
 * Array com atributos que possuem valor para impressão.
 * Ao buscar os dados, caso encontre o registro, incrementa o array
 */
$aAtributosSelecionaveis  = array();
$oDaoAtributoSelecionavel = new cl_lab_valorreferenciasel();
$sSqlAtributos            = $oDaoAtributoSelecionavel->sql_query_file();
$rsAtributosSelecionaveis = $oDaoAtributoSelecionavel->sql_record($sSqlAtributos);

for ($iAtributo = 0; $iAtributo < $oDaoAtributoSelecionavel->numrows; $iAtributo++) {

  $oDadosAtributoSelecionavel = db_utils::fieldsMemory($rsAtributosSelecionaveis, $iAtributo);
  $aAtributosSelecionaveis[$oDadosAtributoSelecionavel->la28_i_codigo] = $oDadosAtributoSelecionavel->la28_c_descr;
}

/**
 * Percorre os exames da requisição, para montar a estrutura do relatório
 */
foreach( $oRequisicaoLaboratorial->getRequisicoesDeExames() as $oRequisicao ) {

  /**
   * Caso não seja do tipo CONFERIDO '7 - Conferido', segue percorrendo o próximo registro
   */
  if ( !in_array($oRequisicao->getSituacao(), array(RequisicaoExame::CONFERIDO, RequisicaoExame::ENTREGUE)) ) {
    continue;
  }

  /**
   * Valida se foi selecionado algum exame específico
   */
  if ( isset( $requiitem ) && !empty( $requiitem ) && $requiitem != $oRequisicao->getCodigo() ) {
    continue;
  }

  /**
   * Valida se foi selecionado algum laboratório específico
   */
  if ( isset( $iLabSetor ) && !empty( $iLabSetor ) && $iLabSetor != $oRequisicao->getLaboratorioSetor()->getCodigo() ) {
    continue;
  }

  $oDadosEstrutura->aExames[] = $oRequisicao->getCodigo();

  /**
   * Preenche os dados do solicitante para impressão do cabeçalho
   */
  $dtNascimento = $oRequisicao->getSolicitante()->getDataNascimento()->getDate();

  $oDadosEstrutura->lSalvarArquivo        = $oRequisicao->getSituacao() == RequisicaoExame::CONFERIDO;
  $oDadosEstrutura->oSolicitante->iCodigo = $oRequisicao->getSolicitante()->getCodigo();
  $oDadosEstrutura->oSolicitante->sNome   = $oRequisicao->getSolicitante()->getNome();
  $oDadosEstrutura->oSolicitante->sSexo   = $oRequisicao->getSolicitante()->getSexo();
  $oDadosEstrutura->oSolicitante->iIdade  = getIdadeSolicitante( $dtNascimento )->anos;

  $oExame                          = $oRequisicao->getExame();
  $oResultadoExame                 = $oRequisicao->getResultado();
  $aAtributos                      = $oExame->getAtributos();


  $oDadosEstrutura->aConsideracoes = $oResultadoExame->getConsideracao();
  $aAtributosObservacao            = array();

  /**
   * Percorre cada atributo, e monta o objeto com as informações necessários do mesmo
   */
  foreach ($aAtributos as $oAtributo) {

    $oAtributoDoExame                     = new stdClass();
    $oAtributoDoExame->nome               = $oAtributo->getNome();
    $oAtributoDoExame->nivel              = $oAtributo->getNivel();
    $oAtributoDoExame->valorabsoluto      = '';
    $oAtributoDoExame->valorpercentual    = '';
    $oAtributoDoExame->unidade            = '';
    $oAtributoDoExame->referencia         = '';
    $oAtributoDoExame->tipo               = $oAtributo->getTipo();
    $oAtributoDoExame->tiporeferencia     = $oAtributo->getTipoReferencia();
    $oAtributoDoExame->iSetor             = $oRequisicao->getLaboratorioSetor()->getCodigo();

    if( !in_array( $oRequisicao->getCodigo(), $aAtributosObservacao ) ) {

      $aAtributosObservacao[]             = $oRequisicao->getCodigo();
      $oAtributoDoExame->sObservacaoExame = $oRequisicao->getObservacao();
    }

    $oResultadoAtributo = $oResultadoExame->getValorDoAtributo($oAtributo);

    if ($oAtributo->getUnidadeMedida() != "") {
      $oAtributoDoExame->unidade = $oAtributo->getUnidadeMedida()->getNome();
    }

    if (!empty($oResultadoAtributo)) {

      $oAtributoDoExame->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
      $oAtributoDoExame->valorpercentual = $oResultadoAtributo->getValorPercentual();

      switch ($oAtributo->getTipoReferencia() ) {

        case AtributoExame::REFERENCIA_NUMERICA:

          $oReferenciaAtributo  = $oResultadoAtributo->getFaixaUtilizada();


          if( !empty($oReferenciaAtributo) && $oReferenciaAtributo->getCodigo() == '' ) {
            $oReferenciaAtributo = $oAtributo->getValoresDeReferenciaParaExame($oRequisicao);
          }

          $iCasasDecimaisApresentacao = null;

          if( $oReferenciaAtributo instanceof AtributoValorReferenciaNumerico ) {
            $iCasasDecimaisApresentacao = $oReferenciaAtributo->getCasasDecimaisApresentacao();
          }

          $oAtributoDoExame->valorabsoluto = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oAtributoDoExame->valorabsoluto);

          if ($oReferenciaAtributo != '') {

            $iValorMinimo = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oReferenciaAtributo->getValorMinimo());
            $iValorMaximo = MascaraValorAtributoExame::mascarar($iCasasDecimaisApresentacao, $oReferenciaAtributo->getValorMaximo());

            $sStringReferencia            = "({$iValorMinimo} - {$iValorMaximo}) {$oAtributoDoExame->unidade}";
            $oAtributoDoExame->referencia = $sStringReferencia;
          }
          break;

        case AtributoExame::REFERENCIA_SELECIONAVEL:

          $oAtributoDoExame->referencia    = $oAtributoDoExame->unidade;
          if (isset($aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()])) {
            $oAtributoDoExame->valorabsoluto = $aAtributosSelecionaveis[$oResultadoAtributo->getValorAbsoluto()];
          }
          break;

        case AtributoExame::REFERENCIA_FIXA:

          $oAtributoDoExame->referencia    = $oAtributoDoExame->unidade;
          $oAtributoDoExame->valorabsoluto = $oResultadoAtributo->getValorAbsoluto();
          break;
      }
    }

    /**
     * Cria um objeto com os dados do setor e um array vazio de atributos a ser incrementado após incrementar o array
     * dos atributos
     */
    $oDadosSetor             = new stdClass();
    $oDadosSetor->iCodigo    = $oRequisicao->getLaboratorioSetor()->getCodigo();
    $oDadosSetor->sDescricao = $oRequisicao->getLaboratorioSetor()->getDescricao();
    $oDadosSetor->aAtributos = array();

    $oDadosEstrutura->aSetor[ $oRequisicao->getLaboratorioSetor()->getCodigo() ] = $oDadosSetor;
    $aAtributosDoExame[] = $oAtributoDoExame;
  }
}

if ( count( $aAtributosDoExame ) == 0 ) {

  db_msgbox("Nenhum registro encontrado.");
  db_redireciona("lab4_emissaoresult001.php");
}

/**
 * Percorre o array dos atributos, validando o setor de cada, e montando a estrutura por setor
 */
foreach( $aAtributosDoExame as $oDadosExame ) {

  if ( array_key_exists( $oDadosExame->iSetor, $oDadosEstrutura->aSetor ) ) {
    $oDadosEstrutura->aSetor[ $oDadosExame->iSetor ]->aAtributos[] = $oDadosExame;
  }
}

$oPdf = new FpdfMultiCellBorder();
$oPdf->exibeHeader(false);
$oPdf->mostrarRodape(false);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetTopMargin(1);
$oPdf->SetAutoPageBreak( false, 5 );

$oDadosEstrutura->sNome  = "Resultado({$oDadosEstrutura->oSolicitante->iCodigo})" . $oDadosEstrutura->oSolicitante->iCodigo . "_";
$oDadosEstrutura->sNome .= date("d-m-Y",db_getsession("DB_datausu")).".pdf";

/**
 * Percorre os setores
 */
foreach( $oDadosEstrutura->aSetor as $iSetor => $oSetor ) {

  $oDadosEstrutura->iSetor     = $iSetor;
  $oDadosEstrutura->aAtributos = $oSetor->aAtributos;

  montaCabecalho( $oPdf, $oDadosEstrutura );
  $oPdf->SetY( 50 );
  $oPdf->Cell( 190, $oDadosEstrutura->iAlturaPadrao, $oSetor->sDescricao, 'B', 1, "L" );
  $oPdf->SetFont('courier', "B", 10);
  atributosExame( $oPdf, $oDadosEstrutura );
  rodape( $oPdf, $oDadosEstrutura, $iSetor );
}

/**
 * ********************************************************
 * Monta o corpo do relatório com a estrutura dos atributos
 * @param scpdf $oPdf
 * @param $oDadosEstrutura
 * ********************************************************
 */
function atributosExame( scpdf $oPdf, $oDadosEstrutura ) {

  $oDadosEstrutura->lPrimeiroRegistro = true;

  /**
   * Array com a posição do X a ser setada, de acordo com o nível do atributo
   */
  $aPosicaoAtributos    = array();
  $aPosicaoAtributos[1] = 12;
  $aPosicaoAtributos[2] = 14;
  $aPosicaoAtributos[3] = 16;

  /**
   * String contendo as observações de todos os exames por setor
   */
  $sObservacao = '';

  /**
   * Percorre os atributos a serem impressos
   */
  foreach( $oDadosEstrutura->aAtributos as $oAtributos ) {

    $sNegrito                       = $oAtributos->tipo == 1 ? "b" : "";
    $oDadosEstrutura->iAlturaPadrao = $oAtributos->tipo == 1 ? 5 : 3.5;


    /**
     * Caso seja o primeiro registro da página, imprime o texto dos valores
     */
    if ( $oDadosEstrutura->lPrimeiroRegistro ) {

      $iPosicaoY                          = $oPdf->GetY();
      $oDadosEstrutura->lPrimeiroRegistro = false;
      $oPdf->SetXY( 136, $iPosicaoY );
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, "Valores de Referência", 0, 1, "L" );

      if ( $oAtributos->nivel == 1) {
        $oPdf->SetY( $iPosicaoY );
      }
    }


    $oPdf->SetFont( 'courier', $sNegrito, 10 );
    $oPdf->SetX( $aPosicaoAtributos[ $oAtributos->nivel ] );

    $iAlturaLinhaPadrao    = $oDadosEstrutura->iAlturaPadrao;
    $iNumeroLinhasOcupadas = 1;

    $iColunaNome        = 70;
    $iColunaVlrPercent  = 20;
    $iColunaVlrAbsoluto = 40;
    $iColunaReferencia  = 55;
    if ( $oPdf->NbLines($iColunaNome, $oAtributos->nome) > $iNumeroLinhasOcupadas) {
      $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaNome, $oAtributos->nome);
    }
    if ( $oPdf->NbLines($iColunaVlrPercent, $oAtributos->valorpercentual) > $iNumeroLinhasOcupadas) {
      $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaVlrPercent, $oAtributos->valorpercentual);
    }
    if ( $oPdf->NbLines($iColunaVlrAbsoluto,  $oAtributos->valorabsoluto) > $iNumeroLinhasOcupadas) {
      $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaVlrAbsoluto, $oAtributos->valorabsoluto);
    }
    if ( $oPdf->NbLines($iColunaReferencia,  $oAtributos->referencia) > $iNumeroLinhasOcupadas) {
      $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaReferencia, $oAtributos->referencia);
    }

    $iYInicio = $oPdf->GetY();
    $iXInicio = $oPdf->GetX();

    $iAlturaLinhaUsada = $iAlturaLinhaPadrao;
    $oPdf->SetXY($iXInicio, $iYInicio);

    /**
     * Verifica se o tipo de atributo é diferente de 1 e adiciona : após o nome
     */
    if ( $oAtributos->tipo != 1 ) {
      $oAtributos->nome .= ':';
    }

    $oPdf->MultiCell($iColunaNome, $iAlturaLinhaUsada, $oAtributos->nome, 0, 'L');
    $iXInicio += $iColunaNome;
    $oPdf->SetXY($iXInicio, $iYInicio);
    $oPdf->MultiCell($iColunaVlrPercent, $iAlturaLinhaUsada, $oAtributos->valorpercentual, 0, 'L');

    $iXInicio += $iColunaVlrPercent;
    $oPdf->SetXY($iXInicio, $iYInicio);
    $oPdf->MultiCell($iColunaVlrAbsoluto, $iAlturaLinhaUsada, $oAtributos->valorabsoluto, 0, 'L');
    $iXInicio += $iColunaVlrAbsoluto;
    $oPdf->SetXY($iXInicio, $iYInicio);
    $oPdf->MultiCell($iColunaReferencia, $iAlturaLinhaUsada, $oAtributos->referencia, 0, 'L');

    $oPdf->SetY($iYInicio + ($iAlturaLinhaPadrao * $iNumeroLinhasOcupadas) );

    if (!empty($oAtributos->sObservacaoExame) ) {
      $sObservacao .= " - " . $oAtributos->sObservacaoExame . "\n";
    }
  }

  if ( !empty($sObservacao) ) {

    $oPdf->Ln( 4 );
    $oPdf->SetFont( 'courier', 'b', 7 );

    $iLinhasObservacao = $oPdf->NbLines($oDadosEstrutura->iLarguraPadrao , $sObservacao);
    $iYinicio          = $oPdf->GetY();
    $iAlturaObservacao = ($iLinhasObservacao * 4) + $iYinicio;

    /**
     * 252 é a Altura até o quadro do rodapé
     */
    if ( $iAlturaObservacao > 252 ) {

      rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
      montaCabecalho( $oPdf, $oDadosEstrutura );
      $oPdf->SetY( 50 );
    }
    $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, 5, "Observação", 0, 1, "L" );
    $oPdf->SetFont( 'courier', '', 7 );
    $oPdf->MultiCell($oDadosEstrutura->iLarguraPadrao, 4, $sObservacao, 0, 'J');
  }

  if ( !empty( $oDadosEstrutura->aConsideracoes ) ) {

    $oPdf->Ln( 4 );
    $oPdf->SetFont( 'courier', 'b', 7 );

    $iLinhasConsideracoes = $oPdf->NbLines($oDadosEstrutura->iLarguraPadrao , $oDadosEstrutura->aConsideracoes);
    $iYInicio             = $oPdf->GetY();
    $iAlturaConsideracoes = ($iLinhasConsideracoes * 3) + $iYInicio;

    /**
     * 252 é a Altura até o quadro do rodapé
     */
    if ( $iAlturaConsideracoes > 252 ) {

      rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
      montaCabecalho( $oPdf, $oDadosEstrutura );
      $oPdf->SetY( 50 );
    }

    $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, 5, "Considerações", 0, 1, "L" );

    $oPdf->SetFont( 'courier', '', 7 );
    $oPdf->MultiCell( $oDadosEstrutura->iLarguraPadrao, 3, $oDadosEstrutura->aConsideracoes, 0, "L" );
  }
}

/**
 * *********************************************************
 * Monta o rodapé da página com a assinatura do profissional
 * @param scpdf $oPdf
 * @param $oDadosEstrutura
 * *********************************************************
 */
function rodape( scpdf $oPdf, $oDadosEstrutura, $iSetor ) {

  $oPdf->SetXY( 10, 250 );
  $oPdf->Rect( 8, 256, 192, 25 );

  $oPdf->SetXY( 14, 260 );
  $oPdf->SetFont( 'arial', '', 9 );

  $oDaoUsuarioLogado     = new cl_lab_labsetor();
  $sCamposUsuarioLogado  = " la47_i_login";
  $sWhereUsuarioLogado   = " la21_i_requisicao = {$oDadosEstrutura->iRequisicao} and la24_i_setor = {$iSetor}";
  $sSqlUsuarioLogado     = $oDaoUsuarioLogado->sql_query_cgm_lab_setor( null, $sCamposUsuarioLogado, null, $sWhereUsuarioLogado );
  $rsUsuarioLogado       = db_query( $sSqlUsuarioLogado );

  if ( $rsUsuarioLogado && pg_num_rows( $rsUsuarioLogado ) > 0 ) {

    $iUsuario = db_utils::fieldsMemory( $rsUsuarioLogado, 0 )->la47_i_login;
    $oUsuario = new UsuarioSistema( $iUsuario );

    $sCampos             = " la06_c_orgaoclasse as orgao_classe, la24_c_nomearq, la24_o_assinatura as assinatura";
    $sWhere              = " id_usuario = {$iUsuario} and la24_i_setor = {$iSetor}";
    $sSqlDadosAssinatura = $oDaoUsuarioLogado->sql_query_conferencia('', $sCampos, '', $sWhere);
    $rsDadosAssinatura   = db_query( $sSqlDadosAssinatura );

    if ( $rsDadosAssinatura && pg_num_rows( $rsDadosAssinatura ) > 0 ) {

      $oDadosAssinatura = db_utils::fieldsMemory( $rsDadosAssinatura, 0 );
      $sProfissional    = "Profissional: {$oUsuario->getCodigo()} - {$oUsuario->getNome()}";
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sProfissional, 0, 1, "L" );

      $oPdf->Ln( 2 );
      $oPdf->SetX( 14 );
      $sOrgaoClasse = "Órgão Classe: {$oDadosAssinatura->orgao_classe}";
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sOrgaoClasse, 0, 1, "L" );

      $sArquivo = "tmp/" . $oDadosAssinatura->la24_c_nomearq;

      db_query("begin");
      pg_loexport( $oDadosAssinatura->assinatura, $sArquivo );
      db_query("end");

      $oPdf->Image( $sArquivo, 170, 260, 15 );
    }

    $oPdf->Line( 10, 282, 200, 282 );

    $oDepartamento         = new DBDepartamento( $_SESSION['DB_coddepto'] );
    $oEnderecoDepartamento = $oDepartamento->getEndereco();

    $sEndereço = "Endereço: " . $oEnderecoDepartamento->sRua;

    if ( !empty($oEnderecoDepartamento->iNumero) ) {
      $sEndereço .= ", {$oEnderecoDepartamento->iNumero}";
    }

    if ( !empty($oEnderecoDepartamento->sComplemento) ) {
      $sEndereço .= " - {$oEnderecoDepartamento->sComplemento}";
    }

    if ( !empty($oEnderecoDepartamento->sBairro) ) {
      $sEndereço .= " - {$oEnderecoDepartamento->sBairro}";
    }

    if ( $oDepartamento->getInstituicao()->getMunicipio() != "" ) {
      $sEndereço .= " - " . $oDepartamento->getInstituicao()->getMunicipio();

      if ( $oDepartamento->getInstituicao()->getUf() != "" ) {
        $sEndereço .= "/" . $oDepartamento->getInstituicao()->getUf();
      }
    }

    $sContato = "";

    if ( $oDepartamento->getTelefone() != "" ) {
      $sContato = "Contato: " . $oDepartamento->getTelefone();
    }
    
    if ( $oDepartamento->getEmailDepartamento() != "" ){
      $sContato .= " - " . $oDepartamento->getEmailDepartamento();
    }
    
    $oPdf->setY(282);
    $oPdf->SetFont("arial","B", 5);
    $oPdf->Cell($oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sEndereço, 0, 1, "C");
    $oPdf->Cell($oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sContato,  0, 1, "C");
  }
}

/**
 * *************************
 * Salva o relatório gerado
 * @param $oDadosEstrutura
 * *************************
 */
function salvaRelatorio( $oDadosEstrutura ) {

  $oDaoLabEmissao = new cl_lab_emissao();

  db_inicio_transacao();

  foreach( $oDadosEstrutura->aExames as $iExame ) {

    $iOid     = DBLargeObject::criaOID(true);
    $mEscrita = DBLargeObject::escrita( "tmp/{$oDadosEstrutura->sNome}", $iOid );

    $oDaoLabEmissao->la34_o_laudo     = $mEscrita;
    $oDaoLabEmissao->la34_c_nomearq   = "tmp/$oDadosEstrutura->sNome";
    $oDaoLabEmissao->la34_d_data      = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoLabEmissao->la34_c_hora      = db_hora();
    $oDaoLabEmissao->la34_i_requiitem = $iExame;
    $oDaoLabEmissao->la34_i_usuario   = $oDadosEstrutura->iUsuario;
    $oDaoLabEmissao->la34_i_forma     = 1;
    $oDaoLabEmissao->incluir(null);
  }
  db_fim_transacao();
}

/**
 * Calcula a idade que o solicitante tem com base na data do sistema
 * @param  date    $dtNascimento
 * @return stdClass
 */
function getIdadeSolicitante( $dtNascimento ) {

  $oIdade        = new stdClass();
  $oIdade->anos  = 0;
  $oIdade->meses = 0;
  $oIdade->dias  = 0;

  if ($dtNascimento == "") {
    return '';
  }

  $dtSistema       = date("Y-m-d", db_getsession("DB_datausu"));
  $sSqlAnoMesDia   = "SELECT fc_idade_anomesdia('{$dtNascimento}', '{$dtSistema}', false) as dias;";
  $rsAnoMesDia     = db_query($sSqlAnoMesDia);
  if ($rsAnoMesDia && pg_num_rows($rsAnoMesDia) > 0) {

    $aDadosIdade   = explode(',', db_utils::fieldsMemory($rsAnoMesDia, 0)->dias);
    $oIdade->anos  = trim($aDadosIdade[0]);
    $oIdade->meses = trim($aDadosIdade[1]);
    $oIdade->dias  = trim($aDadosIdade[2]);

  }

  return $oIdade;
}

function montaCabecalho( $oPdf, $oDadosEstrutura ) {
  $oPdf->AddPage();

  if ( !isset($_SESSION['DB_coddepto']) || empty($_SESSION['DB_coddepto']) ) {
    return;
  }

  try{

    $oDepartamento = new DBDepartamento( $_SESSION['DB_coddepto'] );
    $oInstituicao  = $oDepartamento->getInstituicao()->getDadosPrefeitura();

    if ( $oInstituicao->getImagemLogo() != "" ) {
      $oPdf->Image('imagens/files/' . $oInstituicao->getImagemLogo(), 7, 7, 20);
    }

    $oPdf->SetFont("arial","B", 8);
    $oPdf->Text( 33, 9, $oDepartamento->getNomeDepartamento() );
    $oPdf->Text( 33, 14, substr($oInstituicao->getDescricao(),0 , 42 ) );
    $oPdf->SetFont("arial","", 8);

    $sEndereço  = $oInstituicao->getLogradouro();
    $sEndereço .= ", " . $oInstituicao->getNumero();

    if ( $oInstituicao->getComplemento() != "" ) {
      $sEndereço .= ", " . $oInstituicao->getComplemento();
    }

    $oPdf->Text( 33, 19, $sEndereço );

    $sMunicipio  = $oInstituicao->getMunicipio();
    $sMunicipio .= " - " . $oInstituicao->getUf();
    $oPdf->Text( 33, 23, $sMunicipio );

    $sTelefoneCnpj  = $oInstituicao->getTelefone();

    if ( $oInstituicao->getCNPJ() != "" ) {
      $sTelefoneCnpj .= " - CNPJ: " . $oInstituicao->getCNPJ();
    }

    $oPdf->Text( 33, 27, $sTelefoneCnpj );
    $oPdf->Text( 33, 31, substr($oInstituicao->getEmail(), 0, 48 ));
    $oPdf->Text( 33, 35, substr($oInstituicao->getSite(),  0, 50 ));


    $aSexo        = array();
    $aSexo[ "M" ] = "MASCULINO";
    $aSexo[ "F" ] = "FEMININO";

    $iLarguraLabel     = 16;
    $iLarguraDescricao = 63;

    $oPdf->SetFillColor(240);
    $oPdf->RoundedRect( 120, 6, 83, 39, 2, 'DF', '123' );

    $oPdf->SetY(8);

    $oPdf->setX(120);
    $oPdf->SetFont("arial", "B", 7);
    $oPdf->Cell( $iLarguraDescricao + $iLarguraLabel, 4, "DADOS DO PACIENTE", 0, 1, "C" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Requisição:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $oDadosEstrutura->iRequisicao, 0, 1, "L" );

    $oPdf->SetX(120);    
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Paciente:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->MultiCell( $iLarguraDescricao, 4, $oDadosEstrutura->oSolicitante->sNome, 0, "L" );

    $oPdf->SetX(120);    
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Idade:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $oDadosEstrutura->oSolicitante->iIdade, 0, 1, "L" );

    $oPdf->SetX(120);    
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Sexo:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $aSexo[$oDadosEstrutura->oSolicitante->sSexo], 0, 1, "L" );

    $oPdf->SetX(120);   
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Médico:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->MultiCell( $iLarguraDescricao, 4, $oDadosEstrutura->oSolicitante->sMedico, 0, "L" );

    $oPdf->SetX(120);    
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Convênio:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, "SUS", 0, 1, "L" );

    $oPdf->Line( 10, 45, 200, 45 );
  } catch( Exception $oErro ) {

    db_msgbox($oErro->getMessage());
    db_redireciona("lab4_emissaoresult001.php");
  }
}


$oPdf->Output( $oDadosEstrutura->sNome, true, true );
if ($oDadosEstrutura->lSalvarArquivo) {
  salvaRelatorio( $oDadosEstrutura );
}