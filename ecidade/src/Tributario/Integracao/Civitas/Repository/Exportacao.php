<?php
/**
 *     E-cidade Software protectedo para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca protecteda Geral GNU, conforme
 *  protectedada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca protecteda Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca protecteda Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Integracao\Civitas\Repository;

use ECidade\Tributario\Integracao\Civitas\Logger\RequestLogger;
use \stdClass;
use \db_utils;
use \DBDate;
use \cl_iptubase;
use \cl_lote;
use \cl_cargrup;
use \cl_iptuconstrhabite;
use \Lote;
use \Imovel;
use \Exception;

final class Exportacao
{
    /**
     * Busca informacoes da testada
     *
     * @param $idbql
     * @return string
     */
    private static function  getTestadas($idbql)
    {
        $sSql      = "select  *  from testada  where  j36_idbql  = ". $idbql ;
        $rsTestada = db_query($sSql);

        $aTestadas =  pg_fetch_all($rsTestada);

        $stringRetorno = "{";

        foreach ($aTestadas as $key => $testada) {


            $isPrincipal  = self::isPrincipal($testada['j36_idbql'], $testada['j36_face']);

            $stringRetorno .= "Cod_face:"  . $testada['j36_face'] . ";";
            $stringRetorno .= "cod_lograd:". $testada['j36_codigo'] . ";";
            $stringRetorno .= "Metragem_testada:". $testada['j36_testad'] . ";";
            $stringRetorno .= "Principal:". $isPrincipal . "";

            if (!empty($aTestadas[$key + 1])) {
                $stringRetorno .= ",";
            }

        }

        $stringRetorno .= "}";

        return $stringRetorno;
    }

    /**
     *  Retorna se testada e principal
     *
     * @param $idbql
     * @param $codigoFace
     * @return string
     */
    private static function isPrincipal($idbql, $codigoFace)
    {

        $sSql      = "select  *  from testpri  where  j49_idbql = ". $idbql. "  and j49_face = ". $codigoFace ;
        $rsTestadaPri = db_query($sSql);

        return  (pg_num_rows($rsTestadaPri) > 0 ?  "S" : "N");
    }


    /**
     * @param $mariucla
     * @return string
     */
    private static function getGlobalId($iMatricula)
    {
        $sWhere = "matricula = {$iMatricula}  OR  nova_matricula = {$iMatricula}";
        $sSqlAtualizacaoMatricula = "SELECT * FROM  cadastro.civitasinfoscomplementar WHERE ".$sWhere;
        $rsGlobalId   = db_query($sSqlAtualizacaoMatricula);

        if (!$rsGlobalId) {
            return "";
        }

        return   \db_utils::fieldsMemory($rsGlobalId, 0)->codigo_api;
    }


    /**
     * @param DBDate $dataInicio
     * @param null|DBDate$dataFinal
     * @return array
     * @throws Exception
     */
    public static function getDados(DBDate $dataInicio, $dataFinal = null)
    {
        $sData = $dataInicio->getDate();
        $oDaoCargrup = new cl_cargrup;
        $rsCargrup = $oDaoCargrup->sql_record($oDaoCargrup->sql_query_file(null, "*", "j32_grupo"));
        $aCaracteristicasDisponiveis = db_utils::getCollectionByRecord($rsCargrup);

        $oDaoLote = new cl_lote;

        $where = array("histocorrencia.ar23_data = '{$dataInicio->getDate()}'");
        if (!is_null($dataFinal)) {
            $where = array(
                "histocorrencia.ar23_data >= '{$dataInicio->getDate()}'",
                "histocorrencia.ar23_data <= '{$dataFinal->getDate()}'"
            );
        }

        $sql = "
        with matriculas_alteradas as (
          select ar25_matric as matricula, max(ar23_data) as data_alteracao
            from histocorrenciamatric
            join histocorrencia on histocorrencia.ar23_sequencial = histocorrenciamatric.ar25_histocorrencia
          where " . implode(' and ', $where)."
          group by 1
        ), lotes_alterados as (
          select j01_idbql, data_alteracao, matricula
            from iptubase
            join lote on j34_idbql = j01_idbql
            join matriculas_alteradas on matriculas_alteradas.matricula = iptubase.j01_matric
           where j01_baixa is null
        )
        select * from lotes_alterados order by data_alteracao;
        ";

        $rsSql = $oDaoLote->sql_record($sql);
        if (empty($rsSql)) {
            return array();
        }

        $aGeodados = db_utils::getCollectionByRecord($rsSql);
        $aDados = array();

        /**
         * Conversado com Thiago e visto que os dados devem vir com o ano da sessão
         */
        $anoSessao = db_getsession('DB_anousu');
        foreach ($aGeodados as $oGeodados) {

            $dataAlteracao = new DBDate($oGeodados->data_alteracao);
            $sData = $oGeodados->data_alteracao;
            $aLinha = array();
            $oLote = new Lote($oGeodados->j01_idbql);
            $infosTestada = self::getTestadas($oGeodados->j01_idbql);

            $aLinha["codigo_setor"]              = $oLote->getCodigoSetor();
            $aLinha["codigo_quadra"]             = $oLote->getQuadra();
            $aLinha["codigo_lote"]               = $oLote->getLote();
            $aLinha["idbql"]                     = $oLote->getCodigoLote();
            $aLinha["rua_codigo"]                = $oLote->getCodigoLogradouro();
            $aLinha["rua_nome"]                  = $oLote->getLogradouro();
            $aLinha["bairro_codigo"]             = $oLote->getCodigoBairro();
            $aLinha["bairro_descricao"]          = $oLote->getBairro();
            $aLinha["rua_cep"]                   = $oLote->getCep();
            $aLinha["lote_codigo_loteamento"]    = $oLote->getCodigoLoteamento();
            $aLinha["lote_descricao_loteamento"] = $oLote->getDescricaoLoteamento();
            $aLinha["lote_area"]                 = $oLote->getAreaLote();
            $aLinha["valor_testada_lote"]        = $oLote->getValorTestadaLote();
            $aLinha["rua_tipo_testada"]          = $oLote->getCodigoTipoLogradouro();
            $aLinha["rua_tipo_sigla_testada"]    = $oLote->getSiglaTipoLogradouro();
            $aLinha["testadas_lote"]             = $infosTestada;

            $aCaracteristicasFaceLote = array();
            $aCaracteristicasLote     = array();

            foreach ($oLote->getCaracteristicasFace() as $oCaracteristicaFace) {
                $aCaracteristicasFaceLote[$oCaracteristicaFace->iCodigoGrupo] = $oCaracteristicaFace;
            }

            foreach ($oLote->getCaracteristicasLote() as $oCaracteristicaLote) {
                $aCaracteristicasLote[$oCaracteristicaLote->iCodigoGrupo] = $oCaracteristicaLote;
            }

            $iFace = 1;
            $iLote = 1;
            $iConstrucao = 1;

            foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

                if($oCaracteristicaDisponivel->j32_tipo == 'F') {

                    $sCaracteristicaFaceTipo    = "caracteristicas_face_tipo_"     . $iFace;
                    $iCaracteristicaFaceIdGrupo = "caracteristicas_face_id_grupo_" . $iFace;
                    $sCaracteristicaFaceGrupo   = "caracteristicas_face_grupo_"    . $iFace;

                    $aLinha[$sCaracteristicaFaceTipo]    = $oCaracteristicaDisponivel->j32_tipo ;
                    $aLinha[$iCaracteristicaFaceIdGrupo] = $oCaracteristicaDisponivel->j32_grupo;
                    $aLinha[$sCaracteristicaFaceGrupo]   = $oCaracteristicaDisponivel->j32_descr;

                    $iCaracteristicaFaceId     = "caracteristicas_face_id_"        . $iFace;
                    $sCaracteristicaFaceCar    = "caracteristicas_face_descricao_" . $iFace;
                    $iCaracteristicaFacePontos = "caracteristicas_face_pontos_"    . $iFace;

                    if (isset($aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo])) {

                        $aLinha[$iCaracteristicaFaceId]     = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                        $aLinha[$sCaracteristicaFaceCar]    = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica;
                        $aLinha[$iCaracteristicaFacePontos] = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;

                    } else {

                        $aLinha[$iCaracteristicaFaceId]     = "";
                        $aLinha[$sCaracteristicaFaceCar]    = "";
                        $aLinha[$iCaracteristicaFacePontos] = "";
                    }

                    $iFace++;
                } else if ($oCaracteristicaDisponivel->j32_tipo == 'L') {

                    $sCaracteristicaLoteTipo    = "lote_tipo_"     . $iLote;
                    $iCaracteristicaLoteIdGrupo = "lote_id_grupo_" . $iLote;
                    $sCaracteristicaLoteGrupo   = "lote_grupo_"    . $iLote;

                    $aLinha[$sCaracteristicaLoteTipo]    = $oCaracteristicaDisponivel->j32_tipo ;
                    $aLinha[$iCaracteristicaLoteIdGrupo] = $oCaracteristicaDisponivel->j32_grupo;
                    $aLinha[$sCaracteristicaLoteGrupo]   = $oCaracteristicaDisponivel->j32_descr;

                    $iCaracteristicaLoteId     = "lote_id_"        . $iLote;
                    $sCaracteristicaLoteCar    = "lote_descricao_" . $iLote;
                    $iCaracteristicaLotePontos = "lote_pontos_"    . $iLote;

                    if (isset($aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo])) {
                        $aLinha[$iCaracteristicaLoteId]     = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                        $aLinha[$sCaracteristicaLoteCar]    = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica;
                        $aLinha[$iCaracteristicaLotePontos] = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;
                    } else {
                        $aLinha[$iCaracteristicaLoteId]     = '';
                        $aLinha[$sCaracteristicaLoteCar]    = '';
                        $aLinha[$iCaracteristicaLotePontos] = '';
                    }

                    $iLote++;

                } else if($oCaracteristicaDisponivel->j32_tipo == 'C') {

                    $sCaracteristicaConstrucaoTipo    = "construcao_tipo_"      . $iConstrucao;
                    $iCaracteristicaConstrucaoIdGrupo = "construcao_id_grupo_"  . $iConstrucao;
                    $sCaracteristicaConstrucaoGrupo   = "construcao_grupo_"     . $iConstrucao;
                    $iCaracteristicaConstrucaoId      = "construcao_id_"        . $iConstrucao;
                    $sCaracteristicaConstrucaoCar     = "construcao_descricao_" . $iConstrucao;
                    $iCaracteristicaConstrucaoPontos  = "construcao_pontos_"    . $iConstrucao;

                    $aLinha[$sCaracteristicaConstrucaoTipo]    = '';
                    $aLinha[$iCaracteristicaConstrucaoIdGrupo] = '';
                    $aLinha[$sCaracteristicaConstrucaoGrupo]   = '';
                    $aLinha[$iCaracteristicaConstrucaoId]      = '';
                    $aLinha[$sCaracteristicaConstrucaoCar]     = '';
                    $aLinha[$iCaracteristicaConstrucaoPontos]  = '';

                    $iConstrucao++;
                }
            }

                $oImovel = new Imovel($oGeodados->matricula);

                $aLinha["matricula"] = $oImovel->getMatricula();
                $aLinha["globalid"]  = self::getGlobalId($oImovel->getMatricula());

                $sSql  = " select 1 as iptu_calculo,                                                            ";
                $sSql .= "        (select 1 from arrecad where k00_numpre = j20_numpre limit 1) as iptu_aberto, ";
                $sSql .= "        (select 1 from arrepaga where k00_numpre = j20_numpre limit 1) as iptu_pago   ";
                $sSql .= "   from iptunump                                                                      ";
                $sSql .= "  where j20_matric = ".$aLinha["matricula"]."                                         ";
                $sSql .= "    and j20_anousu = {$anoSessao} ";

                $rsCalcIptu = db_query($sSql);

                if (empty($rsCalcIptu)) {
                    throw new Exception("Não foi possível consultar a situação do IPTU para a matricula {$aLinha["matricula"]}.");
                }

                $oCalcIptu = db_utils::fieldsMemory($rsCalcIptu, 0);

                $sSituacaoIptu = "Sem Calculo";

                if (!empty($oCalcIptu->iptu_aberto)) {
                    $sSituacaoIptu = "Em aberto";
                } else if (empty($oCalcIptu->iptu_aberto) and !empty($oCalcIptu->iptu_calculo) and !empty($oCalcIptu->iptu_pago)) {
                    $sSituacaoIptu = "Quitado";
                }

                $aLinha["situacao_iptu"] = $sSituacaoIptu;

                $sIsencao  = " select case when j17_descr is not null                                                                     ";
                $sIsencao .= "                       then 'SIM'                                                                           ";
                $sIsencao .= "                       else 'NAO'                                                                           ";
                $sIsencao .= "                     end as incide_taxa,                                                                    ";
                $sIsencao .= "                     j21_valor,                                                                             ";
                $sIsencao .= "                     case when iptucalhconf.j89_codhis is not null                                          ";
                $sIsencao .= "                       then (select sum(x.j21_valor)                                                        ";
                $sIsencao .= "                               from iptucalv x                                                              ";
                $sIsencao .= "                              where x.j21_anousu = iptucalv.j21_anousu                                      ";
                $sIsencao .= "                                and x.j21_matric = iptucalv.j21_matric                                      ";
                $sIsencao .= "                                and x.j21_receit = iptucalv.j21_receit                                      ";
                $sIsencao .= "                                and x.j21_codhis = iptucalhconf.j89_codhis)                                 ";
                $sIsencao .= "                       else 0                                                                               ";
                $sIsencao .= "                     end as j21_valorisen                                                                   ";
                $sIsencao .= "                from iptucalv                                                                               ";
                $sIsencao .= "                     inner join iptucalh on iptucalh.j17_codhis = j21_codhis                                ";
                $sIsencao .= "                     left join iptucalhconf on iptucalhconf.j89_codhispai = j21_codhis                      ";
                $sIsencao .= "                     inner join tabrec on tabrec.k02_codigo = j21_receit                                    ";
                $sIsencao .= "                     left join iptucadtaxaexe on iptucadtaxaexe.j08_tabrec = j21_receit                     ";
                $sIsencao .= "                                             and iptucadtaxaexe.j08_anousu = {$anoSessao} ";
                $sIsencao .= "               where j21_matric = ".$aLinha["matricula"]."                                                  ";
                $sIsencao .= "                 and j21_anousu = {$anoSessao}                                            ";
                $sIsencao .= "                 and j17_codhis not in (select j89_codhis from iptucalhconf) and j17_codhis = 2             ";
                $sIsencao .= "               order by iptucalh.j17_codhis                                                                 ";

                $rsIsencao = db_query($sIsencao);

                if (empty($rsIsencao)) {
                    throw new Exception("Não foi possível consultar a isenção de IPTU para a matricula {$aLinha["matricula"]}.");
                }

                $oIsencao = db_utils::fieldsMemory($rsIsencao, 0);

                $sIncideTaxa = "NAO";

                if (!empty($oIsencao->incide_taxa)) {
                    $sIncideTaxa = $oIsencao->incide_taxa;
                }

                $aLinha["incide_taxa"]        = $sIncideTaxa;
                $aLinha["valor_taxa"]         = $oIsencao->j21_valor + 0;
                $aLinha["valor_isencao_taxa"] = $oIsencao->j21_valorisen + 0;

                $sAtivEconomica  = " select j01_matric,                                                                               ";
                $sAtivEconomica .= "        array_accum(q02_inscr::varchar || ';' ||                                                  ";
                $sAtivEconomica .= "                    q07_ativ::varchar || ';' ||                                                   ";
                $sAtivEconomica .= "                    case when q71_estrutural is not null                                          ";
                $sAtivEconomica .= "                      then 'CNAE'                                                                 ";
                $sAtivEconomica .= "                      else 'CBO'                                                                  ";
                $sAtivEconomica .= "                    end || ';' ||                                                                 ";
                $sAtivEconomica .= "                    coalesce(q71_estrutural, '') || ';' ||                                        ";
                $sAtivEconomica .= "                    case when rh70_estrutural is null                                             ";
                $sAtivEconomica .= "                      then ''                                                                     ";
                $sAtivEconomica .= "                      else rh70_estrutural                                                        ";
                $sAtivEconomica .= "                    end                                                                           ";
                $sAtivEconomica .= "        ) as atividades_economicas                                                                ";
                $sAtivEconomica .= "   from iptubase                                                                                  ";
                $sAtivEconomica .= "        inner join issmatric on j01_matric = q05_matric                                           ";
                $sAtivEconomica .= "        inner join issbase on q05_inscr = q02_inscr                                               ";
                $sAtivEconomica .= "        left join iptubaixa on j01_matric = j02_matric                                            ";
                $sAtivEconomica .= "        inner join tabativ on q07_inscr = q02_inscr and q07_datafi is null and q07_databx is null ";
                $sAtivEconomica .= "        inner join ativid on q07_ativ = q03_ativ                                                  ";
                $sAtivEconomica .= "        left join atividcnae on atividcnae.q74_ativid = ativid.q03_ativ                           ";
                $sAtivEconomica .= "        left join cnaeanalitica on cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica    ";
                $sAtivEconomica .= "        left join cnae on cnae.q71_sequencial = cnaeanalitica.q72_cnae                            ";
                $sAtivEconomica .= "        left join atividcbo on atividcbo.q75_ativid = ativid.q03_ativ                             ";
                $sAtivEconomica .= "        left join rhcbo on rhcbo.rh70_sequencial = atividcbo.q75_rhcbo                            ";
                $sAtivEconomica .= "  where j02_matric is null and q02_dtbaix is null                                                 ";
                $sAtivEconomica .= "    and j01_matric = ".$aLinha["matricula"]."                                                     ";
                $sAtivEconomica .= "  group by j01_matric                                                                             ";
                $sAtivEconomica .= "  order by j01_matric                                                                             ";

                $rsAtividadesEconomicas = db_query($sAtivEconomica);

                if(empty($rsAtividadesEconomicas)){
                  throw new Exception("Erro");
                }

                $oAtividadesEconomicas = db_utils::fieldsMemory($rsAtividadesEconomicas, 0);

                $aLinha["atividades_economicas"] = $oAtividadesEconomicas->atividades_economicas;

                $aLinha["profundidade_padrao"] = 25;
                $aLinha["area_lote_vila"] = '';
                $aLinha["area_da_vila"] = '';

                $sqlDadosVila = "
                    select j34_area, j34_areal
                      from iptubase
                           join lote on j01_idbql = lote.j34_idbql
                           join carlote on j35_idbql = lote.j34_idbql
                    join caracter on caracter.j31_codigo = carlote.j35_caract
                    where j01_matric = {$aLinha['matricula']}
                     and j31_grupo = 9
                     and j31_codigo = 903
                ";
                $rsDadosVila = db_query($sqlDadosVila);
                if (pg_num_rows($rsDadosVila) > 0) {
                    $dadosVila = db_utils::fieldsMemory($rsDadosVila, 0);
                    $aLinha["area_lote_vila"] = $dadosVila->j34_area;
                    $aLinha["area_da_vila"] = $dadosVila->j34_areal;
                }

                /**
                 * Busca o valor do metro linear
                 */
                $sqlVlrLinear = "
                    select j90_valor
                      from iptubase
                      join lote on lote.j34_idbql = iptubase.j01_idbql
                      join lotesetorfiscal on lotesetorfiscal.j91_idbql = lote.j34_idbql
                      join setorfiscal on setorfiscal.j90_codigo = lotesetorfiscal.j91_codigo
                    where j01_matric = {$aLinha['matricula']}
                ";
                $aLinha["vlr_metro_linear_testada_v0"] = '';
                $rsVlrLinear = db_query($sqlVlrLinear);
                if (pg_num_rows($rsVlrLinear) > 0) {
                    $aLinha["vlr_metro_linear_testada_v0"] = db_utils::fieldsMemory($rsVlrLinear, 0)->j90_valor;
                }

                // unifit
                $aLinha["unifit"] = '';
                $sql = "select j18_vlrref from cfiptu where j18_anousu = {$anoSessao}";
                $rs = db_query($sql);
                if (pg_num_rows($rs) > 0) {
                    $aLinha["unifit"] = db_utils::fieldsMemory($rs, 0)->j18_vlrref;
                }

                // Alíquota
                $aLinha["aliquota"] = '';
                $sql = "select  j23_aliq from iptucalc where j23_anousu = {$anoSessao} and j23_matric = {$aLinha['matricula']} ";
                $rs = db_query($sql);
                if (pg_num_rows($rs) > 0) {
                    $aLinha["aliquota"] = db_utils::fieldsMemory($rs, 0)->j23_aliq;
                }

                $aLinha["proprietario"] = '';
                $oCgmProprietarioPrincipal = $oImovel->getProprietarioPrincipal();
                if (!empty($oCgmProprietarioPrincipal)) {
                    $aLinha["proprietario"] = $oCgmProprietarioPrincipal->getNome();
                }

                $aLinha["promitente"] = '';
                $oCgmPromitentePrincipal = $oImovel->getPromitentePrincipal();
                if (!empty($oCgmPromitentePrincipal)) {
                    $aLinha["promitente"] = $oCgmPromitentePrincipal->getNome();
                }

                $oImovelEndereco = $oImovel->getImovelEndereco();
                $aLinha["endereco_entrega"]             = $oImovelEndereco->getEndereco();
                $aLinha["endereco_entrega_numero"]      = $oImovelEndereco->getNumero();
                $aLinha["endereco_entrega_complemento"] = $oImovelEndereco->getComplemento();
                $aLinha["endereco_entrega_bairro"]      = $oImovelEndereco->getBairro();
                $aLinha["endereco_entrega_municipio"]   = $oImovelEndereco->getMunicipio();
                $aLinha["endereco_entrega_uf"]          = $oImovelEndereco->getUf();
                $aLinha["endereco_entrega_cep"]         = $oImovelEndereco->getCep();
                $aLinha["endereco_entrega_caixapostal"] = $oImovelEndereco->getCaixaPostal();
                $aLinha["referencia_anterior"]          = $oImovel->getReferenciaAnterior();

                $oIsencao = $oImovel->getDadosIsencaoExercicio();
                $aLinha["isencao_codigo"]    = $oIsencao->iTipoIsencao;
                $aLinha["isencao_descricao"] = $oIsencao->sDescricaoIsencao;

                $oCalculo = $oImovel->getCalculo();
                $aLinha["valor_venal_terreno"] = '';
                $aLinha["valor_venal_construcao"] = '';
                $aLinha["valor_venal_forcado"] = '';

                $sql = "
                  select j23_vlrter as valor_venal_terreno,
                         (select sum(j22_valor) from iptucale where j22_matric = j23_matric and j22_anousu = j23_anousu) as valor_venal_construcao
                    from iptucalc 
                   where j23_matric = {$aLinha['matricula']}  and j23_anousu = {$anoSessao}
                ";
                $rs = db_query($sql);
                if (pg_num_rows($rs) > 0) {
                    $calculo = db_utils::fieldsMemory($rs, 0);
                    $aLinha["valor_venal_terreno"] = $calculo->valor_venal_terreno;
                    $aLinha["valor_venal_construcao"] = $calculo->valor_venal_construcao;
                    $aLinha["valor_venal_forcado"] = $calculo->valor_venal_terreno + $calculo->valor_venal_construcao;
                }

                $aLinha["valor_iptu_terreno"] = '';
                if($oCalculoIptu = $oCalculo->getCalculoValorIptu()) {
                    $aLinha["valor_iptu_terreno"] = $oCalculoIptu->nValorTerreno * ($oCalculoIptu->nAliquota / 100);
                }

                $aConstrucoes = $oImovel->getConstrucoes();

                $aLinha["codigo_construcao"]         = '';
                $aLinha["construcao_numero"]         = '';
                $aLinha["construcao_complemento"]    = '';
                $aLinha["construcao_area"]           = '';
                $aLinha["ano_construcao"]            = '';
                $aLinha["data_demolicao"] = '';
                $aLinha["data_habite"]    = '';
                $aLinha["num_habite"]     = '';
                $aLinha["valor_venal_construcao"]    = '';
                $aLinha["valor_iptu_construcao"]     = '';
                $aLinha["tipo_imovel_2"] = "";
                $aLinha["pontos"] = "";

                $sql = "
                    select j22_pontos from iptucale
                    inner join iptuconstr on j22_matric = j39_matric and  j22_idcons = j39_idcons
                    where j22_matric = {$aLinha['matricula']} and j22_anousu = {$anoSessao}
                    limit 1
                ";
                $rs = db_query($sql);
                if (pg_num_rows($rs) > 0) {
                    $aLinha["pontos"] = db_utils::fieldsMemory($rs, 0)->j22_pontos;
                }

                $totalPontos = 0;
                $totalCaracteisticasPossiveis = 20;
                if(count($aConstrucoes) > 0) {
                    foreach ($aConstrucoes as $oConstrucao) {
                        $aLinha["codigo_construcao"]         = '';
                        $aLinha["construcao_numero"]         = '';
                        $aLinha["construcao_complemento"]    = '';
                        $aLinha["construcao_area"]           = '';
                        $aLinha["ano_construcao"]            = '';
                        $aLinha["data_demolicao"] = '';
                        $aLinha["data_habite"]    = '';
                        $aLinha["num_habite"]     = '';
                        $aLinha["valor_venal_construcao"]    = '';
                        $aLinha["valor_iptu_construcao"]     = '';
                        $aLinha["tipo_imovel_2"] = '';
                        $aLinha["codigo_construcao"]         = $oConstrucao->getCodigoConstrucao();
                        $aLinha["construcao_numero"]         = $oConstrucao->getNumeroEndereco();
                        $aLinha["construcao_complemento"]    = $oConstrucao->getComplementoEndereco();
                        $aLinha["construcao_area"]           = $oConstrucao->getArea();
                        $aLinha["ano_construcao"]            = $oConstrucao->getAnoConstrucao();
                        $aLinha["data_demolicao"] = $oConstrucao->getDataDemolicao();

                        $oHabite = $oConstrucao->getHabite();

                        $aLinha["num_habite"]  = isset($oHabite->ob09_habite) ? $oHabite->ob09_habite : '';
                        $aLinha["data_habite"] = isset($oHabite->ob09_data) ? $oHabite->ob09_data : '';

                        if ($oCalculoConstrucao = $oCalculo->getCalculoConstrucao($oConstrucao->getCodigoConstrucao())) {
                            $aLinha["valor_venal_construcao"] = $oCalculoConstrucao->nValor;
                            $aLinha["valor_iptu_construcao"]  = ($oCalculoConstrucao->nValor * ($oCalculoIptu->nAliquota / 100));

                        }

                        $aCaracteristicasConstrucao = array();

                        foreach ($oConstrucao->getCaracteristicasConstrucao() as $oCaracteristicaConstrucao) {
                            $aCaracteristicasConstrucao[$oCaracteristicaConstrucao->iCodigoGrupo] = $oCaracteristicaConstrucao;
                            if ($oCaracteristicaConstrucao->iCodigoGrupo == 19) {
                                $aLinha["tipo_imovel_2"] = $oCaracteristicaConstrucao->sCaracteristica;
                            }
                        }

                        $iConstrucao = 1;

                        foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

                            if($oCaracteristicaDisponivel->j32_tipo == 'C') {

                                $sCaracteristicaConstrucaoTipo    = "construcao_tipo_"     . $iConstrucao;
                                $iCaracteristicaConstrucaoIdGrupo = "construcao_id_grupo_" . $iConstrucao;
                                $sCaracteristicaConstrucaoGrupo   = "construcao_grupo_"    . $iConstrucao;

                                $aLinha[$sCaracteristicaConstrucaoTipo]    = $oCaracteristicaDisponivel->j32_tipo ;
                                $aLinha[$iCaracteristicaConstrucaoIdGrupo] = $oCaracteristicaDisponivel->j32_grupo;
                                $aLinha[$sCaracteristicaConstrucaoGrupo]   = $oCaracteristicaDisponivel->j32_descr;

                                $iCaracteristicaConstrucaoId     = "construcao_id_"        . $iConstrucao;
                                $sCaracteristicaConstrucaoCar    = "construcao_descricao_" . $iConstrucao;
                                $iCaracteristicaConstrucaoPontos = "construcao_pontos_"    . $iConstrucao;

                                if (isset($aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo])) {
                                    $aLinha[$iCaracteristicaConstrucaoId]     = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                                    $aLinha[$sCaracteristicaConstrucaoCar]    = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica;
                                    $aLinha[$iCaracteristicaConstrucaoPontos] = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;
                                    $totalPontos += $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;
                                } else {
                                    $aLinha[$iCaracteristicaConstrucaoId]     = '';
                                    $aLinha[$sCaracteristicaConstrucaoCar]    = '';
                                    $aLinha[$iCaracteristicaConstrucaoPontos] = '';
                                }

                                $iConstrucao++;
                            }
                        }
                        $caracteristicasExistentes = $iConstrucao - 1;

                        for ($i = $caracteristicasExistentes; $i <= $totalCaracteisticasPossiveis; $i++) {
                            $aLinha["construcao_tipo_{$i}"] = '';
                            $aLinha["construcao_id_grupo_{$i}"] = '';
                            $aLinha["construcao_grupo_{$i}"] = '';
                            $aLinha["construcao_id_{$i}"] = '';
                            $aLinha["construcao_descricao_{$i}"] = '';
                            $aLinha["construcao_pontos_{$i}"] = '';
                        }

                        $aDados[] = $aLinha;
                    }
                } else {

                    $aDados[] = $aLinha;
                }
        }

        return $aDados;
    }


    /**
     * @param DBDate $oData
     * @return array
     * @throws \DBException
     */
    public static function getMatriculasNovasRejeitadas(DBDate $oData)
    {
        $sData = $oData->getDate();

        $DaoAtualizaMatricula = new  \cl_atualizacaoiptuschemamatricula();
        $sSql = $DaoAtualizaMatricula->buscaMatriculasRejeitadas("codigo_api as global_id","j146_data = '{$sData}' and j144_matriculanova  is true");

        $rsMatriculasReitadas= db_query($sSql);

        if (!$rsMatriculasReitadas) {
              throw  new \DBException("Erro  buscar matrículas rejeitadas.");
        }

        $headers = \ECidade\Tributario\Integracao\Civitas\Arquivo\Exportacao::getHeader();

        $matriculas = \db_utils::makeCollectionFromRecord($rsMatriculasReitadas, function($dados) use ($headers) {
            
            $retorno = array();
            foreach ($headers as $key => $item) {
                $retorno[$key] = "";
            }
            $retorno["globalid"] = $dados->global_id;
            return $retorno;
        });

        return $matriculas;

    }

}


