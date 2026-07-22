<?php

namespace ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;

class AlvaraAutonomo extends SolicitacaoAlvara
{
    public function toJSON($objetoSolicitacaoAlvara)
    {
        $objetoSolicitacaoAlvara = trim($objetoSolicitacaoAlvara->metadados);
        $objetoSolicitacaoAlvara = JSON::create()->parse($objetoSolicitacaoAlvara);
        file_put_contents('tmp/solicitacaoAlvaraAutonomoJSON', print_r($objetoSolicitacaoAlvara, true));

        $solicitacao = (object) array(
             "requerente"  => $this->objetoRequerente($objetoSolicitacaoAlvara)
            ,"responsavel" => $this->objetoDadosResponsavel($objetoSolicitacaoAlvara)
            ,"outros_dados"  => $this->objetoOutrosDados($objetoSolicitacaoAlvara)
            ,"endereco_municipio" => $this->objetoEndereco($objetoSolicitacaoAlvara)
            ,"atividades" => $this->objetoEmpresaAtividades($objetoSolicitacaoAlvara)
            ,"documentos"  => $this->objetoDocumentos($objetoSolicitacaoAlvara)
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
        );

        return $outrosDados;
    }
}
