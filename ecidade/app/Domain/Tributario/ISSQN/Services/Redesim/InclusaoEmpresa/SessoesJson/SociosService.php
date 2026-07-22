<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson;

use App\Domain\Configuracao\Endereco\Model\Municipio;
use App\Domain\Configuracao\Endereco\Model\Pais;
use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\AtendimentoInclusaoInscricaoJsonService;

class SociosService
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
        $oSessao->nome = "socios";
        $oSessao->tipo = "tabela";
        $oSessao->label = "Sócios";
        $oSessao->campos = [];
        $oSessao->campos[] = $this->buildCampoCpf();
        $oSessao->campos[] = $this->buildCampoNome();
        $oSessao->campos[] = $this->buildCampoTipoSocio();
        $oSessao->campos[] = $this->buildCampoValorCapital();
        $oSessao->campos[] = $this->buildCampoNascimento();
        $oSessao->campos[] = $this->buildCampoSexo();
        $oSessao->campos[] = $this->buildCampoEstadoCivil();
        $oSessao->campos[] = $this->buildCampoNacionalidade();
        $oSessao->campos[] = $this->buildCampoTelefone();
        $oSessao->campos[] = $this->buildCampoCelular();
        $oSessao->campos[] = $this->buildCampoCep();
        $oSessao->campos[] = $this->buildCampoPais();
        $oSessao->campos[] = $this->buildCampoEstado();
        $oSessao->campos[] = $this->buildCampoMunicipio();
        $oSessao->campos[] = $this->buildCampoBairro();
        $oSessao->campos[] = $this->buildCampoLogradouro();
        $oSessao->campos[] = $this->buildCampoNumero();
        $oSessao->campos[] = $this->buildCampoComplemento();
        $oSessao->campos[] = $this->buildCampoQualificacao();
        $oSessao->campos[] = $this->buildCampoEmail();
        $oSessao->campos[] = $this->buildNomeMae();
        $oSessao->resposta = $this->buildResposta();

        return $oSessao;
    }

    private function buildCampoCpf()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "cpf";
        $oCampo->tipo = "cpf";
        $oCampo->label = "CPF";

        return $oCampo;
    }

    private function buildCampoNome()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "nome";
        $oCampo->tipo = "string";
        $oCampo->label = "Nome";

        return $oCampo;
    }

    private function buildCampoTipoSocio()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "tipo_socio";
        $oCampo->tipo = "lista";
        $oCampo->label = "Tipo de Sócio";

        return $oCampo;
    }

    private function buildCampoValorCapital()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "valor_capital";
        $oCampo->tipo = "string";
        $oCampo->label = "Valor do Capital Social do Sócio";

        return $oCampo;
    }

    private function buildCampoNascimento()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "nascimento";
        $oCampo->tipo = "data";
        $oCampo->label = "Nascimento";

        return $oCampo;
    }

    private function buildCampoSexo()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "sexo";
        $oCampo->tipo = "lista";
        $oCampo->label = "Sexo";

        return $oCampo;
    }

    private function buildCampoEstadoCivil()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "estado_civil";
        $oCampo->tipo = "lista";
        $oCampo->label = "Estado Cívil";

        return $oCampo;
    }

    private function buildCampoNacionalidade()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "nacionalidade";
        $oCampo->tipo = "lista";
        $oCampo->label = "Nacionalidade";

        return $oCampo;
    }

    private function buildCampoTelefone()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "telefone";
        $oCampo->tipo = "string";
        $oCampo->label = "Telefone";

        return $oCampo;
    }

    private function buildCampoCelular()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "celular";
        $oCampo->tipo = "string";
        $oCampo->label = "Celular";

        return $oCampo;
    }

    private function buildCampoCep()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "cep";
        $oCampo->tipo = "string";
        $oCampo->label = "CEP";

        return $oCampo;
    }

    private function buildCampoPais()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "pais";
        $oCampo->tipo = "texto";
        $oCampo->label = "Pais";

        return $oCampo;
    }

    private function buildCampoEstado()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "estado";
        $oCampo->tipo = "texto";
        $oCampo->label = "Estado";

        return $oCampo;
    }

    private function buildCampoMunicipio()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "municipio";
        $oCampo->tipo = "texto";
        $oCampo->label = "Municipio";

        return $oCampo;
    }

    private function buildCampoBairro()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "bairro";
        $oCampo->tipo = "texto";
        $oCampo->label = "Bairro";

        return $oCampo;
    }

    private function buildCampoLogradouro()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "logradouro";
        $oCampo->tipo = "texto";
        $oCampo->label = "Logradouro";

        return $oCampo;
    }

    private function buildCampoNumero()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "numero";
        $oCampo->tipo = "inteiro";
        $oCampo->label = "Numero";

        return $oCampo;
    }

    private function buildCampoComplemento()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "complemento";
        $oCampo->tipo = "string";
        $oCampo->label = "Complemento";

        return $oCampo;
    }

    private function buildCampoQualificacao()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "qualificacao";
        $oCampo->tipo = "inteiro";
        $oCampo->label = "Qualificação";

        return $oCampo;
    }

    private function buildCampoEmail()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "email";
        $oCampo->tipo = "string";
        $oCampo->label = "E-mail";

        return $oCampo;
    }

    private function buildNomeMae()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "nomeMae";
        $oCampo->tipo = "string";
        $oCampo->label = "Nome da Mãe";

        return $oCampo;
    }

    private function buildResposta()
    {
        $aResposta = [];

        foreach ($this->oDados->dadosRedesim["socios"]["socio"] as $key => $aSocio) {
            $sDataNascimento = AtendimentoInclusaoInscricaoJsonService::data($aSocio["dataNascimento"], true);
            $pais = Pais::query()->where("db70_codigoreceita", $aSocio["enderecoSocio"]["codPais"])->first();
            $municipio = Municipio::sistemaExterno(20, $aSocio["enderecoSocio"]["codMunicipio"])->first();

            $iQualificacao = null;
            if (isset($aSocio["codQualificacaoSocio"]) && !empty($aSocio["codQualificacaoSocio"])) {
                $clQualificacaoSocio = new \cl_qualificacaosocio();
                $rQualificacaoSocio = $clQualificacaoSocio->sql_record(
                    $clQualificacaoSocio->sql_query_file(
                        null,
                        "q180_sequencial",
                        null,
                        "q180_codigo = {$aSocio["codQualificacaoSocio"]}"
                    )
                );

                if ($rQualificacaoSocio) {
                    $oQualificacaoSocio = \db_utils::fieldsMemory($rQualificacaoSocio, 0);

                    $iQualificacao = $oQualificacaoSocio->q180_sequencial;
                }
            }

            $aSexo = ["codigo" => "M", "descricao" => "Masculino"];
            if ($aSocio["sexo"] == "F") {
                $aSexo = ["codigo" => "F", "descricao" => "Feminino"];
            }

            $sTelefone = "";
            $sCelular = "";
            $sEmail = "";

            if (isset($aSocio["contatoSocio"])) {
                $sTelefone = $aSocio["contatoSocio"]["dddTelefone1"].$aSocio["contatoSocio"]["telefone1"];

                if (array_key_exists("dddTelefone2", $aSocio["contatoSocio"])
                        &&
                    array_key_exists("telefone2", $aSocio["contatoSocio"])
                ) {
                    $sCelular = $aSocio["contatoSocio"]["dddTelefone2"].$aSocio["contatoSocio"]["telefone2"];
                }

                if (isset($aSocio["contatoSocio"]["correioEletronico"])) {
                    $sEmail = $aSocio["contatoSocio"]["correioEletronico"];
                }
            }

            $sComplemento = "";

            if (array_key_exists("complemento", $aSocio["enderecoSocio"])) {
                $sComplemento = $aSocio["enderecoSocio"]["complemento"];
            }

            $sNomeMae = "";

            if (array_key_exists("nomeMae", $aSocio)) {
                $sNomeMae = $aSocio["nomeMae"];
            }
            $valorCapital = empty($aSocio["capitalSocialSocio"])?"0.00":number_format(
                $aSocio["capitalSocialSocio"] / 100,
                2,
                '.',
                ''
            );
            $aResposta[] = (object) [
                "codigo" => $key + 1,
                "cpf" => $aSocio["cnpjCpfSocio"],
                "nome" => $aSocio["nome"],
                "tipo_socio" => (object) ["codigo" => "1", "descricao" => "Sócio"],
                "valor_capital" => $valorCapital,
                "nascimento" => $sDataNascimento,
                "sexo" => (object) $aSexo,
                "estado_civil" => (object) $this->getEstadoCivil($aSocio["estadoCivil"]),
                "nacionalidade" => (object) ["codigo" => 1, "descricao" => "Brasileiro"],
                "telefone" => $sTelefone,
                "celular" => $sCelular,
                "cep" => $aSocio["enderecoSocio"]["cep"],
                "pais" => $pais->db70_descricao,
                "estado" => $aSocio["enderecoSocio"]["uf"],
                "municipio" => $municipio->db72_descricao,
                "bairro" => $aSocio["enderecoSocio"]["bairro"],
                "logradouro" => $aSocio["enderecoSocio"]["logradouro"],
                "numero" => $aSocio["enderecoSocio"]["numLogradouro"],
                "complemento" => $sComplemento,
                "qualificacao" => $iQualificacao,
                "email" => $sEmail,
                "nomeMae" => $sNomeMae
            ];
        }

        return $aResposta;
    }

    private function getEstadoCivil($codigoEstadoCivil)
    {
        switch ($codigoEstadoCivil) {
            case 1:
                return ["codigo" => 1, "descricao" => "Solteiro"];
            case 2:
                return ["codigo" => 2, "descricao" => "Casado"];
            case 3:
                return ["codigo" => 3, "descricao" => "Viúvo"];
            case 4:
                return ["codigo" => 6, "descricao" => "Separado Judicial"];
            case 5:
                return ["codigo" => 4, "descricao" => "Divorciado"];
            case 6:
                return ["codigo" => 7, "descricao" => "União Estavel"];
        }
    }
}
