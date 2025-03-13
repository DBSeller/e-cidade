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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Importacao;
use ECidade\V3\Extension\Logger;

?>

<html>
<head>
  <?php
  db_app::load('scripts.js, strings.js, prototype.js, estilos.css, datagrid.widget.js, AjaxRequest.js, ProgressBar.widget.js, DBDownload.widget.js');
  ?>
  <style media="screen" type="text/css">
    #logs {
      height: 50px;
      overflow-y: auto;
      width: 100%;
      background-color: #000;
      padding-top: 3px;
      border-radius: 3px; 
    }

    #logs .item-log {
      margin: 05px 10px 2px 10px;
      color: rgba(230, 221, 221, 0.85);
    }
    #voltar {
      margin-top: 14px;
      display: none;
    }
  </style>
</head>
<body class="body-default">
  <div class="container">
    <fieldset style="width: 700px; padding: 2px">
      <progress id="barra-progresso-arquivo" value="0" style="width: 100%; height: 25px;">Processando</progress>
    </fieldset> 
    <fieldset style="width: 700px; padding: 2px">
      <progress id="barra-progresso-linha" value="0" style="width: 100%; height: 25px;">Processando</progress>
    </fieldset> 
    <div id="logs"></div>
    <input type="button" name="voltar" id="voltar" onclick="location.href='rec4_pontoeletronicoimportacaoarquivo001.php'" value="Voltar" />
  </div>
<script type="text/javascript">
  var barraArquivo          = $('barra-progresso-arquivo');
  var barraLinha            = $('barra-progresso-linha');
  var barraProgressoArquivo = new ProgressBar(barraArquivo, $('logs'));
  var barraProgressoLinha   = new ProgressBar(barraLinha, $('logs'));
</script>
<?php

  try {

    db_inicio_transacao();

    $qtdeLinhasArquivos = 0;
    $pisIgnorados       = array();
    $aArquivos          = (object)$_FILES['rh196_arquivos'];
    $iCodigoInstituicao = db_getsession('DB_instit');
    
    $barraProgressoArquivo = new \ProgressBar('barraProgressoArquivo');
    $barraProgressoLinha   = new \ProgressBar('barraProgressoLinha');
    
    $path   = ECIDADE_PATH . 'tmp/.log/';
    if(!is_dir($path)) {
      mkdir($path);
    }
    $sNomeArquivoLog = 'importacao_ponto_eletronico_'. date('Ymd') .'.log';
    $path           .= $sNomeArquivoLog;
    $logger = new Logger($path, Logger::INFO);
    
    $iArquivo = 0;

    foreach ($aArquivos->tmp_name as $indice => $arquivoTmpName) {
      
      $sNomeArquivo  = 'tmp/'.$aArquivos->name[$indice];

      move_uploaded_file($arquivoTmpName, $sNomeArquivo);

      $aNomesArquivos[]    = $sNomeArquivo;
      $qtdeLinhasArquivos += filesize($sNomeArquivo);
    }

    $barraProgressoArquivo->updateMaxProgress(count($aNomesArquivos));

    foreach ($aNomesArquivos as $sNomeArquivo) {

      $sequencialArquivo  = null;
      $serialRelogioPonto = null;
      $oLayoutArquivo     = new \DBLayoutReader(Importacao::CODIGO_LAYOUT_ARQUIVO, $sNomeArquivo, true, false);
      $oLayoutArquivo->processarArquivo(0, true, true);

      $iLinha   = 0;
      $barraProgressoLinha->updateMaxProgress(count($oLayoutArquivo->getLines()));
      $barraProgressoLinha->updatePercentual($iLinha);

      $debug = '-----------------------------------------------------------------------------------------------------------------';
      $logger->info($debug);

      $debug = '-- Arquivo: '. $sNomeArquivo .' '. str_pad('', (strlen($sNomeArquivo)-1), '-');
      $logger->info($debug);

      $debug = '-----------------------------------------------------------------------------------------------------------------';
      $logger->info($debug);


      foreach($oLayoutArquivo->getLines() as $oLinha) {

        if(!empty($oLinha->TIPO_REGISTRO)) {

          switch($oLinha->TIPO_REGISTRO) {
  
            case Importacao::REGISTRO_CABECALHO:
  
              $iOid           = \DBLargeObject::criaOID(true);
              $lSalvaArquivo  = \DBLargeObject::escrita($sNomeArquivo, $iOid);
  
              if(!$lSalvaArquivo) {
                throw new \DBException("Erro ao salvar o arquivo.");
              }
  
              $oDataInicial = new \DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_INICIAL));
              $oDataFinal   = new \DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_FINAL));
              
              $oDaoPontoEletronicoArquivoImportacao = new cl_pontoeletronicoarquivoimportacao;
  
              $oDaoPontoEletronicoArquivoImportacao->rh228_instituicao = $iCodigoInstituicao;
              $oDaoPontoEletronicoArquivoImportacao->rh228_arquivo     = $iOid;
              $oDaoPontoEletronicoArquivoImportacao->rh228_serial      = $oLinha->NUMERO_FABRICACAO_REP;
              $oDaoPontoEletronicoArquivoImportacao->rh228_data_inicio = $oDataInicial;
              $oDaoPontoEletronicoArquivoImportacao->rh228_data_fim    = $oDataFinal;
              
              $oDaoPontoEletronicoArquivoImportacao->rh228_sequencial = 0;
              if(!$oDaoPontoEletronicoArquivoImportacao->incluir(null)) {
                throw new \DBException($oDaoPontoEletronicoArquivoImportacao->erro_msg);
              }
  
              $sequencialArquivo  = $oDaoPontoEletronicoArquivoImportacao->rh228_sequencial;
              $serialRelogioPonto = $oDaoPontoEletronicoArquivoImportacao->rh228_serial;
              break;
  
            case Importacao::REGISTRO_MARCACAO_PONTO:           
              
  
              $oServidor = \ServidorRepository::getServidorByPIS((float)$oLinha->PIS_EMPREGADO);
              if(empty($oServidor) || (!empty($oServidor) && $oServidor->hasServidorVinculado())) {
              
                $iLinha++;
                $barraProgressoLinha->updatePercentual($iLinha);            
                $pisIgnorados[$oLinha->PIS_EMPREGADO] = $oLinha->PIS_EMPREGADO;
  
                $debug  = 'Linha: '. ($iLinha + 1);
                
                if(empty($oServidor)) {
                  $debug .= ' - IGNORADA por nao ser possivel encontrar o PIS: '. $oLinha->PIS_EMPREGADO;
                } else {
                  $debug .= ' - IGNORADA por servidor ser duplo vinculo: '. $oServidor->getMatricula() .' - '. $oServidor->getServidorVinculado()->getMatricula();
                }
                
                $debug .= ' - Data: '. $oLinha->DATA_MARCACAO;
                $debug .= ' | Hora: '. $oLinha->HORARIO_MARCACAO;
                $logger->info($debug);
                continue 2;
              }
  
              $iMatricula    = $oServidor->getMatricula();
              $oDataMarcacao = new \DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_MARCACAO));
  
              $oDaoPontoEletronicoArquivoImportacaoRegistro = new \cl_pontoeletronicoarquivoimportacaoregistro;
  
              $oDaoPontoEletronicoArquivoImportacaoRegistro->rh229_pontoeletronicoarquivoimportacao  = $sequencialArquivo;
              $oDaoPontoEletronicoArquivoImportacaoRegistro->rh229_pis                               = $oLinha->PIS_EMPREGADO;
              $oDaoPontoEletronicoArquivoImportacaoRegistro->rh229_matricula                         = $iMatricula;
              $oDaoPontoEletronicoArquivoImportacaoRegistro->rh229_data                              = $oDataMarcacao;
              $oDaoPontoEletronicoArquivoImportacaoRegistro->rh229_hora                              = $oLinha->HORARIO_MARCACAO;
              $oDaoPontoEletronicoArquivoImportacaoRegistro->rh229_serial                            = $serialRelogioPonto;
  
             
              if(!$oDaoPontoEletronicoArquivoImportacaoRegistro->incluir(null)) {
                throw new \DBException($oDaoPontoEletronicoArquivoImportacaoRegistro->erro_msg);
              }
              
              break;
  
            default:
  
              $debug  = 'NAO encontrado layout para linha: '. ($iLinha + 1);
              $logger->info($debug);
              break;
            
          }
        }
        
        $iLinha++;
        $barraProgressoLinha->updatePercentual($iLinha);
      }

      $debug  = '-----------------------------------------------------------------------------------------------------------------';
      $logger->info($debug);

      $iArquivo++;
      $barraProgressoArquivo->updatePercentual($iArquivo);
    }

    db_fim_transacao(false);
    db_msgbox('Arquivos Importados com Sucesso.');

  } catch (\Exception $exception) {
    
    db_fim_transacao(true);
    db_msgbox($exception->getMessage());
    echo "<script type=\"text/javascript\">$('voltar').style.display='block'</script>";
  }
?>
<?php if(file_exists($path)): ?>
<script type="text/javascript">
(function () {

    var download = new DBDownload();
        download.addFile('tmp/.log/<?=$sNomeArquivoLog;?>', 'Log de Importação');
        download.show();
})();
</script>
<?php endif;?>
</body>
</html>
