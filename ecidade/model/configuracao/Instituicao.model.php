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

/**
 * Class Instituicao
 */
class Instituicao
{
    /**
     * Caminho das mensagens
     * @type string
     */
    const MENSAGEM = 'configuracao.configuracao.Instituicao.';

    const TIPO_PREFEITURA = 1;
    const TIPO_CAMARA = 2;
    const TIPO_SECRETARIA_DA_EDUCACAO = 3;
    const TIPO_SECRETARIA_DA_SAUDE = 4;
    const TIPO_RPPS_EXCETO_AUTARQUIA = 5;
    const TIPO_AUTARQUIA_RPPS = 6;
    const TIPO_AUTARQUIA_EXCETO_RPPS = 7;
    const TIPO_FUNDACAO = 8;
    const TIPO_EMPRESA_ESTATAL_DEPENDENTE = 9;
    const TIPO_EMPRESA_ESTATAL_NAO_DEPENDENTE = 10;
    const TIPO_CONSORCIO = 11;
    const TIPO_OUTRAS = 12;
    const TIPO_MINISTERIO_PUBLICO_ESTADUAL = 101;
    const TIPO_TRIBUNAL_DE_JUSTICA = 13;
    const TIPO_TRIBUNAL_DE_CONTAS_ESTADO = 14;

    /**
     * Código da Instituicao
     *
     * @var integer
     */
    protected $iSequencial;

    /**
     * Descricao da Instituicao
     *
     * @var string
     */
    protected $sDescricao;

    /**
     * Boolean prefeitura
     *
     * @var boolean
     */
    protected $lPrefeitura;

    /**
     * CGM da instituicao
     * @var CgmBase
     */
    protected $oCgm;

    /**
     * Tipo de poder instituicao
     *
     * @var integer
     * @access protected
     */
    protected $iTipo;

    /**
     * Código do Cliente
     * @var integer
     */
    protected $iCodigoCliente;

    /**
     * CNPJ
     * @var string
     */
    protected $sCNPJ;

    /**
     * Retorna o Município da instituição
     * @var string
     */
    protected $sMunicipio;
    /**
     * Retorna o estado com o prefixo Estado   
     * @var string
     */
    protected $sUfExtenso;

    /**
     * Retorna o endereço da instituição
     * @var string
     */
    protected $sLogradouro;

    /**
     * Descrição Abreviada do Nome Prefeitura
     * @var string
     */
    protected $sDescricaoAbreviada;

    /**
     * Descricao do Bairro
     * @var string
     */
    protected $sBairro;

    /**
     * Numero Telefone da Prefeitura
     * @var string
     */
    protected $sTelefone;

    /**
     * Site da Prefeitura
     * @var string
     */
    protected $sSite;

    /**
     * Email da Prefeitura
     * @var string
     */
    protected $sEmail;

    /**
     * Código IBGE do Municipio
     * @var string
     */
    protected $sIbge;

    /**
     * Numero do CGM da prefeitura
     * @var string
     */
    protected $iNumeroCgm;

    /**
     * Retorna o Logo definido à instituição
     * @var string
     */
    protected $sImagemLogo;

    /**
     * Retorna Numero
     * @var string
     */
    protected $sNumero;

    /**
     * Complemento da Prefeitura
     * @var string
     */
    protected $sComplemento;

    /**
     * Retorna o Estado da Prefeitura
     * @var string
     */
    protected $sUf;

    /**
     * Retorna o Cep da Prefeitura
     * @var string
     */
    protected $sCep;

    /**
     * Retorna Numero do Fax
     * @var string
     */
    protected $sFax;

    /**
     * Código sequencial do CGM
     * @var integer
     */
    protected $iCodigoCGM;

    /**
     * @var boolean
     */
    protected $lUsaSisagua;

    /** @var string */
    protected $sCodigoSiconfi;

    /** @var integer */
    protected $codigoTribunal;

    protected $regraDebitosISSQN = 0;

    protected $regraDebitosIPTU = 0;

    /** @var bool */
    protected $unidadeGestoraRpps = false;

    /** @var int */
    protected $esferaOrgaoPublico;

    /** @var float */
    protected $valorTetoRemuneratorio = 0;

    /** @var bool */
    protected $isEnteFederativoResp = false;

    /** @var string */
    protected $cnpjEfr;

    /** @var bool */
    protected $efrPrevidenciaComplementar = false;

    /** @var bool */
    protected $possuiRpps = false;

    /** @var int */
    protected $tipoPoder;

    /**
     * Instituicao constructor.
     * @param null $iSequencial
     * @throws DBException
     */
    public function __construct($iSequencial = null)
    {
        if ($iSequencial != null) {
            $oDaoDBConfig = new cl_db_config;

            $sCampos = "nomeinst, prefeitura, db21_tipoinstit, numcgm, cgc, munic, logo, nomeinstabrev, ender, numero,";
            $sCampos .= "telef, url, uf, db21_compl, email, db21_usasisagua, cep, bairro, db21_regracgmiss, db21_regracgmiptu, ";
            $sCampos .= " (select db21_codcli from db_config where prefeitura is true) as db21_codcli, db21_codsiconfi, codtrib,";
            $sCampos .= "db21_unidade_gestora_rpps, db21_esfera_op, db21_valor_teto_remuneratorio, db21_ente_federativo_resp,";
            $sCampos .= "db21_cnpj_efr, db21_efr_previdencia_compl, db21_possui_rpps, db21_tipopoder, tribinst";
            
            $sSqlDBConfig = $oDaoDBConfig->sql_query_file($iSequencial, $sCampos);
            $rsDBConfig = $oDaoDBConfig->sql_record($sSqlDBConfig);
            if (!$rsDBConfig || $oDaoDBConfig->erro_status == "0") {
                throw new DBException(_M(self::MENSAGEM . 'instituicao_nao_encontrada'));
            }

            if ($oDaoDBConfig->numrows > 0) {
                $oDadoInstituicao = db_utils::fieldsMemory($rsDBConfig, 0);
                $this->sDescricao = $oDadoInstituicao->nomeinst;
                $this->lPrefeitura = $oDadoInstituicao->prefeitura;
                $this->iSequencial = $iSequencial;
                $this->iTipo = $oDadoInstituicao->db21_tipoinstit;
                $this->iCodigoCGM = $oDadoInstituicao->numcgm;
                $this->iCodigoCliente = $oDadoInstituicao->db21_codcli;
                $this->sCNPJ = $oDadoInstituicao->cgc;
                $this->sMunicipio = $oDadoInstituicao->munic;
                $this->sEmail = $oDadoInstituicao->email;
                $this->sSite = $oDadoInstituicao->url;
                $this->sImagemLogo = $oDadoInstituicao->logo;
                $this->sLogradouro = $oDadoInstituicao->ender;
                $this->sUf = $oDadoInstituicao->uf;
                $this->sNumero = $oDadoInstituicao->numero;
                $this->sComplemento = $oDadoInstituicao->db21_compl;
                $this->sTelefone = $oDadoInstituicao->telef;
                $this->sDescricaoAbreviada = $oDadoInstituicao->nomeinstabrev;
                $this->lUsaSisagua = $oDadoInstituicao->db21_usasisagua == 't';
                $this->sCodigoSiconfi = $oDadoInstituicao->db21_codsiconfi;
                $this->codigoTribunal = $oDadoInstituicao->codtrib;
                $this->sCep = $oDadoInstituicao->cep;
                $this->sBairro = $oDadoInstituicao->bairro;
                $this->setRegraDebitosIPTU($oDadoInstituicao->db21_regracgmiptu);
                $this->setRegraDebitosISSQN($oDadoInstituicao->db21_regracgmiss);
                $this->sBairro = $oDadoInstituicao->bairro;
                $this->unidadeGestoraRpps = $oDadoInstituicao->db21_unidade_gestora_rpps == 't';
                $this->esferaOrgaoPublico = $oDadoInstituicao->db21_esfera_op;
                $this->valorTetoRemuneratorio = $oDadoInstituicao->db21_valor_teto_remuneratorio;
                $this->isEnteFederativoResp = $oDadoInstituicao->db21_ente_federativo_resp == 't';
                $this->cnpjEfr = $oDadoInstituicao->db21_cnpj_efr;
                $this->efrPrevidenciaComplementar = $oDadoInstituicao->db21_efr_previdencia_compl == 't';
                $this->possuiRpps = $oDadoInstituicao->db21_possui_rpps == 't';
                $this->tipoPoder = $oDadoInstituicao->db21_tipopoder;
                $this->tribInst = $oDadoInstituicao->tribinst;

                $this->setUfExtenso($oDadoInstituicao->uf);
            }
        }
    }

    /**
     * Retorna Instituição SIAPC/PAD.
     * @return integer
     */
    public function getTribInst()
    {
        return $this->tribInst;
    }

    /**
     * Retorna Sequencial da Inscricao
     * @return integer
     */
    public function getSequencial()
    {
        return $this->iSequencial;
    }

    /**
     * Retorna codigo da instituicao
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iSequencial;
    }

    /**
     * Retorna Descricao Instituicao
     * @return string
     */
    public function getDescricao()
    {
        return $this->sDescricao;
    }

    public function getUfExtenso() {        
        return $this->sUfExtenso;
    }

    /**
     * Retorna Descricao Instituicao Abreviada
     * @return string
     */
    public function getDescricaoAbreviada()
    {
        return $this->sDescricaoAbreviada;
    }

    /**
     * Retorna boolean referente a pertencer ou nao a Prefeitura
     * @return integer
     * @deprecated
     * @see prefeitura
     */
    public function isPrefeitura()
    {
        return $this->lPrefeitura;
    }

    /**
     * @return bool
     */
    public function prefeitura()
    {
        return $this->lPrefeitura == 't';
    }


    /**
     * Seta Sequencial da Inscricao
     * @param integer
     */
    public function setSequencial($iSequencial)
    {
        $this->iSequencial = $iSequencial;
    }

    /**
     * Seta Descricao Instituicao
     * @param string
     */
    public function setDescricao($sDescricao)
    {
        $this->sDescricao = $sDescricao;
    }

    /**
     * Seta boolean referente a pertencer ou nao a Prefeitura
     * @param integer
     */
    public function setPrefeitura($lPrefeitura)
    {
        $this->lPrefeitura = $lPrefeitura;
    }

    /**
     * Retorna o objeto CGM da instituicao
     * @return CgmFisico|CgmJuridico
     */
    public function getCgm()
    {
        if (!empty($this->iCodigoCGM)) {
            $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoCGM);
        }
        return $this->oCgm;
    }

    /**
     * Retorna o tipo de poder que a instituição pertence
     *
     * @access public
     * @return integer
     */
    public function getTipo()
    {
        return $this->iTipo;
    }

    /**
     * Define o tipo de poder que a instituição pertence
     * @param integer $iTipo
     * @access public
     * @return void
     */
    public function setTipo($iTipo)
    {
        $this->iTipo = $iTipo;
    }

    /**
     * Retorna o código do cliente
     * @return integer
     */
    public function getCodigoCliente()
    {
        return $this->iCodigoCliente;
    }

    /**
     * CNPJ da Instituição
     */
    public function getCNPJ()
    {
        return $this->sCNPJ;
    }

    /**
     * Retorna o Município da Instituição
     * @return string
     */
    public function getMunicipio()
    {
        return $this->sMunicipio;
    }

    /**
     * Retorna o logo da prefeitura
     * @return string
     */
    public function getImagemLogo()
    {
        return $this->sImagemLogo;
    }

    /**
     * Retorna Logradouro da Prefeitura
     * @return string
     */
    public function getLogradouro()
    {
        return $this->sLogradouro;
    }

    /**
     * Retorna o Bairro da Prefeitura
     * @return string
     */
    public function getBairro()
    {
        return $this->sBairro;
    }

    /**
     * Retorna o Telefone
     * @return string
     */
    public function getTelefone()
    {
        return $this->sTelefone;
    }

    /**
     * Retorna o Site da Prefeitura
     * @return string
     */
    public function getSite()
    {
        return $this->sSite;
    }

    /**
     * Retorna o Email
     * @return string
     */
    public function getEmail()
    {
        return $this->sEmail;
    }

    /**
     * Retorna o Bairro da Prefeitura
     * @return string
     */
    public function getCodigoIbge()
    {
        return $this->sIbge;
    }

    /**
     * Retorna o Numero do Cgm
     * @return integer
     */
    public function getNumeroCgm()
    {
        return $this->iNumeroCgm;
    }

    /**
     * Retorna o Numero do Predio da prefeitura
     * @return string
     */
    public function getNumero()
    {
        return $this->sNumero;
    }

    /**
     * Retorna complemento da prefeitura
     * @return string
     */
    public function getComplemento()
    {
        return $this->sComplemento;
    }

    /**
     * Retorna o estado em que a prefeitura pertence
     * @return string
     */
    public function getUf()
    {
        return $this->sUf;
    }

    /**
     * Retorna o Cep da prefeitura
     * @return string
     */
    public function getCep()
    {
        return $this->sCep;
    }

    /**
     * Retorna o numero do Fax
     * @return string
     */
    public function getFax()
    {
        return $this->sFax;
    }


    /**
     * Retorna a Instituição por tipo
     * @return Instituicao
     * @throws DBException
     */
    public function getDadosPrefeitura()
    {
        $oDaoDBConfig = new cl_db_config();

        $sCampos = "nomeinst, db21_tipoinstit, numcgm, cgc, munic, logo, nomeinstabrev, ender, munic, ";
        $sCampos .= "bairro, telef, url, email, db21_codigomunicipoestado, numero, db21_compl, uf, cep, fax, db21_codcli, db21_codsiconfi ";
        $sSqlDBConfig = $oDaoDBConfig->sql_query_file(null, $sCampos, null, "prefeitura is true");
        $rsDBConfig = $oDaoDBConfig->sql_record($sSqlDBConfig);
        if (!$rsDBConfig || $oDaoDBConfig->erro_status == "0") {
            throw new DBException(_M(self::MENSAGEM . 'instituicao_nao_encontrada'));
        }

        $oRetorno = new StdClass();
        if ($oDaoDBConfig->numrows > 0) {

            $oDadoInstituicao = db_utils::fieldsMemory($rsDBConfig, 0);
            $this->sDescricao = $oDadoInstituicao->nomeinst;
            $this->sDescricaoAbreviada = $oDadoInstituicao->nomeinstabrev;
            $this->sCNPJ = $oDadoInstituicao->cgc;
            $this->sLogradouro = $oDadoInstituicao->ender;
            $this->sMunicipio = $oDadoInstituicao->munic;
            $this->sBairro = $oDadoInstituicao->bairro;
            $this->sTelefone = $oDadoInstituicao->telef;
            $this->sSite = $oDadoInstituicao->url;
            $this->sEmail = $oDadoInstituicao->email;
            $this->iNumeroCgm = $oDadoInstituicao->numcgm;
            $this->sImagemLogo = $oDadoInstituicao->logo;
            $this->sNumero = $oDadoInstituicao->numero;
            $this->sComplemento = $oDadoInstituicao->db21_compl;
            $this->sUf = $oDadoInstituicao->uf;
            $this->sCep = $oDadoInstituicao->cep;
            $this->sFax = $oDadoInstituicao->fax;
            $this->iCodigoCliente = $oDadoInstituicao->db21_codcli;
            $this->sCodigoSiconfi = $oDadoInstituicao->db21_codsiconfi;

            $oDaoMunicipio = new cl_cadendermunicipiosistema();

            $sWhere = "     to_ascii(db72_descricao, 'LATIN1') = to_ascii('{$oDadoInstituicao->munic}') ";
            $sWhere .= " and db71_sigla                         = '{$oDadoInstituicao->uf}'    ";
            $sWhere .= " and db125_db_sistemaexterno            = 4                            ";
            $sSqlMunicipio = $oDaoMunicipio->sql_query(null, 'db125_codigosistema', null, $sWhere);
            $rsCodigoIbgeMunicipio = $oDaoMunicipio->sql_record($sSqlMunicipio);
            if ($rsCodigoIbgeMunicipio && pg_num_rows($rsCodigoIbgeMunicipio) > 0) {
                $this->sIbge = db_utils::fieldsMemory($rsCodigoIbgeMunicipio, 0)->db125_codigosistema;
            }

            $this->setUfExtenso($oDadoInstituicao->uf);

        }

        return $this;
    }

    /**
     * Seta a Uf por extenso 
     * @param siglaUF 
     */
    public function setUfExtenso($siglaUF){
        $oDaoEstado = new cl_db_uf();
        $sWhere = " db12_uf                         = '{$siglaUF}'    ";
        $sSqlUf = $oDaoEstado->sql_query(null, 'db12_extenso', null, $sWhere);
        $rsUfExtenso = $oDaoEstado->sql_record($sSqlUf);
        if ($rsUfExtenso && pg_num_rows($rsUfExtenso) > 0) {
            $this->sUfExtenso = db_utils::fieldsMemory($rsUfExtenso, 0)->db12_extenso;
        }
    }

    /**
     * @return boolean
     */
    public function getUsaSisagua()
    {
        return $this->lUsaSisagua;
    }

    /**
     * @param boolean $lUsaSisagua
     */
    public function setUsaSisagua($lUsaSisagua)
    {
        $this->lUsaSisagua = $lUsaSisagua;
    }

    /**
     * Retorna código SiConfi.
     *
     * @return string
     */
    public function getCodigoSiconfi()
    {
        return $this->sCodigoSiconfi;
    }

    /**
     * Define código SiConfi.
     *
     * @param string $sCodigoSiconfi
     *
     * @return Instituicao
     */
    public function setCodigoSiconfi($sCodigoSiconfi)
    {
        $this->sCodigoSiconfi = $sCodigoSiconfi;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCodigoTribunal()
    {
        return $this->codigoTribunal;
    }

    /**
     * @param integer $codigoTribunal
     *
     * @return Instituicao
     */
    public function setCodigoTribunal($codigoTribunal)
    {
        $this->codigoTribunal = $codigoTribunal;
        return $this;
    }

    /**
     * @return int
     */
    public function getRegraDebitosISSQN()
    {
        return $this->regraDebitosISSQN;
    }

    /**
     * @param int $regraDebitosISSQN
     */
    public function setRegraDebitosISSQN($regraDebitosISSQN)
    {
        $this->regraDebitosISSQN = $regraDebitosISSQN;
    }

    /**
     * @return int
     */
    public function getRegraDebitosIPTU()
    {
        return $this->regraDebitosIPTU;
    }

    /**
     * @param int $regraDebitosIPTU
     */
    public function setRegraDebitosIPTU($regraDebitosIPTU)
    {
        $this->regraDebitosIPTU = $regraDebitosIPTU;
    }

    public function setUnidadeGestoraRpps($unidadeGestoraRpps)
    {
        $this->unidadeGestoraRpps = $unidadeGestoraRpps;
    }

    public function setEsferaOrgaoPublico($esferaOrgaoPublico)
    {
        $this->esferaOrgaoPublico = $esferaOrgaoPublico;
    }

    public function setValorTetoRemuneratorio($valorTetoRemuneratorio)
    {
        $this->valorTetoRemuneratorio = $valorTetoRemuneratorio;
    }

    public function setIsEnteFederativoResp($isEnteFederativoResp)
    {
        $this->isEnteFederativoResp = $isEnteFederativoResp;
    }

    public function setEfrPrevidenciaComplementar($efrPrevidenciaComplementar)
    {
        $this->efrPrevidenciaComplementar = $efrPrevidenciaComplementar;
    }

    public function setPossuiRpps($possuiRpps)
    {
        $this->possuiRpps = $possuiRpps;
    }

    public function setCnpjEfr($cnpjEfr)
    {
        $this->cnpjEfr = $cnpjEfr;
    }

    public function setTipoPoder($tipoPoder)
    {
        $this->tipoPoder = $tipoPoder;
    }

    public function getUnidadeGestoraRpps()
    {
        return $this->unidadeGestoraRpps;
    }

    public function getEsferaOrgaoPublico()
    {
        return $this->esferaOrgaoPublico;
    }

    public function getValorTetoRemuneratorio()
    {
        return $this->valorTetoRemuneratorio;
    }

    public function getIsEnteFederativoResp()
    {
        return $this->isEnteFederativoResp;
    }

    public function getEfrPrevidenciaComplementar()
    {
        return $this->efrPrevidenciaComplementar;
    }

    public function getPossuiRpps()
    {
        return $this->possuiRpps;
    }

    public function getCnpjEfr()
    {
        return $this->cnpjEfr;
    }

    public function getTipoPoder()
    {
        return $this->tipoPoder;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'descricao' => $this->getDescricao(),
            'cgm' => $this->getCgm() instanceof CgmBase ? $this->getCgm()->toArray() : null,
            'cnpj' => $this->getCNPJ()
        );
    }
}
