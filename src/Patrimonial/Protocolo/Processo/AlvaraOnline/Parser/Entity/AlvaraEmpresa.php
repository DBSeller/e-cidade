<?php

namespace ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;

class AlvaraEmpresa extends SolicitacaoAlvara
{
    public function toJSON($objetoSolicitacaoAlvara)
    {
        $objetoSolicitacaoAlvara = trim($objetoSolicitacaoAlvara->metadados);
        file_put_contents('tmp/solicitacaoAlvaraEmpresa.json', $objetoSolicitacaoAlvara);
        $objetoSolicitacaoAlvara = JSON::create()->parse($objetoSolicitacaoAlvara);

        $solicitacao = (object) array(
             "requerente" => $this->objetoRequerente($objetoSolicitacaoAlvara)
            ,"empresa"    => $this->objetoEmpresa($objetoSolicitacaoAlvara)
            ,"documentos" => $this->objetoDocumentos($objetoSolicitacaoAlvara)
        );

        file_put_contents('tmp/solicitacaoAlvaraEmpresa_response_old.json', JSON::create()->stringify($solicitacao));
        return JSON::create()->stringify($solicitacao);
    }

    public function objetoEmpresa($objetoSolicitacaoAlvara)
    {
        $empresa = array(
            "tipo_empresa" => (object) array(
                 "label" => "Tipo de Empresa"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'tipo_empresa')
            ),
            "cnpj" => (object) array(
                 "label" => "CPF/CNPJ"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'cnpj')
            ),
            "razao_social" => (object) array(
                 "label" => "Nome/Razão Social"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'razao_social')
            ),
            "nome_fantasia" => (object) array(
                 "label" => "Nome Fantasia"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'nome_fantasia')
            ),
            "inscricao_estadual" => (object) array(
                 "label" => "Inscrição Estadual"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA,
                    'inscricao_estadual'
                )
            )
            ,"endereco"     => $this->objetoEmpresaEndereco($objetoSolicitacaoAlvara)
            ,"data_junta_comercial" => (object) array (
                "label" => "Data Junta"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA,
                    'data_junta_comercial'
                )
            ),
            "registro_junta" => (object) array (
                "label" => "Registro Junta"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA,
                    'registro_junta'
                )
            )
            ,"outros_dados" => $this->objetoEmpresaOutrosDados($objetoSolicitacaoAlvara)
            ,"simples"      => $this->objetoEmpresaSimples($objetoSolicitacaoAlvara)
            ,"atividades"   => $this->objetoEmpresaAtividades($objetoSolicitacaoAlvara)
            ,"socios"       => $this->objetoEmpresaSocios($objetoSolicitacaoAlvara)
        );

        return (object) $empresa;
    }

    public function objetoEmpresaSimples($objetoSolicitacaoAlvara)
    {
        $optanteSimples = $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'optante_simples');

        $simples = array(
            "optante_simples" => (object) array(
                 "label" => "Optante Simples"
                ,"value" => $optanteSimples
            ),
        );

        if ((int)$optanteSimples->codigo === 1) { //Significa que eh optante pelo simples
            $simples['data_opcao_simples'] = (object) array(
                 "label" => "Data da Opção pelo Simples"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA,
                    'data_opcao_simples'
                )
            );
            
            $categoriaSimples = $this->getInformacaoJSON(
                $objetoSolicitacaoAlvara,
                self::DADOS_EMPRESA,
                'categoria_simples'
            );
            $simples['categoria_simples']  = (object) array(
                 "label" => "Categoria no Simples"
                ,"value" => $categoriaSimples
            );
        }

        return (object) $simples;
    }
}
