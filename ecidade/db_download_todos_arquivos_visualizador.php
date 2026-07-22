<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));

use \Mpdf\Mpdf;

try
{
    if(empty($_GET['arquivos'])){
        throw new \Exception("Arquivos não encontrados");
    }

    $mpdf = new Mpdf([
        'orientation' => 'P',
        'pagenumPrefix' => 'Pag ',
        'nbpgPrefix' => '/',

    ]);

    $mpdf->setFooter('{PAGENO}{nbpg}');
    foreach($_GET['arquivos'] as $arquivo){
        if(!file_exists($arquivo)){
            continue;
        }
        $mpdf->AddPage();
        $mpdf->Image($arquivo,15,10);
    }
    

    $mpdf->Output('documento.pdf',\Mpdf\Output\Destination::DOWNLOAD);
}catch(\Exception $ex){
  echo "<script>alert('{$ex->getMessage()}'); window.close();</script>";
}
