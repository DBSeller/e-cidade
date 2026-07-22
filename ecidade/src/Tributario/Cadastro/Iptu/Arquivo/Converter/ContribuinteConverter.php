<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Contribuinte;
use ECidade\Tributario\Library\Entity;

final class ContribuinteConverter extends Converter
{
    public function get(Entity $contribuinte)
    {
        $l = '';

        $size = $this->layout->getSize(Contribuinte::NOME);
        $l   .= str_pad(substr($contribuinte->getNome(),                           0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROMITENTE);
        $l   .= str_pad(substr($contribuinte->getPromitente(),                     0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO);
        $l   .= str_pad(substr($contribuinte->getProprietario(),                   0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_ENDERECO);
        $l   .= str_pad(substr($contribuinte->getProprietarioEndereco(),           0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_NUMERO);
        $l   .= str_pad(substr($contribuinte->getProprietarioNumero(),             0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_COMPLEMENTO);
        $l   .= str_pad(substr($contribuinte->getProprietarioComplemento(),        0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_MUNICIPIO);
        $l   .= str_pad(substr($contribuinte->getProprietarioMunicipio(),          0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_CEP);
        $l   .= str_pad(substr($contribuinte->getProprietarioCep(),                0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_UF);
        $l   .= str_pad(substr($contribuinte->getProprietarioUf(),                 0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::PROPRIETARIO_CNPJ_CPF);
        $l   .= str_pad(substr($contribuinte->getProprietarioCnpjcpf(),            0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::IMOVEL_CODIGO_LOGRADOURO);
        $l   .= str_pad(substr($contribuinte->getImovelCodigoLogradouro(),         0, $size), $size, '0', STR_PAD_LEFT);

        $size = $this->layout->getSize(Contribuinte::IMOVEL_TIPO_LOGRADOURO);
        $l   .= str_pad(substr($contribuinte->getImovelTipoLogradouro(),           0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::IMOVEL_NOME_LOGRADOURO);
        $l   .= str_pad(substr($contribuinte->getImovelNomeLogradouro(),           0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::IMOVEL_NUMERO);
        $l   .= str_pad(substr($contribuinte->getImovelNumero(),                   0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::IMOVEL_COMPLEMENTO);
        $l   .= str_pad(substr($contribuinte->getImovelComplemento(),              0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::IMOVEL_BAIRRO);
        $l   .= str_pad(substr($contribuinte->getImovelBairro(),                   0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_LOGRADOURO);
        $l   .= str_pad(substr($contribuinte->getEntregaLogradouro(),              0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_NUMERO);
        $l   .= str_pad(substr($contribuinte->getEntregaNumero(),                  0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_COMPLEMENTO);
        $l   .= str_pad(substr($contribuinte->getEntregaComplemento(),             0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_BAIRRO);
        $l   .= str_pad(substr($contribuinte->getEntregaBairro(),                  0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_CIDADE);
        $l   .= str_pad(substr($contribuinte->getEntregaCidade(),                  0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_UF);
        $l   .= str_pad(substr($contribuinte->getEntregaUf(),                      0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_CEP);
        $l   .= str_pad(substr($contribuinte->getEntregaCep(),                     0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_CAIXA_POSTAL);
        $l   .= str_pad(substr($contribuinte->getEntregaCaixaPostal(),             0, $size), $size);

        $size = $this->layout->getSize(Contribuinte::ENTREGA_DESTINATARIO);
        $l   .= str_pad(substr($contribuinte->getEntregaDestinatario(),            0, $size), $size);


        return $l;
    }
}
