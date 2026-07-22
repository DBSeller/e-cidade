<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Helper;

use \InstituicaoRepository;
use ECidade\Tributario\Issqn\ParametrosProcessoEletronicoBag;
use ECidade\Patrimonial\Protocolo\Servicos\InclusaoCgmLegacy;

class ProcessoEletronicoHelper
{
    const
        ACAO_ALVARA_AUTONOMO = 'ALVARA_AUTONOMO',
        ACAO_ALVARA_EMPRESA = 'ALVARA_EMPRESA',
        ACAO_ALVARA_MEI = 'ALVARA_MEI',
        CLASSIFICACAO_RISCO = array(
            "B" => 1,
            "M" => 2,
            "A" => 3
        );

    private function __construct()
    {
    }

    //Função que verifica se variavel é objeto ou valor, se for objeto retorna o codigo se nao retorna o valor
    public static function getValueJson($var)
    {
        if (is_object($var)) {
            if (isset($var->codigo)) {
                return $var->codigo;
            } elseif (isset($var->value)) {
                return $var->value;
            }
        }
        return $var;
    }

    public static function processaCgmsByDados(InclusaoCgmLegacy $inclusaoCgmService, $dados, $acao)
    {
        $cgms = array();
        $dadosMunicipio = self::getDadosMunicipio("munic, uf");

        switch ($acao) {
            case 'ALVARA_EMPRESA':
                $dados->empresa->endereco->estado->value = $dadosMunicipio["uf"];
                $dados->empresa->endereco->municipio->value = $dadosMunicipio["munic"];
//                $cgms['cgmRequerente'] = $inclusaoCgmService->processaDadosCgm($dados->requerente);
                $cgms['cgmEmpresa'] = $inclusaoCgmService->processaDadosCgm($dados->empresa);
                $cgms['cgmSocios'] = array();
                foreach ($dados->empresa->socios as $key => $socio) {
                    $cgms['cgmSocios'][$key] = $inclusaoCgmService->processaDadosCgm($socio);
                }

                break;

            case 'ALVARA_MEI':
                $dados->empresa->endereco->municipio->value = $dadosMunicipio["munic"];
                $dados->empresa->endereco->estado->value = $dadosMunicipio["uf"];
//                $cgms['cgmRequerente'] = $inclusaoCgmService->processaDadosCgm($dados->requerente);
                $cgms['cgmEmpresa'] = $inclusaoCgmService->processaDadosCgm($dados->empresa);

                if (array_key_exists('responsavel_mei', $dados->empresa)
                    && ! is_null($dados->empresa->responsavel_mei)
                ) {
//                    $cgms['cgmResponsavel'] = $inclusaoCgmService->processaDadosCgm($dados->empresa->responsavel_mei);

                    $cgms['cgmResponsavel'] = array();

                    foreach ($dados->empresa->responsavel_mei as $key => $responsavel) {
                        $cgms['cgmResponsavel'][$key] = $inclusaoCgmService->processaDadosCgm($responsavel);
                    }
                } else {
                    $cgms['cgmResponsavel'][0] = $inclusaoCgmService->processaDadosCgm($dados->requerente);
                }

                break;

            case 'ALVARA_AUTONOMO':
//                $cgms['cgmRequerente'] = $inclusaoCgmService->processaDadosCgm($dados->requerente);

                if (array_key_exists('responsavel', $dados)
                    && ! is_null($dados->responsavel)
                    && ! is_null(self::getValueJson($dados->responsavel->cpf->value))
                ) {
                    $dados->endereco_municipio->municipio->value = $dadosMunicipio["munic"];
                    $dados->endereco_municipio->estado->value = $dadosMunicipio["uf"];
                    $dados->responsavel->endereco = $dados->endereco_municipio;
                    $cgms['cgmEmpresa'] = $inclusaoCgmService->processaDadosCgm($dados->responsavel);
                } else {
                    $cgms['cgmEmpresa'] = $inclusaoCgmService->processaDadosCgm($dados->requerente);
                }

                break;

            case 'REJEITAR':
            default:
                break;
        }

        if (isset($dados->outros_dados)) {
            $outrosDados = $dados->outros_dados;
        } else {
            $outrosDados = $dados->empresa->outros_dados;
        }

        if (array_key_exists('escritorio_contabil', $outrosDados)
            && self::getValueJson($outrosDados->escritorio_contabil->value) != null
        ) {
            $cgms['cgmEscritorio'] = \CgmFactory::getInstanceByCgm(self::getValueJson(
                $outrosDados->escritorio_contabil->value
            ));
        }

        if ($acao == 'REJEITAR') {
            $cgms['cgmProcesso'] = InstituicaoRepository::getInstituicaoPrefeitura()->getCgm();
        } else {
            $cgms['cgmProcesso'] = $cgms['cgmEmpresa'];
        }

        return $cgms;
    }

    public static function atualizaCgmsEmpresaByDados(InclusaoCgmLegacy $inclusaoCgmService, $dados, $acao)
    {
        $cgm = null;
        $dadosMunicipio = self::getDadosMunicipio("munic, uf");

        switch ($acao) {
            case self::ACAO_ALVARA_EMPRESA:
                $dados->empresa->endereco->estado->value = $dadosMunicipio["uf"];
                $dados->empresa->endereco->municipio->value = $dadosMunicipio["munic"];
                $cgm = $inclusaoCgmService->consultarDadosCgm($dados->empresa);
                $cgm = $inclusaoCgmService->atualizarCgm($dados->empresa, $cgm);
                break;

            case self::ACAO_ALVARA_MEI:
                $dados->empresa->endereco->municipio->value = $dadosMunicipio["munic"];
                $dados->empresa->endereco->estado->value = $dadosMunicipio["uf"];
                $cgm = $inclusaoCgmService->consultarDadosCgm($dados->empresa);
                $cgm = $inclusaoCgmService->atualizarCgm($dados->empresa, $cgm);
                break;

            case self::ACAO_ALVARA_AUTONOMO:
                if (array_key_exists('responsavel', $dados)
                    && !is_null($dados->responsavel)
                    && !is_null(self::getValueJson($dados->responsavel->cpf->value))
                ) {
                    $dados->endereco_municipio->municipio->value = $dadosMunicipio["munic"];
                    $dados->endereco_municipio->estado->value = $dadosMunicipio["uf"];
                    $dados->responsavel->endereco = $dados->endereco_municipio;
                    $cgm = $inclusaoCgmService->consultarDadosCgm($dados->responsavel);
                    $cgm = $inclusaoCgmService->atualizarCgm($dados->responsavel, $cgm);
                } else {
                    $cgm = $inclusaoCgmService->consultarDadosCgm($dados->requerente);
                    $cgm = $inclusaoCgmService->atualizarCgm($dados->requerente, $cgm);
                }
                break;
            case 'REJEITAR':
            default:
                break;
        }

        return $cgm;
    }

    public static function consultarCgmsByDados($inclusaoCgmService, $dados, $acao)
    {
        $cgms = array();
        $dadosMunicipio = self::getDadosMunicipio("munic, uf");

        switch ($acao) {
            case 'ALVARA_EMPRESA':
                $dados->empresa->endereco->estado->value = $dadosMunicipio["uf"];
                $dados->empresa->endereco->municipio->value = $dadosMunicipio["munic"];
                $cgms['cgmEmpresa'] = $inclusaoCgmService->consultarDadosCgm($dados->empresa);
                $cgms['cgmSocios'] = array();
                foreach ($dados->empresa->socios as $key => $socio) {
                    $cgms['cgmSocios'][$key] = $inclusaoCgmService->consultarDadosCgm($socio);
                }

                break;

            case 'ALVARA_MEI':
                $dados->empresa->endereco->municipio->value = $dadosMunicipio["munic"];
                $dados->empresa->endereco->estado->value = $dadosMunicipio["uf"];
                $cgms['cgmEmpresa'] = $inclusaoCgmService->consultarDadosCgm($dados->empresa);

                if (array_key_exists('responsavel_mei', $dados->empresa)
                    && ! is_null($dados->empresa->responsavel_mei)
                ) {
                    $cgms['cgmResponsavel'] = array();
                    foreach ($dados->empresa->responsavel_mei as $key => $responsavel) {
                        $cgms['cgmResponsavel'][$key] = $inclusaoCgmService->consultarDadosCgm($responsavel);
                    }
                } else {
                    $cgms['cgmResponsavel'][0] = $inclusaoCgmService->consultarDadosCgm($dados->requerente);
                }

                break;

            case 'ALVARA_AUTONOMO':
                if (array_key_exists('responsavel', $dados)
                    && ! is_null($dados->responsavel)
                    && ! is_null(self::getValueJson($dados->responsavel->cpf->value))
                ) {
                    $dados->endereco_municipio->municipio->value = $dadosMunicipio["munic"];
                    $dados->endereco_municipio->estado->value = $dadosMunicipio["uf"];
                    $dados->responsavel->endereco = $dados->endereco_municipio;
                    $cgms['cgmEmpresa'] = $inclusaoCgmService->consultarDadosCgm($dados->responsavel);
                } else {
                    $cgms['cgmEmpresa'] = $inclusaoCgmService->consultarDadosCgm($dados->requerente);
                }

                break;

            case 'REJEITAR':
            default:
                break;
        }

        if (isset($dados->outros_dados)) {
            $outrosDados = $dados->outros_dados;
        } else {
            $outrosDados = $dados->empresa->outros_dados;
        }

        if (array_key_exists('escritorio_contabil', $outrosDados)
            && self::getValueJson($outrosDados->escritorio_contabil->value) != null
        ) {
            $cgms['cgmEscritorio'] = \CgmFactory::getInstanceByCgm(self::getValueJson(
                $outrosDados->escritorio_contabil->value
            ));
        }

        if ($acao == 'REJEITAR') {
            $cgms['cgmProcesso'] = InstituicaoRepository::getInstituicaoPrefeitura()->getCgm();
        } else {
            $cgms['cgmProcesso'] = $cgms['cgmEmpresa'];
        }

        return $cgms;
    }

    public static function andamentoProcesso(
        \processoProtocolo $oProcesso,
        $despacho,
        $departamentoOrigem,
        $departamentoDestino
    ) {
        $iCodTransferencia = $oProcesso->transferir(
            $departamentoDestino,
            '0',
            $departamentoOrigem,
            db_getsession('DB_id_usuario')
        );

        $iCodRecebimento   = $oProcesso->receber(
            $iCodTransferencia,
            $departamentoDestino,
            db_getsession('DB_id_usuario'),
            $despacho
        );
    }

    public static function getClassificacaoGrauRiscoByDados(
        $dados
    ) {
        $classificacaoGrau = null;

        if (array_key_exists('atividades', $dados)) {
            $atividadePrincipal = $dados->atividades;
            if (array_key_exists('atividades_secundarias', $dados->atividades)) {
                $atividadesSecundarias = $dados->atividades->atividades_secundarias;
            }
        } elseif (array_key_exists('atividades', $dados->empresa)) {
            $atividadePrincipal = $dados->empresa->atividades;
            if (array_key_exists('atividades_secundarias', $dados->empresa->atividades)) {
                $atividadesSecundarias = $dados->empresa->atividades->atividades_secundarias;
            }
        } else {
            throw new Exception("Atividades não encontradas");
        }

        $classificacaoGrau = self::CLASSIFICACAO_RISCO[$atividadePrincipal->atividade->risco];

        foreach ($atividadesSecundatias as $key => $atividade) {
            if (self::CLASSIFICACAO_RISCO[$atividade->atividade->risco] > $classificacaoGrau) {
                $classificacaoGrau = self::CLASSIFICACAO_RISCO[$atividade->atividade->risco];
            }
        }

        return $classificacaoGrau;
    }

    public static function getClassificacaoGrauRisco(
        $grauRisco
    ) {
        return self::CLASSIFICACAO_RISCO[$grauRisco];
    }

    public static function getTipoAlvara(
        ParametrosProcessoEletronicoBag $parameterBag,
        $classificacaoGrau
    ) {
        switch ($classificacaoGrau) {
            case 1:
                return $parameterBag->getAlvaraBaixoRisco();
                break;

            case 2:
                return $parameterBag->getAlvaraMedioRisco();
                break;

            case 3:
                return $parameterBag->getAlvaraAltoRisco();
                break;

            default:
                throw new Exception("Classificação de grau de risco inválidada");
                break;
        }
    }

    public static function getDadosMunicipio(
        $campos
    ) {
        return pg_fetch_array(db_query("select $campos from db_config where prefeitura is true"));
    }
}
