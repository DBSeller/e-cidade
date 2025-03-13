<?php

namespace App\Domain\Patrimonial\Ouvidoria\Model\Cidadao;

use Illuminate\Database\Eloquent\Model;
use ECidade\Lib\Session\DefaultSession;

/**
 * Class Cidadao
 * @package App\Domain\Patrimonial\Ouvidoria\Model\Cidadao
 *
 * @property int ov02_sequencial
 * @property int ov02_seq
 * @property string ov02_nome
 * @property string ov02_ident
 * @property string ov02_cnpjcpf
 * @property string ov02_endereco
 * @property int ov02_numero
 * @property string ov02_compl
 * @property string ov02_bairro
 * @property string ov02_munic
 * @property string ov02_uf
 * @property string ov02_cep
 * @property int ov02_situacaocidadao
 * @property boolean ov02_ativo
 * @property date ov02_data
 * @property date ov02_datanascimento
 * @property string ov02_sexo
 */
class Cidadao extends Model
{
    protected $table = 'ouvidoria.cidadao';
    // protected $primaryKey = ['ov02_sequencial', 'ov02_seq'];
    protected $primaryKey = 'ov02_sequencial';
    public $timestamps = false;
    
    protected $fillable = array(
        'ov02_nome',
        'ov02_ident',
        'ov02_cnpjcpf',
        'ov02_endereco',
        'ov02_numero',
        'ov02_compl',
        'ov02_bairro',
        'ov02_munic',
        'ov02_uf',
        'ov02_cep',
        'ov02_situacaocidadao',
        'ov02_ativo',
        'ov02_data',
        'ov02_datanascimento',
        'ov02_sexo',
    );

    /**
     * @param int $ov02_sequencial
     * @return this
     */
    public function setSequencial($ov02_sequencial)
    {
        $this->ov02_sequencial = $ov02_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->ov02_sequencial;
    }

    /**
     * @param int $ov02_seq
     * @return this
     */
    public function setCodigo($ov02_seq)
    {
        $this->ov02_seq = $ov02_seq;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->ov02_seq;
    }

    /**
     * @param string $ov02_nome
     * @return this
     */
    public function setNome($ov02_nome)
    {
        $this->ov02_nome = $ov02_nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->ov02_nome;
    }

    /**
     * @param string $ov02_ident
     * @return this
     */
    public function setIdentidade($ov02_ident)
    {
        $this->ov02_ident = $ov02_ident;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentidade()
    {
        return $this->ov02_ident;
    }

    /**
     * @param string $ov02_cnpjcpf
     * @return this
     */
    public function setCnpjCpf($ov02_cnpjcpf)
    {
        $this->ov02_cnpjcpf = $ov02_cnpjcpf;
        return $this;
    }

    /**
     * @return string
     */
    public function getCnpjCpf()
    {
        return $this->ov02_cnpjcpf;
    }

    /**
     * @param string $ov02_endereco
     * @return this
     */
    public function setEndereco($ov02_endereco)
    {
        $this->ov02_endereco = $ov02_endereco;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->ov02_endereco;
    }

    /**
     * @param int $ov02_numero
     * @return this
     */
    public function setNumero($ov02_numero)
    {
        $this->ov02_numero = $ov02_numero;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->ov02_numero;
    }

    /**
     * @param string $ov02_compl
     * @return this
     */
    public function setComplemento($ov02_compl)
    {
        $this->ov02_compl = $ov02_compl;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento()
    {
        return $this->ov02_compl;
    }

    /**
     * @param string $ov02_bairro
     * @return this
     */
    public function setBairro($ov02_bairro)
    {
        $this->ov02_bairro = $ov02_bairro;
        return $this;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->ov02_bairro;
    }

    /**
     * @param string $ov02_munic
     * @return this
     */
    public function setMunicipio($ov02_munic)
    {
        $this->ov02_munic = $ov02_munic;
        return $this;
    }

    /**
     * @return string
     */
    public function getMunicipio()
    {
        return $this->ov02_munic;
    }

    /**
     * @param string $ov02_uf
     * @return this
     */
    public function setUf($ov02_uf)
    {
        $this->ov02_uf = $ov02_uf;
        return $this;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->ov02_uf;
    }

    /**
     * @param string $ov02_cep
     * @return this
     */
    public function setCep($ov02_cep)
    {
        $this->ov02_cep = $ov02_cep;
        return $this;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->ov02_cep;
    }

    /**
     * @param int $ov02_situacaocidadao
     * @return this
     */
    public function setSituacao($ov02_situacaocidadao)
    {
        $this->ov02_situacaocidadao = $ov02_situacaocidadao;
        return $this;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->ov02_situacaocidadao;
    }

    /**
     * @param boolean $ov02_ativo
     * @return this
     */
    public function setAtivo($ov02_ativo)
    {
        $this->ov02_ativo = $ov02_ativo;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ov02_ativo;
    }

    /**
     * @param data $ov02_data
     * @return this
     */
    public function setData($ov02_data)
    {
        $this->ov02_data = $ov02_data;
        return $this;
    }

    /**
     * @return data
     */
    public function getData()
    {
        return $this->ov02_data;
    }

    /**
     * @param int $ov02_datanascimento
     * @return this
     */
    public function setDataNascimento($ov02_datanascimento)
    {
        $this->ov02_datanascimento = $ov02_datanascimento;
        return $this;
    }

    /**
     * @return int
     */
    public function getDataNascimento()
    {
        return $this->ov02_datanascimento;
    }

    /**
     * @param int $ov02_sexo
     * @return this
     */
    public function setSexo($ov02_sexo)
    {
        $this->ov02_sexo = $ov02_sexo;
        return $this;
    }

    /**
     * @return int
     */
    public function getSexo()
    {
        return $this->ov02_sexo;
    }
}
