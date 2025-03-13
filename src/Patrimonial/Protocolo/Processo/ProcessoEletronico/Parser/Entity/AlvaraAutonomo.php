<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;

class AlvaraAutonomo extends SolicitacaoAlvara
{
    public function toJSON($objetoSolicitacaoAlvara)
    {
        $objetoSolicitacaoAlvara = trim($objetoSolicitacaoAlvara->metadados);
        file_put_contents('tmp/solicitacaoAlvaraAutonomoProcessoEletronico.json', $objetoSolicitacaoAlvara);
        $objetoSolicitacaoAlvara = JSON::create()->parse($objetoSolicitacaoAlvara);

        $solicitacao = (object) array(
             "requerente"  => $this->objetoSolicitacaoRequerente($objetoSolicitacaoAlvara)
            ,"responsavel" => $this->objetoDadosResponsavel($objetoSolicitacaoAlvara)
            ,"outros_dados"  => $this->objetoOutrosDados($objetoSolicitacaoAlvara)
            ,"endereco_municipio" => $this->objetoEndereco($objetoSolicitacaoAlvara)
            ,"atividades" => $this->objetoSolicitacaoAtividades($objetoSolicitacaoAlvara)
            ,"documentos"  => $this->objetoSolicitacaoDocumentos($objetoSolicitacaoAlvara)
        );

        file_put_contents(
            'tmp/solicitacaoAlvaraAutonomoProcessoEletronico_response.json',
            JSON::create()->stringify($solicitacao)
        );
        return JSON::create()->stringify($solicitacao);
    }

    public function objetoDadosResponsavel($objetoSolicitacaoAlvara)
    {
        $responsavel = array(
            "cpf" => (object) array(
                "label" => "CPF"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_RESPONSAVEL,
                    'cpf'
                )
            ),
            "razao_social" => (object) array (
                "label" => "Razão Social"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_RESPONSAVEL,
                    'razao_social'
                )
            ),
            "tipo_empresa" => (object) array (
                "label" => "Tipo Empresa"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_RESPONSAVEL,
                    'tipo_empresa'
                )
            ),
            "porte" => (object) array (
                "label" => "Porte"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_RESPONSAVEL,
                    'porte'
                )
            )
        );

        return $responsavel;
    }

    public function objetoOutrosDados($objetoSolicitacaoAlvara)
    {
        $outrosDados = array(
            "escritorio_contabil" => (object) array(
                "label" => "Escritório Contábil"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::OUTROS_DADOS,
                    'escritorio_contabil'
                )
            ),
            "data_junta_comercial" => (object) array(
                "label" => "Data Junta Comercial", "value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::OUTROS_DADOS,
                    'data_junta_comercial'
                )
            ),
            "registro_junta" => (object) array(
                "label" => "Registro Junta Comercial", "value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::OUTROS_DADOS,
                    'registro_junta'
                )
            ),
        );

        return $outrosDados;
    }

    public function objetoEndereco($objetoSolicitacaoAlvara)
    {
        $matricula  = $this->getInformacaoJSON(
            $objetoSolicitacaoAlvara,
            self::DADOS_EMPRESA_ENDERECO,
            'matricula_imovel'
        );

        $aux = $this->getInformacaoJSON(
            $objetoSolicitacaoAlvara,
            self::DADOS_EMPRESA_ENDERECO,
            'nome_proprietario'
        );

        if (!is_null($aux) && $aux != '') {
            $matricula .= " - ";
            $matricula .= $aux;
        }

        $endereco = array(
            "matricula" => (object) array(
                 "label" => "Matrícula"
                ,"value" => $matricula
            ),
            "telefone" => (object) array(
                 "label" => "Telefone"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'telefone')
            ),
            "celular" => (object) array(
                 "label" => "Celular"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'celular')
            ),
            "cep" => (object) array(
                "label" => "CEP", "value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'cep'
                )
            ),
            "bairro" => (object) array(
                "label" => "Bairro", "value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'bairro'
                )
            ),
            "logradouro" => (object) array(
                 "label" => "Logradouro"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'logradouro'
                )
            ),
            "municipio" => (object) array(
                "label" => "Municipio"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'municipio'
                )
            ),
            "estado" => (object) array(
                "label" => "Estado"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'estado')
            ),
            "numero" => (object) array(
                 "label" => "Número"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'numero')
            ),
            "complemento" => (object) array(
                "label" => "Complemento", "value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'complemento'
                )
            ),
            "zona" => (object) array(
                 "label" => "Zona"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'zona')
            ),
            "ponto_referencia" => (object) array(
                 "label" => "Pronto de Referencia"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'ponto_referencia'
                )
            )
        );

        return (object) $endereco;
    }
}
