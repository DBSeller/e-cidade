<?php

namespace ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;

abstract class SolicitacaoAlvara
{
    const
        REQUERENTE             = 'REQUERENTE',
        DADOS_EMPRESA          = 'DADOS_EMPRESA',
        DADOS_EMPRESA_ENDERECO = 'DADOS_EMPRESA_ENDERECO',
        OUTROS_DADOS           = 'OUTROS_DADOS',
        ATIVIDADES             = 'ATIVIDADES',
        SOCIOS                 = 'SOCIOS',
        DOCUMENTOS             = 'DOCUMENTOS',
        DADOS_RESPONSAVEL      = 'DADOS_RESPONSAVEL',
        ATIVIDADES_AUTONOMO    = 'ATIVIDADES_AUTONOMO'
    ;

    private $collectionAtividades;

    public function __construct($collectionAtividades)
    {
        if (empty($collectionAtividades)) {
            throw new ParameterException("Informe as atividades de CNPJ");
        }

        $this->collectionAtividades    = $collectionAtividades;
    }

    public function toJSON($objetoSolicitacaoAlvara)
    {
        $objetoSolicitacaoAlvara = trim($objetoSolicitacaoAlvara->metadados);
        $objetoSolicitacaoAlvara = JSON::create()->parse($objetoSolicitacaoAlvara);

        $solicitacao = (object) array();

        return JSON::create()->stringify($solicitacao);
    }

    public function getInformacaoJSON($objetoSolicitacaoAlvara, $secao, $atributo)
    {
        $valor = null;

        switch ($secao) {
            case self::REQUERENTE:
                $secaoJSON = 'requerente';
                break;

            case self::DADOS_EMPRESA:
                $secaoJSON = 'dados_empresa';
                break;

            case self::DADOS_EMPRESA_ENDERECO:
                $secaoJSON = 'endereco_municipio';
                break;

            case self::DADOS_RESPONSAVEL:
                $secaoJSON = 'dados_responsavel';
                break;

            case self::OUTROS_DADOS:
                $secaoJSON = 'outros_dados';
                break;

            case self::ATIVIDADES:
            case self::DOCUMENTOS:
            case self::SOCIOS:
                if (!isset($objetoSolicitacaoAlvara->{$atributo})) {
                    return $valor;
                }

                return !empty($objetoSolicitacaoAlvara->{$atributo}) ? $objetoSolicitacaoAlvara->{$atributo} : null;
                break;

            default:
                $valor = 'SECAO NAO IMPLEMENTADA NO OBJETO JSON';
                break;
        }

        if (isset($objetoSolicitacaoAlvara->{$secaoJSON})) {
            if (isset($objetoSolicitacaoAlvara->{$secaoJSON}->{$atributo})) {
                $valor = $objetoSolicitacaoAlvara->{$secaoJSON}->{$atributo};
            }
        }

        return $valor;
    }

    public function objetoRequerente($objetoSolicitacaoAlvara)
    {
        $requerente = array(
            "cpf" => (object) array(
                 "label" => "CPF"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'cpf')
            ),
            "cnpj" => (object) array(
                 "label" => "CNPJ"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'cnpj')
            ),
            "nome" => (object) array(
                 "label" => "Nome / Razão Social"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'nome')
            ),
            "telefone" => (object) array(
                 "label" => "Telefone"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'telefone')
            ),
            "celular" => (object) array(
                 "label" => "Celular"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'celular')
            ),
            "logradouro" => (object) array(
                 "label" => "Logradouro"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'logradouro')
            ),
            "numero" => (object) array(
                 "label" => "Número"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'numero')
            ),
            "complemento" => (object) array(
                 "label" => "Complemento"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'complemento')
            ),
            "bairro" => (object) array(
                 "label" => "Bairro"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'bairro')
            ),
            "estado" => (object) array(
                 "label" => "Estado"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'estado')
            ),
            "municipio" => (object) array(
                 "label" => "Município"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'municipio')
            ),
            "pais" => (object) array(
                 "label" => "País"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'pais')
            ),
            "cep" => (object) array(
                 "label" => "CEP"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::REQUERENTE, 'cep')
            ),
        );

        return (object) $requerente;
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
            "logradouro" => (object) array(
                 "label" => "Logradouro"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'logradouro'
                )
            ),
            "complemento" => (object) array(
                 "label" => "Complemento"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'complemento'
                )
            ),
            "bairro" => (object) array(
                 "label" => "Bairro"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'bairro')
            ),
            "numero" => (object) array(
                 "label" => "Número"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'numero')
            ),
            "cep" => (object) array(
                 "label" => "CEP"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'cep')
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

    public function objetoEmpresaAtividades($objetoSolicitacaoAlvara)
    {
        $atividades = array(
            "atividade" => (object) array(
                 "label" => "Atividade Principal"
                ,"value" => ""
            ),
            "data_inicio" => (object) array(
                 "label" => "Data de Início"
                ,"value" => ""
            ),
        );

        if (!empty($objetoSolicitacaoAlvara->atividades)) {
            foreach ($objetoSolicitacaoAlvara->atividades as $key => $atividade) {
                $cadastroAtividades = $this->buscarAtividade($atividade->idcodigo);

                if (empty($cadastroAtividades)) {
                    continue;
                }

                $descricaoAtividade = $cadastroAtividades->descricao;
                $riscoAtividade     = $cadastroAtividades->risco;

                if (!empty($cadastroAtividades->codigo)) {
                    $descricaoAtividade  = $cadastroAtividades->codigo;
                    $descricaoAtividade .= ' - ';
                    $descricaoAtividade .= $this->getInformacaoJSON($atividade, self::ATIVIDADES, 'idcodigo');
                    $descricaoAtividade .= ' - ';
                    $descricaoAtividade .= $cadastroAtividades->descricao;
                }

                if (!empty($atividade->principal) && !empty($atividade->principal->codigo)) {
                    if ((int)$atividade->principal->codigo === 1) {
                        $atividadePrincipal = (object) array(
                             "id"          => $this->getInformacaoJSON($atividade, self::ATIVIDADES, 'idcodigo')
                            ,"descricao"   => $descricaoAtividade
                            ,"data_inicio" => $this->getInformacaoJSON($atividade, self::ATIVIDADES, 'data_inicio')
                            ,"risco"       => $riscoAtividade
                        );

                        continue;
                    }
                }

                $atividadeSolicitacao = (object) array(
                    "atividade" => (object) array(
                         "label" => "Atividade"
                        ,"value" => $descricaoAtividade
                        ,"id"    => $this->getInformacaoJSON($atividade, self::ATIVIDADES, 'idcodigo')
                        ,"risco" => $riscoAtividade
                    ),
                    "data_inicio" => (object) array(
                         "label" => "Data de Início"
                        ,"value" => $this->getInformacaoJSON($atividade, self::ATIVIDADES, 'data_inicio')
                    ),
                );

                $atividades['atividades_secundarias']['atividade_'. ($key + 1)] = (object) $atividadeSolicitacao;
            }

            if (empty($atividadePrincipal)) {
                return array();
            }

            $atividades['atividade']->id      = $atividadePrincipal->id;
            $atividades['atividade']->value   = $atividadePrincipal->descricao;
            $atividades['atividade']->risco   = $atividadePrincipal->risco;
            $atividades['data_inicio']->value = $atividadePrincipal->data_inicio;
        }

        return (object) $atividades;
    }

    public function objetoEmpresaSocios($objetoSolicitacaoAlvara)
    {
        $socios = array();

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

                $socios['socio_'. ($key+1)] = (object)$socioSolicitacao;
            }
        }

        return $socios;
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
            "logradouro" => (object) array(
                 "label" => "Logradouro"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'logradouro'
                )
            ),
            "complemento" => (object) array(
                 "label" => "Complemento"
                ,"value" => $this->getInformacaoJSON(
                    $objetoSolicitacaoAlvara,
                    self::DADOS_EMPRESA_ENDERECO,
                    'complemento'
                )
            ),
            "bairro" => (object) array(
                 "label" => "Bairro"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'bairro')
            ),
            "numero" => (object) array(
                 "label" => "Número"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'numero')
            ),
            "cep" => (object) array(
                 "label" => "CEP"
                ,"value" => $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DADOS_EMPRESA_ENDERECO, 'cep')
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

    public function objetoDocumentos($objetoSolicitacaoAlvara)
    {
        $documentos = array();

        if (!empty($objetoSolicitacaoAlvara->documentos)) {
            foreach ($objetoSolicitacaoAlvara->documentos as $key => $documento) {
                $documentoAnexo = (object) array(
                     "label"     => $this->getInformacaoJSON($documento, self::DOCUMENTOS, 'descricao')
                    ,"value"     => $this->getInformacaoJSON($documento, self::DOCUMENTOS, 'codigo_estorage')
                    ,"descricao" => $this->getInformacaoJSON($documento, self::DOCUMENTOS, 'descricao')
                    ,"codigo_vinculo" => $this->getInformacaoJSON($documento, self::DOCUMENTOS, 'codigo_vinculo')
                    ,"tipo" => $this->getInformacaoJSON($documento, self::DOCUMENTOS, 'tipo')
                );

                $documentos['documento_'. ($key + 1)] = $documentoAnexo;
            }
        }

        return $documentos;
    }

    protected function buscarAtividade($codigo)
    {
        if (is_object($codigo)) {
            return null;
        }

        $atividade = $this->collectionAtividades->offsetGet($codigo);

        if (empty($atividade)) {
            throw new BusinessException("Não foi possível identificar a atividade informada.");
        }

        return $atividade;
    }
}
