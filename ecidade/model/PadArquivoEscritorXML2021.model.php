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

require_once(modification('model/PadArquivoEscritor.model.php'));

class PadArquivoEscritorXML2021 extends \PadArquivoEscritor {
  
  /**
   * 
   */
  function __construct() {

  }
  
  /**
   * Transforma os dados passados para XML 
   * @param iPadArquivoBase $oArquivo
   * @return caminho do Arquivo 
   */
  public function criarArquivo(iPadArquivoBase $oArquivo) {
    
    $oXmlWriter = new XMLWriter();
    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0','ISO-8859-1');
    //$oXmlWriter->startDocument('1.0','UTF-8');
    $oXmlWriter->endDtd();
    $oXmlWriter->startElement('Raiz');
    
    $oXmlWriter->startElement('MesAnoMovimento');
      $oXmlWriter->text(utf8_encode($oArquivo->mesAnoMovimento));
    $oXmlWriter->endElement();

    foreach ($oArquivo->getDados() as $oLinha) {

      $oXmlWriter->startElement('PessoalAtivo');//
      foreach ($oArquivo->getNomeElementos() as $sElemento) {

        // se for solteiro remove as tags de conjuge e cpfconjuge
        $isSolteiro = $oLinha->estadocivil != '2' && in_array($sElemento, $oArquivo->aTagsRemovidas[$oLinha->matricula]);
        if($isSolteiro) continue;

        $dataExclusaoNaoExiste = empty($oLinha->dataexclusao) && in_array($sElemento, $oArquivo->aTagsRemovidas[$oLinha->matricula]);
        if($dataExclusaoNaoExiste) continue;

        if($oArquivo->isAgrupamento($sElemento)){

          foreach ($oArquivo->aDadosAgrupados[$oLinha->matricula] as $oLinhaFilhos){
            $oXmlWriter->startElement($sElemento);
              foreach ($oLinhaFilhos as $tag => $valor){
                
                $oXmlWriter->startElement($tag);
                $oXmlWriter->text(utf8_encode($valor));
                $oXmlWriter->endElement();
              }
              $oXmlWriter->endElement();
          }

        } else {
          $oXmlWriter->startElement($sElemento);
          $oXmlWriter->text(utf8_encode($oLinha->$sElemento));
          $oXmlWriter->endElement();
        }
        
      }
      $oXmlWriter->endElement();
    }

    $oXmlWriter->endElement();
    $sNomeArquivo = "tmp/{$oArquivo->getNomeArquivo()}.{$oArquivo->getOutput()}";
    $rsArquivoXML = fopen($sNomeArquivo, "w");
    fputs($rsArquivoXML, $oXmlWriter->outputMemory());
    fclose($rsArquivoXML);
    unset($oXmlWriter);
    return $sNomeArquivo;
  }
  
}

?>