<?php


class ServidorDocumento
{
    /**
     * @table rhpesdoc
     **/

    /**
     * @column rh16_regist
     * @var integer
     */
    protected $matricula;

    /**
     * @column rh16_titele
     * @var integer
     */
    protected $tituloDeEleitor;

    /**
     * @column rh16_zonael
     * @var integer|null
     */
    protected $zonaTituloDeEleitor;

    /**
     * @column rh16_secaoe
     * @var integer|null
     */
    protected $secaoTituloDeEleitor;

    /**
     * @column rh16_reserv
     * @var string
     */
    protected $reservista_numero;

    /**
     * @column rh16_catres
     *
     */
    protected $reservista_categoria;

    /**
     * @column rh16_ctps_n
     * @var integer
     */
    protected $ctps_numero;

    /**
     * @column rh16_ctps_s
     * @var integer
     */
    protected $ctps_serie;

    /**
     * @column rh16_ctps_d
     * @var integer
     */
    protected $ctps_digito;

    /**
     * @column rh16_ctps_uf
     * @var string
     */
    protected $ctps_uf;

    /**
     * @column rh16_emissao
     * @var string
     * example 1999-12-11
     */
    protected $ctps_emissao;

    /**
     * @column  rh16_pis
     * @var  integer
     */
    protected $pis;

    /**
     * @column rh16_carth_n
     * @var integer
     */
    protected $cnh_numero;

    /**
     * @column r16_carth_cat
     * @var string
     */
    protected $cnh_categoria;

    /**
     * @column r16_carth_cat
     * @var string
     * example 1999-12-11
     */
    protected $cnh_validade;

    /**
     * @column rh16_data_emissao_cnh
     * @var string
     * example 1999-12-11
     */
    protected $cnh_emissao;

    /**
     * @column rh16_uf_cnh
     * @var string
     */
    protected $cnh_uf;

    /**
     * @column $rh16_orgao_classe
     * @var string
     */
    protected $orgaoClasse;

    /**
     * @column rh16_data_orgao_classe
     * @var string
     */
    protected $orgaoClasseData;

    /**
     * @column rh16_orgao_emissor_classe
     * @var string
     */
    protected $orgaoClasseEmissor;

    /**
     * @column rh16_data_validade_orgao_classe
     * @return string
     * example 1999-12-11
     */
    protected $orgaoClasseValidade;

        /**
     * @column rh16_orgao_emissor_rne
     * @var string
     */
    protected $rneOrgaoEmissor;

    /**
     * @column rh16_data_emissao_rne
     * @var string
     * example 1999-12-11
     */
    protected $rneEmissao;

    /**
     * @column rh16_data_entrada_rne
     * @var string
     * example 1999-12-11
     */
    protected $rneEntrada;

    /**
     * @column $rh16_data_validade_rne
     * @var string
     * example 1999-12-11
     */
    protected $rneValidade;

    /**
     * @column rh16_registro_rne
     * @var string
     */
    protected $rneRegistro;



    /**
     * @return int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return int
     */
    public function getTituloDeEleitor()
    {
        return $this->tituloDeEleitor;
    }

    /**
     * @param int $tituloDeEleitor
     */
    public function setTituloDeEleitor($tituloDeEleitor)
    {
        $this->tituloDeEleitor = $tituloDeEleitor;
    }

    /**
     * @return int|null
     */
    public function getZonaTituloDeEleitor()
    {
        return $this->zonaTituloDeEleitor;
    }

    /**
     * @param int|null $zonaTituloDeEleitor
     */
    public function setZonaTituloDeEleitor($zonaTituloDeEleitor)
    {
        $this->zonaTituloDeEleitor = $zonaTituloDeEleitor;
    }

    /**
     * @return int|null
     */
    public function getSecaoTituloDeEleitor()
    {
        return $this->secaoTituloDeEleitor;
    }

    /**
     * @param int|null $secaoTituloDeEleitor
     */
    public function setSecaoTituloDeEleitor($secaoTituloDeEleitor)
    {
        $this->secaoTituloDeEleitor = $secaoTituloDeEleitor;
    }

    /**
     * @return string
     */
    public function getReservistaNumero()
    {
        return $this->reservista_numero;
    }

    /**
     * @param string $reservista_numero
     */
    public function setReservistaNumero($reservista_numero)
    {
        $this->reservista_numero = $reservista_numero;
    }

    /**
     * @return mixed
     */
    public function getReservistaCategoria()
    {
        return $this->reservista_categoria;
    }

    /**
     * @param mixed $reservista_categoria
     */
    public function setReservistaCategoria($reservista_categoria)
    {
        $this->reservista_categoria = $reservista_categoria;
    }

    /**
     * @return int
     */
    public function getCtpsNumero()
    {
        return $this->ctps_numero;
    }

    /**
     * @param int $ctps_numero
     */
    public function setCtpsNumero($ctps_numero)
    {
        $this->ctps_numero = $ctps_numero;
    }

    /**
     * @return int
     */
    public function getCtpsSerie()
    {
        return $this->ctps_serie;
    }

    /**
     * @param int $ctps_serie
     */
    public function setCtpsSerie($ctps_serie)
    {
        $this->ctps_serie = $ctps_serie;
    }

    /**
     * @return int
     */
    public function getCtpsDigito()
    {
        return $this->ctps_digito;
    }

    /**
     * @param int $ctps_digito
     */
    public function setCtpsDigito($ctps_digito)
    {
        $this->ctps_digito = $ctps_digito;
    }

    /**
     * @return string
     */
    public function getCtpsUf()
    {
        return $this->ctps_uf;
    }

    /**
     * @param string $ctps_uf
     */
    public function setCtpsUf($ctps_uf)
    {
        $this->ctps_uf = $ctps_uf;
    }

    /**
     * @return string
     */
    public function getCtpsEmissao()
    {
        return $this->ctps_emissao;
    }

    /**
     * @param string $ctps_emissao
     */
    public function setCtpsEmissao($ctps_emissao)
    {
        $this->ctps_emissao = $ctps_emissao;
    }

    /**
     * @return int
     */
    public function getPis()
    {
        return $this->pis;
    }

    /**
     * @param int $pis
     */
    public function setPis($pis)
    {
        $this->pis = $pis;
    }

    /**
     * @return int
     */
    public function getCnhNumero()
    {
        return $this->cnh_numero;
    }

    /**
     * @param int $cnh_numero
     */
    public function setCnhNumero($cnh_numero)
    {
        $this->cnh_numero = $cnh_numero;
    }

    /**
     * @return string
     */
    public function getCnhCategoria()
    {
        return $this->cnh_categoria;
    }

    /**
     * @param string $cnh_categoria
     */
    public function setCnhCategoria($cnh_categoria)
    {
        $this->cnh_categoria = $cnh_categoria;
    }

    /**
     * @return string
     */
    public function getCnhValidade()
    {
        return $this->cnh_validade;
    }

    /**
     * @param string $cnh_validade
     */
    public function setCnhValidade($cnh_validade)
    {
        $this->cnh_validade = $cnh_validade;
    }

    /**
     * @return string
     */
    public function getCnhEmissao()
    {
        return $this->cnh_emissao;
    }

    /**
     * @param string $cnh_emissao
     */
    public function setCnhEmissao($cnh_emissao)
    {
        $this->cnh_emissao = $cnh_emissao;
    }

    /**
     * @return string
     */
    public function getOrgaoClasse()
    {
        return $this->orgaoClasse;
    }

    /**
     * @param string $orgaoClasse
     */
    public function setOrgaoClasse($orgaoClasse)
    {
        $this->orgaoClasse = $orgaoClasse;
    }

    /**
     * @return string
     */
    public function getOrgaoClasseData()
    {
        return $this->orgaoClasseData;
    }

    /**
     * @param string $orgaoClasseData
     */
    public function setOrgaoClasseData($orgaoClasseData)
    {
        $this->orgaoClasseData = $orgaoClasseData;
    }

    /**
     * @return string
     */
    public function getOrgaoClasseEmissor()
    {
        return $this->orgaoClasseEmissor;
    }

    /**
     * @param string $orgaoClasseEmissor
     */
    public function setOrgaoClasseEmissor($orgaoClasseEmissor)
    {
        $this->orgaoClasseEmissor = $orgaoClasseEmissor;
    }

    /**
     * @return mixed
     */
    public function getOrgaoClasseValidade()
    {
        return $this->orgaoClasseValidade;
    }

    /**
     * @param mixed $orgaoClasseValidade
     */
    public function setOrgaoClasseValidade($orgaoClasseValidade)
    {
        $this->orgaoClasseValidade = $orgaoClasseValidade;
    }


    /**
     * @return string
     */
    public function getRneOrgaoEmissor()
    {
        return $this->rneOrgaoEmissor;
    }

    /**
     * @param string $rneOrgaoEmissor
     */
    public function setRneOrgaoEmissor($rneOrgaoEmissor)
    {
        $this->rneOrgaoEmissor = $rneOrgaoEmissor;
    }

    /**
     * @return string
     */
    public function getRneEmissao()
    {
        return $this->rneEmissao;
    }

    /**
     * @param string $rneEmissao
     */
    public function setRneEmissao($rneEmissao)
    {
        $this->rneEmissao = $rneEmissao;
    }

    /**
     * @return string
     */
    public function getRneEntrada()
    {
        return $this->rneEntrada;
    }

    /**
     * @param string $rneEntrada
     */
    public function setRneEntrada($rneEntrada)
    {
        $this->rneEntrada = $rneEntrada;
    }

    /**
     * @return string
     */
    public function getRneValidade()
    {
        return $this->rneValidade;
    }

    /**
     * @param string $rneValidade
     */
    public function setRneValidade($rneValidade)
    {
        $this->rneValidade = $rneValidade;
    }

    /**
     * @return string
     */
    public function getRneRegistro()
    {
        return $this->rneRegistro;
    }

    /**
     * @param string $rneRegistro
     */
    public function setRneRegistro($rneRegistro)
    {
        $this->rneRegistro = $rneRegistro;
    }

    /**
     * @return string
     */
    public function getCnhUf()
    {
        return $this->cnh_uf;
    }

    /**
     * @param string $cnh_uf
     */
    public function setCnhUf($cnh_uf)
    {
        $this->cnh_uf = $cnh_uf;
    }

    private function validate()
    {
        if (strlen($this->getPis()) > 11) {
            throw new \Exception("O número do pis não pode ser maior que 11 caracteres");
        }

        if (strlen($this->getTituloDeEleitor()) > 12) {
            throw new \Exception("O número do título não pode ser maior que 12 caracteres");
        }

        if (strlen($this->getCnhNumero()) > 12) {
            throw new \Exception("O número da CNH não pode ser maior que 12 caracteres");
        }

        if (strlen($this->getOrgaoClasseEmissor()) > 15) {
            throw new \Exception("A descrição do Órgão emissor não pode ser maior que 15 caracteres");
        }

        if (strlen($this->getSecaoTituloDeEleitor()) > 4) {
            throw new \Exception("A seção do titulo de eleitor não pode ser maior que 4 caracteres");
        }

        if (strlen($this->getZonaTituloDeEleitor()) > 4) {
            throw new \Exception("A zona do titulo de eleitor não pode ser maior que 4 caracteres");
        }

        if (strlen($this->getCnhCategoria()) > 4) {
            throw new \Exception("A categoria da CNH não pode ser maior que 4 caracteres");
        }

        if (strlen($this->getReservistaNumero()) > 15) {
            throw new \Exception("A Número de reservista não pode ser maior que 15 caracteres");

        }

        if (strlen($this->getOrgaoClasse()) > 15) {
            throw new \Exception("O orgão classe não pode ser maior que 15 caracteres");
        }
    }


    public function save()
    {
        $this->validate();

        $rhpesdoc = new cl_rhpesdoc();
        $rhpesdoc->rh16_regist = $this->getMatricula();
        $rhpesdoc->rh16_titele = $this->getTituloDeEleitor();
        $rhpesdoc->rh16_secaoe = $this->getSecaoTituloDeEleitor();
        $rhpesdoc->rh16_zonael = $this->getZonaTituloDeEleitor();
        $rhpesdoc->rh16_reserv = $this->getReservistaNumero();
        $rhpesdoc->rh16_catres = $this->getReservistaCategoria();
        $rhpesdoc->rh16_ctps_n = $this->getCtpsNumero();
        $rhpesdoc->rh16_ctps_s = $this->getCtpsSerie();
        $rhpesdoc->rh16_ctps_d = $this->getCtpsDigito();
        $rhpesdoc->rh16_ctps_uf = $this->getCtpsUf();
        $rhpesdoc->rh16_emissao = $this->getCtpsEmissao();
        $rhpesdoc->rh16_pis = $this->getPis();
        $rhpesdoc->rh16_carth_n = $this->getCnhNumero();
        $rhpesdoc->r16_carth_cat = $this->getCnhCategoria();
        $rhpesdoc->rh16_carth_val = $this->getCnhValidade();
        $rhpesdoc->rh16_data_emissao_cnh = $this->getCnhEmissao();
        $rhpesdoc->rh16_uf_cnh = $this->getCnhUf();
        $rhpesdoc->rh16_orgao_classe = $this->getOrgaoClasse();
        $rhpesdoc->rh16_data_orgao_classe = $this->getOrgaoClasseData();
        $rhpesdoc->rh16_orgao_emissor_classe = $this->getOrgaoClasseEmissor();
        $rhpesdoc->rh16_data_validade_orgao_classe = $this->getOrgaoClasseValidade();
        $rhpesdoc->rh16_orgao_emissor_rne = $this->getRneOrgaoEmissor();
        $rhpesdoc->rh16_data_emissao_rne = $this->getRneEmissao();
        $rhpesdoc->rh16_data_entrada_rne = $this->getRneEntrada();
        $rhpesdoc->rh16_data_validade_rne = $this->getRneValidade();
        $rhpesdoc->rh16_registro_rne = $this->getRneRegistro();

        if (empty($this->getMatricula())) {
            throw new \Exception("Para incluir documentos é necessário informar a matrícula.");
        }

        $sql = $rhpesdoc->sql_query_file($this->getMatricula(), "rh16_regist");
        $rs = pg_query($sql);
        $matricula = pg_fetch_object($rs);
        if (empty($matricula)) {
            $rhpesdoc->incluir($this->getMatricula());
        } else {
            $rhpesdoc->rh16_regist = $this->getMatricula();
            $rhpesdoc->alterar($this->getMatricula());
        }
    }

    public static function findByMatricula($matricula)
    {
        $rhpesdoc = new cl_rhpesdoc();
        $sql = $rhpesdoc->sql_query_file($matricula, "*");
        $rs = pg_query($sql);
        $matricula = pg_fetch_object($rs);
        if (empty($matricula)) {
            return false;
        }
        return self::fromDao($matricula);
    }

    /**
     * @param stdClass $documento
     * @return ServidorDocumento
     */
    public static function fromDao(stdClass $documento)
    {
        $documentoModel = new self();
        $documentoModel->setMatricula($documento->rh16_regist);
        $documentoModel->setTituloDeEleitor($documento->rh16_titele);
        $documentoModel->setSecaoTituloDeEleitor($documento->rh16_secaoe);
        $documentoModel->setReservistaNumero($documento->rh16_reserv);
        $documentoModel->setReservistaCategoria($documento->rh16_catres);
        $documentoModel->setCtpsNumero($documento->rh16_ctps_n);
        $documentoModel->setCtpsSerie($documento->rh16_ctps_s);
        $documentoModel->setCtpsDigito($documento->rh16_ctps_d);
        $documentoModel->setCtpsUf($documento->rh16_ctps_uf);
        $documentoModel->setCtpsEmissao($documento->rh16_emissao);
        $documentoModel->setPis($documento->rh16_pis);
        $documentoModel->setCnhNumero($documento->rh16_carth_n);
        $documentoModel->setCnhCategoria($documento->r16_carth_cat);
        $documentoModel->setCnhValidade($documento->rh16_carth_val);
        $documentoModel->setCnhEmissao($documento->rh16_data_emissao_cnh);
        $documentoModel->setCnhEmissao($documento->rh16_data_emissao_cnh);
        $documentoModel->setCnhUf($documento->rh16_uf_cnh);
        $documentoModel->setOrgaoClasse($documento->rh16_orgao_classe);
        $documentoModel->setOrgaoClasseData($documento->rh16_data_orgao_classe);
        $documentoModel->setOrgaoClasseEmissor($documento->rh16_orgao_emissor_classe);
        $documentoModel->getOrgaoClasseValidade($documento->rh16_data_validade_orgao_classe);
        $documentoModel->setRneOrgaoEmissor($documento->rh16_orgao_emissor_rne);
        $documentoModel->setRneEmissao($documento->rh16_data_emissao_rne);
        $documentoModel->setRneEntrada($documento->rh16_data_entrada_rne);
        $documentoModel->setRneValidade($documento->rh16_data_validade_rne);
        $documentoModel->setRneRegistro($documento->rh16_registro_rne);
        return $documentoModel;
    }

}