<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson;

use App\Domain\Tributario\Cadastro\Models\Bairro;
use App\Domain\Tributario\Cadastro\Models\Ruas;

class EnderecoMunicipioService
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
        $oSessao->nome = "endereco_municipio";
        $oSessao->tipo = "form";
        $oSessao->label = "Endereco no Municipio";
        $oSessao->campos = [];
        $oSessao->campos[] = $this->buildCampoMatriculaImovel();
        $oSessao->campos[] = $this->buildCampoTelefone();
        $oSessao->campos[] = $this->buildCampoCelular();
        $oSessao->campos[] = $this->buildCampoCep();
        $oSessao->campos[] = $this->buildCampoBairro();
        $oSessao->campos[] = $this->buildCampoLogradouro();
        $oSessao->campos[] = $this->buildCampoNumero();
        $oSessao->campos[] = $this->buildCampoComplemento();
        $oSessao->campos[] = $this->buildCampoZona();

        return $oSessao;
    }

    private function buildCampoMatriculaImovel()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "matricula_imovel";
        $oCampo->tipo = "string";
        $oCampo->label = "Matricula do Imovel";
        $oCampo->resposta = null;

        return $oCampo;
    }

    private function buildCampoTelefone()
    {
        $sTelefone = $this->oDados->dadosRedesim["contato"]["dddTelefone1"];
        $sTelefone .= $this->oDados->dadosRedesim["contato"]["telefone1"];

        $oCampo = new \stdClass();
        $oCampo->nome = "telefone";
        $oCampo->tipo = "string";
        $oCampo->label = "Telefone";
        $oCampo->resposta = $sTelefone;

        return $oCampo;
    }

    private function buildCampoCelular()
    {
        $aContato = $this->oDados->dadosRedesim["contato"];
        $sCelular = "";

        if (array_key_exists("dddTelefone2", $aContato) && array_key_exists("telefone2", $aContato)) {
            $sCelular = $this->oDados->dadosRedesim["contato"]["dddTelefone2"];
            $sCelular .= $this->oDados->dadosRedesim["contato"]["telefone2"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "celular";
        $oCampo->tipo = "string";
        $oCampo->label = "Celular";
        $oCampo->resposta = $sCelular;

        return $oCampo;
    }

    private function buildCampoCep()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "cep";
        $oCampo->tipo = "cep";
        $oCampo->label = "CEP";
        $oCampo->resposta = $this->oDados->dadosRedesim["endereco"]["cep"];

        return $oCampo;
    }

    private function buildCampoBairro()
    {
        $bairro = Bairro::nome($this->oDados->dadosRedesim["endereco"]["bairro"])->first();

        if (!$bairro) {
            throw new \BusinessException("Bairro não encontrado.");
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "bairro";
        $oCampo->tipo = "autocomplete";
        $oCampo->label = "Bairro";
        $oCampo->resposta = (object) ["id" => $bairro->j13_codi, "descricao" => $bairro->j13_descr];

        return $oCampo;
    }

    private function buildCampoLogradouro()
    {
        $logradouro = Ruas::nome($this->oDados->dadosRedesim["endereco"]["logradouro"])->first();

        if (!$logradouro) {
            throw new \BusinessException("Logradouro não encontrado.");
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "logradouro";
        $oCampo->tipo = "autocomplete";
        $oCampo->label = "Logradouro";
        $oCampo->resposta = (object) ["id" => $logradouro->j14_codigo, "descricao"=> $logradouro->j14_nome];

        return $oCampo;
    }

    private function buildCampoNumero()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "numero";
        $oCampo->tipo = "inteiro";
        $oCampo->label = "Numero";
        $oCampo->resposta = $this->oDados->dadosRedesim["endereco"]["numLogradouro"];

        return $oCampo;
    }

    private function buildCampoComplemento()
    {
        $sComplemento = "";

        if (array_key_exists("complemento", $this->oDados->dadosRedesim["endereco"])) {
            $sComplemento = $this->oDados->dadosRedesim["endereco"]["complemento"];
        }

        $oCampo = new \stdClass();
        $oCampo->nome = "complemento";
        $oCampo->tipo = "string";
        $oCampo->label = "Complemento";
        $oCampo->resposta = $sComplemento;

        return $oCampo;
    }

    private function buildCampoZona()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "zona";
        $oCampo->tipo = "lista_dinamica";
        $oCampo->label = "Zonas";
        $oCampo->resposta = (object) ["id" => 1];

        return $oCampo;
    }
}
