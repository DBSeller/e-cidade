<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/02/19
 * Time: 17:19
 */

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Header;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Enum\TipoRegistro;
use ECidade\Tributario\Library\Entity;

final class HeaderConverter extends Converter
{
    const IDENTIFICACAO_ARQUIVO = "RCB800";

    /**
     * @param Entity $entity
     * @return string
     */
    public function build(Entity $entity)
    {
        $codigoCliente = $entity->getConvenio()->getCedente() . $entity->getConvenio()->getDigitoCedente();
        
        $size   = $this->layout->getSize(Header::TIPO_REGISTRO_HEADER);
        $header = TipoRegistro::HEADER;
        
        $size = $this->layout->getSize(Header::NUMERO_CONVENIO);
       // $convArrecadacao = $entity->getConvenio()->getConvenioArrecadacao();
        $convArrecadacao = $this->layout->getDefault(Header::NUMERO_CONVENIO);
        $header .= substr(str_pad($convArrecadacao, $size, '0', STR_PAD_LEFT), ($size * -1));

        $size = $this->layout->getSize(Header::DATA_GERACAO);
        $header .= substr(str_pad($entity->getDataGeracao()->format("Ymd"), $size, '0', STR_PAD_LEFT), 0, $size);

        $size    = $this->layout->getSize(Header::IDENTIFICACAO_ARQUIVO);
        $header .= substr(str_pad(self::IDENTIFICACAO_ARQUIVO, $size, ' ', STR_PAD_RIGHT), 0, $size);

        $size         = $this->layout->getSize(Header::TIPO_ARQUIVO);
        $defaultValue = $this->layout->getDefault(Header::TIPO_ARQUIVO);
        $tipo = $entity->getTipoArquivo();
        if (empty($tipo)) {
            $tipo = $defaultValue;
        }
        $header .= substr(str_pad($tipo, $size, ' ', STR_PAD_RIGHT), 0, $size);

        $size = $this->layout->getSize(Header::PREFIXO_AGENCIA);
        $header .= substr(str_pad($entity->getConvenio()->getCodigoAgencia(), $size, '0', STR_PAD_LEFT), ($size * -1));

        $size = $this->layout->getSize(Header::ANO_REMESSA);
        $header .= substr(str_pad($entity->getAnoRemessa(), $size, '0', STR_PAD_LEFT), 0, $size);

        $size = $this->layout->getSize(Header::NUMERO_REMESSA);
        $header .= substr(str_pad($entity->getNumero(), $size, '0', STR_PAD_LEFT), 0, $size);

        $size = $this->layout->getSize(Header::INICIO_VIGENCIA);
        $header .= substr(str_pad($entity->getDataInicioVigencia()->format("Ymd"), $size, '0', STR_PAD_LEFT), 0, $size);

        $size = $this->layout->getSize(Header::FIM_VIGENCIA);
        $header .= substr(str_pad($entity->getDataFimVigencia()->format("Ymd"), $size, '0', STR_PAD_LEFT), 0, $size);

        $size = $this->layout->getSize(Header::CODIGO_CLIENTE_BANCO);
        $codigoCliente = $this->layout->getDefault(Header::CODIGO_CLIENTE_BANCO);
        $header .= substr(str_pad($codigoCliente, $size, '0', STR_PAD_LEFT), 0, $size);

        //Espaços em branco
        $size         = $this->layout->getSize(Header::RESERVADO);
        $defaultValue = $this->layout->getDefault(Header::RESERVADO);
        $header      .= str_repeat($defaultValue, $size);
       
        $size         = $this->layout->getSize(Header::SEQUENCIAL);
        $defaultValue = $this->layout->getDefault(Header::SEQUENCIAL);
        $header      .= substr(str_pad($defaultValue, $size, '0', STR_PAD_LEFT), 0, $size);

        return $header;
    }
}
