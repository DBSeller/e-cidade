<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009 DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_utils.php"));
require_once(modification('libs/db_libsys.php'));
require_once(modification("std/DBLargeObject.php"));
require_once(modification("model/processoProtocolo.model.php"));

class CertidaoExistencia
{
    /**
     * Numero da Certidão de Existencia
     * @var integer
     */
    private $iCodigoCertidao;

    /**
     * Numero do Usuario emissor da certidao
     * @var integer
     */
    private $iCodigoUsuario;

    /**
     * Codigo do Imóvel da Certidão
     * @var integer
     */
    private $iMatricula;

    /**
     * código da construção do imovel
     * @var integer
     */
    private $iCodigoConstrucao;

    /**
     * data da emissao da acertidão
     * @var string (data)
     */
    private $dtEmissao;

    /**
     * hora da emissão da certidão
     * @var string (hora)
     */
    private $sHoraEmissao;

    /**
     * nome do arquivo gerado
     * @var string
     */
    private $fArquivo;

    /**
     * observação relacionada a certidao da construção
     * @var string
     */
    private $sObservacao;

    /**
     * flag que identifica se o processo selecionado pertence ao sistema
     * @var boolean
     */
    private $lProcessoSistema;

    /**
     * numero do processo selecionado
     * @var string
     */
    private $sCodigoProcesso;

    /**
     * titular do processo,será preeenchido se o processo selecionado nao pertencer ao sistema
     * @var string
     */
    private $sTitularProcesso;

    /**
     * data do processo, será nula se o processo não pertencer ao sistema
     * @var string (date)
     */
    private $dtProcesso;

    /**
     * Dados do Processo
     * @var std_class
     */
    private $oDadosProcesso;

    /**
     * Caminho aonde o arquivo com o documento esta salvo localmente0
     * @var string
     */
    private $sCaminhoArquivo = "tmp/__certidaoExistencia.sxw";

    private $iOidArquivo;

    /**
     * Area do Lote
     * @var string
     */
    private $sAreaLote;

    /**
     * Area Real do Lote
     * @var string
     */
    private $sAreaRealLote;

    /**
     * Area Construida
     * @var string
     */
    private $sAreaConstruida;

    /**
     * Area Real Construida
     * @var string
     */
    private $sAreaRealConst;

    /**
     * Numero Habite-se
     * @var string
     */
    private $sNumHabite;

    /**
     * Data Habite-se
     * @var string (date)
     */
    private $dtHabite;

    /**
     * Construtor da Classe
     * @param integer $iCodigoCertidao
     * @throws exception
     */
    function __construct($iCodigoCertidao = null)
    {
        $this->setCodigoCertidao($iCodigoCertidao);

        if (!empty($iCodigoCertidao)) {
            require_once(modification("classes/db_certidaoexistencia_classe.php"));

            $sCampos = "*, (SELECT CASE tipo_processo 
										WHEN 0 THEN NULL
	                                 	WHEN 1 THEN j133_processo
	                                 	WHEN 2 THEN (SELECT p58_numero||'/'||p58_ano FROM protprocesso WHERE p58_codproc = j134_protprocesso::integer)
	                                 END) AS processo,
	                        (SELECT CASE tipo_processo 
										WHEN 0 THEN NULL
	                                 	WHEN 1 THEN j133_titulaprocesso
	                                 	WHEN 2 THEN (SELECT p58_requer FROM protprocesso WHERE p58_codproc = j134_protprocesso::integer)
	                                 END) AS j133_titulaprocesso,
	                        (SELECT CASE tipo_processo 
										WHEN 0 THEN NULL
	                                 	WHEN 1 THEN j133_dtprocesso
	                                 	WHEN 2 THEN (SELECT p58_dtproc FROM protprocesso WHERE p58_codproc = j134_protprocesso::integer)
	                                 END) AS j133_data
 							FROM (SELECT *,
       									CASE
								           WHEN j133_processo IS NULL AND j134_certidaoexistencia IS NULL THEN 0
								           WHEN j133_processo IS NOT NULL AND j134_certidaoexistencia IS NULL THEN 1
								           WHEN j133_processo IS NOT NULL AND j134_certidaoexistencia IS NOT NULL THEN 2
								        END AS tipo_processo";
            $oDaoCertidaoExistencia = db_utils::getDao("certidaoexistencia");

            $sSqlCertidaoExistencia = $oDaoCertidaoExistencia->sql_queryDadosCertidao($iCodigoCertidao, $sCampos);
            $sSqlCertidaoExistencia .= ") as x";
            $rsCertidaoExistencia = $oDaoCertidaoExistencia->sql_record($sSqlCertidaoExistencia);
            $oCertidaoExistencia = db_utils::fieldsmemory($rsCertidaoExistencia, 0);

            $this->setDadosProcesso($oCertidaoExistencia->processo,
              $oCertidaoExistencia->j133_titulaprocesso,
              $oCertidaoExistencia->j133_data);

            $this->setCodigoConstrucao($oCertidaoExistencia->j133_iptuconstr);
            $this->setCodigoUsuario($oCertidaoExistencia->j133_db_usuarios);
            $this->setDataEmissao($oCertidaoExistencia->j133_data);
            $this->setHoraEmissao($oCertidaoExistencia->j133_hora);
            $this->setMatricula($oCertidaoExistencia->j133_matric);
            $this->setObservacao($oCertidaoExistencia->j133_observacao);
            $this->setAreaLote($oCertidaoExistencia->j133_arealote);
            $this->setAreaRealLote($oCertidaoExistencia->j133_areareallote);
            $this->setAreaConstruida($oCertidaoExistencia->j133_areaconstruida);
            $this->setAreaRealConst($oCertidaoExistencia->j133_arearealconstruida);
            $this->setNumHabite($oCertidaoExistencia->j133_numhabite);
            $this->setDatHabite($oCertidaoExistencia->j133_dathabite);
            $this->setOidArquivo($oCertidaoExistencia->j133_arquivo);
        }
    }

    /**
     * Retorna o Código da Certidão
     * @return $iCodigoCertidao
     */
    public function getCodigoCertidao()
    {
        return $this->iCodigoCertidao;
    }

    /**
     * Define o Código da Certidão
     * @param $iCodigoCertidao
     */
    private function setCodigoCertidao($iCodigoCertidao)
    {
        $this->iCodigoCertidao = $iCodigoCertidao;
    }

    /**
     * Retorna o Usuario da Emissao da Certidão
     * @return integer $iCodigoUsuario
     */
    public function getCodigoUsuario()
    {
        return $this->iCodigoUsuario;
    }

    /**
     * Define o Usuario da Emissao da Certidão
     * @param $iCodigoUsuario
     */
    public function setCodigoUsuario($iCodigoUsuario)
    {
        $this->iCodigoUsuario = $iCodigoUsuario;
    }

    /**
     * Retorna Código da Matrícula da Certidão
     * @return integer $iMatricula
     */
    public function getMatricula()
    {
        return $this->iMatricula;
    }

    /**
     * Define Código da Matrícula da Certidão
     * @param $iMatricula
     */
    public function setMatricula($iMatricula)
    {
        $this->iMatricula = $iMatricula;
    }

    /**
     * Retorna Código da construção Certidão
     * @return integer $iCodigoConstrucao
     */
    public function getCodigoConstrucao()
    {
        return $this->iCodigoConstrucao;
    }

    /**
     * Define Código da construção Certidão
     * @param $iCodigoConstrucao
     */
    public function setCodigoConstrucao($iCodigoConstrucao)
    {
        $this->iCodigoConstrucao = $iCodigoConstrucao;
    }

    /**
     * Retorna Data de Emissao Certidão
     * @return string $dtEmissao
     */
    public function getDataEmissao()
    {
        return $this->dtEmissao;
    }

    /**
     * Define Data de Emissao Certidão
     * @param $dtEmissao
     */
    public function setDataEmissao($dtEmissao)
    {
        $this->dtEmissao = $dtEmissao;
    }

    /**
     * Retorna Hora da Emissao Certidão
     * @return string $sHoraemissao
     */
    public function getHoraEmissao()
    {
        return $this->sHoraEmissao;
    }

    /**
     * Define Hora da Emissao Certidão
     * @param $sHoraEmissao
     */
    public function setHoraEmissao($sHoraEmissao)
    {
        $this->sHoraEmissao = $sHoraEmissao;
    }

    /**
     * retorna o nome do arquivo
     * @return string $fArquivo
     */
    public function getCaminhoArquivo()
    {
        return $this->sCaminhoArquivo;
    }

    /**
     * Define o nome do arquivo
     * @param $fArquivo
     */
    private function setCaminhoArquivo($sCaminhoArquivo)
    {
        $this->sCaminhoArquivo = $sCaminhoArquivo;
    }

    /**
     * retorna a observação da certidao
     * @return string $sObservacao
     */
    public function getObservacao()
    {
        return $this->sObservacao;
    }

    /**
     * Define a observacao da certidao
     * @param $sObservacao
     */
    public function setObservacao($sObservacao)
    {
        $this->sObservacao = $sObservacao;
    }

    /**
     * retorna se o processo da certidao é ou não do sistema
     * @return boolean $lProcessoSistema
     */
    public function isProcessoSistema()
    {
        return $this->lProcessoSistema;
    }

    /**
     * define se o oprocesso é ou não do sistema
     * @param $lProcessoSistema
     */
    public function setProcessoSistema($lProcessoSistema)
    {
        $this->lProcessoSistema = $lProcessoSistema;
    }

    /**
     * retorna o codigo do processo da certidao
     * @return string $sCodigoProcesso
     */
    public function getDadosProcesso()
    {
        return $this->oDadosProcesso;
    }

    /**
     * retorna a area do lote da certidao
     * @return float $sAreaLote
     */
    public function getAreaLote()
    {
        return $this->sAreaLote;
    }

    /**
     * Define a area do lote da certidao
     * @param $sAreaLote
     */
    public function setAreaLote($sAreaLote)
    {
        $this->sAreaLote = $sAreaLote;
    }

    /**
     * retorna a area real do lote da certidao
     * @return float $sAreaRealLote
     */
    public function getAreaRealLote()
    {
        return $this->sAreaRealLote;
    }

    /**
     * Define a area real do lote da certidao
     * @param $sAreaRealLote
     */
    public function setAreaRealLote($sAreaRealLote)
    {
        $this->sAreaRealLote = $sAreaRealLote;
    }

    /**
     * retorna a area construida lote da certidao
     * @return float $sAreaConstruida
     */
    public function getAreaConstruida()
    {
        return $this->sAreaConstruida;
    }

    /**
     * Define a area construida do lote da certidao
     * @param $sAreaConstruida
     */
    public function setAreaConstruida($sAreaConstruida)
    {
        $this->sAreaConstruida = $sAreaConstruida;
    }

    /**
     * retorna a area real construida lote da certidao
     * @return float $sAreaRealConst
     */
    public function getAreaRealConst()
    {
        return $this->sAreaRealConst;
    }

    /**
     * Define a area real construida do lote da certidao
     * @param $sAreaRealConst
     */
    public function setAreaRealConst($sAreaRealConst)
    {
        $this->sAreaRealConst = $sAreaRealConst;
    }

    /**
     * retorna o numero do Habite-se da certidao
     * @return string $sNumHabite
     */
    public function getNumHabite()
    {
        return $this->sNumHabite;
    }

    /**
     * Define o numero do Habite-se certidao
     * @param $sNumHabite
     */
    public function setNumHabite($sNumHabite)
    {
        $this->sNumHabite = $sNumHabite;
    }

    /**
     * retorna a data do Habite-se da certidao
     * @return string(date) $dtHabite
     */
    public function getDatHabite()
    {
        return $this->dtHabite;
    }

    /**
     * Define a data do Habite-se certidao
     * @param $dtHabite
     */
    public function setDatHabite($dtHabite)
    {
        $this->dtHabite = $dtHabite;
    }

    /**
     * Define os Dados do Processo
     * @param string $sCodigoProcesso
     * @param null $sTitular
     * @param null $dtProcesso
     * @throws exception
     */
    public function setDadosProcesso($sCodigoProcesso, $sTitular = null, $dtProcesso = null)
    {
        $oDadosProcesso = new stdClass();

        if ($this->isProcessoSistema()) {
            if (empty($sCodigoProcesso)) {
                throw new exception("Código do Processo não informado");
            }
            $oProcesso = new processoProtocolo($sCodigoProcesso);
            $oDadosProcesso->sCodigoProcesso = $oProcesso->getCodProcesso();
            $oDadosProcesso->sTitularProcesso = $oProcesso->getRequerente();
            $oDadosProcesso->sDataProcesso = $oProcesso->getDataProcesso();
        } else {
            $oDadosProcesso->sCodigoProcesso = $sCodigoProcesso;
            $oDadosProcesso->sTitularProcesso = $sTitular;
            $oDadosProcesso->sDataProcesso = $dtProcesso;
        }

        $this->oDadosProcesso = $oDadosProcesso;
    }

    /**
     *
     */
    public function getOidArquivo()
    {
        return $this->iOidArquivo;
    }

    /**
     *
     * @param integer $iOid
     */
    private function setOidArquivo($iOid)
    {
        $this->iOidArquivo = $iOid;
    }

    /**
     * Gera documento do open office
     */
    public function geraArquivoOpenOffice()
    {
        require_once(modification('std/db_stdClass.php'));
        require_once(modification('dbagata/classes/core/AgataAPI.class'));
        require_once(modification('model/documentoTemplate.model.php'));

        $sArquivoAgt = "cadastro/certidao_existencia_parte1.agt";
        $iTipo = 18;
        $oDaoCfIPTU = db_utils::getDao('cfiptu');
        $oParametrosCadastroImobiliario = $oDaoCfIPTU->getParametrosCadastroImobiliario(db_getsession("DB_anousu"));
        if (!$oParametrosCadastroImobiliario) {
            throw new Exception('[1] - Erro o buscar parametros do Módulo Cadastro Imobiliário.' . $oDaoCfIPTU->erro_msg);
        }

        $iModeloImpressao = $oParametrosCadastroImobiliario[0]->j18_templatecertidaoexitencia;

        ini_set("error_reporting", "E_ALL & ~NOTICE");
        $oAgata = new cl_dbagata($sArquivoAgt);
        $oApiAgata = $oAgata->api;

        $oApiAgata->setOutputPath($this->getCaminhoArquivo());
        $oApiAgata->setParameter('$iCodigoCertidaoExistencia', $this->getCodigoCertidao());
        try {
            $oDocumentoTemplate = new documentoTemplate($iTipo, $iModeloImpressao, '', true);
        } catch (Exception $eException) {

            $sErroMsg = $eException->getMessage();
            throw new Exception("Erro ao Buscar Documento Template: {$sErroMsg}");
        }
        $sArquivo = $oDocumentoTemplate->getArquivoTemplate();

        $lGeracaoArquivo = $oApiAgata->parseOpenOffice($sArquivo);
        if (!$lGeracaoArquivo) {
            throw new Exception("Erro ao Gerar Arquivo.");
        }

        $iOidGerado = DBLargeObject::escrita($this->getCaminhoArquivo(), $this->getOidArquivo());

        return $iOidGerado;
    }

    /**
     * Salva os dados da certidão
     */
    public function salvar()
    {
        if (!db_utils::inTransaction()) {
            throw new Exception("Sem transacao Ativa");
        }
        /**
         * Executa geracao do Arquivo do OpenOffice
         */
        $iOIDArquivo = DBLargeObject::criaOID(true);
        $oDadosProcesso = $this->getDadosProcesso();
        $oDaoCertidaoExistecia = db_utils::getDao("certidaoexistencia");

        $oDaoCertidaoExistecia->j133_db_usuarios = $this->getCodigoUsuario();
        $oDaoCertidaoExistecia->j133_matric = $this->getMatricula();
        $oDaoCertidaoExistecia->j133_iptuconstr = $this->getCodigoConstrucao();
        $oDaoCertidaoExistecia->j133_data = $this->getDataEmissao();
        $oDaoCertidaoExistecia->j133_hora = $this->getHoraEmissao();
        $oDaoCertidaoExistecia->j133_arquivo = $iOIDArquivo;
        $oDaoCertidaoExistecia->j133_observacao = utf8_decode($this->getObservacao());
        $oDaoCertidaoExistecia->j133_processo = $oDadosProcesso->sCodigoProcesso;
        $oDaoCertidaoExistecia->j133_titulaprocesso = utf8_decode($oDadosProcesso->sTitularProcesso);
        $oDaoCertidaoExistecia->j133_dtprocesso = $oDadosProcesso->sDataProcesso;
        $oDaoCertidaoExistecia->j133_arealote = $this->getAreaLote();
        $oDaoCertidaoExistecia->j133_areareallote = $this->getAreaRealLote();
        $oDaoCertidaoExistecia->j133_areaconstruida = $this->getAreaConstruida();
        $oDaoCertidaoExistecia->j133_arearealconstruida = $this->getAreaRealConst();
        $oDaoCertidaoExistecia->j133_numhabite = $this->getNumHabite();
        $oDaoCertidaoExistecia->j133_dathabite = $this->getDatHabite();

        if ($this->isProcessoSistema()) {
            $oDaoCertidaoExistecia->j133_processo = "";
            $oDaoCertidaoExistecia->j133_titulaprocesso = "";
            $oDaoCertidaoExistecia->j133_dtprocesso = "";
        }

        $oDaoCertidaoExistecia->incluir(null);

        $this->setCodigoCertidao($oDaoCertidaoExistecia->j133_sequencial);

        if ((int)$oDaoCertidaoExistecia->erro_status == 0) {
            throw new Exception("CertdidãoExistecia:Salvar: " . $oDaoCertidaoExistecia->erro_msg);
        }

        if ($this->isProcessoSistema()) {
            $oDaoCertidaoExisteciaProtProcesso = db_utils::getDao("certidaoexistenciaprotprocesso");
            $oDaoCertidaoExisteciaProtProcesso->j134_certidaoexistencia = $this->getCodigoCertidao();
            $oDaoCertidaoExisteciaProtProcesso->j134_protprocesso = $oDadosProcesso->sCodigoProcesso;
            $oDaoCertidaoExisteciaProtProcesso->incluir("");

            if ($oDaoCertidaoExisteciaProtProcesso->erro_status == "0") {
                throw new Exception("CertdidãoExisteciaProtProcesso:Salvar: " . $oDaoCertidaoExisteciaProtProcesso->erro_msg);
            }
        }

        $this->setOidArquivo($iOIDArquivo);
    }
}
