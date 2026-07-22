<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson;

use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\AtendimentoInclusaoInscricaoJsonService;
use ECidade\V3\Extension\Registry;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Filter\ListagemAtividades as FiltroListagemAtividades;

class AtividadesService
{
    /**
     * @var object
     */
    private $oDados;

    /**
     * @param object $oDados
     */
    public function setDados($oDados)
    {
        $this->oDados = $oDados;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function build()
    {
        $oSessao = new \stdClass();
        $oSessao->nome = "atividades";
        $oSessao->tipo = "tabela";
        $oSessao->label = "Atividades";
        $oSessao->campos = [];
        $oSessao->campos[] = $this->buildCampoAtividade();
        $oSessao->campos[] = $this->buildCampoPrincipal();
        $oSessao->campos[] = $this->buildCampoDataInicio();
        $oSessao->resposta = $this->buildResposta();

        return $oSessao;
    }

    private function buildCampoAtividade()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "atividade";
        $oCampo->tipo = "autocomplete";
        $oCampo->label = "Atividade";

        return $oCampo;
    }

    private function buildCampoPrincipal()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "principal";
        $oCampo->tipo = "lista";
        $oCampo->label = "Principal";

        return $oCampo;
    }

    private function buildCampoDataInicio()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "data_inicio";
        $oCampo->tipo = "data";
        $oCampo->label = "Data de Inicio";

        return $oCampo;
    }

    private function buildResposta()
    {
        $sDataInicio = AtendimentoInclusaoInscricaoJsonService::data(
            $this->oDados->dadosRedesim["dataInicioAtividade"],
            true
        );

        $aAtividades = $this->oDados->dadosRedesim["atividadesEconomica"];
        $aResposta = [];

        $oAtividade = $this->buscarAtividadeCnae($aAtividades["cnaeFiscal"]["codigo"]);

        $aResposta[] = (object) [
            "codigo" => 1,
            "atividade" => (object) [
                "id" => $oAtividade->sequencial,
                "descricao" => ""
            ],
            "principal" => (object) ["codigo" => 1, "descricao" => "Sim"],
            "data_inicio" => $sDataInicio
        ];

        if (!array_key_exists("cnaesSecundarias", $aAtividades)
                ||
            !array_key_exists("cnaeSecundaria", $aAtividades["cnaesSecundarias"])
        ) {
            return $aResposta;
        }

        foreach ($aAtividades["cnaesSecundarias"]["cnaeSecundaria"] as $aAtividade) {
            $oAtividade = $this->buscarAtividadeCnae($aAtividade["codigo"]);

            $aResposta[] = (object) [
                "codigo" => end($aResposta)->codigo + 1,
                "atividade" => (object) [
                    "id" => $oAtividade->sequencial,
                    "descricao" => ""
                ],
                "principal" => (object) ["codigo" => 2, "descricao" => "Não"],
                "data_inicio" => $sDataInicio
            ];
        }

        return $aResposta;
    }

    private function buscarAtividadeCnae($sCnae)
    {
        $container = Registry::get('app.container')->get('tributario.container');
        $repositoryAtividades = $container->get('Inscricao\Atividades\Repository\Atividades');
        $filtroListagemAtividades = new FiltroListagemAtividades();

        $filtroListagemAtividades->setEstruturalCnae($sCnae);
        $aAtivid = $repositoryAtividades->listarAtividades($filtroListagemAtividades);

        if (count($aAtivid) == 0) {
            throw new \BusinessException("Atividade não encontrada. [CNAE: {$sCnae}]");
        }

        return $aAtivid[0];
    }
}
