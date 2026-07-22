<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class Contribuinte extends Layout
{
    public function __construct()
    {
        $this->fields = array(
            'NOME' => array(
                 'name'        => 'NOME'
                ,'description' => 'NOME A SER IMPRESSO NO CARNE'
                ,'size'        => 40
            )
            ,'PROMITENTE' => array(
                 'name'        => 'PROMITENTE'
                ,'description' => 'PROMITENTE COMPRADOR POR CONTRATO'
                ,'size'        => 40
            )
            ,'PROPRIETARIO' => array(
                 'name'        => 'PROPRIETARIOESCRITURA'
                ,'description' => 'PROPRIETARIO DA ESCRITURA'
                ,'size'        => 40
            )
            ,'PROPRIETARIOENDERECO' => array(
                 'name'        => 'ENDNOME'
                ,'description' => 'ENDERECO DO CGM DO PROPRIETARIO'
                ,'size'        => 40
            )
            ,'PROPRIETARIONUMERO' => array(
                 'name'        => 'NUMIMONOME'
                ,'description' => 'NUMERO DO IMOVEL DO CGM DO PROPRIETARIO'
                ,'size'        => 10
            )
            ,'PROPRIETARIOCOMPLEMENTO' => array(
                 'name'        => 'COMPLIMONOME'
                ,'description' => 'COMPLEMENTO DO CGM DO PROPRIETARIO'
                ,'size'        => 20
            )
            ,'PROPRIETARIOMUNICIPIO' => array(
                 'name'        => 'MUNICNOME'
                ,'description' => 'MUNICIPIO DO CGM DO PROPRIETARIO'
                ,'size'        => 20
            )
            ,'PROPRIETARIOCEP' => array(
                 'name'        => 'CEPNOME'
                ,'description' => 'CEP DO CGM DO PROPRIETARIO'
                ,'size'        => 8
            )
            ,'PROPRIETARIOUF' => array(
                 'name'        => 'UFNOME'
                ,'description' => 'UF DO CGM DO PROPRIETARIO'
                ,'size'        => 2
            )
            ,'PROPRIETARIOCNPJCPF' => array(
                 'name'        => 'CNPJCPFNOME'
                ,'description' => 'CNPJ/CPF DO CGM DO PROPRIETARIO'
                ,'size'        => 20
            )
            ,'IMOVELCODIGOLOGRADOURO' => array(
                 'name'        => 'CODLOGIMO'
                ,'description' => 'CODIGO DO LOGRADOURO DO IMOVEL'
                ,'size'        => 6
            )
            ,'IMOVELTIPOLOGRADOURO' => array(
                 'name'        => 'TIPOLOGIMO'
                ,'description' => 'TIPO DO LOGRADOURO DO IMOVEL'
                ,'size'        => 20
            )
            ,'IMOVELNOMELOGRADOURO' => array(
                 'name'        => 'DESCRLOGIMO'
                ,'description' => 'NOME DO LOGRADOURO PRINCIPAL DO IMOVEL'
                ,'size'        => 50
            )
            ,'IMOVELNUMERO' => array(
                 'name'        => 'NUMIMOIMO'
                ,'description' => 'NUMERO DO IMOVEL'
                ,'size'        => 10
            )
            ,'IMOVELCOMPLEMENTO' => array(
                 'name'        => 'COMPLIMOIMO'
                ,'description' => 'COMPLEMENTO DO IMOVEL'
                ,'size'        => 20
            )
            ,'IMOVELBAIRRO' => array(
                 'name'        => 'BAIIMO'
                ,'description' => 'BAIRRO DO IMOVEL'
                ,'size'        => 40
            )
            ,'ENTREGALOGRADOURO' => array(
                 'name'        => 'LOGRADENDENT'
                ,'description' => 'DESCRICAO DO LOGRADOURO DO ENDERECO DE ENTREGA'
                ,'size'        => 50
            )
            ,'ENTREGANUMERO' => array(
                 'name'        => 'NUMIMOENDENT'
                ,'description' => 'NUMERO DO ENDERECO DE ENTREGA'
                ,'size'        => 10
            )
            ,'ENTREGACOMPLEMENTO' => array(
                 'name'        => 'COMPLENDENT'
                ,'description' => 'COMPLEMENTO DO ENDERECO DE ENTREGA'
                ,'size'        => 20
            )
            ,'ENTREGABAIRRO' => array(
                 'name'        => 'BAIENDENT'
                ,'description' => 'BAIRRO DO ENDERECO DE ENTREGA'
                ,'size'        => 40
            )
            ,'ENTREGACIDADE' => array(
                 'name'        => 'CIDENDENT'
                ,'description' => 'CIDADE DO ENDERECO DE ENTREGA'
                ,'size'        => 40
            )
            ,'ENTREGAUF' => array(
                 'name'        => 'UFENDENT'
                ,'description' => 'UF DO ENDERECO DE ENTREGA'
                ,'size'        => 2
            )
            ,'ENTREGACEP' => array(
                 'name'        => 'CEPENDENT'
                ,'description' => 'CEP DO ENDERECO DE ENTREGA'
                ,'size'        => 10
            )
            ,'ENTREGACAIXAPOSTAL' => array(
                 'name'        => 'CXPENDENT'
                ,'description' => 'CAIXA POSTAL DO ENDERECO DE ENTREGA'
                ,'size'        => 10
            )
            ,'ENTREGADESTINATARIO' => array(
                 'name'        => 'DESTENDENT'
                ,'description' => 'DESTINATARIO DO ENDERECO DE ENTREGA'
                ,'size'        => 40
            )
        );
    }
}
