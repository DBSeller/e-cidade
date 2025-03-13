<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Parser\Entity;

use \ParameterException;
use \BusinessException;
use \JSON;
use stdClass;

abstract class SolicitacaoAlvara
{
    const
        REQUERENTE             = 'REQUERENTE',
        DADOS_EMPRESA          = 'DADOS_EMPRESA',
        DADOS_EMPRESA_ENDERECO = 'DADOS_EMPRESA_ENDERECO',
        OUTROS_DADOS           = 'OUTROS_DADOS',
        ATIVIDADES             = 'ATIVIDADES',
        SOCIOS                 = 'SOCIOS',
        RESPONSAVEL_MEI        = 'RESPONSAVEL_MEI',
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
        // file_put_contents('tmp/solicitacaoAlvaraJSON', print_r($objetoSolicitacaoAlvara, true));

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
                $secaoJSON = 'atividades';
                break;

            case self::RESPONSAVEL_MEI:
                $secaoJSON = 'responsavel';
                break;

            case self::DOCUMENTOS:
                $secaoJSON = 'anexos';
                break;

            case self::SOCIOS:
                $secaoJSON = 'socios';
                break;

            default:
                $valor = 'SECAO NAO IMPLEMENTADA NO OBJETO JSON';
                break;
        }

        foreach ($objetoSolicitacaoAlvara->secoes as $secao) {
            if ($secao->nome == $secaoJSON) {
                if (in_array($secao->tipo, ['tabela', 'anexo'])) {
                    return $secao->{$atributo};
                }

                foreach ($secao->campos as $campo) {
                    if ($campo->nome != $atributo) {
                        continue;
                    }

                    $valor = $this->getResposta($campo);
                }
            }
        }

        return $valor;
    }

    public function objetoSolicitacaoRequerente($objetoSolicitacaoAlvara)
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

    public function objetoSolicitacaoAtividades($objetoSolicitacaoAlvara)
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

        $objetoSolicitacaoAlvaraAtividades = $this->getInformacaoJSON(
            $objetoSolicitacaoAlvara,
            self::ATIVIDADES,
            'resposta'
        );
        $objetoSolicitacaoAlvaraAtividadesCampos = $this->getInformacaoJSON(
            $objetoSolicitacaoAlvara,
            self::ATIVIDADES,
            'campos'
        );
        $atividadesSelecionadas = [];

        if (!empty($objetoSolicitacaoAlvaraAtividades)) {
            foreach ($objetoSolicitacaoAlvaraAtividades as $atividade) {
                $resposta = null;
                $atividadeSelecionada = new stdClass;

                foreach ($objetoSolicitacaoAlvaraAtividadesCampos as $campo) {
                    if (!empty($valorCampoAtividade = $atividade->{$campo->nome})) {
                        $resposta = $this->getResposta($campo, $valorCampoAtividade);

                        $atividadeSelecionada->{$campo->nome} = $resposta;
                        $atividadeSelecionada->{$campo->nome} = $valorCampoAtividade;
                    }
                }

                $atividadesSelecionadas[] = $atividadeSelecionada;
            }
        }

        if (!empty($atividadesSelecionadas)) {
            foreach ($atividadesSelecionadas as $key => $atividade) {
                if (isset($atividade->id)) {
                    $ID_CODIGO = $atividade->id;
                } elseif (isset($atividade->codigo)) {
                    $ID_CODIGO = $atividade->codigo;
                } else {
                    $ID_CODIGO = $atividade->atividade;

                    if (isset($ID_CODIGO->id)) {
                        $ID_CODIGO = $ID_CODIGO->id;
                    } elseif (!empty($ID_CODIGO->codigo)) {
                        $ID_CODIGO = $ID_CODIGO->codigo;
                    } else {
                        $ID_CODIGO = null;
                    }
                }

                if (empty($ID_CODIGO)) {
                    continue;
                }

                $cadastroAtividades = $this->buscarAtividade($ID_CODIGO);

                if (empty($cadastroAtividades)) {
                    continue;
                }

                $descricaoAtividade = $cadastroAtividades->descricao;
                $riscoAtividade     = $cadastroAtividades->risco;

                if (!empty($cadastroAtividades->codigo)) {
                    $descricaoAtividade  = $cadastroAtividades->codigo;
                    $descricaoAtividade .= ' - ';
                    $descricaoAtividade .= $ID_CODIGO;
                    $descricaoAtividade .= ' - ';
                    $descricaoAtividade .= $cadastroAtividades->descricao;
                }

                if (!empty($atividade->principal)) {
                    if (!empty($atividade->principal->id) || !empty($atividade->principal->codigo)) {
                        if ((isset($atividade->principal->id) && (int)$atividade->principal->id === 1)
                                ||
                            (isset($atividade->principal->codigo) && (int)$atividade->principal->codigo === 1)
                        ) {
                            $atividadePrincipal = (object) array(
                                 "id"          => $ID_CODIGO
                                ,"descricao"   => $descricaoAtividade
                                ,"data_inicio" => $atividade->data_inicio
                                ,"risco"       => $riscoAtividade
                            );

                            continue;
                        }
                    }
                }

                $atividadeSolicitacao = (object) array(
                    "atividade" => (object) array(
                         "label" => "Atividade"
                        ,"value" => $descricaoAtividade
                        ,"id"    => $ID_CODIGO
                        ,"risco" => $riscoAtividade
                    ),
                    "data_inicio" => (object) array(
                         "label" => "Data de Início"
                        ,"value" => $atividade->data_inicio
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

    public function objetoSolicitacaoDocumentos($objetoSolicitacaoAlvara)
    {
        $documentos = array();
        $documentosAnexos = $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DOCUMENTOS, 'resposta');
        $camposAnexos     = $this->getInformacaoJSON($objetoSolicitacaoAlvara, self::DOCUMENTOS, 'campos');

        if (!empty($documentosAnexos)) {
            foreach ($documentosAnexos as $key => $documento) {
                $nome = null;

                foreach ($camposAnexos as $campo) {
                    if ($campo->nome == $documento->nome) {
                        $tipo           = $campo->tipo;
                        $nome           = $campo->label;
                    }
                }

                $nome = !empty($nome) ? $nome : preg_replace('/(.*?)_\d+$/', "$1", $documento->nome);
                $nome = mb_strtoupper($nome);

                $documentoAnexo = (object) array(
                     "label"     => $nome
                    ,"value"     => $documento->codigo
                    ,"descricao" => $documento->descricao
                    ,"codigo_vinculo" => !empty($codigo_vinculo) ? $codigo_vinculo : null
                    ,"tipo" => !empty($tipo) ? $tipo : null
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

    protected function getResposta($campo, $resposta = null)
    {

        $valor = null;

        if (empty($resposta)) {
            if (isset($campo->resposta)) {
                $resposta = $campo->resposta;
            } else {
                $resposta = null;
            }
        }

        switch ($campo->tipo) {
            case 'lista_dinamica':
            case 'lista':
            case 'autocomplete':
                if (empty($resposta)) {
                    $valor = $resposta;
                    break;
                }

                $resposta = (array) $resposta;

                if (!empty($resposta) && count($resposta) > 0) {
                    $res = [];

                    array_walk($resposta, function ($val, $k) use (&$res) {

                        switch (strtolower($k)) {
                            case 'id':
                            case 'uf':
                                $k = 'codigo';
                                break;
                        }

                        $res[$k] = $val;
                    });

                    $valor = (object) $res;
                }
                break;

            default:
                $valor = $resposta;
                break;
        }

        return $valor;
    }
}
