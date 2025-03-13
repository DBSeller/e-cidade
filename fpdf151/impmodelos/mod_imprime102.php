<?php
$borda = 0;
$altura = 3.5;
$ylin = 21;
$xcol = 4;
$this->objpdf->AddPage();

$iPreencheFundo = 1;

$pos = $this->objpdf->gety();

if (!$this->lLiberado) {

  $this->objpdf->SetFont('Times','B',78);
  $this->objpdf->SetFillColor(178);
  $this->objpdf->TextWithRotation(12,$pos+115,"NÃO LIBERADA",20,0);
  $this->objpdf->SetFillColor(235);
  $iPreencheFundo = 0;
}

$this->objpdf->RoundedRect($xcol, $ylin - 15, 202, 16, 0, 'D', '1234');

$this->objpdf->SetFillColor(235);
$y = $this->objpdf->gety() - 2;
$this->objpdf->Image('imagens/files/'.$this->logoitbi,10,$y,14);
$this->objpdf->SetFont('Times','B',15);
$this->objpdf->setx(30);
$this->objpdf->Cell(100,3,$this->nomeinst,$borda,1,"L",0);
$this->objpdf->SetFont('Times','',10);
$this->objpdf->setx(30);

$this->objpdf->setx(30);
$this->objpdf->Cell(100,10,"SECRETARIA MUNICIPAL DE FAZENDA - SEMFAZ",$borda,1,"L",0);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->setx(30);

$this->objpdf->RoundedRect($xcol, $ylin + 1, 202, 5, 0, 'DF', '1234');

$this->objpdf->setx(30);
$this->objpdf->Cell(150,3.5,"DAM - DOCUMENTO DE ARRECADAÇÃO MUNICIPAL",$borda,1,"C",0);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->setx(30);

$this->objpdf->RoundedRect($xcol, $ylin + 6, 91, 8, 0, 'D', '1234');

$this->objpdf->Image($this->imagemlogo,4,27,91,8);

$this->objpdf->RoundedRect($xcol + 91, $ylin + 6, 20, 8, 0, 'D', '1234');

$this->objpdf->sety(27.5);
$this->objpdf->setx(95);
$this->objpdf->Cell(10,3.5,"Banco",$borda,1,"C",0);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->setx(30);

$this->objpdf->sety(31.5);
$this->objpdf->setx(105);
$this->objpdf->Cell(10,3.5,$this->numbanco,$borda,1,"C",0);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->setx(30);

$this->objpdf->RoundedRect($xcol + 111, $ylin + 6, 91, 8, 0, 'D', '1234');

$this->objpdf->sety(27.5);
$this->objpdf->setx(128);
$this->objpdf->Cell(10,3.5,"Agência / Cód. do Cedente",$borda,1,"C",0);
$this->objpdf->SetFont('Times','B',8);
$this->objpdf->setx(30);

$this->objpdf->sety(31,531.5);
$this->objpdf->setx(115);
$this->objpdf->Cell(91,3.5,$this->agencia_cedente,$borda,1,"C",0);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->setx(30);

// Número da guia
$this->objpdf->RoundedRect($xcol + 0, $ylin + 14, 31, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(4);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(31,3.5,"Número da Guia",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(4);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(31,3.5,$this->numpreitbi,$borda,1,"R",0);

// Parcela
$this->objpdf->RoundedRect($xcol + 31, $ylin + 14, 16, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(35);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(16,3.5,"Parcela",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(35);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(16,3.5,$this->descr10,$borda,1,"R",0);

// Ano
$this->objpdf->RoundedRect($xcol + 47, $ylin + 14, 15, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(51);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(15,3.5,"Ano",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(51);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(15,3.5,$this->ano,$borda,1,"R",0);

// MÊS
$this->objpdf->RoundedRect($xcol + 62, $ylin + 14, 12, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(66);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(12,3.5,"Mês",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(66);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(12,3.5,date('m', strtotime($this->it01_data)),$borda,1,"R",0);


// Vencimento
$this->objpdf->RoundedRect($xcol + 74, $ylin + 14, 21, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(78);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(21,3.5,"Vencimento",$borda,1,"L",0);


$this->objpdf->sety(40);
$this->objpdf->setx(78);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(21,3.5,date('d/m/Y', strtotime($this->datavencimento)),$borda,1,"R",0);

// Validade
$this->objpdf->RoundedRect($xcol + 95, $ylin + 14, 19, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(99);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(19,3.5,"Validade",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(99);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(19,3.5,date('d/m/Y', strtotime($this->datavencimento)),$borda,1,"R",0);

// Emissão
$this->objpdf->RoundedRect($xcol + 114, $ylin + 14, 19, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(118);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(19,3.5,"Emissão",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(118);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(19,3.5,date('d/m/Y', strtotime($this->dataemissao)),$borda,1,"R",0);

// Hora
$this->objpdf->RoundedRect($xcol + 133, $ylin + 14, 12, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(137);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(12,3.5,"Hora",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(137);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(12,3.5,$this->Rhora,$borda,1,"R",0);

// Nosso Número
$this->objpdf->RoundedRect($xcol + 145, $ylin + 14, 34, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(149);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(35,3.5,"Nosso Número",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(150);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(33,3.5,$this->nosso_numero,$borda,1,"R",0);

// Número Emissão
$this->objpdf->RoundedRect($xcol + 179, $ylin + 14, 23, 9, 0, 'D', '1234');
$this->objpdf->sety(36);
$this->objpdf->setx(183);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(23,3.5,"Nº Emissões",$borda,1,"L",0);

$this->objpdf->sety(40);
$this->objpdf->setx(183);
$this->objpdf->SetFont('Times','',8);
$this->objpdf->Cell(23,3.5,$this->numero_emissoes,$borda,1,"R",0);

//ADQUIRENTE
$this->objpdf->RoundedRect($xcol, $ylin + 23, 202, 17, 0, 'D', '1234');
$this->objpdf->sety(45);
$this->objpdf->setx(4);
$this->objpdf->SetFont('Times','',9);
$this->objpdf->Cell(31,3.5,"Adquirente",$borda,1,"L",0);

$this->objpdf->sety(49);
$this->objpdf->setx(9);
$this->objpdf->SetFont('Times','',9);
$this->objpdf->Cell(65,3.5,$this->descr11_1,$borda,1,"L",0);

$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(53);
$this->objpdf->setx(9);
$this->objpdf->Cell(65,3.5,"Inscrição: {$this->pretitulo8}",$borda,1,"L",0);

$this->objpdf->sety(53);
$this->objpdf->setx(74);
$this->objpdf->Cell(65,3.5," COD. CONTRIBUINTE: {$this->numcgm}",$borda,1,"L",0);

$this->objpdf->sety(57);
$this->objpdf->setx(9);
$this->objpdf->Cell(60,3.5,$this->descr11_2,$borda,1,"L",0);

//INFORMAÇÕES
$this->objpdf->RoundedRect($xcol, $ylin + 40, 202, 22, 0, 'D', '1234');

$this->objpdf->sety(62);
$this->objpdf->setx(4);
$this->objpdf->SetFont('Times','',9);
$this->objpdf->Cell(31,3.5,"Informações",$borda,1,"L",0);


$this->objpdf->SetFont('Times','',7.5);
$this->objpdf->sety(66.5);
$this->objpdf->setx(9);
$this->objpdf->Cell(85,3.5,"Transmitente: ".$this->transmitente ,$borda,1,"L",0);

//COLUNA 1
$this->objpdf->sety(66.5);
$this->objpdf->setx(94);
$this->objpdf->Cell(35,3.5,"Área Lote: ".$this->areaterreno,$borda,1,"L",0);

$this->objpdf->sety(66.5);
$this->objpdf->setx(129);
$this->objpdf->Cell(35,3.5,"Área Unidade: {$this->areatrans}",$borda,1,"L",0);

$this->objpdf->sety(66.5);
$this->objpdf->setx(164);
$this->objpdf->Cell(35,3.5,"Fração Ideal: {$this->fracaoIdeal}",$borda,1,"L",0);

$this->objpdf->sety(70.5);
$this->objpdf->setx(164);
$this->objpdf->Cell(35,3.5,"Vlr.Venal: ".trim(db_formatar($this->it14_valoraval,"f")),$borda,1,"L",0);

$this->objpdf->sety(74.5);
$this->objpdf->setx(164);
$this->objpdf->Cell(35,3.5,"Vlr. Venda: ".trim(db_formatar($this->it01_valortransacao,"f")),$borda,1,"L",0);

$linhaForma = 70.5;

foreach ($this->aDadosFormasPgto as $aForma) {
    if (empty($aForma["Descricao"])) {
        continue;
    }

    $this->objpdf->sety($linhaForma);
    $this->objpdf->setx(9);
    $this->objpdf->Cell(35,3.5,$aForma["Descricao"].": ".trim(db_formatar($aForma["Valor"],"f")),$borda,1,"L",0);

    $this->objpdf->sety($linhaForma);
    $this->objpdf->setx(94);
    $this->objpdf->Cell(35,3.5,"Aliquota: ".$aForma["Aliquota"]."%",$borda,1,"L",0);

    $linhaForma = $linhaForma + 4;
}

// IMPOSTO/TAXAS

$this->objpdf->SetFont('Times','',8);

$this->objpdf->sety(83);
$this->objpdf->setx(4);
$this->objpdf->Cell(25,5,"Código",1,1,"C",0);

$this->objpdf->sety(83);
$this->objpdf->setx(29);
$this->objpdf->Cell(152,5,"Tributo",1,1,"C",0);

$this->objpdf->sety(83);
$this->objpdf->setx(181);
$this->objpdf->Cell(25,5,"Valor",1,1,"C",0);

$this->objpdf->RoundedRect($xcol, $ylin + 67, 25, 30, 0, 'D', '1234');
$this->objpdf->RoundedRect($xcol + 25, $ylin + 67, 152, 30, 0, 'D', '1234');
$this->objpdf->RoundedRect($xcol + 177, $ylin + 67, 25, 30, 0, 'D', '1234');

$linha = 88;

foreach ($this->aTaxas2 as $oTaxas) {
    $this->objpdf->sety($linha);
    $this->objpdf->setx(4);
    $this->objpdf->Cell(25,5,$oTaxas->receita,$borda,1,"R",0);

    $this->objpdf->sety($linha);
    $this->objpdf->setx(29);
    $this->objpdf->Cell(152,5,$oTaxas->descricao,$borda,1,"L",0);

    $this->objpdf->sety($linha);
    $this->objpdf->setx(181);
    $this->objpdf->Cell(25,5,db_formatar($oTaxas->valor,"f"),$borda,1,"R",0);

    $linha = $linha + 3;
}

// OBSERVAÇÕES
$this->objpdf->RoundedRect($xcol, $ylin + 97, 202, 25, 0, 'D', '1234');
$this->objpdf->RoundedRect($xcol + 127, $ylin + 97, 50, 25, 0, 'D', '1234');

//OBSERVAÇÃO
$this->objpdf->SetFont('Times','B',8);
$this->objpdf->sety(118);
$this->objpdf->setx(4);
$this->objpdf->Cell(60,5,"Observações: NÃO RECEBER EM CHEQUE.",$borda,1,"L",0);

$this->objpdf->SetFont('Times','',6);

$matricIptuant = !empty($this->matricIptuant) ? '  Matrícula do Imóvel: '.$this->matricIptuant : '';

// FRASE ABAIXO DA OBSERVACAO
$this->objpdf->sety(121.5);
$this->objpdf->setx(4);
$this->objpdf->MultiCell(133,2,"\n{$matricIptuant}{$this->descr12_1}", $borda);

// SERVIDOR
if (!empty($this->usuarioNomeIncluido)) {
    $this->objpdf->sety(136.5);
    $this->objpdf->setx(4);
    $this->objpdf->Cell(90,5,"  SERVIDOR(A): ".$this->usuarioNomeIncluido,$borda,1,"L",0);
}

// NUMERAÇÃO
$this->objpdf->SetFont('Times','B',8);
$this->objpdf->sety(139);
$this->objpdf->setx(23);
$this->objpdf->Cell(90,5,$this->linha_digitavel,$borda,1,"L",0);

// SUB TOTAL
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(118);
$this->objpdf->setx(131);
$this->objpdf->Cell(50,5,"Sub Total................:",$borda,1,"L",0);

// VALOR DO SUB TOTAL
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(118);
$this->objpdf->setx(181);
$this->objpdf->Cell(25,5,db_formatar((str_replace(",", ".", str_replace(".", "", $this->valor_cobrado)) + (str_replace(",", ".", str_replace(".", "", $this->desconto_abatimento)))), "f"),$borda,1,"R",0);

// MULTA
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(123);
$this->objpdf->setx(131);
$this->objpdf->Cell(50,5,"Multa.......................:",$borda,1,"L",0);

// VALOR MULTA
$this->objpdf->sety(123);
$this->objpdf->setx(181);
$this->objpdf->Cell(25,5,"",$borda,1,"R",0);

// CORREÇÃO
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(128);
$this->objpdf->setx(131);
$this->objpdf->Cell(50,5,"Correção.................:",$borda,1,"L",0);

// VALOR CORREÇÃO
$this->objpdf->sety(128);
$this->objpdf->setx(181);
$this->objpdf->Cell(25,5,"",$borda,1,"R",0);

// DESCONTOS
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(133);
$this->objpdf->setx(131);
$this->objpdf->Cell(50,5,"Descontos...............:",$borda,1,"L",0);

// VALOR DESCONTOS
$this->objpdf->sety(133);
$this->objpdf->setx(181);
$this->objpdf->Cell(25,5,$this->desconto_abatimento,$borda,1,"R",0);

// TOTAL A RECOLHER
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(138);
$this->objpdf->setx(131);
$this->objpdf->Cell(50,5,"Total a Recolher......:",$borda,1,"L",0);

// VALOR TOTAL A RECOLHER
$this->objpdf->SetFont('Times','',8);
$this->objpdf->sety(138);
$this->objpdf->setx(181);
$this->objpdf->Cell(25,5,$this->valor_cobrado,$borda,1,"R",0);

$this->objpdf->SetFont('Times','',7.5);
$this->objpdf->sety(143);
$this->objpdf->setx(10);
$this->objpdf->Cell(40,5,"AUTENTICAÇÃO MECÂNICA",$borda,1,"C",0);

$this->objpdf->SetFont('Times','',7.5);
$this->objpdf->sety(143);
$this->objpdf->setx(155);
$this->objpdf->Cell(40,5,"VIA DO CONTRIBUINTE",$borda,1,"C",0);

// $this->objpdf->SetFont('Times','',70);
$this->objpdf->SetTextColor(128,128,128);
$this->objpdf->SetFont('Times','B',70);
$this->objpdf->sety(148);
$this->objpdf->setx(4);
$tipoitbi = strtoupper($this->tipoitbi);
$this->objpdf->Cell(202,25,"ITBI " . $tipoitbi,$borda,1,"C",0);

$this->objpdf->SetTextColor(50,50,50);

// *********************** BOLETO *********************** //

if ($this->lLiberado) {
    // incluir a ficha de compensação
    include(modification("fpdf151/impmodelos/mod_imprime48.php"));
}


