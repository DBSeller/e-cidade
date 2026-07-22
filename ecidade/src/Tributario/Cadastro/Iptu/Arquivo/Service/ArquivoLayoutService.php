<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use ECidade\Tributario\Library\Service;

final class ArquivoLayoutService extends ArquivoTxtService
{
    private $arquivo;
    private $layoutBanco;
    private $layoutContribuinte;
    private $layoutExercicio;
    private $layoutFace;
    private $layoutImovelAnterior;
    private $layoutImovel;
    private $layoutLocalizacao;
    private $layoutNossoNumero;
    private $layoutsNossoNumeroUnica;
    private $layoutNossoNumeroVersao2;
    private $layoutNossoNumeroUnicaVersao2;
    private $layoutParcelaInicio;
    private $layoutParcelaPaga;
    private $layoutParcela;
    private $layoutParcelaRecibo;
    private $layoutsUnica;
    private $layoutsTaxa;
    private $layoutBranco86;
    private $layoutContador;
    private $layoutFimUnicas;
    private $layoutTotalPago;
    private $layoutsTaxaSegundoBloco;

    public function __construct(
        $arquivo,
        $layoutBanco,
        $layoutContribuinte,
        $layoutExercicio,
        $layoutFace,
        $layoutImovelAnterior,
        $layoutImovel,
        $layoutLocalizacao,
        $layoutNossoNumero,
        $layoutsNossoNumeroUnica,
        $layoutNossoNumeroVersao2,
        $layoutNossoNumeroUnicaVersao2,
        $layoutParcelaInicio,
        $layoutParcelaPaga,
        $layoutParcela,
        $layoutParcelaRecibo,
        $layoutTotalUnica,
        $layoutsUnica,
        $layoutsTaxa,
        $layoutBranco86,
        $layoutContador,
        $layoutFimUnicas,
        $layoutTotalPago,
        $layoutsTaxaSegundoBloco
    ) {
        $this->arquivo                       = $arquivo;
        $this->layoutBanco                   = $layoutBanco;
        $this->layoutContribuinte            = $layoutContribuinte;
        $this->layoutExercicio               = $layoutExercicio;
        $this->layoutFace                    = $layoutFace;
        $this->layoutImovelAnterior          = $layoutImovelAnterior;
        $this->layoutImovel                  = $layoutImovel;
        $this->layoutLocalizacao             = $layoutLocalizacao;
        $this->layoutNossoNumero             = $layoutNossoNumero;
        $this->layoutsNossoNumeroUnica       = $layoutsNossoNumeroUnica;
        $this->layoutNossoNumeroVersao2      = $layoutNossoNumeroVersao2;
        $this->layoutNossoNumeroUnicaVersao2 = $layoutNossoNumeroUnicaVersao2;
        $this->layoutParcelaInicio           = $layoutParcelaInicio;
        $this->layoutParcelaPaga             = $layoutParcelaPaga;
        $this->layoutParcela                 = $layoutParcela;
        $this->layoutParcelaRecibo           = $layoutParcelaRecibo;
        $this->layoutTotalUnica              = $layoutTotalUnica;
        $this->layoutsUnica                  = $layoutsUnica;
        $this->layoutsTaxa                   = $layoutsTaxa;
        $this->layoutBranco86                = $layoutBranco86;
        $this->layoutContador                = $layoutContador;
        $this->layoutFimUnicas               = $layoutFimUnicas;
        $this->layoutTotalPago               = $layoutTotalPago;
        $this->layoutsTaxaSegundoBloco       = $layoutsTaxaSegundoBloco;
    }

    public function getArquivo()
    {
        return $this->arquivo;
    }

    public function execute()
    {
        $l = '';

        $l .= $this->layoutContador->setStart(1)->get(1);
        $start = $this->layoutContador->getEnd();
        $start++;
        $counter = $this->layoutContador->getLast();

        $l .= $this->layoutImovel->setStart($start)->get($counter);
        $start = $this->layoutImovel->getEnd();
        $start++;
        $counter = $this->layoutImovel->getLast();
        
        $l .= $this->layoutContribuinte->setStart($start)->get($counter);
        $start = $this->layoutContribuinte->getEnd();
        $start++;
        $counter = $this->layoutContribuinte->getLast();
        
        $l .= $this->layoutExercicio->setStart($start)->get($counter);
        $start = $this->layoutExercicio->getEnd();
        $start++;
        $counter = $this->layoutExercicio->getLast();

        $l .= $this->layoutTotalUnica->setStart($start)->get($counter);
        $start = $this->layoutTotalUnica->getEnd();
        $start++;
        $counter = $this->layoutTotalUnica->getLast();

        foreach($this->layoutsUnica as $layoutUnica) {

            $l .= $layoutUnica->setStart($start)->get($counter);
            $start = $layoutUnica->getEnd();
            $start++;
            $counter = $layoutUnica->getLast();
        }

        $counter++;
     // $l .= "177   | VLRDESCUNICATAXA1              | VALOR DE DESCONTO NA UNICA DE TAXA1                                              | 0011 | 3555 | 3565\n";
        $l .= "72    | FIMUNICAS                      | EXPRESSAO # FIM DAS UNICAS                                                       | 0016 | ".$start." | ".($start + 15)."\n";
        
        $start += 16;
        
        // $l .= $this->layoutFimUnicas->setStart($start)->get($counter);
        // $start = $this->layoutFimUnicas->getEnd();
        // $start++;
        // $counter = $this->layoutFimUnicas->getLast();

        $l .= $this->layoutParcelaInicio->setStart($start)->get($counter);
        $start = $this->layoutParcelaInicio->getEnd();
        $start++;
        $counter = $this->layoutParcelaInicio->getLast();

        $l .= $this->layoutParcelaRecibo->setStart($start)->get($counter);
        $start = $this->layoutParcelaRecibo->getEnd();
        $start++;
        $counter = $this->layoutParcelaRecibo->getLast();
        

        // $l .= $this->layoutParcelaPaga->setStart($start)->get($counter);
        // $start = $this->layoutParcelaPaga->getEnd();
        // $start++;
        // $counter = $this->layoutParcelaPaga->getLast();

        // $l .= $this->layoutTotalPago->setStart($start)->get($counter);
        // $start = $this->layoutTotalPago->getEnd();
        // $start++;
        // $counter = $this->layoutTotalPago->getLast();

        foreach($this->layoutsTaxa as $layoutTaxa) {

            $l .= $layoutTaxa->setStart($start)->get($counter);
            $start = $layoutTaxa->getEnd();
            $start++;
            $counter = $layoutTaxa->getLast();
        }

        // $l .= $this->blocoBrancos(6, $start, $counter);

        $l .= $this->layoutImovelAnterior->setStart($start)->get($counter);
        $start = $this->layoutImovelAnterior->getEnd();
        $start++;
        $counter = $this->layoutImovelAnterior->getLast();

        // $l .= $this->layoutParcela->setStart($start)->get($counter);
        // $start = $this->layoutParcela->getEnd();
        // $start++;
        // $counter = $this->layoutParcela->getLast();
        
        // $l .= $this->layoutFace->setStart($start)->get($counter);
        // $start = $this->layoutFace->getEnd();
        // $start++;
        // $counter = $this->layoutFace->getLast();
        
        // foreach($this->layoutsTaxaSegundoBloco as $layoutTaxaSegundoBloco) {

        //     $l .= $layoutTaxaSegundoBloco->setStart($start)->get($counter);
        //     $start = $layoutTaxaSegundoBloco->getEnd();
        //     $start++;
        //     $counter = $layoutTaxaSegundoBloco->getLast();
        // }
        // $l .= $this->blocoBrancos(4, $start, $counter);
        
        // foreach($this->layoutsNossoNumeroUnica as $layoutsNossoNumeroUnica) {

        //     $l .= $layoutsNossoNumeroUnica->setStart($start)->get($counter);
        //     $start = $layoutsNossoNumeroUnica->getEnd();
        //     $start++;
        //     $counter = $layoutsNossoNumeroUnica->getLast();
        // }

        // $l .= $this->layoutNossoNumero->setStart($start)->get($counter);
        // $start = $this->layoutNossoNumero->getEnd();
        // $start++;
        // $counter = $this->layoutNossoNumero->getLast();
        
        // $l .= $this->layoutBanco->setStart($start)->get($counter);
        // $start = $this->layoutBanco->getEnd();
        // $start++;
        // $counter = $this->layoutBanco->getLast();

        // $l .= $this->layoutNossoNumeroUnicaVersao2->setStart($start)->get($counter);
        // $start = $this->layoutNossoNumeroUnicaVersao2->getEnd();
        // $start++;
        // $counter = $this->layoutNossoNumeroUnicaVersao2->getLast();

        // $l .= $this->layoutNossoNumeroVersao2->setStart($start)->get($counter);
        // $start = $this->layoutNossoNumeroVersao2->getEnd();
        // $start++;
        // $counter = $this->layoutNossoNumeroVersao2->getLast();
        
        // $l .= $this->layoutLocalizacao->setStart($start)->get($counter);

     // $l .= "159   | VALORM2CALCULO                 | VALOR DO METRO QUADRADO DO TERRENO DO CALCULO                                    | 0018 | 3174 | 3191";

     // $l .= "160   | CODARREIPTU                    | CODIGO DE ARRECADACAO DO DEBITO DE IPTU                                          | 0011 | 3192 | 3202\n";
     // $l .= "161   | VLRDESCUNICAIPTU               | VALOR DE DESCONTO NA UNICA DE IPTU                                               | 0011 | 3203 | 3213\n";
     // $l .= "162   | CODARRETAXA1                   | CODIGO DE ARRECADACAO DO DEBITO DE TAXA1                                         | 0011 | 3214 | 3224\n";
     // $l .= "163   | VLRDESCUNICATAXA1              | VALOR DE DESCONTO NA UNICA DE TAXA1                                              | 0011 | 3225 | 3235\n";

            // 174   | VALORM2CALCULO                 | VALOR DO METRO QUADRADO DO TERRENO DO CALCULO                                    | 0018 | 3520 | 3537

        $l .= "174   | CODARREIPTU                    | CODIGO DE ARRECADACAO DO DEBITO DE IPTU                                          | 0011 | 3538 | 3548\n";
        $l .= "175   | VLRDESCUNICAIPTU               | VALOR DE DESCONTO NA UNICA DE IPTU                                               | 0011 | 3549 | 3559\n";
        $l .= "176   | CODARRETAXA1                   | CODIGO DE ARRECADACAO DO DEBITO DE TAXA1                                         | 0011 | 3560 | 3570\n";
        $l .= "177   | VLRDESCUNICATAXA1              | VALOR DE DESCONTO NA UNICA DE TAXA1                                              | 0011 | 3571 | 3581\n";
        $l .= "178   | LOTEAMENTO                     | DESCRICAO DO LOTEAMENTO                                                          | 0040 | 3582 | 3621\n";

        $this->arquivo->addline($l);

        return $this;
    }

    public function blocoBrancos($qtde, & $start, & $counter)
    {
        $l  = '';

        for($i=1; $i<=$qtde; $i++) {


            $l .= $this->layoutBranco86->setStart($start)->get($counter);
            $start = $this->layoutBranco86->getEnd();
            $start++;
            $counter = $this->layoutBranco86->getLast();
        }

        return $l;
    }
}
