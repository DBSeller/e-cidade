<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("fpdf151/pdf.php");

$oGet = db_utils::postMemory( $_GET );

/**
 * Cria a instância de Escola para preenchimento do cabeçalho
 */
$oEscola = EscolaRepository::getEscolaByCodigo( db_getsession( "DB_coddepto" ) );
$oJson   = new Services_JSON();

/**
 * Lê o contéudo do arquivo de log gerado
 */
$sArquivoLog  = file_get_contents( "{$oGet->sCaminhoArquivo}" );
$oJsonArquivo = $oJson->decode( $sArquivoLog );

/**
 * Define Largura e Altura padrões para a linha do arquivo PDF
 */
$iLargura = 192;
$iAltura  = 4;
 
/**
 * Caso o atributo aLogs não tenha sido setado ou não existam logs gerados, apresenta a mensagem e redireciona para 
 * o formulário de importação
 */
if ( !isset( $oJsonArquivo->aLogs ) || count( $oJsonArquivo->aLogs ) == 0 ) {
  
  $sMensagem = "Não foram encontrados dados com os filtros informados para geração do arquivo de log.";
  db_redireciona( "db_erros.php?fechar=true&db_erro={$sMensagem}" );
}

/**
 * Dados do cabeçalho
 */
$head1 = "EXPORTAÇÃO SITUAÇÃO DO ALUNO DO CENSO";
$head3 = "ESCOLA: {$oEscola->getCodigo()} - {$oEscola->getNome()}";
$head4 = "ANO: {$oGet->iAno}";
$head6 = "Registros com erros";

/**
 * Cria a instância de PDF e inicializa os métodos padrões
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true, 20);

$oPdf->addPage();
$oPdf->SetFont( "arial", "", 7 );
$oPdf->SetFillColor( 225, 225, 225 ); 

$iContador       = 0;
$iTotalRegistros = count($oJsonArquivo->aLogs);
foreach($oJsonArquivo->aLogs as $oErro) {

  $iPreenchimento = 0;
    
  if ( $iContador % 2 != 0 ) {
    $iPreenchimento = 1;
  }
  
  $oPdf->MultiCell($iLargura, $iAltura, utf8_decode($oErro->sErro), 0, 'L', $iPreenchimento);
  $iContador++;
}

$oPdf->Output();