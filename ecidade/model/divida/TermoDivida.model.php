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


class TermoDivida
{
  /**
   *
   */
    protected $iCodigo     = null;

    protected $aEnvolvidos = array();

    protected $aDebitos    = array();

    protected $dataEmissao = null;

    protected $iFolha      = 0;

    protected $iLivro      = 0;

    protected $iTipo       = 0;

    protected $dataLivro   = null;

    protected $massafalida = 0;

    protected $oDaoCertid  = null;

    protected $iAno        = null;

    protected $lComposicao = false;

    protected $oDataRecalculoJurosMulta = null;

  /**
   *
   */
    public function __construct($iCodigo)
    {

        db_utils::getDao('certid', false);

        if (!empty($iCodigo)) {
            $this->oDaoCertid    = new cl_certid;
            $sSqlDadosCertidao  = "select certid.v13_certid,";
            $sSqlDadosCertidao .= "       v13_dtemis,";
            $sSqlDadosCertidao .= "       coalesce(certidmassa.v13_certid) as v13_certidmassa,";
            $sSqlDadosCertidao .= "       v26_numerofolha as folha,";
            $sSqlDadosCertidao .= "       v25_numero as livro,";
            $sSqlDadosCertidao .= "       v25_datainc as datalivro,";
            $sSqlDadosCertidao .= "       (case when certter.v14_certid is not null then 1 ";
            $sSqlDadosCertidao .= "             when certdiv.v14_certid is not null then 2 end) as tipocertidao";
            $sSqlDadosCertidao .= "  from certid";
            $sSqlDadosCertidao .= "       left outer join certidlivrofolha on v26_certid         = v13_certid";
            $sSqlDadosCertidao .= "       left outer join certdiv          on certid.v13_certid = certdiv.v14_certid";
            $sSqlDadosCertidao .= "       left outer join certter          on certid.v13_certid = certter.v14_certid";
            $sSqlDadosCertidao .= "       left outer join certidlivro      on v26_certidlivro    = v25_sequencial ";
            $sSqlDadosCertidao .= "       left outer join certidmassa  on certidmassa.v13_certid = certid.v13_certid ";
            $sSqlDadosCertidao .= "  where certid.v13_certid = {$iCodigo} ";
            $sSqlDadosCertidao .= "  limit 1";

            $rsCertid           = $this->oDaoCertid->sql_record($sSqlDadosCertidao);

            if ($rsCertid != false && $this->oDaoCertid->numrows > 0) {
                $oDadosCertid      = db_utils::fieldsMemory($rsCertid, 0);
                $this->iCodigo     = $oDadosCertid->v13_certid;
                $this->dataEmissao = $oDadosCertid->v13_dtemis;
                $this->iFolha      = $oDadosCertid->folha;
                $this->iLivro      = $oDadosCertid->livro;
                $this->dataLivro   = $oDadosCertid->datalivro;
                $this->iTipo       = $oDadosCertid->tipocertidao;
                $this->massafalida = $oDadosCertid->v13_certidmassa;
                $this->iAno        = substr($oDadosCertid->v13_dtemis, 0, 4);
            }
        }
    }

  /**
   * @return unknown
   */
    public function getDataEmissao()
    {

        return $this->dataEmissao;
    }

  /**
   * @param unknown_type $dataEmissao
   */
    public function setDataEmissao($dataEmissao)
    {

        $this->dataEmissao = $dataEmissao;
    }

  /**
   * @return unknown
   */
    public function getDataLivro()
    {

        return $this->dataLivro;
    }

  /**
   * @param unknown_type $dataLivro
   */
    public function setDataLivro($dataLivro)
    {

        $this->dataLivro = $dataLivro;
    }

  /**
   * @return unknown
   */
    public function getCodigo()
    {

        return $this->iCodigo;
    }

  /**
   * @return unknown
   */
    public function getFolha()
    {

        return $this->iFolha;
    }

  /**
   * @param unknown_type $iFolha
   */
    public function setFolha($iFolha)
    {

        $this->iFolha = $iFolha;
    }

    public function setDataRecalculoJurosMulta($oDataRecalculoJurosMulta)
    {
        $this->oDataRecalculoJurosMulta = $oDataRecalculoJurosMulta;
    }

  /**
   * @return unknown
   */
    public function getLivro()
    {

        return $this->iLivro;
    }

  /**
   * @return unknown
   */
    public function getMassafalida()
    {

        return $this->massafalida;
    }

    public function getAno()
    {

        return $this->iAno;
    }


    public function getOrigensDebito()
    {

        $aOrigem = array();
        if ($this->getTipo() == 2) {
            $aOrigem = $this->getOrigemDebitoDivida();
        } elseif ($this->getTipo() == 1) {
            $aOrigem = $this->getOrigemDebitoParcelamento();
        }
        if (count($aOrigem) == 0) {
            throw new Exception("CDA {$this->getCodigo()} sem Débitos.");
        }

        return $aOrigem;
    }

    function getDevedoresEnvolvidos($sTipoEndereco = 'o')
    {

        $aParams  = db_stdClass::getParametro("pardiv", array(db_getsession("DB_instit")));

        if (count($aParams) == 0) {
            throw new Exception("Sem parametros para o módulo dívida configurados");
        }

        $oPardiv = $aParams[0];

        $sExpressaoFalecimento = "";
        if ($oPardiv->v04_confexpfalec != 2) {
            $sExpressaoFalecimento = $oPardiv->v04_expfalecimentocda;
        }

        if ($oPardiv->v04_envolprinciptu == "f") {
            $lRegra = "false";
        } else {
            $lRegra = "true";
        }

        $aMatric              = array();
        $aInscr               = array();
        $aCgm                 = array();
        $aImoveisEnvolvidos   = array();
        $aEmpresasEnvolvidos  = array();
        $aDevedoresEnvolvidos = array();
        $aOrigens = $this->getOrigensDebito();

        foreach ($aOrigens as $oOrigens) {
            if ($oOrigens->matric > 0 && in_array($oOrigens->matric, $aMatric)) {
                continue;
            } else {
                if ($oOrigens->matric > 0) {

                  /**
                   * Procuramos o texto para o possuidor da matricula
                   */
                    $sqlPossuidor    = " select j18_textoprom                           ";
                    $sqlPossuidor   .= "   from cfiptu                                  ";
                    $sqlPossuidor   .= "  where j18_anousu= ".db_getsession("DB_anousu") ;
                    $resultPossuidor = db_query($sqlPossuidor);
                    $linhasPossuidor = pg_num_rows($resultPossuidor);
                    $possuidor = "POSSUIDOR";
                    if ($linhasPossuidor > 0) {
                        $oTextoPossuido = db_utils::fieldsmemory($resultPossuidor, 0);
                        if (trim($oTextoPossuido->j18_textoprom) != "") {
                            $possuidor = $oTextoPossuido->j18_textoprom;
                        }
                    }

                /**
                 * Buscamos as matriculas da divida
                 */
                    $sSqlEnvol    = "
                      select *
                      from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiptu},'M',{$oOrigens->matric})";
                    $rsEnvol      = db_query($sSqlEnvol) or die($sSqlEnvol);
                    $iLinhasEnvol = pg_num_rows($rsEnvol);
                    if ($oPardiv->v04_envolcdaiptu == 2 && $iLinhasEnvol == 0) {
                        $sSqlEnvol  = " select j01_numcgm as rinumcgm,   ";
                        $sSqlEnvol .= "        1          as ritipoenvol ";
                        $sSqlEnvol .= "   from iptubase                  ";
                        $sSqlEnvol .= "  where j01_matric = {$oOrigens->matric}    ";
                        $rsEnvol      = db_query($sSqlEnvol) or die($sSqlEnvol);
                        $iLinhasEnvol = pg_num_rows($rsEnvol);
                    }

                    for ($i = 0; $i < $iLinhasEnvol; $i++) {
                        $oDevedor = new stdClass();
                        $oEnvol   = db_utils::fieldsMemory($rsEnvol, $i);

                        $sSqlDadosEnvol  = " select z01_numcgm,                     ";
                        $sSqlDadosEnvol .= "        z01_nome,                       ";
                        $sSqlDadosEnvol .= "        z01_cgccpf,                     ";
                        $sSqlDadosEnvol .= "        z01_telef,                      ";
                        $sSqlDadosEnvol .= "        z01_telcel,                     ";
                        $sSqlDadosEnvol .= "        z01_ender,                      ";
                        $sSqlDadosEnvol .= "        z01_numero,                     ";
                        $sSqlDadosEnvol .= "        z01_compl,                      ";
                        $sSqlDadosEnvol .= "        z01_bairro,                     ";
                        $sSqlDadosEnvol .= "        z01_munic,                      ";
                        $sSqlDadosEnvol .= "        z01_cep,                        ";
                        $sSqlDadosEnvol .= "        z01_uf,                         ";
                        $sSqlDadosEnvol .= "        z01_dtfalecimento               ";
                        $sSqlDadosEnvol .= "   from cgm                             ";
                        $sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";
                        $rsDadosEnvol      = db_query($sSqlDadosEnvol) or die($sSqlDadosEnvol);
                        $iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);
                        if ($iLinhasDadosEnvol > 0) {
                            $oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol, 0);
                            if (trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11
                            && $oDadosEnvol != '00000000000') {
                                  $oDevedor->nome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
                            } else {
                                $oDevedor->nome = $oDadosEnvol->z01_nome;
                            }
                            $oDevedor->numcgm   = $oDadosEnvol->z01_numcgm;
                            $oDevedor->telefone = $oDadosEnvol->z01_telef;
                            $oDevedor->celular  = $oDadosEnvol->z01_telcel;
                            $oDevedor->endereco = "";
                            $oDevedor->endereco = $oDadosEnvol->z01_ender != "" ? $oDadosEnvol->z01_ender : "";
                            if (trim($oDadosEnvol->z01_numero) !="0" and trim($oDadosEnvol->z01_numero)!="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->z01_numero} ";
                            }
                            if (trim($oDadosEnvol->z01_compl)  !="0" and trim($oDadosEnvol->z01_compl) !="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->z01_compl} ";
                            }
                            if (trim($oDadosEnvol->z01_bairro) !="0" and trim($oDadosEnvol->z01_bairro)!="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->z01_bairro} ";
                            }
                            if (trim($oDadosEnvol->z01_munic)  !="0" and trim($oDadosEnvol->z01_munic) !="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->z01_munic}/{$oDadosEnvol->z01_uf} ";
                            }
                            if (trim($oDadosEnvol->z01_cep) !="0" and trim($oDadosEnvol->z01_cep) !="") {
                                $oDevedor->endereco .= "- CEP {$oDadosEnvol->z01_cep}";
                            }

                        /**
                         * Verifica o tipo do Devedor
                         */
                            if ($oEnvol->ritipoenvol == "1" || $oEnvol->ritipoenvol == "2") {
                                $oDevedor->tipo = "PROPRIETÁRIO";
                            } else {
                                $oDevedor->tipo = $possuidor;
                            }

                            if (strlen($oDadosEnvol->z01_cgccpf) == 14) {
                                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf, "cnpj");
                            } else {
                                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf, "cpf");
                            }
                            $aDevedoresEnvolvidos[] = $oDevedor;
                        }
                    }

                /**
                 * Retornamos os dados do imovel
                 */
                    $sSqlProprietario  = " select *                    ";
                    $sSqlProprietario .= " from proprietario         ";
                    $sSqlProprietario .= " left join ruascep on proprietario.codpri = ruascep.j29_codigo";
                    $sSqlProprietario .= " where proprietario.j01_matric = $oOrigens->matric ";
                    $rsProprietario    = db_query($sSqlProprietario) or die($sSqlProprietario);
                    $oProprietario     = db_utils::fieldsMemory($rsProprietario, 0);
                    $oImovel           = new stdClass();

                    $sqlcidade = "select munic, uf, cep from db_config where codigo = ".db_getsession('DB_instit');
                    $resultcidade     = db_query($sqlcidade);
                    $oCidade          = db_utils::fieldsmemory($resultcidade, 0);
                    $oImovel->cep    = $oProprietario->j29_cep ? $oProprietario->j29_cep : $oCidade->cep;

                    /**
                     * quando solicitado  o endereço de origem
                     */

                    if ($sTipoEndereco == "o") {
                        $oImovel->endereco = $oProprietario->nomepri
                            . (isset($oProprietario->j39_numero) && $oProprietario->j39_numero != "" ?
                                ", " . $oProprietario->j39_numero : ""
                            )
                            . (isset($oProprietario->j39_compl) && $oProprietario->j39_compl != "" ?
                                ", " . $oProprietario->j39_compl : "");
                        $oImovel->bairro  = $oProprietario->j13_descr;
                        $oImovel->cidade = $oCidade->munic.' / '.$oCidade->uf;

                    } elseif ($sTipoEndereco == "c") {
                        $oImovel->endereco = $oProprietario->z01_ender.
                                 ($oProprietario->z01_numero != ""?', ' . $oProprietario->z01_numero:"").
                                 ($oProprietario->z01_compl != ""?"/" . $oProprietario->z01_compl:"");
                        $oImovel->bairro   = $oProprietario->z01_bairro;
                        $oImovel->cidade   = $oProprietario->z01_munic.' / '.$oProprietario->z01_uf;
                    }

                    $oImovel->setor       = $oProprietario->j34_setor;
                    $oImovel->quadra      = $oProprietario->j34_quadra;
                    $oImovel->lote        = $oProprietario->j34_lote;
                    $oImovel->matricula   = $oOrigens->matric;
                    $oImovel->matricula_ri  = $oOrigens->matric_ri;
                    
                    $oImovel->refanterior = $oProprietario->j40_refant;

                    $oImovel->setorloc      = $oProprietario->pql_localizacao;

                    $aImoveisEnvolvidos[] = $oImovel;
                    $aMatric[]            = $oOrigens->matric;
                }
            }

          /**
           * Verificando as inscrições
           */
            if ($oOrigens->inscr > 0 && in_array($oOrigens->inscr, $aInscr)) {
                continue;
            } else {
                if ($oOrigens->inscr > 0) {
                    $sSqlEnvol = "
                        select *
                        from fc_busca_envolvidos({$lRegra},{$oPardiv->v04_envolcdaiss},'I',{$oOrigens->inscr})";
                    $rsEnvol      = db_query($sSqlEnvol) or die($sSqlEnvol);
                    $iLinhasEnvol = pg_num_rows($rsEnvol);
                    for ($i = 0; $i < $iLinhasEnvol; $i++) {
                        $oDevedor = new stdClass();
                        $oEnvol = db_utils::fieldsMemory($rsEnvol, $i);
                        if (empty($oEnvol->rinumcgm)) {
                            continue;
                        }
                        $sSqlDadosEnvol  = " select z01_numcgm,                     ";
                        $sSqlDadosEnvol .= "        z01_nome,                       ";
                        $sSqlDadosEnvol .= "        z01_cgccpf,                     ";
                        $sSqlDadosEnvol .= "        z01_telef,                      ";
                        $sSqlDadosEnvol .= "        z01_telcel,                     ";
                        $sSqlDadosEnvol .= "        z01_numero,                     ";
                        $sSqlDadosEnvol .= "        z01_ender  as ender,            ";
                        $sSqlDadosEnvol .= "        z01_numero as numero,           ";
                        $sSqlDadosEnvol .= "        z01_compl  as compl,            ";
                        $sSqlDadosEnvol .= "        z01_bairro as bairro,           ";
                        $sSqlDadosEnvol .= "        z01_munic  as munic,            ";
                        $sSqlDadosEnvol .= "        z01_cep    as cep,              ";
                        $sSqlDadosEnvol .= "        z01_uf     as uf,               ";
                        $sSqlDadosEnvol .= "        z01_dtfalecimento               ";
                        $sSqlDadosEnvol .= "   from cgm                             ";
                        $sSqlDadosEnvol .= "  where z01_numcgm = {$oEnvol->rinumcgm}";
                        $rsDadosEnvol      = db_query($sSqlDadosEnvol);
                        $iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);
                        if ($iLinhasDadosEnvol > 0) {
                            $oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol, 0);

                            if (trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11) {
                                $oDevedor->nome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
                            } else {
                                $oDevedor->nome = $oDadosEnvol->z01_nome;
                            }

                            $oDevedor->numcgm   = $oDadosEnvol->z01_numcgm;
                            $oDevedor->telefone = $oDadosEnvol->z01_telef;
                            $oDevedor->celular  = $oDadosEnvol->z01_telcel;
                            $oDevedor->endereco = "";
                            $oDevedor->endereco = $oDadosEnvol->ender;
                            if (trim($oDadosEnvol->numero) !="0" and trim($oDadosEnvol->numero)!="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->numero} ";
                            }
                            if (trim($oDadosEnvol->compl)  !="0" and trim($oDadosEnvol->compl) !="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->compl} ";
                            }
                            if (trim($oDadosEnvol->bairro) !="0" and trim($oDadosEnvol->bairro)!="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->bairro} ";
                            }
                            if (trim($oDadosEnvol->munic)  !="0" and trim($oDadosEnvol->munic) !="") {
                                $oDevedor->endereco .= ",{$oDadosEnvol->munic}/{$oDadosEnvol->uf}";
                            }
                            if (trim($oDadosEnvol->cep)    !="0" and trim($oDadosEnvol->cep)   !="") {
                                $oDevedor->endereco .= "- CEP {$oDadosEnvol->cep} .";
                            }

                            if (strlen($oDadosEnvol->z01_cgccpf) > 11) {
                                if ($oEnvol->ritipoenvol == "4") {
                                    $oDevedor->tipo = "EMPRESA";
                                } elseif ($oEnvol->ritipoenvol == "5") {
                                    $oDevedor->tipo = "SÓCIO";
                                }
                            } else {
                                $oDevedor->tipo = "CONTRIBUINTE";
                            }

                            if (strlen($oDadosEnvol->z01_cgccpf) > 11) {
                                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf, "cnpj");
                            } else {
                                $oDevedor->cgcCpf = db_formatar($oDadosEnvol->z01_cgccpf, "cpf");
                            }
                        }
                        $aDevedoresEnvolvidos[] = $oDevedor;
                    }

                  /**
                   * Retorna os dados da Inscrição
                   */

                    $sql = "select
                                issruas.q02_inscr as inscricao,
                                case when ruastipo.j88_sigla != '' then ruastipo.j88_sigla || ' ' end ||
                                ruas.j14_nome || ', ' ||
                                issruas.q02_numero || ' ' ||
                                case when issruas.q02_compl != '' then issruas.q02_compl else '' end as ref_ao_alvara,
                                bairro.j13_descr bairro,
                                issruas.z01_cep cep
                            from issruas
                            inner join ruas on ruas.j14_codigo = issruas.j14_codigo
                            left join ruastipo on ruas.j14_tipo = ruastipo.j88_codigo
                            inner join issbairro on issruas.q02_inscr = issbairro.q13_inscr
                            inner join bairro on issbairro.q13_bairro = bairro.j13_codi
                            where issruas.q02_inscr = $oOrigens->inscr";

                    $rsSql = db_query($sql) or die($sql);
                    $dadosInscricao = db_utils::fieldsMemory($rsSql, 0);


                    $sql = "select
                                munic || ' / ' || uf as municipio_uf
                            from db_config
                            where codigo = " . db_getsession("DB_instit");

                    $rsSql = db_query($sql) or die($sql);
                    $dadosInstituicao = db_utils::fieldsMemory($rsSql, 0);

                    $oEmpresa->inscricao = $dadosInscricao->inscricao;
                    $oEmpresa->endereco  = $dadosInscricao->ref_ao_alvara;
                    $oEmpresa->bairro    = $dadosInscricao->bairro;
                    $oEmpresa->cidade    = $dadosInstituicao->municipio_uf;
                    $oEmpresa->cep       = $dadosInscricao->cep;
                    $aInscr[]            = $oOrigens->inscr;
                    $aEmpresasEnvolvidos[] = $oEmpresa;
                }
            }

          /**
           * Verificamos o CGM
           */
            if (in_array($oOrigens->numcgm, $aCgm)) {
                continue;
            } else {
                if ($oOrigens->matric == 0  && $oOrigens->inscr == 0) {
                    $sSqlCgm  = " select *                             ";
                    $sSqlCgm .= "   from cgm                           ";
                    $sSqlCgm .= "  where z01_numcgm = $oOrigens->numcgm";
                    $rsCgm = db_query($sSqlCgm) or die($sSqlCgm);
                    $oCgm  = db_utils::fieldsMemory($rsCgm, 0);
                    $oDevedor = new stdClass();
                    $oDevedor->endereco = $oCgm->z01_ender;

                    if (trim($oCgm->z01_numero)!="0" and trim($oCgm->z01_numero)!="") {
                        $oDevedor->endereco .= ",{$oCgm->z01_numero} ";
                    }
                    if (trim($oCgm->z01_compl)!="0" and trim($oCgm->z01_compl)!="") {
                        $oDevedor->endereco .= ",{$oCgm->z01_compl} ";
                    }
                    if (trim($oCgm->z01_bairro)!="0" and  trim($oCgm->z01_bairro)!="") {
                        $oDevedor->endereco .= ",{$oCgm->z01_bairro} ";
                    }
                    if (trim($oCgm->z01_munic) !="0" and trim($oCgm->z01_munic)!="") {
                        $oDevedor->endereco .= ",{$oCgm->z01_munic}/{$oCgm->z01_uf} ";
                    }

                    if (trim($oCgm->z01_cep) !="0" and trim($oCgm->z01_cep)!="") {
                        $oDevedor->endereco .= "- CEP {$oCgm->z01_cep} .";
                    }

                    $oDevedor->numcgm   = $oCgm->z01_numcgm;
                    $oDevedor->telefone = $oCgm->z01_telef;
                    $oDevedor->celular = $oCgm->z01_telcel;
                    $oDevedor->nome     = $oCgm->z01_nome;
                    if (strlen($oCgm->z01_cgccpf) > 11) {
                        $oDevedor->cgcCpf = db_formatar($oCgm->z01_cgccpf, 'cnpj');
                    } else {
                        $oDevedor->cgcCpf = db_formatar($oCgm->z01_cgccpf, 'cpf');
                    }
                    $oDevedor->tipo = "";
                    $aCgm[]    = $oOrigens->numcgm;
                    $aDevedoresEnvolvidos[] =  $oDevedor;
                }
            }
        }
        $oRetorno = new stdClass();
        $oRetorno->aDevedores = array_map("unserialize", array_unique(array_map("serialize", $aDevedoresEnvolvidos)));
        $oRetorno->aImoveis   = $aImoveisEnvolvidos;
        $oRetorno->aEmpresas  = $aEmpresasEnvolvidos;
        return $oRetorno;
    }

    function getDebitos($lRemissao = false)
    {

        $aDebitos = array();

        if ($this->getTipo() == 2) {
            $aDebitos = $this->getDebitosDivida($lRemissao);
        } elseif ($this->getTipo() == 1) {
            $aDebitos = $this->getDebitosParcelamento($lRemissao);
        }

      /**
       * Verificamos se existem procedencias que devemos agrupar, e
       * agrupamos com a seguinte lógica :
       *   - Criamos um hash com os campos exercício/parcela/origem/procedência (v24_procedagrupa)
       *   - Comparar exercício/parcela/origem/procedência
       *   - é somado todos os valores e e os outros campos (exercício/livro e folha/data inscrição/data vencimento)
       *     é utilizado sempre o do registro da procedência principal que está agrupando
       */

      /**
       * Array com todos os debitos agrupados
       */
        $aDebitosAgrupado    = array();

      /**
       * Debitos que sao agrupadores debitos sem procedenciaagrupa
       */
        $aDebitosAgrupadores = array();

      /**
       * Debitos com procedenciaagrupa
       */
        $aDebitosParaAgrupar = array();

      /**
       * Verificamos quais debitos estao configurados para agrupar
       */
        $i     = 0;
        $sHash = "";

        foreach ($aDebitos as $oOrigem) {
            if ($oOrigem->procedenciaagrupar != "") {
                $sHash = $oOrigem->exercicio.$oOrigem->numpar.$oOrigem->procedenciaagrupar;
                if (isset($aDebitosParaAgrupar[$sHash])) {
                    $aDebitosParaAgrupar[$sHash]->valorhistorico += $oOrigem->valorhistorico;
                    $aDebitosParaAgrupar[$sHash]->valorcorrigido += $oOrigem->valorcorrigido;
                    $aDebitosParaAgrupar[$sHash]->valorcorrecao  += $oOrigem->valorcorrigido - $oOrigem->valorhistorico;
                    $aDebitosParaAgrupar[$sHash]->valormulta     += $oOrigem->valormulta;
                    $aDebitosParaAgrupar[$sHash]->valorjuros     += $oOrigem->valorjuros;
                    $aDebitosParaAgrupar[$sHash]->valortotal     += $oOrigem->valortotal;
                } else {
                    $aDebitosParaAgrupar[$sHash] = $oOrigem;
                    $aDebitosParaAgrupar[$sHash]->hash = $sHash;
                }
            } else {
                $sHash = $oOrigem->exercicio.$oOrigem->numpar.$oOrigem->codigoprocedencia;
                $aDebitosAgrupadores[$i] = $oOrigem;
                $aDebitosAgrupadores[$i]->hash = $sHash;
                $i++;
            }
        }

        foreach ($aDebitosParaAgrupar as $sHash => $oDebitoAgrupar) {
            $iTotalDebitosAgrupadores = count($aDebitosAgrupadores);

            $lFound = '';
            for ($i=0; $i < $iTotalDebitosAgrupadores; $i ++) {
                if ($aDebitosAgrupadores[$i]->hash == $sHash) {
                    $aDebitosAgrupadores[$i]->valorhistorico += $oDebitoAgrupar->valorhistorico;
                    $aDebitosAgrupadores[$i]->valorcorrigido += $oDebitoAgrupar->valorcorrigido;
                    $aDebitosAgrupadores[$i]->valorcorrecao += $oDebitoAgrupar->valorcorrigido
                        - $oDebitoAgrupar->valorhistorico;
                    $aDebitosAgrupadores[$i]->valormulta     += $oDebitoAgrupar->valormulta;
                    $aDebitosAgrupadores[$i]->valorjuros     += $oDebitoAgrupar->valorjuros;
                    $aDebitosAgrupadores[$i]->valortotal     += $oDebitoAgrupar->valortotal;
                    $lFound = true;
                    break;
                } else {
                    $lFound = false;
                }
            }

            if (!$lFound) {
                $aDebitosAgrupadores[] = $oDebitoAgrupar;
            }
        }

        ksort($aDebitosAgrupadores);

      /**
       * Percorremos os outros debitos e fizemso os agrupamentos
       */
        unset($aDebitosParaAgrupar);

        return $aDebitosAgrupadores;
    }

    public function getProcedencias()
    {

        if ($this->getTipo() == 1) {
            require_once(modification("classes/db_termo_classe.php"));
            $aOrigens     = $this->getOrigensDebito();
            $oDaoTermo    = new cl_termo;
            $campos       = " distinct v01_proced";
            $sProcedencia = "";
            $sVirgula     = "";
            $aProcedenciasAgrupadas = array();
            foreach ($aOrigens as $oOrigem) {
                $sqlProc        = $oDaoTermo->sql_query_origem_divida($oOrigem->numpre, $campos, true);
                $rsProcedencias = $oDaoTermo->sql_record($sqlProc);
                $aProcedencias  = db_utils::getCollectionByRecord($rsProcedencias);
                foreach ($aProcedencias as $oProcedencia) {
                    $aProcedenciasAgrupadas[$oProcedencia->v01_proced] = $oProcedencia->v01_proced;
                }
            }
        } elseif ($this->getTipo() == 2) {
            $sqlDadosDivida  = "select distinct v01_proced ";
            $sqlDadosDivida .= "  from certdiv  ";
            $sqlDadosDivida .= "       inner join divida           on v14_coddiv = v01_coddiv ";
            $sqlDadosDivida .= "                                  and v01_instit = ".db_getsession('DB_instit');
            $sqlDadosDivida .= " where certdiv.v14_certid = {$this->getCodigo()}";
            $rsDadosDivida   = $this->oDaoCertid->sql_record($sqlDadosDivida);
            $aProced         = db_utils::getCollectionByRecord($rsDadosDivida);
            foreach ($aProced as $oProced) {
                $aProcedenciasAgrupadas[$oProced->v01_proced] = $oProced->v01_proced;
            }
        }

        if (count($aProcedenciasAgrupadas) > 1) {
            $sSqlPRocedAgrupa = "Select * from procedenciaagrupa where v24_proced in("
                . implode(",", $aProcedenciasAgrupadas) . ")";
            $rsProcedAgrupa = db_query($sSqlPRocedAgrupa);
            $aProcedencias = db_utils::getCollectionByRecord($rsProcedAgrupa);
            foreach ($aProcedencias as $oProcedAgrupa) {
                if (in_array($oProcedAgrupa->v24_procedagrupa, $aProcedenciasAgrupadas)) {
                    $aProcedenciasAgrupadas[$oProcedAgrupa->v24_procedagrupa] = $oProcedAgrupa->v24_procedagrupa;
                    unset($aProcedenciasAgrupadas[$oProcedAgrupa->v24_proced]);
                }
            }
        }
        return $aProcedenciasAgrupadas;
    }
  /*
   *  Asl
   *
   */
    public function setComposicao($lComposicao = false)
    {

        $this->lComposicao = $lComposicao;
    }
}
