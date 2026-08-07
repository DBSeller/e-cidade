<?php

class Cidadao
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $seq;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $identidade;
    /**
     * @var boolean
     */
    private $ativo;
    /**
     * @var string
     *
     */
    private $sexo;
    /**
     * @var string
     */
    private $dataNascimento;
    /**
     * @var string
     */
    private $data;
    /**
     * @var integer
     */
    private $situacaocidadaoId;
    /**
     * @var string
     */
    private $cep;
    /**
     * @var string
     */
    private $uf;
    /**
     * @var string
     */
    private $municipio;
    /**
     * @var string
     */
    private $bairro;
    /**
     * @var string
     */
    private $complemento;
    /**
     * @var integer
     */
    private $numero;
    /**
     * @var string
     */
    private $endereco;
    /**
     * @var
     */
    private $cnpjCpf;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * @param int $seq
     * @return $this
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return $this
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentidade()
    {
        return $this->identidade;
    }

    /**
     * @param string $identidade
     * @return $this
     */
    public function setIdentidade($identidade)
    {
        $this->identidade = $identidade;
        return $this;
    }

    /**
     * @return bool
     *
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     * @return $this
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @param string $sexo
     * @return $this
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * @param string $dataNascimento
     * @return $this
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getSituacaocidadaoId()
    {
        return $this->situacaocidadaoId;
    }

    /**
     * @param int $situacaocidadao
     * @return $this
     */
    public function setSituacaocidadaoId($situacaocidadaoId)
    {
        $this->situacaocidadaoId = $situacaocidadaoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param string $cep
     * @return $this
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param string $uf
     * @return $this
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * @return string
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param string $municipio
     * @return $this
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
        return $this;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param string $bairro
     * @return $this
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param string $complemento
     *  @return $this
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     * @return $this
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param string $endereco
     * @return $this
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCnpjCpf()
    {
        return $this->cnpjCpf;
    }

    /**
     * @param mixed $cnpjCpf
     * @return $this
     */
    public function setCnpjCpf($cnpjCpf)
    {
        $this->cnpjCpf = $cnpjCpf;
        return $this;
    }

    /**
     * @param $id
     * @return Cidadao|false|void
     */
    public static function find($id)
    {
        if (empty($id)) {
            return false;
        }
        $cidadao = new cl_cidadao();
        $sql = $cidadao->sql_query_file($id);
        $rs = $cidadao->sql_record($sql);

        if ($cidadao->numrows < 1) {
            return false;
        }

        $objCidadao = pg_fetch_object($rs);
        if (empty($objCidadao)) {
            return false;
        }

        return self::fromDao($objCidadao);
    }

    /**
     * @param $resultDao
     * @return Cidadao|void
     */
    public static function fromDao($resultDao)
    {
        $cidadao = new self();
        return $cidadao
               ->setId($resultDao->ov02_sequencial)
               ->setSeq($resultDao->ov02_seq)
               ->setNome($resultDao->ov02_nome)
               ->setIdentidade($resultDao->ov02_ident)
               ->setCnpjCpf($resultDao->ov02_cnpjcpf)
               ->setEndereco($resultDao->ov02_cnpjcpf)
               ->setNumero($resultDao->ov02_numero)
               ->setComplemento($resultDao->ov02_compl)
               ->setBairro($resultDao->ov02_bairro)
               ->setMunicipio($resultDao->ov02_munic)
               ->setUf($resultDao->ov02_uf)
               ->setCep($resultDao->ov02_cep)
               ->setSituacaocidadaoId($resultDao->ov02_situacaocidadao)
               ->setAtivo($resultDao->ov02_ativo)
               ->setData($resultDao->ov02_data)
               ->setDataNascimento($resultDao->ov02_datanascimento)
               ->setSexo($resultDao->ov02_sexo);

    }


}
