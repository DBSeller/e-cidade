<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Educacao\Escola\Censo\Identificacao\Model;

/**
 * Class Campo
 * @package ECidade\Educacao\Escola\Censo\Identificacao\Model
 */
class Pessoa
{
    private $codigoPessoa;
    private $cpf;
    private $certidaoNascimento;
    private $nome;
    private $dataNascimento;
    private $filiacao1;
    private $filiacao2;
    private $codigoMunicipioNascimento;
    private $inep = null;
    /**
     * @var int
     */
    private $nacionalidade;

    /**
     * @return mixed
     */
    public function getCodigoPessoa()
    {
        return $this->codigoPessoa;
    }

    /**
     * @param mixed $codigoPessoa
     * @return Pessoa
     */
    public function setCodigoPessoa($codigoPessoa)
    {
        $this->codigoPessoa = $codigoPessoa;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     * @return Pessoa
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCertidaoNascimento()
    {
        return $this->certidaoNascimento;
    }

    /**
     * @param mixed $certidaoNascimento
     * @return Pessoa
     */
    public function setCertidaoNascimento($certidaoNascimento)
    {
        $this->certidaoNascimento = $certidaoNascimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     * @return Pessoa
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * @param mixed $dataNascimento
     * @return Pessoa
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFiliacao1()
    {
        return $this->filiacao1;
    }

    /**
     * @param mixed $filiacao1
     * @return Pessoa
     */
    public function setFiliacao1($filiacao1)
    {
        $this->filiacao1 = $filiacao1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFiliacao2()
    {
        return $this->filiacao2;
    }

    /**
     * @param mixed $filiacao2
     * @return Pessoa
     */
    public function setFiliacao2($filiacao2)
    {
        $this->filiacao2 = $filiacao2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoMunicipioNascimento()
    {
        return $this->codigoMunicipioNascimento;
    }

    /**
     * @param mixed $codigoMunicipioNascimento
     * @return Pessoa
     */
    public function setCodigoMunicipioNascimento($codigoMunicipioNascimento)
    {
        $this->codigoMunicipioNascimento = $codigoMunicipioNascimento;
        return $this;
    }

    /**
     * @return null
     */
    public function getInep()
    {
        return $this->inep;
    }

    /**
     * @param null $inep
     * @return Pessoa
     */
    public function setInep($inep)
    {
        $this->inep = $inep;
        return $this;
    }

    public function toArray()
    {
        return [
            'codigo_pessoa' => $this->getCodigoPessoa(),
            'cpf' => $this->getCpf(),
            'certidao_nascimento' => $this->getCertidaoNascimento(),
            'nome' => $this->getNome(),
            'data_nascimento' => $this->getDataNascimento(),
            'filiacao_1' => $this->getFiliacao1(),
            'filiacao_2' => $this->getFiliacao2(),
            'municipio_nascimento' => $this->getCodigoMunicipioNascimento(),
            'inep' => $this->getInep()
        ];
    }

    /**
     * @param integer $nacionalidade
     * @return Pessoa
     */
    public function setNacionalidade($nacionalidade)
    {
        $this->nacionalidade = (int) $nacionalidade;
        return $this;
    }

    /**
     * @return int
     */
    public function getNacionalidade()
    {
        return $this->nacionalidade;
    }
}
