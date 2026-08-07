<?php

namespace ECidade\Configuracao\Instituicao\Model;

use DBDate;

class Instituicao
{
    /**
     * @var integer
     */
    private $codigo;
    private $identidade;
    private $diario;
    private $numeroCgm;
    private $instituicaoSiapcPad;
    private $segmento;
    private $formaVencimentoFebraban;
    private $numeroEndereco;
    private $tipoInstituicao;
    private $ativo;
    private $regraCgmIssbase;
    private $regraCgmIptu;
    private $codigoCliente;
    private $esfera;
    private $tipoPoder;
    private $codigoMunicipioTj;
    private $codigoSiconfi;
    private $esferaOp;
    private $codigoDepartamentoPrincipal;

    /**
     * @var float
     */
    private $taxaBancaria;
    private $tetoRemuneratorio;
    /**
     * @var string
     */
    private $nome;
    private $nomeAbreviado;
    private $nomeContaDebito;
    private $cep;
    private $endereco;
    private $municipio;
    private $uf;
    private $telefone;
    private $email;
    private $numeroBanco;
    private $site;
    private $logo;
    private $figura;
    private $prefeito;
    private $vicePrefeito;
    private $fax;
    private $cgc;
    private $codigoOrgaoUnidade;
    private $codigoMunicipioEstado;
    private $complemento;
    private $cnpjEnteFederativoResp;
    private $descricaoDepartamentoAbreviado;

    /**
     * @var DBDate
     */
    private $dataContabilidade;
    private $dataLimite;
    private $dataCriacao;

    /**
     * @var boolean
     */
    private $isDebitoProprietarios;
    private $isDebitoSocios;
    private $isPrefeitura;
    private $usaSisAgua;
    private $isUnidadeGestoraRpps;
    private $isEnteFederativoResp;
    private $efrInstituiPrevidenciaComplementar;
    private $possuiRpps;
    
    /**
     * @var oid
     */
    private $imgMarcaDagua;

    /**
     * @param integer $codigo
     * @return Instituicao
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return integer $codigo
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $nome
     * @return Instituicao
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string $nome
     */
    public function getNome()
    {
        return $this->nome;
    }
    
    /**
     * @param string $endereco
     * @return Instituicao
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    /**
     * @return string $endereco
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param string $municipio
     * @return Instituicao
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
        return $this;
    }

    /**
     * @return string $municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param string $uf
     * @return Instituicao
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * @return string $uf
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param string $telefone
     * @return Instituicao
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
        return $this;
    }

    /**
     * @return string $telefone
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param string $email
     * @return Instituicao
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param integer $identidade
     * @return Instituicao
     */
    public function setIdentidade($identidade)
    {
        $this->identidade = $identidade;
        return $this;
    }

    /**
     * @return integer $identidade
     */
    public function getIdentidade()
    {
        return $this->identidade;
    }

    /**
     * @param float $taxaBancaria
     * @return Instituicao
     */
    public function setTaxaBancaria($taxaBancaria)
    {
        $this->taxaBancaria = $taxaBancaria;
        return $this;
    }

    /**
     * @return float $taxaBancaria
     */
    public function getTaxaBancaria()
    {
        return $this->taxaBancaria;
    }

    /**
     * @param string $numeroBanco
     * @return Instituicao
     */
    public function setNumeroBanco($numeroBanco)
    {
        $this->numeroBanco = $numeroBanco;
        return $this;
    }

    /**
     * @return string $numeroBanco
     */
    public function getNumeroBanco()
    {
        return $this->numeroBanco;
    }

    /**
     * @param string $site
     * @return Instituicao
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return string $site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $logo
     * @return Instituicao
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return string $logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $figura
     * @return Instituicao
     */
    public function setFigura($figura)
    {
        $this->figura = $figura;
        return $this;
    }

    /**
     * @return string $figura
     */
    public function getFigura()
    {
        return $this->figura;
    }

    /**
     * @param DBDate $dataContabilidade
     * @return Instituicao
     */
    public function setDataContabilidade($dataContabilidade)
    {
        $this->dataContabilidade = $dataContabilidade;
        return $this;
    }

    /**
     * @return DBDate $dataContabilidade
     */
    public function getDataContabilidade()
    {
        return $this->dataContabilidade;
    }

    /**
     * @param integer $diario
     * @return Instituicao
     */
    public function setDiario($diario)
    {
        $this->diario = $diario;
        return $this;
    }

    /**
     * @return integer $diario
     */
    public function getDiario()
    {
        return $this->diario;
    }

    /**
     * @param string $prefeito
     * @return Instituicao
     */
    public function setPrefeito($prefeito)
    {
        $this->prefeito = $prefeito;
        return $this;
    }

    /**
     * @return string $prefeito
     */
    public function getPrefeito()
    {
        return $this->prefeito;
    }

    /**
     * @param string $vicePrefeito
     * @return Instituicao
     */
    public function setVicePrefeito($vicePrefeito)
    {
        $this->vicePrefeito = $vicePrefeito;
        return $this;
    }

    /**
     * @return string $vicePrefeito
     */
    public function getVicePrefeito()
    {
        return $this->vicePrefeito;
    }

    /**
     * @param string $fax
     * @return Instituicao
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * @return string $fax
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $cgc
     * @return Instituicao
     */
    public function setCgc($cgc)
    {
        $this->cgc = $cgc;
        return $this;
    }

    /**
     * @return string $cgc
     */
    public function getCgc()
    {
        return $this->cgc;
    }

    /**
     * @param string $cep
     * @return Instituicao
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * @return string $cep
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param boolean $isDebitoProprietarios
     * @return Instituicao
     */
    public function setDebitoProprietarios($isDebitoProprietarios)
    {
        $this->isDebitoProprietarios = $isDebitoProprietarios;
        return $this;
    }

    /**
     * @return boolean $isDebitoProprietarios
     */
    public function isDebitoProprietarios()
    {
        return $this->isDebitoProprietarios;
    }

    /**
     * @param boolean $isDebitoSocios
     * @return Instituicao
     */
    public function setDebitoSocios($isDebitoSocios)
    {
        $this->isDebitoSocios = $isDebitoSocios;
        return $this;
    }

    /**
     * @return boolean $isDebitoSocios
     */
    public function isDebitoSocios()
    {
        return $this->isDebitoSocios;
    }

    /**
     * @param boolean $isPrefeitura
     * @return Instituicao
     */
    public function setPrefeitura($isPrefeitura)
    {
        $this->isPrefeitura = $isPrefeitura;
        return $this;
    }

    /**
     * @return boolean $isPrefeitura
     */
    public function isPrefeitura()
    {
        return $this->isPrefeitura;
    }

    /**
     * @param string $bairro
     * @return Instituicao
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return string $bairro
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param integer $numeroCgm
     * @return Instituicao
     */
    public function setNumeroCgm($numeroCgm)
    {
        $this->numeroCgm = $numeroCgm;
        return $this;
    }

    /**
     * @return integer $numeroCgm
     */
    public function getNumeroCgm()
    {
        return $this->numeroCgm;
    }

    /**
     * @param string $codigoOrgaoUnidade
     * @return Instituicao
     */
    public function setCodigoOrgaoUnidade($codigoOrgaoUnidade)
    {
        $this->codigoOrgaoUnidade = $codigoOrgaoUnidade;
        return $this;
    }

    /**
     * @return string $codigoOrgaoUnidade
     */
    public function getCodigoOrgaoUnidade()
    {
        return $this->codigoOrgaoUnidade;
    }

    /**
     * @param integer $instituicaoSiapcPad
     * @return Instituicao
     */
    public function setInstituicaoSiapcPad($instituicaoSiapcPad)
    {
        $this->instituicaoSiapcPad = $instituicaoSiapcPad;
        return $this;
    }

    /**
     * @return integer $instituicaoSiapcPad
     */
    public function getInstituicaoSiapcPad()
    {
        return $this->instituicaoSiapcPad;
    }

    /**
     * @param integer $segmento
     * @return Instituicao
     */
    public function setSegmento($segmento)
    {
        $this->segmento = $segmento;
        return $this;
    }

    /**
     * @return integer $segmento
     */
    public function getSegmento()
    {
        return $this->segmento;
    }

    /**
     * @param integer $formaVencimentoFebraban
     * @return Instituicao
     */
    public function setFormaVencimentoFebraban($formaVencimentoFebraban)
    {
        $this->formaVencimentoFebraban = $formaVencimentoFebraban;
        return $this;
    }

    /**
     * @return integer $formaVencimentoFebraban
     */
    public function getFormaVencimentoFebraban()
    {
        return $this->formaVencimentoFebraban;
    }

    /**
     * @param integer $numeroEndereco
     * @return Instituicao
     */
    public function setNumeroEndereco($numeroEndereco)
    {
        $this->numeroEndereco = $numeroEndereco;
        return $this;
    }

    /**
     * @return integer $numeroEndereco
     */
    public function getNumeroEndereco()
    {
        return $this->numeroEndereco;
    }

    /**
     * @param string $nomeContaDebito
     * @return Instituicao
     */
    public function setNomeContaDebito($nomeContaDebito)
    {
        $this->nomeContaDebito = $nomeContaDebito;
        return $this;
    }

    /**
     * @return string $nomeContaDebito
     */
    public function getNomeContaDebito()
    {
        return $this->nomeContaDebito;
    }

    /**
     * @param integer $tipoInstituicao
     * @return Instituicao
     */
    public function setTipoInstituicao($tipoInstituicao)
    {
        $this->tipoInstituicao = $tipoInstituicao;
        return $this;
    }

    /**
     * @return integer $tipoInstituicao
     */
    public function getTipoInstituicao()
    {
        return $this->tipoInstituicao;
    }

    /**
     * @param integer $ativo
     * @return Instituicao
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @return integer $ativo
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param integer $regraCgmIssbase
     * @return Instituicao
     */
    public function setRegraCgmIssbase($regraCgmIssbase)
    {
        $this->regraCgmIssbase = $regraCgmIssbase;
        return $this;
    }

    /**
     * @return integer $regraCgmIssbase
     */
    public function getRegraCgmIssbase()
    {
        return $this->regraCgmIssbase;
    }

    /**
     * @param integer $regraCgmIptu
     * @return Instituicao
     */
    public function setRegraCgmIptu($regraCgmIptu)
    {
        $this->regraCgmIptu = $regraCgmIptu;
        return $this;
    }

    /**
     * @return integer $regraCgmIptu
     */
    public function getRegraCgmIptu()
    {
        return $this->regraCgmIptu;
    }

    /**
     * @param integer $codigoCliente
     * @return Instituicao
     */
    public function setCodigoCliente($codigoCliente)
    {
        $this->codigoCliente = $codigoCliente;
        return $this;
    }

    /**
     * @return integer $codigoCliente
     */
    public function getCodigoCliente()
    {
        return $this->codigoCliente;
    }

    /**
     * @param string $nomeAbreviado
     * @return Instituicao
     */
    public function setNomeAbreviado($nomeAbreviado)
    {
        $this->nomeAbreviado = $nomeAbreviado;
        return $this;
    }

    /**
     * @return string $nomeAbreviado
     */
    public function getNomeAbreviado()
    {
        return $this->nomeAbreviado;
    }

    /**
     * @param boolean $usaSisAgua
     * @return Instituicao
     */
    public function setUsaSisAgua($usaSisAgua)
    {
        $this->usaSisAgua = $usaSisAgua;
        return $this;
    }

    /**
     * @return boolean $usaSisAgua
     */
    public function usaSisAgua()
    {
        return $this->usaSisAgua;
    }

    /**
     * @param string $codigoMunicipioEstado
     * @return Instituicao
     */
    public function setCodigoMunicipioEstado($codigoMunicipioEstado)
    {
        $this->codigoMunicipioEstado = $codigoMunicipioEstado;
        return $this;
    }

    /**
     * @return string $codigoMunicipioEstado
     */
    public function getCodigoMunicipioEstado()
    {
        return $this->codigoMunicipioEstado;
    }

    /**
     * @param DBDate $dataLimite
     * @return Instituicao
     */
    public function setDataLimite($dataLimite)
    {
        $this->dataLimite = $dataLimite;
        return $this;
    }

    /**
     * @return DBDate $dataLimite
     */
    public function getDataLimite()
    {
        return $this->dataLimite;
    }

    /**
     * @param DBDate $dataCriacao
     * @return Instituicao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
        return $this;
    }

    /**
     * @return DBDate $dataCriacao
     */
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * @param string $complemento
     * @return Instituicao
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return string $complemento
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param OID $imgMarcaDagua
     * @return Instituicao
     */
    public function setImgMarcaDagua($imgMarcaDagua)
    {
        $this->imgMarcaDagua = $imgMarcaDagua;
        return $this;
    }

    /**
     * @return OID $imgMarcaDagua
     */
    public function getImgMarcaDagua()
    {
        return $this->imgMarcaDagua;
    }

    /**
     * @param integer $esfera
     * @return Instituicao
     */
    public function setEsfera($esfera)
    {
        $this->esfera = $esfera;
        return $this;
    }

    /**
     * @return integer $esfera
     */
    public function getEsfera()
    {
        return $this->esfera;
    }

    /**
     * @param integer $tipoPoder
     * @return Instituicao
     */
    public function setTipoPoder($tipoPoder)
    {
        $this->tipoPoder = $tipoPoder;
        return $this;
    }

    /**
     * @return integer $tipoPoder
     */
    public function getTipoPoder()
    {
        return $this->tipoPoder;
    }

    /**
     * @param integer $codigoMunicipioTj
     * @return Instituicao
     */
    public function setCodigoMunicipioTj($codigoMunicipioTj)
    {
        $this->codigoMunicipioTj = $codigoMunicipioTj;
        return $this;
    }

    /**
     * @return integer $codigoMunicipioTj
     */
    public function getCodigoMunicipioTj()
    {
        return $this->codigoMunicipioTj;
    }

    /**
     * @param string $codigoSiconfi
     * @return Instituicao
     */
    public function setCodigoSiconfi($codigoSiconfi)
    {
        $this->codigoSiconfi = $codigoSiconfi;
        return $this;
    }

    /**
     * @return string $codigoSiconfi
     */
    public function getCodigoSiconfi()
    {
        return $this->codigoSiconfi;
    }

    /**
     * @param boolean $isUnidadeGestoraRpps
     * @return Instituicao
     */
    public function setUnidadeGestoraRpps($isUnidadeGestoraRpps)
    {
        $this->isUnidadeGestoraRpps = $isUnidadeGestoraRpps;
        return $this;
    }

    /**
     * @return boolean $isUnidadeGestoraRpps
     */
    public function isUnidadeGestoraRpps()
    {
        return $this->isUnidadeGestoraRpps;
    }

    /**
     * @param integer $esferaOp
     * @return Instituicao
     */
    public function setEsferaOp($esferaOp)
    {
        $this->esferaOp = $esferaOp;
        return $this;
    }

    /**
     * @return integer $esferaOp
     */
    public function getEsferaOp()
    {
        return $this->esferaOp;
    }

    /**
     * @param float $tetoRemuneratorio
     * @return Instituicao
     */
    public function setTetoRemuneratorio($tetoRemuneratorio)
    {
        $this->tetoRemuneratorio = $tetoRemuneratorio;
        return $this;
    }

    /**
     * @return float $tetoRemuneratorio
     */
    public function getTetoRemuneratorio()
    {
        return $this->tetoRemuneratorio;
    }

    /**
     * @param boolean $isEnteFederativoResp
     */
    public function setEnteFederativoResp($isEnteFederativoResp)
    {
        $this->isEnteFederativoResp = $isEnteFederativoResp;
        return $this;
    }

    /**
     * @return boolean $isEnteFederativoResp
     */
    public function isEnteFederativoResp()
    {
        return $this->isEnteFederativoResp;
    }

    /**
     * @param string $cnpjEnteFederativoResp
     * @return Instituicao
     */
    public function setCnpjEnteFederativoResp($cnpjEnteFederativoResp)
    {
        $this->cnpjEnteFederativoResp = $cnpjEnteFederativoResp;
        return $this;
    }

    /**
     * @return string $cnpjEnteFederativoResp
     */
    public function getCnpjEnteFederativoResp()
    {
        return $this->cnpjEnteFederativoResp;
    }

    /**
     * @param boolean $efrInstituiPrevidenciaComplementar
     * @return Instituicao
     */
    public function setEfrInstituiPrevidenciaComplementar($efrInstituiPrevidenciaComplementar)
    {
        $this->efrInstituiPrevidenciaComplementar = $efrInstituiPrevidenciaComplementar;
        return $this;
    }

    /**
     * @return boolean $efrInstituiPrevidenciaComplementar
     */
    public function efrInstituiPrevidenciaComplementar()
    {
        return $this->efrInstituiPrevidenciaComplementar;
    }

    /**
     * @param boolean $possuiRpps
     * @return Instituicao
     */
    public function setPossuiRpps($possuiRpps)
    {
        $this->possuiRpps = $possuiRpps;
        return $this;
    }

    /**
     * @return boolean $possuiRpps
     */
    public function possuiRpps()
    {
        return $this->possuiRpps;
    }

    /**
     * @param integer $codigoDepartamentoPrincipal
     * @return Instituicao
     */
    public function setCodigoDepartamentoPrincipal($codigoDepartamentoPrincipal)
    {
        $this->codigoDepartamentoPrincipal = $codigoDepartamentoPrincipal;
        return $this;
    }

    /**
     * @return integer $codigoDepartamentoPrincipal
     */
    public function getCodigoDepartamentoPrincipal()
    {
        return $this->codigoDepartamentoPrincipal;
    }

    /**
     * @param string $descricaoDepartamentoAbreviado
     * @return Instituicao
     */
    public function setDescricaoDepartamentoAbreviado($descricaoDepartamentoAbreviado)
    {
        $this->descricaoDepartamentoAbreviado = $descricaoDepartamentoAbreviado;
        return $this;
    }

    /**
     * @return string $descricaoDepartamentoAbreviado
     */
    public function getDescricaoDepartamentoAbreviado()
    {
        return $this->descricaoDepartamentoAbreviado;
    }

    /**
     * Mapeia os dados do array e seta nos parametros da classe
     * @param array $state
     * @return Instituicao
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('codigo', $state)) {
            $self->setCodigo($state['codigo']);
        }
        if (array_key_exists('nomeinst', $state)) {
            $self->setNome($state['nomeinst']);
        }
        if (array_key_exists('ender', $state)) {
            $self->setEndereco($state['ender']);
        }
        if (array_key_exists('munic', $state)) {
            $self->setMunicipio($state['munic']);
        }
        if (array_key_exists('uf', $state)) {
            $self->setUf($state['uf']);
        }
        if (array_key_exists('telef', $state)) {
            $self->setTelefone($state['telef']);
        }
        if (array_key_exists('email', $state)) {
            $self->setEmail($state['email']);
        }
        if (array_key_exists('ident', $state)) {
            $self->setIdentidade($state['ident']);
        }
        if (array_key_exists('tx_banc', $state)) {
            $self->setTaxaBancaria($state['tx_banc']);
        }
        if (array_key_exists('numbanco', $state)) {
            $self->setNumeroBanco($state['numbanco']);
        }
        if (array_key_exists('url', $state)) {
            $self->setSite($state['url']);
        }
        if (array_key_exists('logo', $state)) {
            $self->setLogo($state['logo']);
        }
        if (array_key_exists('figura', $state)) {
            $self->setFigura($state['figura']);
        }
        if (array_key_exists('dtcont', $state)) {
            $dataContabilidade = !empty($state['dtcont']) ? new DBDate($state['dtcont']) : null;
            $self->setDataContabilidade($dataContabilidade);
        }
        if (array_key_exists('diario', $state)) {
            $self->setDiario($state['diario']);
        }
        if (array_key_exists('pref', $state)) {
            $self->setPrefeito($state['pref']);
        }
        if (array_key_exists('vicepref', $state)) {
            $self->setVicePrefeito($state['vicepref']);
        }
        if (array_key_exists('fax', $state)) {
            $self->setFax($state['fax']);
        }
        if (array_key_exists('cgc', $state)) {
            $self->setCgc($state['cgc']);
        }
        if (array_key_exists('cep', $state)) {
            $self->setCep($state['cep']);
        }
        if (array_key_exists('tpropri', $state)) {
            $self->setDebitoProprietarios($state['tpropri'] === 't');
        }
        if (array_key_exists('tsocios', $state)) {
            $self->setDebitoSocios($state['tsocios'] === 't');
        }
        if (array_key_exists('prefeitura', $state)) {
            $self->setPrefeitura($state['prefeitura'] === 't');
        }
        if (array_key_exists('bairro', $state)) {
            $self->setBairro($state['bairro']);
        }
        if (array_key_exists('numcgm', $state)) {
            $self->setNumeroCgm($state['numcgm']);
        }
        if (array_key_exists('codtrib', $state)) {
            $self->setCodigoOrgaoUnidade($state['codtrib']);
        }
        if (array_key_exists('tribinst', $state)) {
            $self->setInstituicaoSiapcPad($state['tribinst']);
        }
        if (array_key_exists('segmento', $state)) {
            $self->setSegmento($state['segmento']);
        }
        if (array_key_exists('formvencfebraban', $state)) {
            $self->setFormaVencimentoFebraban($state['formvencfebraban']);
        }
        if (array_key_exists('numero', $state)) {
            $self->setNumeroEndereco($state['numero']);
        }
        if (array_key_exists('nomedebconta', $state)) {
            $self->setNomeContaDebito($state['nomedebconta']);
        }
        if (array_key_exists('db21_tipoinstit', $state)) {
            $self->setTipoInstituicao($state['db21_tipoinstit']);
        }
        if (array_key_exists('db21_ativo', $state)) {
            $self->setAtivo($state['db21_ativo']);
        }
        if (array_key_exists('db21_regracgmiss', $state)) {
            $self->setRegraCgmIssbase($state['db21_regracgmiss']);
        }
        if (array_key_exists('db21_regracgmiptu', $state)) {
            $self->setRegraCgmIptu($state['db21_regracgmiptu']);
        }
        if (array_key_exists('db21_codcli', $state)) {
            $self->setCodigoCliente($state['db21_codcli']);
        }
        if (array_key_exists('nomeinstabrev', $state)) {
            $self->setNomeAbreviado($state['nomeinstabrev']);
        }
        if (array_key_exists('db21_usasisagua', $state)) {
            $self->setUsaSisAgua($state['db21_usasisagua'] === 't');
        }
        if (array_key_exists('db21_codigomunicipoestado', $state)) {
            $self->setCodigoMunicipioEstado($state['db21_codigomunicipoestado']);
        }
        if (array_key_exists('db21_datalimite', $state)) {
            $dataLimite = !empty($state['db21_datalimite']) ? new DBDate($state['db21_datalimite']) : null;
            $self->setDataLimite($dataLimite);
        }
        if (array_key_exists('db21_criacao', $state)) {
            $dataCriacao = !empty($state['db21_criacao']) ? new DBDate($state['db21_criacao']) : null;
            $self->setDataCriacao($dataCriacao);
        }
        if (array_key_exists('db21_compl', $state)) {
            $self->setComplemento($state['db21_compl']);
        }
        if (array_key_exists('db21_imgmarcadagua', $state)) {
            $self->setImgMarcaDagua($state['db21_imgmarcadagua']);
        }
        if (array_key_exists('db21_esfera', $state)) {
            $self->setEsfera($state['db21_esfera']);
        }
        if (array_key_exists('db21_tipopoder', $state)) {
            $self->setTipoPoder($state['db21_tipopoder']);
        }
        if (array_key_exists('db21_codtj', $state)) {
            $self->setCodigoMunicipioTj($state['db21_codtj']);
        }
        if (array_key_exists('db21_codsiconfi', $state)) {
            $self->setCodigoSiconfi($state['db21_codsiconfi']);
        }
        if (array_key_exists('db21_unidade_gestora_rpps', $state)) {
            $self->setUnidadeGestoraRpps($state['db21_unidade_gestora_rpps'] === 't');
        }
        if (array_key_exists('db21_esfera_op', $state)) {
            $self->setEsferaOp($state['db21_esfera_op']);
        }
        if (array_key_exists('db21_valor_teto_remuneratorio', $state)) {
            $self->setTetoRemuneratorio($state['db21_valor_teto_remuneratorio']);
        }
        if (array_key_exists('db21_ente_federativo_resp', $state)) {
            $self->setEnteFederativoResp($state['db21_ente_federativo_resp'] === 't');
        }
        if (array_key_exists('db21_cnpj_efr', $state)) {
            $self->setCnpjEnteFederativoResp($state['db21_cnpj_efr']);
        }
        if (array_key_exists('db21_efr_previdencia_compl', $state)) {
            $self->setEfrInstituiPrevidenciaComplementar($state['db21_efr_previdencia_compl'] === 't');
        }
        if (array_key_exists('db21_possui_rpps', $state)) {
            $self->setPossuiRpps($state['db21_possui_rpps'] === 't');
        }
        if (array_key_exists('db21_departamento', $state)) {
            $self->setCodigoDepartamentoPrincipal($state['db21_departamento']);
        }
        if (array_key_exists('db21_descr_depart_abrev', $state)) {
            $self->setDescricaoDepartamentoAbreviado($state['db21_descr_depart_abrev']);
        }
        return $self;
    }
}
