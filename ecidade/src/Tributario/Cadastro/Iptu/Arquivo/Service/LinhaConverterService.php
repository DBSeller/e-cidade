<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use ECidade\Tributario\Library\Service;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\BancoConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ContribuinteConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ExercicioConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\FaceConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ImovelAnteriorConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ImovelConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\LocalizacaoConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaInicioConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaPagaConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaReciboConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\TaxaConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\UnicaConverter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Banco;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Contribuinte;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Exercicio;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Face;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Imovel;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ImovelAnterior;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Localizacao;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaInicio;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaPaga;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaRecibo;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Taxa;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Unica;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Loteamento;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Block\LoteamentoBlock;

use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;

final class LinhaConverterService extends Service
{
    private $imovelConverter;
    
    private $contribuinteConverter;

    private $parcelaConverter;

    private $exercicioConverter;

    private $parcelaPagaConverter;

    private $parcelaReciboConverter;

    private $taxaConverter;

    private $unicaConverter;

    private $loteamentoBlock;

    public function __construct(
        ImovelConverter $imovelConverter,
        ContribuinteConverter $contribuinteConverter,
        ExercicioConverter $exercicioConverter,
        UnicaConverter $unicaConverter,
        ParcelaInicioConverter $parcelaInicioConverter,
        ParcelaReciboConverter $parcelaReciboConverter,
        // ParcelaPagaConverter $parcelaPagaConverter,
        ImovelAnteriorConverter $imovelAnteriorConverter,
        TaxaConverter $taxaConverter,
        // ParcelaConverter $parcelaConverter <--------------------
        // FaceConverter $faceConverter,
        // BancoConverter $bancoConverter,
        // LocalizacaoConverter $localizacaoConverter
        LoteamentoBlock $loteamentoBlock
    ) {
        $this->imovelConverter         = $imovelConverter;
        $this->contribuinteConverter   = $contribuinteConverter;
        $this->exercicioConverter      = $exercicioConverter;
        $this->unicaConverter          = $unicaConverter;
        $this->parcelaInicioConverter  = $parcelaInicioConverter;
        $this->parcelaReciboConverter  = $parcelaReciboConverter;
        // $this->parcelaPagaConverter    = $parcelaPagaConverter;
        $this->imovelAnteriorConverter = $imovelAnteriorConverter;
        $this->taxaConverter = $taxaConverter;
        // $this->parcelaConverter        = $parcelaConverter;
        // $this->faceConverter           = $faceConverter;
        // $this->bancoConverter          = $bancoConverter;
        // $this->localizacaoConverter    = $localizacaoConverter;
        $this->loteamentoBlock = $loteamentoBlock;
    }

    public function build(
        Imovel $imovel,
        Contribuinte $contribuinte,
        Exercicio $exercicio,
        DebitoCollection $debitos,
        $unicas,
        ImovelAnterior $imovelAnterior,
        $parcelaRecibos,
        ParcelaInicio $parcelaInicio,
        $taxas,
        $taxaDescricao,
        Loteamento $loteamento
        // Taxa $taxa
        // Unica $unica
    ) {

        $s  = '';

        // Imovel
        $s .= $this->imovelConverter->get($imovel);

        // Contribuinte
        $s .= $this->contribuinteConverter->get($contribuinte);

        // Exercicio
        $s .= $this->exercicioConverter->getLegacy($exercicio);

        // TotalUnica
        $s .= str_pad(count($unicas), 3, '0', STR_PAD_LEFT);

        // Unica
        $s .= $this->unicaConverter->getArray($unicas);

        // ParcelaInicio
        $s .= $this->parcelaInicioConverter->get($parcelaInicio);

        // ParcelaRecibo
        $s .= $this->parcelaReciboConverter->getArray($parcelaRecibos);

        // ParcelaPaga
        // $s .= $this->parcelaPagaConverter->get(new ParcelaPaga());

        // $s .= str_pad('', (3809 - 3492), ' ', STR_PAD_LEFT);

        // Taxa
        $s .= $this->taxaConverter->getArray($taxas);
        
        // ImovelAnterior
        $s .= $this->imovelAnteriorConverter->get($imovelAnterior);
        $s .= $this->taxaConverter->getArray2($taxaDescricao);

        // Parcela
        // $s .= $this->parcelaConverter->get($debitos);
        
        // $s .= str_pad('', (5289 - 4786), ' ', STR_PAD_LEFT);

        // Face
        // $s .= $this->faceConverter->get(new Face());
        // TaxaValor
        // NossoNumeroUnica
        // NossoNumeroParcela
        // Banco
        // $s .= $this->bancoConverter->get(new Banco());
        // NossoNumeroUnicaNovo
        // NossoNumeroParcelaNovo
        // Localizacao
        // $s .= $this->localizacaoConverter->get(new Localizacao());
        // $s .= str_pad('', (7004 - 5290), ' ', STR_PAD_LEFT);

        $s .= $this->loteamentoBlock->getConverter()->get($loteamento);

        return $s;
    }
}
