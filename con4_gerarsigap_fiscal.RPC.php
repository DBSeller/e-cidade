<?php

use ECidade\Financeiro\Contabilidade\Sigap\ParseXML;
use ECidade\Financeiro\Contabilidade\Sigap\Repository\MeioComunicacaoRespository;
use ECidade\Financeiro\Contabilidade\Sigap\Resource\MeioComunicacaoResource;
use ECidade\Financeiro\Contabilidade\Sigap\Resource\PublicidadeSigapFiscalResource;
use ECidade\Financeiro\Contabilidade\Sigap\Service\PublicidadeSigapFiscalService;
use ECidade\Financeiro\Contabilidade\Sigap\SigapFiscal;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        case 'gerarFiscal':
            if (empty($parametros->periodo)) {
                throw new Exception("Selecione o Período.");
            }
            if (empty($parametros->codigoTCE)) {
                throw new Exception("Informe o Código TCE.");
            }

            $periodo = new Periodo($parametros->periodo);
            $ano = date('Y');
            $instituicoes = InstituicaoRepository::getInstituicoes();
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            if (!in_array($instituicao->getTipo(), [1,2])) {
                throw new Exception("O SIGAP Fiscal só pode ser emitido através da PREFEITURA ou CÂMARA");
            }

            if ($instituicao->getTipo() == 2) {
                $codigoInstituicoes = [$instituicao->getCodigo()];
            } else {
                $codigoInstituicoes = array_filter($instituicoes, function (Instituicao $instituicao) {
                    if ($instituicao->getTipo() != 2) {
                        return $instituicao->getCodigo();
                    }
                });
                $codigoInstituicoes = array_keys($codigoInstituicoes);
            }

            $departamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession('DB_coddepto'));

            if (empty($relatorios)) {
                throw new Exception("Nenhum relatório selecionado.");
            }

            $sigapFiscal = new SigapFiscal($periodo, $departamento, $codigoInstituicoes, $ano, $parametros->codigoTCE);
            $sigapFiscal->processarArquivos($relatorios);
            $arquivoZip = $sigapFiscal->comprimir();

            $retorno->zip = $arquivoZip;
            $retorno->arquivos = $sigapFiscal->getArquivosEmitidos();
            $retorno->mensagem = "Arquivo gerado com sucesso!";
            break;
        case 'validarXML':
            $dados = JSON::create()->parse($parametros->file);
            $parse = new ParseXML($dados->name, $dados->path, $dados->extension);
            $retorno->csv = $parse->dumpToCSV();
            $retorno->csv_nome = $parse->getNome();
            $retorno->linhas = $parse->toArray();
            break;

        case 'buscarMeiosComunicacao':
//            $instituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));

            $repository = new MeioComunicacaoRespository();
//            $repository->scopeUf($instituicao->getUf());
            $repository->scopeUf('RO');
            $meiosComunicao = $repository->get();
            $retorno->meios = MeioComunicacaoResource::toArray($repository->get());
            break;

        case 'salvarPublicidade':
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
            $service = new PublicidadeSigapFiscalService($instituicao);
            $service->salvar($parametros);

            $publicidades = PublicidadeSigapFiscalResource::toArray($service->getPublicidadesPorAno($parametros->ano));
            $retorno->mensagem = "Publicidade salva com sucesso.";
            $retorno->publicidades = $publicidades;
            break;
        case 'buscarPublicidades':
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
            $service = new PublicidadeSigapFiscalService($instituicao);
            $publicidades = PublicidadeSigapFiscalResource::toArray($service->getPublicidadesPorAno($parametros->ano));
            $retorno->publicidades = $publicidades;
            break;
        case 'exclusaoPublicacao':
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
            $service = new PublicidadeSigapFiscalService($instituicao);
            $service->remover($parametros->codigo);

            $retorno->mensagem = "Publicidade removida com sucesso.";
            break;
        case 'validarInstituicao':
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            if (!in_array($instituicao->getTipo(), [1,2])) {
                throw new Exception("O SIGAP Fiscal só pode ser emitido através da PREFEITURA ou CÂMARA");
            }
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
