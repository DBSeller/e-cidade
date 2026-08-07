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

include(modification("fpdf151/pdf.php"));



class  Cad4RecadastramentoRelatorio
{

    /**
     * @var PDF
     */
    private $oPdf;


    /**
     * Cad4RecadastramentoRelatorio constructor.
     */
   public  function  __construct()
   {
       $this->oPdf = new  PDF("P", "mm", "A4");
   }


    /**
     * Busca os dados gerados da pesquisas
     * @return array
     */

   private function getData()
   {
       return json_decode(file_get_contents('tmp/cad4recadastramentomatriculas.json'), true);
   }



   /**
    * genarate pdf
   */
   public function  genarate()
   {
       $this->oPdf->Open();
       $this->content();
       $this->oPdf->Output();
   }

   /**
    * setFilters
   */
   public function setFilters($filters)
   {
       global $head1, $head2 , $head3, $head4, $head5, $head6, $head7;

       $schema = explode('_', $filters['sSchema']);
       $dia = substr($schema[1], 0,2)    ;    // retorna "f"
       $mes = substr($schema[1], 2,2);    // retorna "ef"
       $ano = substr($schema[1], 4, 4);
       $date = $dia . '/'. $mes . '/'.$ano;

       $head1 = 'Importação :  Importação-'. $date;
       $head2 = 'Setor : ' . ($filters['iSetor'] ? : '');
       $head3 = 'Quadra : ' . ($filters['sQuadra'] ? : '');
       $head4 = 'Lote : ' . ($filters['sLote'] ? : '');
       $head5 = 'Matrícula do imóvel : ' . ($filters['iMatricula'] ? : '');
       $head6 = 'Situação : ' . ($filters['iSituacao'] ? : '' );
       $head7 = 'Filtro : ' . ($filters['iFiltro'] ? : '' )  ;

   }

    /**
     *  imprimi conteudo
     */
   private function content()
   {

      $data = $this->getData();
      $somaAntes = 0;
      $somaDepois = 0;

      foreach (array_chunk($data,3) as $values) {
          $this->oPdf->addpage();
          $this->oPdf->ln();

          foreach ($values as $value) {
            $somaAntes = $somaAntes + $value['nValorAtual'];
            $somaDepois = $somaAntes + $value['nValorNovo'];

             $this->imprimi($value);
             $this->oPdf->ln();

          }
      }

      $this->oPdf->addpage();
      $this->oPdf->Cell(25, 5,  "IPTU antes total " , 1, 0,  "L" );
      $this->oPdf->Cell(170, 5, db_formatar($somaAntes, 'f'), 1,  1, "L");

      $this->oPdf->Cell(25, 5,  "IPTU depois total " , 1, 0,  "L" );
      $this->oPdf->Cell(170, 5, db_formatar($somaDepois, 'f'), 1,  1, "L");

      $this->oPdf->Cell(25, 5,  "Diferença total " , 1, 0,  "L" );
      $this->oPdf->Cell(170, 5, db_formatar($somaDepois - $somaAntes, 'f'), 1,  1, "L");
   }

    /**
     * imprimi
     *
     * @param $value
     */
   private function imprimi($value)
   {

       $aSistuacao = array(
             0 => "Pendente",      
             1 => "Pendente",  
             2 => "Aprovada",
             3 => "Rejeitada",
             4 => "Processada" 
       );     

       $aLabes = array(
           'iMatricula' =>"Matrícula",
           'iSituacao' =>"Situação",
           'sSetor' =>"Setor",
           'sQuadra' =>"Quadra",
           'sLote' =>"Lote",
           'sQuadraLocalizacao' =>"Quadra localização",
           'sLoteLocalizacao' => "Lote localização",
           'sEndereoCompleto' => "Endereço",
           'sCaracteristicaConstrucao' => "Caract. da construção",
           'sAeu' => "AEU",
           'nValorAtual' => "IPTU antes",
           'nValorNovo' => "IPTU depois",
           'sMotivoRejeicao' => "Motivo",
           'sRazao' => "Proprietário",
       );

       foreach ($value as $label => $value) {

           if (empty($aLabes[$label]) ) {
              continue;
           }

           if ($label == 'nValorAtual' ||  $label == 'nValorNovo') {
               $value = trim(db_formatar($value, 'f'));
           }

           if ($label == 'iSituacao') {
               $value =  $aSistuacao[$value];   
           }

           $this->oPdf->Cell(27 , 5,  $aLabes[$label] , 1, 0,  "L" );
           $this->oPdf->Cell(165, 5, $value, 1,  1, "L");
       }

   }
}

$params  = json_decode(str_replace('\\','',$_GET['params'] ), true);

$obejct = new  Cad4RecadastramentoRelatorio();

$obejct->setFilters($params);
$obejct->genarate();