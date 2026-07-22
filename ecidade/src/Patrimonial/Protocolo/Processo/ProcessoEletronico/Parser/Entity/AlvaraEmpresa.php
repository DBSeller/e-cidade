<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;

class AlvaraEmpresa extends SolicitacaoAlvara
{
    public function toJSON($objetoSolicitacaoAlvara)
    {
        $objetoSolicitacaoAlvara = trim($objetoSolicitacaoAlvara->metadados);
        file_put_contents('tmp/solicitacaoAlvaraEmpresaProcessoEletronico.json', $objetoSolicitacaoAlvara);
        $objetoSolicitacaoAlvara = JSON::create()->parse($objetoSolicitacaoAlvara);

        $solicitacao = (object) array(
             "requerente" => $this->objetoSolicitacaoRequerente($objetoSolicitacaoAlvara)
            ,"empresa"    => $this->objetoEmpresa($objetoSolicitacaoAlvara)
            ,"documentos" => $this->objetoSolicitacaoDocumentos($objetoSolicitacaoAlvara)
        );

        file_put_contents(
            'tmp/solicitacaoAlvaraEmpresaProcessoEletronico_response.json',
            JSON::create()->stringify($solicitacao)
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
            ),
            "protocolo_junta" => (object) array (
                "label" => "Protocolo Junta"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA,
                    'protocolo_junta'
                )
            ),
            "email" => (object) array (
                "label" => "E-mail"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA,
                    'emailempresa'
                )
            )
            ,"outros_dados" => $this->objetoEmpresaOutrosDados($objetoSolicitacaoAlvara)
            ,"simples"      => $this->objetoEmpresaSimples($objetoSolicitacaoAlvara)
            ,"atividades"   => $this->objetoSolicitacaoAtividades($objetoSolicitacaoAlvara)
            ,"socios"       => $this->objetoEmpresaSocios($objetoSolicitacaoAlvara)
        );

        return (object) $empresa;
    }

    public function objetoEmpresaOutrosDados($objetoSolicitacaoAlvara)
    {
        $outros_dados = array(
            "escritorio_contabil" => (object) array(
                 "label" => "Escritório Contábil"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::OUTROS_DADOS,
                    'escritorio_contabil'
                )
            ),
            "porte" => (object) array(
                 "label" => "Porte"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'porte')
            ),
            "empregados" => (object) array(
                 "label" => "Empregados"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'empregados')
            ),
            "area" => (object) array(
                 "label" => "Área"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA, 'area')
            ),
            "zona" => (object) array(
                 "label" => "Zona"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'zona')
            ),
        );

        return (object) $outros_dados;
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

    public function objetoEmpresaSocios($objetoSolicitacaoAlvara)
    {
        $socios = array();

        $sociosInformados  = $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::SOCIOS, 'resposta');
        $secaoSociosCampos = $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::SOCIOS, 'campos');

        $chavesEndereco = [
            'cep',
            'logradouro',
            'numero',
            'complemento',
            'bairro',
            'municipio',
            'estado',
            'pais'
        ];

        foreach ($sociosInformados as $key => $socio) {
            $socioSolicitacao = new \stdClass();

            foreach ($secaoSociosCampos as $campoSocio) {
                $value = $this->getResposta($campoSocio, $socio->{$campoSocio->nome});
                $label = $campoSocio->label;
                $chave = $campoSocio->nome;

                if (in_array($chave, $chavesEndereco) && isset($socioSolicitacao->endereco)) {
                    $socioSolicitacao->endereco->{$chave} = (object) array(
                        "label" => $label
                        ,"value" => $value
                    );
                } else {
                    $socioSolicitacao->{$chave} = (object) array(
                        "label" => $label
                        ,"value" => $value
                    );
                }
            }

            $socios['socio_'. ($key+1)] = $socioSolicitacao;
        }

        return $socios;
    }

    public function objetoEmpresaEndereco($objetoSolicitacaoAlvara)
    {
        $matricula  = (object) array (
            'codigo' => $this->getInformacaoJSON(
                $objetoSolicitacaoAlvara,
                self::DADOS_EMPRESA_ENDERECO,
                'matricula_imovel'
            ),
            'descricao' => $this->getInformacaoJSON(
                $objetoSolicitacaoAlvara,
                self::DADOS_EMPRESA_ENDERECO,
                'nome_proprietario'
            )
        );

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
                 "label" => "CEP"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'cep')
            ),
            "bairro" => (object) array(
                 "label" => "Bairro"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'bairro')
            ),
            "estado" => (object) array(
                "label" => "Estado"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'estado')
            ),
            "municipio" => (object) array(
                "label" => "Municipio"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'municipio'
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
            "numero" => (object) array(
                 "label" => "Número"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'numero')
            ),
            "complemento" => (object) array(
                 "label" => "Complemento"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'complemento'
                )
            ),
        );
        $pontoReferencia = $this->getInformacaoJSON(
            $objetoSolicitacaoAlvara,
            self::DADOS_EMPRESA_ENDERECO,
            'ponto_referencia'
        );

        if (!empty($pontoReferencia) && !empty($pontoReferencia->codigo)) {
            $endereco['ponto_referencia'] = (object) array(
                 'label' => "Ponto de Referência"
                ,'value' => $pontoReferencia
            );
        }

        return (object) $endereco;
    }
}
