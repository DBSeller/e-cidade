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

namespace ECidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2018;
//use Ecidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2017\Dirf as Dirf2017;

class Dirf extends \ECidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2017\Dirf
{
    public function __construct($iAno, $sCnpj)
    {
        parent::__construct($iAno, $sCnpj);
        $this->setCodigoLayout(297);
    }

    public function getInformacoesComplementares($iCgm, $buscaCpf = false) {

        /**
         * Buscas os dados da base B932 que contem as rubricas de desconto dos planos de saúde,
         * o valor de desconto total de cada uma delas é somada com base no ano base da geração da DIRF
         */
        $sWhereBaseRubricas  = " select distinct r09_rubric                       ";
        $sWhereBaseRubricas .= "  from basesr                                     ";
        $sWhereBaseRubricas .= " where r09_anousu  =    ".\db_anofolha();
        $sWhereBaseRubricas .= "   and r09_mesusu  =    ".\db_mesfolha();
        $sWhereBaseRubricas .= "   and r09_base    IN ('B901', 'B914' , 'B932')   ";
        $sWhereBaseRubricas .= "   and r09_instit  = " . db_getsession("DB_instit");

        $aTabelasCalculo         = array('gerfsal' => 'r14', 'gerfcom' => 'r48', 'gerfres' => 'r20', 'gerffer' => 'r31');
        $aQueryCalculoFinanceiro = array();

        foreach ($aTabelasCalculo as $sTabela => $sSigla) {

          $sSqlCalculoFinanceiro     = " select sum({$sSigla}_valor) as valor,rh27_descr                                             ";
          $sSqlCalculoFinanceiro    .= "   from {$sTabela}                                                                           ";
          $sSqlCalculoFinanceiro    .= "  inner join rhrubricas on rh27_rubric = {$sSigla}_rubric and rh27_instit = {$sSigla}_instit ";
          $sSqlCalculoFinanceiro    .= "  inner join rhpessoal on rh01_regist  = {$sSigla}_regist                                    ";
          $sSqlCalculoFinanceiro    .= " where {$sSigla}_rubric in ( {$sWhereBaseRubricas} )                                         ";
          $sSqlCalculoFinanceiro    .= "   and {$sSigla}_anousu  = {$this->iAno}                                                     ";
          $sSqlCalculoFinanceiro    .= "   and rh01_numcgm = {$iCgm}                                                                 ";
          $sSqlCalculoFinanceiro    .= "   and {$sSigla}_instit  = " . \db_getsession("DB_instit");
          $sSqlCalculoFinanceiro    .= " group by {$sSigla}_rubric, rh01_numcgm, rh27_descr                                          ";
          $aQueryCalculoFinanceiro[] = $sSqlCalculoFinanceiro;
        }

        $sInformacaoComplementar = '';
        $sSqlBaseInfPlanoSaude   = implode($aQueryCalculoFinanceiro, 'union');
        $rsBaseInfPlanoSaude     = \db_query($sSqlBaseInfPlanoSaude);

        if ($rsBaseInfPlanoSaude && pg_num_rows($rsBaseInfPlanoSaude) > 0) {

          for ($iRubricaPlano = 0; $iRubricaPlano < pg_num_rows($rsBaseInfPlanoSaude); $iRubricaPlano++) {

            $oRubricaPlanoSaude       = \db_utils::fieldsMemory($rsBaseInfPlanoSaude, $iRubricaPlano);
            $aNomeRubrica             = explode(" ", $oRubricaPlanoSaude->rh27_descr);
            $sInformacaoComplementar .= "{$aNomeRubrica[0]}(".trim(db_formatar($oRubricaPlanoSaude->valor, 'f')).")";
          }
        }

        return $sInformacaoComplementar;
    }
}
