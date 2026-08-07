<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Converter;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout\Layout;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Entity\Detalhe;

final class DetalheConverter extends Converter
{
    public function __construct(Layout $layout) {
        parent::__construct($layout);
    }

    public function build($linha)
    {
        $colunas = array();
        $layout = $this->layout;

        $colunas[Detalhe::TIPO_REGISTRO_DETALHE]    = substr($linha, $layout->getFieldPosition(Detalhe::TIPO_REGISTRO_DETALHE),    $layout->getSize(Detalhe::TIPO_REGISTRO_DETALHE));
        $colunas[Detalhe::RESPONSAVEL_DEBITO]       = substr($linha, $layout->getFieldPosition(Detalhe::RESPONSAVEL_DEBITO),       $layout->getSize(Detalhe::RESPONSAVEL_DEBITO));
        $colunas[Detalhe::TIPO_ATUALIZACAO]         = substr($linha, $layout->getFieldPosition(Detalhe::TIPO_ATUALIZACAO),         $layout->getSize(Detalhe::TIPO_ATUALIZACAO));
        $colunas[Detalhe::IDENTIFICACAO_DEVEDOR]    = substr($linha, $layout->getFieldPosition(Detalhe::IDENTIFICACAO_DEVEDOR),    $layout->getSize(Detalhe::IDENTIFICACAO_DEVEDOR));
        $colunas[Detalhe::IDENTIFICADOR_DEBITO]     = substr($linha, $layout->getFieldPosition(Detalhe::IDENTIFICADOR_DEBITO),     $layout->getSize(Detalhe::IDENTIFICADOR_DEBITO));
        $colunas[Detalhe::REFERENCIA_DEBITO]        = substr($linha, $layout->getFieldPosition(Detalhe::REFERENCIA_DEBITO),        $layout->getSize(Detalhe::REFERENCIA_DEBITO));
        $colunas[Detalhe::DETALHAMENTO_DEBITO]      = substr($linha, $layout->getFieldPosition(Detalhe::DETALHAMENTO_DEBITO),      $layout->getSize(Detalhe::DETALHAMENTO_DEBITO));
        $colunas[Detalhe::VENCIMENTO_CODIGO_BARRAS] = substr($linha, $layout->getFieldPosition(Detalhe::VENCIMENTO_CODIGO_BARRAS), $layout->getSize(Detalhe::VENCIMENTO_CODIGO_BARRAS));
        $colunas[Detalhe::CODIGO_BARRAS]            = substr($linha, $layout->getFieldPosition(Detalhe::CODIGO_BARRAS),            $layout->getSize(Detalhe::CODIGO_BARRAS));
        $colunas[Detalhe::VALOR_DEBITO]             = substr($linha, $layout->getFieldPosition(Detalhe::VALOR_DEBITO),             $layout->getSize(Detalhe::VALOR_DEBITO));
        $colunas[Detalhe::TIPO_DEBITO]              = substr($linha, $layout->getFieldPosition(Detalhe::TIPO_DEBITO),              $layout->getSize(Detalhe::TIPO_DEBITO));
        $colunas[Detalhe::NUMERO_PARCELA]           = substr($linha, $layout->getFieldPosition(Detalhe::NUMERO_PARCELA),           $layout->getSize(Detalhe::NUMERO_PARCELA));
        $colunas[Detalhe::CHASSI_VEICULO]           = substr($linha, $layout->getFieldPosition(Detalhe::CHASSI_VEICULO),           $layout->getSize(Detalhe::CHASSI_VEICULO));
        $colunas[Detalhe::VALOR_VENAL_IMOVEL]       = substr($linha, $layout->getFieldPosition(Detalhe::VALOR_VENAL_IMOVEL),       $layout->getSize(Detalhe::VALOR_VENAL_IMOVEL));
        $colunas[Detalhe::CODIGO_BARRAS_AGRUPADOR]  = substr($linha, $layout->getFieldPosition(Detalhe::CODIGO_BARRAS_AGRUPADOR),  $layout->getSize(Detalhe::CODIGO_BARRAS_AGRUPADOR));
        $colunas[Detalhe::RESERVADO]                = substr($linha, $layout->getFieldPosition(Detalhe::RESERVADO),                $layout->getSize(Detalhe::RESERVADO));
        $colunas[Detalhe::SEQUENCIAL]               = substr($linha, $layout->getFieldPosition(Detalhe::SEQUENCIAL),               $layout->getSize(Detalhe::SEQUENCIAL));
        $colunas[Detalhe::RETORNO_REGISTRO]         = substr($linha, $layout->getFieldPosition(Detalhe::RETORNO_REGISTRO),         $layout->getSize(Detalhe::RETORNO_REGISTRO));

        $detalhe = new Detalhe();
        $detalhe->setRetornoRegistro($colunas[Detalhe::RETORNO_REGISTRO]);

        list($identificadorDebito, $numnov) = explode('-', $colunas[Detalhe::IDENTIFICADOR_DEBITO]);

        $detalhe->setNumnov($numnov);

        return $detalhe;
    }
}
