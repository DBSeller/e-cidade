<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Builder;

use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\Devedor;
use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\AutoInfracao;

/**
 * Class EnvioRemessaBuilder
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Builder
 */
class EnvioRemessaBuilder
{

    /**
     * @var PAIS_DEFAULT string
     */
    const  PAIS_DEFAULT = "BR";

    /**
     * @param $oDocument
     * @param array $data
     */
    public static function createDadosBasicos($oDocument,  array $data) 
    {
     
        $oDocument->data_envio = $data['sDataGeracaoEnvio']; 
        $oDocument->numero_processo = "";  
        $oDocument->numero = "00000000000000000000";  
        $oDocument->competencia = "11";
        $oDocument->classe_processual = "1116";;
        $oDocument->codigo_processo_eletronico = $data['codigo_processo_eletronico'];

        $oDocument->codigo_localidade = $data['localidade'];
        $oDocument->intervencao_mp   = false;
        $oDocument->tamanho_processo = 0;
        $oDocument->magistrado_atuante = "";
        $oDocument->prioridade  = "";
        $oDocument->valor_causa = trim(str_replace(".", "", db_formatar($data['valortotalinicial'], "f")));
        $oDocument->orgao_julgador = "";
        $oDocument->outrosnumeros  = "";
        $oDocument->nivelSigilo    = "0";
    }

    /**
     * @param $oDocument
     * @param array $data
     * @param $nValorUFIR
     */
    public static function createCertidoes($oDocument,  array $data, $nValorUFIR) 
    {   
        $oDocument->certidoes  = array();
        foreach ($data as $oCertidao) {

            $dadosCertidao = new \stdClass();
            
            $dadosCertidao->numero_certidao = $oCertidao->getNumeroCertidao();
            $dadosCertidao->ano_exercicio   = $oCertidao->getAnoExercicio();
            $dadosCertidao->moeda_divida    = $oCertidao->getMoedaDivida();
            $dadosCertidao->valor_divida    = trim((str_replace(".", "", db_formatar($oCertidao->getValorDivida() ,'f'))));
            $dadosCertidao->natureza_divida = $oCertidao->getNaturezaDivida();
            $dadosCertidao->base_legal      = $oCertidao->getBaseLegal();
            $ufir_divida                    = str_replace(".", "", db_formatar(($oCertidao->getValorDivida() / $nValorUFIR), "f", 0, 4, "e", 4)); 
            $dadosCertidao->ufir_divida     = $ufir_divida;
            
            $oDocument->certidoes[] = $dadosCertidao;
        }
    }

    /**
     * @param $oDocument
     * @param \Instituicao $oInstituicao
     */
    public static function createPoloAtivo($oDocument, \Instituicao $oInstituicao)
    {

        $oDocument->polo_at        = "AT";
        $oDocument->nome_at        = $oInstituicao->getDescricao();
        $oDocument->cpf_at         = preg_replace("[./-]", "", trim($oInstituicao->getCNPJ()));
        $oDocument->tipo_pessoa_at = "juridica";
        $oDocument->cep_at         = substr($oInstituicao->getCep(), 0, 8);
        $oDocument->logradouro_at  = substr($oInstituicao->getLogradouro(), 0, 60);
        $oDocument->numero_end_at  = substr($oInstituicao->getNumero(), 0, 10);
        $oDocument->complemento_at = "";
        $oDocument->bairro_at      = substr($oInstituicao->getBairro(), 0, 40);
        $oDocument->munic_at       = substr($oInstituicao->getMunicipio(), 0, 40);
        $oDocument->uf_at          = substr($oInstituicao->getUf(), 0, 2);
        $oDocument->pais_at        = self::PAIS_DEFAULT;
   
    }


    /**
     * @param $oDocument
     * @param Devedor $oDevedor
     */
    public static function createPoloPassivo($oDocument, Devedor $oDevedor)
    {

        $oDocument->polo = "PA"; 
        $oDocument->nome = str_pad($oDevedor->getNome(), 60, " ", STR_PAD_RIGHT);
        $oDocument->cpf = preg_replace("[./-]", "", trim($oDevedor->getCgccpf()));
        $oDocument->sexo = strtoupper($oDevedor->getGenero());
        $oDocument->nome_genitor = str_pad(substr(trim($oDevedor->getPai()), 0, 40), 60, " ",
            STR_PAD_RIGHT);
        $oDocument->nome_genitora = str_pad(substr(trim($oDevedor->getMae()), 0, 40), 60, " ",
            STR_PAD_RIGHT);
        $oDocument->data_nascimento = $oDevedor->getDataNascimento();
        $oDocument->tipo_pessoa = $oDevedor->getTipoPessoa();
        $oDocument->cidade_natural = substr($oDevedor->getNaturalidade(), 0, 100);

        $oDocument->cep = substr($oDevedor->getCep(), 0, 8);
        $oDocument->logradouro = substr($oDevedor->getEndereco(), 0, 60);
        $oDocument->numero_end = substr($oDevedor->getNumero(), 0, 10);
        $oDocument->complemento = substr($oDevedor->getComplemento(), 0, 40);
        $oDocument->bairro = substr($oDevedor->getBairro(), 0, 40);
        $oDocument->munic = substr($oDevedor->getMunicipio(), 0, 40);
        $oDocument->uf = substr($oDevedor->getUf(), 0, 2);
        $oDocument->pais = self::PAIS_DEFAULT;
             
    }

    /**
     * @param $oDocument
     * @param $oAdvog
     */
    public static function createAdvogado($oDocument,  $oAdvog)
    {
        $oDocument->nome_advog = $oAdvog->getNome();
        $oDocument->cpf_advog = substr(preg_replace("[./-]", "", trim($oAdvog->getCgccpf())), 0, 11);
        $oDocument->cep_advog = substr($oAdvog->getCep(), 0, 8);
        $oDocument->logradouro_advog = substr($oAdvog->getEndereco(), 0, 60);
        $oDocument->numero_advog = substr($oAdvog->getNumero(), 0, 10);
        $oDocument->complemento_advog = substr($oAdvog->getComplemento(), 0, 40);
        $oDocument->bairro_advog = substr($oAdvog->getBairro(), 0, 40);
        $oDocument->cidade_advog = substr($oAdvog->getMunicipio(), 0, 40);
        $oDocument->uf_advog = substr($oAdvog->getUf(), 0, 40);
        $oDocument->oab_advog = $oAdvog->getOab();
        $oDocument->matricula_advogado = $oAdvog->getMatriculaadvogado();

    }

    /**
     * @param $oDocument
     * @param $data
     * @param Devedor $oDevedor
     */
    public static function createDadosImovelInscricao($oDocument,  $data,  Devedor $oDevedor) 
    {

        $oDocument->numero_inscricao        = $data->origem;
        $oDocument->nome_devedor            = substr($oDevedor->getNome(), 0, 90);
        $oDocument->codigo_tipo_log_devedor = $oDevedor->getCodigoLogradouro();

        $oDocument->cep_logradouro          = substr($oDevedor->getCep(), 0, 8);
        $oDocument->tipo_logradouro         = $oDevedor->getTipoLogradouro();
        $oDocument->nome_logradouro         = substr($oDevedor->getEndereco(), 0, 60);
        $oDocument->numero_logradouro       = substr($oDevedor->getNumero(), 0, 10);
        $oDocument->complemento_logradouro  = substr($oDevedor->getComplemento(), 0, 40);
        $oDocument->bairro_logradouro       = substr($oDevedor->getBairro(), 0, 40);
        $oDocument->cidade_logradouro       = substr($oDevedor->getMunicipio(), 0, 40);
        $oDocument->uf_logradouro           = substr($oDevedor->getUf(), 0, 2);
        $oDocument->pais = self::PAIS_DEFAULT;
    }

    /**
     * @param $oDocument
     * @param $data
     */
    public static function createOutrosDados($oDocument,  $data) 
    {
        $oDocument->principal = "true";
        $oDocument->codigo_nacional = $data->codigo_assunto_nacional;
        $oDocument->codigo_assunto = $data->codigo_assunto;
        $oDocument->descricao = "Dívida Ativa";
        $oDocument->codigo_pai_nacional = $data->codigo_assunto_pai_nacional;
        $oDocument->assunto_local_pai = $data->codigo_assunto_pai_local;
        $oDocument->data_ajuizamento = $data->data_calculo;   
    }

    /**
     * @param $oDocument
     * @param AutoInfracao $oAutoInfracao
     */
    public static function createAutoInfracao($oDocument, AutoInfracao $oAutoInfracao)
    {
        $oDocument->auto_infracao = $oAutoInfracao->getCodigoAuto();
        $oDocument->data_infracao = $oAutoInfracao->getDataAuto();
    }

}



