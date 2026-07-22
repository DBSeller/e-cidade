<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson;

use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\AtendimentoInclusaoInscricaoJsonService;

class DadosEmpresaService
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
        $oSessao->nome = "dados_empresa";
        $oSessao->tipo = "form";
        $oSessao->label = "Dados da Empresa";
        $oSessao->campos = [];
        $oSessao->campos[] = $this->buildCampoTipoEmpresa();
        $oSessao->campos[] = $this->buildCampoCnpj();
        $oSessao->campos[] = $this->buildCampoRazaoSocial();
        $oSessao->campos[] = $this->buildCampoNomeFantasia();
        $oSessao->campos[] = $this->buildCampoDataJuntaComercial();
        $oSessao->campos[] = $this->buildCampoRegistroJunta();
        $oSessao->campos[] = $this->buildCampoProtocoloJunta();
        $oSessao->campos[] = $this->buildCampoEmailEmpresa();
        $oSessao->campos[] = $this->buildCampoTelefoneEmpresa();
        $oSessao->campos[] = $this->buildCampoCelularEmpresa();
        $oSessao->campos[] = $this->buildCampoInscricaoEstadual();
        $oSessao->campos[] = $this->buildCampoOptanteSimples();
        $oSessao->campos[] = $this->buildCampoCategoriaSimples();
        $oSessao->campos[] = $this->buildCampoDataOpcaoSimples();
//        $oSessao->campos[] = $this->buildCampoPorte(); // Analisar a necessidade deste campo
        $oSessao->campos[] = $this->buildCampoArea();
        $oSessao->campos[] = $this->buildCampoEmpregados();

        return $oSessao;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoTipoEmpresa()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "tipo_empresa";
        $oCampo->tipo = "lista";
        $oCampo->label = "Tipo de Empresa";
        $oCampo->resposta = (object) [
            "codigo" => $this->oDados->dadosRedesim["codNaturezaJuridica"]
        ];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoCnpj()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "cnpj";
        $oCampo->tipo = "cnpj";
        $oCampo->label = "CNPJ";
        $oCampo->resposta = $this->oDados->dadosRedesim["cnpj"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoRazaoSocial()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "razao_social";
        $oCampo->tipo = "string";
        $oCampo->label = "Nome / Razão Social";
        $oCampo->resposta = $this->oDados->dadosRedesim["nomeEmpresarial"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoNomeFantasia()
    {
        $sNomeFantasia = "";

        if (array_key_exists("nomeFantasia", $this->oDados->dadosRedesim)) {
            $sNomeFantasia = $this->oDados->dadosRedesim["nomeFantasia"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "nome_fantasia";
        $oCampo->tipo = "string";
        $oCampo->label = "Nome Fantasia";
        $oCampo->resposta = $sNomeFantasia;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoDataJuntaComercial()
    {
        $iDataRegistro = null;

        if (isset($this->oDados->atoAprovado["dataRegistro"])) {
            $iDataRegistro = $this->oDados->atoAprovado["dataRegistro"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "data_junta_comercial";
        $oCampo->tipo = "data";
        $oCampo->label = "Data Junta Comercial";
        $oCampo->resposta = AtendimentoInclusaoInscricaoJsonService::data($iDataRegistro);

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoRegistroJunta()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "registro_junta";
        $oCampo->tipo = "string";
        $oCampo->label = "Registro Junta Comercial";
        $oCampo->resposta = $this->oDados->atoAprovado["numeroProcessoOrgaoRegistro"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoProtocoloJunta()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "protocolo_junta";
        $oCampo->tipo = "string";
        $oCampo->label = "Protocolo Junta Comercial";
        $oCampo->resposta = $this->oDados->dadosRedesim["numeroOrgaoRegistro"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoEmailEmpresa()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "emailempresa";
        $oCampo->tipo = "email";
        $oCampo->label = "Email";
        $oCampo->resposta = $this->oDados->dadosRedesim["contato"]["correioEletronico"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoTelefoneEmpresa()
    {
        $sTelefone = $this->oDados->dadosRedesim["contato"]["dddTelefone1"];
        $sTelefone .= $this->oDados->dadosRedesim["contato"]["telefone1"];

        $oCampo = new \stdClass();
        $oCampo->nome = "telefoneempresa";
        $oCampo->tipo = "string";
        $oCampo->label = "Telefone";
        $oCampo->resposta = $sTelefone;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoCelularEmpresa()
    {
        $aContato = $this->oDados->dadosRedesim["contato"];
        $sCelular = "";

        if (array_key_exists("dddTelefone2", $aContato) && array_key_exists("telefone2", $aContato)) {
            $sCelular = $this->oDados->dadosRedesim["contato"]["dddTelefone2"];
            $sCelular .= $this->oDados->dadosRedesim["contato"]["telefone2"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "celularempresa";
        $oCampo->tipo = "string";
        $oCampo->label = "Celular";
        $oCampo->resposta = $sCelular;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoInscricaoEstadual()
    {
        $iInscricaoEstadual = "";

        if (isset($this->oDados->dadosRedesim["nuInscricaoEstadual"])) {
            $iInscricaoEstadual = $this->oDados->dadosRedesim["nuInscricaoEstadual"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "inscricao_estadual";
        $oCampo->tipo = "string";
        $oCampo->label = "Inscrição Estadual";
        $oCampo->resposta = $iInscricaoEstadual;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoOptanteSimples()
    {
        $aOptanteSimples = ["codigo" => 2, "descricao" => "Não"];

        if ($this->oDados->dadosRedesim["opcaoSimplesNacional"] == "S") {
            $aOptanteSimples = ["codigo" => 1, "descricao" => "Sim"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "optante_simples";
        $oCampo->tipo = "lista";
        $oCampo->label = "Optante pelo Simples";
        $oCampo->resposta = (object) $aOptanteSimples;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoCategoriaSimples()
    {
        switch ($this->oDados->dadosRedesim["porte"]) {
            case "ME":
                $aCategoriaSimples = ["codigo" => 1, "descricao" => "Micro Empresa"];
                break;
            case "EPP":
                $aCategoriaSimples = ["codigo" => 2, "descricao" => "Empresa de pequeno porte"];
                break;
            default:
                $aCategoriaSimples = ["codigo" => 5, "descricao" => "Demais"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "categoria_simples";
        $oCampo->tipo = "lista";
        $oCampo->label = "Categoria Simples";
        $oCampo->resposta = (object) $aCategoriaSimples;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoDataOpcaoSimples()
    {
        $sData = "";

        if (array_key_exists("periodosSimplesNacional", $this->oDados->dadosRedesim)
                &&
            array_key_exists("periodo", $this->oDados->dadosRedesim["periodosSimplesNacional"])
        ) {
            $iData = $this->oDados->dadosRedesim["periodosSimplesNacional"]["periodo"][0]["dataInclusao"];
            $sData = AtendimentoInclusaoInscricaoJsonService::data($iData);
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "data_opcao_simples";
        $oCampo->tipo = "data";
        $oCampo->label = "Data de Opção pelo Simples";
        $oCampo->resposta = $sData;

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoPorte()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "porte";
        $oCampo->tipo = "lista_dinamica";
        $oCampo->label = "Porte";
        $oCampo->resposta = (object) ["codigo" => 1, "descricao" => "PEQUENO"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoArea()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "area";
        $oCampo->tipo = "string";
        $oCampo->label = "Área (Construída)";
        $oCampo->resposta = $this->oDados->dadosRedesim["areaTotalEdificacao"];

        return $oCampo;
    }

    /**
     * @return \stdClass
     */
    private function buildCampoEmpregados()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "empregados";
        $oCampo->tipo = "inteiro";
        $oCampo->label = "Empregados";
        $oCampo->resposta = "0";

        return $oCampo;
    }
}
