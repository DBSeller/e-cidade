<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class Contribuinte extends Entity
{
    const NOME                        = 'NOME';
    const PROMITENTE                  = 'PROMITENTE';
    const PROPRIETARIO                = 'PROPRIETARIO';
    const PROPRIETARIO_ENDERECO       = 'PROPRIETARIOENDERECO';
    const PROPRIETARIO_NUMERO         = 'PROPRIETARIONUMERO';
    const PROPRIETARIO_COMPLEMENTO    = 'PROPRIETARIOCOMPLEMENTO';
    const PROPRIETARIO_MUNICIPIO      = 'PROPRIETARIOMUNICIPIO';
    const PROPRIETARIO_CEP            = 'PROPRIETARIOCEP';
    const PROPRIETARIO_UF             = 'PROPRIETARIOUF';
    const PROPRIETARIO_CNPJ_CPF       = 'PROPRIETARIOCNPJCPF';
    const IMOVEL_CODIGO_LOGRADOURO    = 'IMOVELCODIGOLOGRADOURO';
    const IMOVEL_TIPO_LOGRADOURO      = 'IMOVELTIPOLOGRADOURO';
    const IMOVEL_NOME_LOGRADOURO      = 'IMOVELNOMELOGRADOURO';
    const IMOVEL_NUMERO               = 'IMOVELNUMERO';
    const IMOVEL_COMPLEMENTO          = 'IMOVELCOMPLEMENTO';
    const IMOVEL_BAIRRO               = 'IMOVELBAIRRO';
    const ENTREGA_LOGRADOURO          = 'ENTREGALOGRADOURO';
    const ENTREGA_NUMERO              = 'ENTREGANUMERO';
    const ENTREGA_COMPLEMENTO         = 'ENTREGACOMPLEMENTO';
    const ENTREGA_BAIRRO              = 'ENTREGABAIRRO';
    const ENTREGA_CIDADE              = 'ENTREGACIDADE';
    const ENTREGA_UF                  = 'ENTREGAUF';
    const ENTREGA_CEP                 = 'ENTREGACEP';
    const ENTREGA_CAIXA_POSTAL        = 'ENTREGACAIXAPOSTAL';
    const ENTREGA_DESTINATARIO        = 'ENTREGADESTINATARIO';

    private $nome = '';

    private $promitente = '';

    private $proprietario = '';

    private $proprietarioEndereco = '';

    private $proprietarioNumero = '';

    private $proprietarioComplemento = '';

    private $proprietarioMunicipio = '';

    private $proprietarioCep = '';

    private $proprietarioUf = '';

    private $proprietarioCnpjcpf = '';

    private $imovelCodigoLogradouro = '';

    private $imovelTipoLogradouro = '';

    private $imovelNomeLogradouro = '';

    private $imovelNumero = '';

    private $imovelComplemento = '';

    private $imovelBairro = '';

    private $entregaLogradouro = '';

    private $entregaNumero = '';

    private $entregaComplemento = '';

    private $entregaBairro = '';

    private $entregaCidade = '';

    private $entregaUf = '';

    private $entregaCep = '';

    private $entregaCaixaPostal = '';

    private $entregaDestinatario = '';

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setPromitente($promitente)
    {
        $this->promitente = $promitente;
    }

    public function setProprietario($proprietario)
    {
        $this->proprietario = $proprietario;
    }

    public function setProprietarioEndereco($proprietarioEndereco)
    {
        $this->proprietarioEndereco = $proprietarioEndereco;
    }

    public function setProprietarioNumero($proprietarioNumero)
    {
        $this->proprietarioNumero = $proprietarioNumero;
    }

    public function setProprietarioComplemento($proprietarioComplemento)
    {
        $this->proprietarioComplemento = $proprietarioComplemento;
    }

    public function setProprietarioMunicipio($proprietarioMunicipio)
    {
        $this->proprietarioMunicipio = $proprietarioMunicipio;
    }

    public function setProprietarioCep($proprietarioCep)
    {
        $this->proprietarioCep = $proprietarioCep;
    }

    public function setProprietarioUf($proprietarioUf)
    {
        $this->proprietarioUf = $proprietarioUf;
    }

    public function setProprietarioCnpjcpf($proprietarioCnpjcpf)
    {
        $this->proprietarioCnpjcpf = $proprietarioCnpjcpf;
    }

    public function setImovelCodigoLogradouro($imovelCodigoLogradouro)
    {
        $this->imovelCodigoLogradouro = $imovelCodigoLogradouro;
    }

    public function setImovelTipoLogradouro($imovelTipoLogradouro)
    {
        $this->imovelTipoLogradouro = $imovelTipoLogradouro;
    }

    public function setImovelNomeLogradouro($imovelNomeLogradouro)
    {
        $this->imovelNomeLogradouro = $imovelNomeLogradouro;
    }

    public function setImovelNumero($imovelNumero)
    {
        $this->imovelNumero = $imovelNumero;
    }

    public function setImovelComplemento($imovelComplemento)
    {
        $this->imovelComplemento = $imovelComplemento;
    }

    public function setImovelBairro($imovelBairro)
    {
        $this->imovelBairro = $imovelBairro;
    }

    public function setEntregaLogradouro($entregaLogradouro)
    {
        $this->entregaLogradouro = $entregaLogradouro;
    }

    public function setEntregaNumero($entregaNumero)
    {
        $this->entregaNumero = $entregaNumero;
    }

    public function setEntregaComplemento($entregaComplemento)
    {
        $this->entregaComplemento = $entregaComplemento;
    }

    public function setEntregaBairro($entregaBairro)
    {
        $this->entregaBairro = $entregaBairro;
    }

    public function setEntregaCidade($entregaCidade)
    {
        $this->entregaCidade = $entregaCidade;
    }

    public function setEntregaUf($entregaUf)
    {
        $this->entregaUf = $entregaUf;
    }

    public function setEntregaCep($entregaCep)
    {
        $this->entregaCep = $entregaCep;
    }

    public function setEntregaCaixaPostal($entregaCaixaPostal)
    {
        $this->entregaCaixaPostal = $entregaCaixaPostal;
    }

    public function setEntregaDestinatario($entregaDestinatario)
    {
        $this->entregaDestinatario = $entregaDestinatario;
    }
    
    public function getNome()
    {
        return $this->nome;
    }

    public function getPromitente()
    {
        return $this->promitente;
    }

    public function getProprietario()
    {
        return $this->proprietario;
    }

    public function getProprietarioEndereco()
    {
        return $this->proprietarioEndereco;
    }

    public function getProprietarioNumero()
    {
        return $this->proprietarioNumero;
    }

    public function getProprietarioComplemento()
    {
        return $this->proprietarioComplemento;
    }

    public function getProprietarioMunicipio()
    {
        return $this->proprietarioMunicipio;
    }

    public function getProprietarioCep()
    {
        return $this->proprietarioCep;
    }

    public function getProprietarioUf()
    {
        return $this->proprietarioUf;
    }

    public function getProprietarioCnpjcpf()
    {
        return $this->proprietarioCnpjcpf;
    }

    public function getImovelCodigoLogradouro()
    {
        return $this->imovelCodigoLogradouro;
    }

    public function getImovelTipoLogradouro()
    {
        return $this->imovelTipoLogradouro;
    }

    public function getImovelNomeLogradouro()
    {
        return $this->imovelNomeLogradouro;
    }

    public function getImovelNumero()
    {
        return $this->imovelNumero;
    }

    public function getImovelComplemento()
    {
        return $this->imovelComplemento;
    }

    public function getImovelBairro()
    {
        return $this->imovelBairro;
    }

    public function getEntregaLogradouro()
    {
        return $this->entregaLogradouro;
    }

    public function getEntregaNumero()
    {
        return $this->entregaNumero;
    }

    public function getEntregaComplemento()
    {
        return $this->entregaComplemento;
    }

    public function getEntregaBairro()
    {
        return $this->entregaBairro;
    }

    public function getEntregaCidade()
    {
        return $this->entregaCidade;
    }

    public function getEntregaUf()
    {
        return $this->entregaUf;
    }

    public function getEntregaCep()
    {
        return $this->entregaCep;
    }

    public function getEntregaCaixaPostal()
    {
        return $this->entregaCaixaPostal;
    }

    public function getEntregaDestinatario()
    {
        return $this->entregaDestinatario;
    }
}
