<?php

namespace ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;

class AlvaraMei extends SolicitacaoAlvara
{
    public function toJSON($objetoSolicitacaoAlvara)
    {
        $objetoSolicitacaoAlvara = trim($objetoSolicitacaoAlvara->metadados);
        $objetoSolicitacaoAlvara = JSON::create()->parse($objetoSolicitacaoAlvara);
        file_put_contents('tmp/solicitacaoAlvaraMeiJSON', print_r($objetoSolicitacaoAlvara, true));

        $solicitacao = (object) array(
             "requerente" => $this->objetoRequerente($objetoSolicitacaoAlvara)
            ,"empresa"    => $this->objetoEmpresa($objetoSolicitacaoAlvara)
            ,"documentos" => $this->objetoDocumentos($objetoSolicitacaoAlvara)
        );

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
            ,"endereco"        => $this->objetoEmpresaEndereco($objetoSolicitacaoAlvara)
            ,"outros_dados"    => $this->objetoEmpresaOutrosDados($objetoSolicitacaoAlvara)
            ,"atividades"      => $this->objetoEmpresaAtividades($objetoSolicitacaoAlvara)
            ,"responsavel_mei" => $this->objetoEmpresaResponsavelMei($objetoSolicitacaoAlvara)
        );

        return (object) $empresa;
    }

    public function objetoEmpresaResponsavelMei($objetoSolicitacaoAlvara)
    {
        $responsavel = null;

        if (isset($objetoSolicitacaoAlvara->socios)) {
            foreach ($objetoSolicitacaoAlvara->socios as $key => $socio) {
                $socioSolicitacao = (object) array(
                    "cpf" => (object) array(
                         "label" => "CPF"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'cpf')
                    ),
                    "tipo_socio" => (object) array(
                         "label" => "Tipo de Sócio"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'tipo_socio')
                    ),
                    "valor_capital" => (object) array(
                         "label" => "Valor do Capital"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'valor_capital')
                    ),
                    "nome" => (object) array(
                         "label" => "Nome"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'nome')
                    ),
                    "nascimento" => (object) array(
                         "label" => "Data de Nascimento"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'nascimento')
                    ),
                    "sexo" => (object) array(
                         "label" => "Sexo"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'sexo')
                    ),
                    "telefone" => (object) array(
                         "label" => "Telefone"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'telefone')
                    ),
                    "celular" => (object) array(
                         "label" => "Celular"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'celular')
                    ),
                    "estado_civil" => (object) array(
                         "label" => "Estado Civil"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'estado_civil')
                    ),
                    "nacionalidade" => (object) array(
                         "label" => "Nacionalidade"
                        ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'nacionalidade')
                    ),
                    "endereco" => (object) array(
                        "cep" => (object) array(
                             "label" => "CEP"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'cep')
                        ),
                        "logradouro" => (object) array(
                             "label" => "Endereço"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'logradouro')
                        ),
                        "numero" => (object) array(
                             "label" => "Número"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'numero')
                        ),
                        "complemento" => (object) array(
                             "label" => "Compl"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'complemento')
                        ),
                        "bairro" => (object) array(
                             "label" => "Bairro"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'bairro')
                        ),
                        "municipio" => (object) array(
                             "label" => "Município"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'municipio')
                        ),
                        "estado" => (object) array(
                             "label" => "Estado"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'estado')
                        ),
                        "pais" => (object) array(
                             "label" => "País"
                            ,"value" => $this->getInformacaoJSON($socio, self::SOCIOS, 'pais')
                        ),
                    ),
                );

                $responsavel = (object)$socioSolicitacao;
            }
        }

        return $responsavel;
    }
}
