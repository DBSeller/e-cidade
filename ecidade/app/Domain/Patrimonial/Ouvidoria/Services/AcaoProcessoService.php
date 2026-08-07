<?php

namespace App\Domain\Patrimonial\Ouvidoria\Services;

use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;
use App\Domain\Tributario\ISSQN\Services\AlvaraOnline\AlvaraOnlineService;
use App\Domain\Tributario\ITBI\Services\LancamentoItbiService;

class AcaoProcessoService
{
    /**
     * @var \stdClass
     */
    private $solicitacao;

    /**
     * @var Processo
     */
    private $processo;

    /**
     * @var string
     */
    private $mensagem = "";

    /**
     * @var null|\stdClass
     */
    private $camposAdicionais = null;

    /**
     * @var null|array
     */
    private $aDadosRetorno;

    /**
     * @param \stdClass $solicitacao
     * @return AcaoProcessoService
     */
    public function setSolicitacao($solicitacao)
    {
        $this->solicitacao = $solicitacao;
        return $this;
    }

    /**
     * @param Processo $processo
     * @return AcaoProcessoService
     */
    public function setProcesso($processo)
    {
        $this->processo = $processo;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * @param string $mensagem
     * @return AcaoProcessoService
     */
    private function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    private function setAtributoRetorno($sNome, $value)
    {
        if (empty($this->aDadosRetorno)) {
            $this->aDadosRetorno = [];
        }

        $this->aDadosRetorno[$sNome] = $value;
    }

    public function getDadosRetorno()
    {
        return $this->aDadosRetorno;
    }

    /**
     * @param \stdClass|null|string $camposAdicionais
     * @return AcaoProcessoService
     */
    public function setCamposAdicionais($camposAdicionais)
    {
        if (is_string($camposAdicionais)) {
            $camposAdicionais = json_decode(str_replace("\\", "", $camposAdicionais));
        }

        $this->camposAdicionais = $camposAdicionais;
        return $this;
    }

    /**
     * Trata os dados vindos do processo eletronico para o melhor uso
     * @param $aSecoes
     * @return object
     */
    private function getSecoesAjustadas($aSecoes)
    {
        $secoes = [];

        foreach ($aSecoes->secoes as $secao) {
            if ($secao->tipo == "tabela") {
                foreach ($secao->resposta as $key0 => $resposta) {
                    foreach ((array) $resposta as $key1 => $dadosRespost) {
                        if (is_object($dadosRespost)) {
                            if (isset($dadosRespost->codigo)) {
                                $secao->resposta[$key0]->$key1 = $dadosRespost->codigo;
                            } else {
                                if (isset($dadosRespost->id)) {
                                    $secao->resposta[$key0]->$key1 = $dadosRespost->id;
                                }
                            }
                        }
                    }
                }

                $secoes[$secao->nome] = $secao->resposta;
            } else {
                $campos = [];

                foreach ($secao->campos as $campo) {
                    $resposta = null;

                    if (isset($campo->resposta)) {
                        if (is_object($campo->resposta)) {
                            if (isset($campo->resposta->codigo)) {
                                $resposta = $campo->resposta->codigo;
                            } else {
                                if (isset($campo->resposta->id)) {
                                    $resposta = $campo->resposta->id;
                                }
                            }
                        } else {
                            $resposta = $campo->resposta;
                        }
                    }

                    $campos[$campo->nome] = $resposta;
                }

                $secoes[$secao->nome] = (object) $campos;
            }
        }

        return(object) $secoes;
    }

    /**
     * Executa uma ação com base no método definido no JSON
     */
    public function executa()
    {
        if (!isset($this->solicitacao->metadados->acao)) {
            return;
        }

        $oDados = $this->getSecoesAjustadas($this->solicitacao->metadados);
        $oDados->tipo_processo = $this->solicitacao->metadados->tipo_processo;
        $oDados->descricao = $this->solicitacao->metadados->descricao;
        $oDados->acao = $this->solicitacao->metadados->acao;
        $oDados->departamento = $this->solicitacao->departamento;

        switch (trim($oDados->acao)) {
            case "gerarAlvara":
                $alvaraOnlineService = new AlvaraOnlineService();
                $alvaraOnlineService = $alvaraOnlineService->setProcesso($this->processo)
                    ->setCamposAdicionais($this->camposAdicionais)
                    ->setDados($oDados);

                if (!empty($this->solicitacao->inscricao)) {
                    $alvaraOnlineService->setInscricao($this->solicitacao->inscricao);
                    $alvaraOnlineService->alterar();
                    $this->setMensagem("Alterado a inscrição: {$alvaraOnlineService->getInscricao()}");
                } else {
                    $alvaraOnlineService->gerar();
                    $this->setMensagem("Gerado a inscrição: {$alvaraOnlineService->getInscricao()}");
                }

                $this->setAtributoRetorno("numeroInscricao", $alvaraOnlineService->getInscricao());
                $this->setAtributoRetorno("numeroProcesso", $this->processo->p58_codproc);
                break;
            case "lancarItbi":
                $lancamentoItbiService = new LancamentoItbiService();
                $lancamentoItbiService->setProcesso($this->processo)
                                      ->setDepartamento($oDados->departamento)
                                      ->setSecoes($oDados)
                                      ->lancar();

                $this->setMensagem("Gerado ITBI: {$lancamentoItbiService->getNumeroGuia()}");
                break;
        }
    }
}
