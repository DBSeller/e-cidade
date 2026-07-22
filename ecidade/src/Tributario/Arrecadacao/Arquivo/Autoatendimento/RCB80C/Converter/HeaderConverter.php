<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Converter;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout\Layout;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Entity\Header;
use ECidade\Tributario\Arrecadacao\Repository\Convenio as ConvenioRepository;

use DateTime;

final class HeaderConverter extends Converter
{
    public function __construct(Layout $layout, $format = null)
    {
        parent::__construct($layout, $format);
    }

    public function build($linha)
    {
        $colunas = array();
        $layout = $this->layout;

        $colunas[Header::TIPO_REGISTRO_HEADER]  = substr($linha, $layout->getFieldPosition(Header::TIPO_REGISTRO_HEADER),   $layout->getSize(Header::TIPO_REGISTRO_HEADER));
        $colunas[Header::NUMERO_CONVENIO]       = substr($linha, $layout->getFieldPosition(Header::NUMERO_CONVENIO),        $layout->getSize(Header::NUMERO_CONVENIO));
        $colunas[Header::DATA_GERACAO]          = substr($linha, $layout->getFieldPosition(Header::DATA_GERACAO),           $layout->getSize(Header::DATA_GERACAO));
        $colunas[Header::IDENTIFICACAO_ARQUIVO] = substr($linha, $layout->getFieldPosition(Header::IDENTIFICACAO_ARQUIVO),  $layout->getSize(Header::IDENTIFICACAO_ARQUIVO));
        $colunas[Header::TIPO_ARQUIVO]          = substr($linha, $layout->getFieldPosition(Header::TIPO_ARQUIVO),           $layout->getSize(Header::TIPO_ARQUIVO));
        $colunas[Header::PREFIXO_AGENCIA]       = substr($linha, $layout->getFieldPosition(Header::PREFIXO_AGENCIA),        $layout->getSize(Header::PREFIXO_AGENCIA));
        $colunas[Header::ANO_REMESSA]           = substr($linha, $layout->getFieldPosition(Header::ANO_REMESSA),            $layout->getSize(Header::ANO_REMESSA));
        $colunas[Header::DATA_PROCESSAMENTO]    = substr($linha, $layout->getFieldPosition(Header::DATA_PROCESSAMENTO),     $layout->getSize(Header::DATA_PROCESSAMENTO));
        $colunas[Header::RESERVADO1]            = substr($linha, $layout->getFieldPosition(Header::RESERVADO1),             $layout->getSize(Header::RESERVADO1));
        $colunas[Header::CODIGO_CLIENTE_BANCO]  = substr($linha, $layout->getFieldPosition(Header::CODIGO_CLIENTE_BANCO),   $layout->getSize(Header::CODIGO_CLIENTE_BANCO));
        $colunas[Header::RESERVADO2]            = substr($linha, $layout->getFieldPosition(Header::RESERVADO2),             $layout->getSize(Header::RESERVADO2));
        $colunas[Header::SEQUENCIAL_REGISTRO]   = substr($linha, $layout->getFieldPosition(Header::SEQUENCIAL_REGISTRO),    $layout->getSize(Header::SEQUENCIAL_REGISTRO));
        $colunas[Header::RESERVADO3]            = substr($linha, $layout->getFieldPosition(Header::RESERVADO3),             $layout->getSize(Header::RESERVADO3));

        $header = new Header();
        $header->setTipoRegistro($colunas[Header::TIPO_REGISTRO_HEADER]);

        $convenio = ConvenioRepository::getInstanciaPorCodigo($colunas[Header::CODIGO_CLIENTE_BANCO]);

        $header->setConvenio($convenio);
        $header->setDataGeracao(new DateTime($colunas[Header::DATA_GERACAO]));
        $header->setIdentificacaoArquivo($colunas[Header::IDENTIFICACAO_ARQUIVO]);
        $header->setTipoArquivo($colunas[Header::TIPO_ARQUIVO]);
        $header->setPrefixoAgencia($colunas[Header::PREFIXO_AGENCIA]);
        $header->setAnoRemessa($colunas[Header::ANO_REMESSA]);
        $header->setDataProcessamento(new DateTime($colunas[Header::DATA_PROCESSAMENTO]));
        $header->setReservado1($colunas[Header::RESERVADO1]);
        $header->setCodigoClienteBanco($colunas[Header::CODIGO_CLIENTE_BANCO]);
        $header->setReservado2($colunas[Header::RESERVADO2]);
        $header->setSequencialRegistro($colunas[Header::SEQUENCIAL_REGISTRO]);
        $header->setReservado3($colunas[Header::RESERVADO3]);

        return $header;
    }
}
