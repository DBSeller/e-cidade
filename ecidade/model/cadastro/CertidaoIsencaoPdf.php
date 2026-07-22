<?php
use Fpdf\Fpdf; 
use InstituicaoRepository;

class CertidaoInsencaoPdf extends  Fpdf {  
  public function __construct($orientation, $unit, $size)
  {
    if (!defined('FPDF_FONTPATH')) {
      define('FPDF_FONTPATH', 'fpdf/font/');
    }

    parent::__construct($orientation, $unit, $size);
    $this->AliasNbPages();
    $this->SetAutoPageBreak(false, 20);
  }

  function Header()
  { 
    $instituicao = $this->buscaDadosInstituicao()->getDadosPrefeitura();
    $nome        = $instituicao->getDescricao();
    $ufExtenso   = $instituicao->getUfExtenso();
    $fonteNomeInstituicao = 14;
    if (strlen($nome) > 42) {
      $fonteNomeInstituicao = 8;
    }

    $Letra = 'Times';
    
    $this->SetFont($Letra,'',12);
    $this->MultiCell(0,20, "",0,"C",0);
    $this->MultiCell(0,4, $ufExtenso,0,"C",0);
    $this->SetFont($Letra,'B',13);
    $this->MultiCell(0,6,$nome,0,"C",0);
    $this->SetFont($Letra,'B',12);
    $this->MultiCell(0,4,$this->titulos['head1'],0,"C",0);
    $this->SetLeftMargin(20);
        
    $this->SetFont($Letra, 'BI', $fonteNomeInstituicao);
    $this->SetY(35);       
  }
  
  private function buscaDadosInstituicao()
  {
      return InstituicaoRepository::getInstituicaoPrefeitura();
  }

  public function getH() {
    return $this->h;
  }
  
  function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}