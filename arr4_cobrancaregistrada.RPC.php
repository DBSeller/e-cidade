<?php
/**
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Factory;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use \cl_remessacobrancaregistrada as RemessaCobrancaRegistrada;
use \cl_conveniocobranca as ConvenioCobranca;
use \db_utils as DbUtils;
use \Exception as Exception;
use \DBDate as DBDate;

$oJson    = \JSON::create();
$oRetorno = new stdClass();

$oRetorno->erro = false;
$oRetorno->sMensagem = '';

$oParametros = $oJson->parse(str_replace("\\", "", $_POST["json"]));

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "getRemessasGeradas":

      if (empty($oParametros->sDataEmissaoInicio) or empty($oParametros->sDataEmissaoFim)) {
        throw new Exception("Campo Data de Emissão é de preenchimento obrigatório.");
      }

      $oDataEmissaoInicio = new DBDate($oParametros->sDataEmissaoInicio);
      $sDataEmissaoInicio = $oDataEmissaoInicio->getDate(DBDate::DATA_PTBR);

      $oDataEmissaoFim = new DBDate($oParametros->sDataEmissaoFim);
      $sDataEmissaoFim = $oDataEmissaoFim->getDate(DBDate::DATA_PTBR);

      $sWhere  = "k147_instit = ".db_getsession("DB_instit");
      $sWhere .= " and k147_dataemissao between '{$sDataEmissaoInicio}' and '{$sDataEmissaoFim}' ";

      if (!empty($oParametros->iConvenio)) {
        $sWhere .= " and k147_convenio = {$oParametros->iConvenio} ";
      }

      $oRemessaCobrancaRegistrada = new RemessaCobrancaRegistrada();
      $sSqlRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_query(
        null,
        "remessacobrancaregistrada.*, cadconvenio.ar11_nome",
        "k147_sequencialremessa desc",
        $sWhere
      );

      $rsRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_record($sSqlRemessaCobrancaRegistrada);

      if (!empty($oRemessaCobrancaRegistrada->erro_banco)) {
        throw new Exception("Erro ao buscar remessas.");
      }

      if ($oRemessaCobrancaRegistrada->numrows == 0) {
        throw new Exception("Nenhum registro encontrado para os filtros selecionados!");
      }

      $arrTipos = array();
      $oRetorno->aRemessasGeradas = DbUtils::makeCollectionFromRecord($rsRemessaCobrancaRegistrada, function($oItem) {

        $oDataEmissao = new DBDate($oItem->k147_dataemissao);

        $arrAux = (strpos($oItem->k147_tiposemissao, '.')) ? explode('.', $oItem->k147_tiposemissao) : array($oItem->k147_tiposemissao);
        $strTipos = "";

        if($oItem->k147_tiposemissao == 0){
          $strTipos = "Emissao Geral";
        } else {          
          foreach ($arrAux as $value) {
            if(!array_key_exists($value, $arrTipos)){
              $rsArretipo = db_query("select * from arretipo where k00_tipo = $value::integer;");

              if (! $rsArretipo) {                    
                  throw new DBException("Erro ao buscar tipo");                    
              }

              $dados = db_utils::fieldsMemory($rsArretipo, 0);

              $arrTipos[$value] = $dados->k00_descr;
            }
            $strTipos .= ', '.$arrTipos[$value];
          }
          $strTipos = substr($strTipos, 2);
        }

        return (object) array(
          "codigo"          => $oItem->k147_sequencial,
          "sequencial"      => $oItem->k147_sequencialremessa,
          "codigo_convenio" => $oItem->k147_convenio,
          "nome_convenio"   => $oItem->ar11_nome,
          "data"            => $oDataEmissao->getDate(DBDate::DATA_PTBR),
          "hora"            => $oItem->k147_horaemissao,
          "tipos"           => $strTipos
        );
      });

      break;

    case "getRemessaGeradaBaixar":

      $iSequencial = $oParametros->iSequencial;

      if (empty($iSequencial)) {
        throw new Exception("Campo sequencial da remessa é obrigatório.");
      }

      $oRemessaCobrancaRegistrada = new RemessaCobrancaRegistrada();
      $sSqlRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_query($iSequencial);
      $rsRemessaCobrancaRegistrada = $oRemessaCobrancaRegistrada->sql_record($sSqlRemessaCobrancaRegistrada);

      if (!empty($oRemessaCobrancaRegistrada->erro_banco)) {
        throw new Exception("Erro ao buscar remessas.");
      }

      if ($oRemessaCobrancaRegistrada->numrows == 0) {
        throw new Exception("Nenhum registro encontrado para o sequencial da remessa!");
      }

      $oRCR = db_utils::fieldsMemory($rsRemessaCobrancaRegistrada, 0);

      $sDataEmissao = str_replace("-", "", $oRCR->k147_dataemissao);
      $sHoraEmissao = str_replace(":", "", $oRCR->k147_horaemissao);

      $sArquivoNome = "Remessa{$oRCR->k147_sequencialremessa}{$sDataEmissao}{$sHoraEmissao}.zip";
      $sArquivo = "tmp/{$sArquivoNome}";

      $lReemitiuArquivo = DBLargeObject::leitura($oRCR->k147_arquivoremessa, $sArquivo);

      if (!$lReemitiuArquivo) {
        throw new BusinessException("Erro ao buscar arquivo de remessa!");
      }

      $oRetorno->sArquivo = $sArquivo;
      $oRetorno->sArquivoNome = $sArquivoNome;

      break;

    case "buscarParcelasUnicas":

      $k00_tipo = $oParametros->k00_tipo;
      $arrUnicas = array();

      if (empty($k00_tipo)) {
        throw new Exception("Campo k00_tipo é obrigatório.");
      }
      
      $exercicio = date("Y-m-d",db_getsession("DB_datausu"));
      $sql  = "SELECT recibounica.k00_dtvenc,                               ";
      $sql .= "       recibounica.k00_dtoper,                               ";
      $sql .= "       recibounica.k00_percdes,                              ";
      $sql .= "       arrecad.k00_tipo,                                     ";
      $sql .= "       k00_descr,                                            ";
      $sql .= "       Count(*)                                              ";
      $sql .= "FROM   recibounica                                           ";
      $sql .= "       INNER JOIN arrecad                                    ";
      $sql .= "               ON recibounica.k00_numpre = arrecad.k00_numpre";
      $sql .= "       INNER JOIN arretipo                                   ";
      $sql .= "               ON arretipo.k00_tipo = arrecad.k00_tipo       ";
      $sql .= "WHERE  k00_tipoger = 'G'                                     ";
      $sql .= "       AND arrecad.k00_tipo = $k00_tipo                      ";
      $sql .= "       AND recibounica.k00_dtvenc > '$exercicio'             ";
      $sql .= "GROUP  BY 1,2,3,4,5                                          ";
      $sql .= "ORDER  BY 1,2,3,4,5;                                         ";

      $rs = db_query($sql);

      while ($parcelaunica = pg_fetch_array($rs)) {
        $aux = array();
        $aux['k00_dtvenc']  = $parcelaunica['k00_dtvenc'];
        $aux['k00_dtoper']  = $parcelaunica['k00_dtoper'];
        $aux['k00_percdes'] = $parcelaunica['k00_percdes'];
        $aux['k00_tipo']    = $parcelaunica['k00_tipo'];
        $aux['k00_descr']   = $parcelaunica['k00_descr'];
        $arrUnicas[]        = $aux;
      }

      $oRetorno->unicas = $arrUnicas;

      break;

    case "buscarParcelas":

      $k00_tipo = $oParametros->k00_tipo;
      $arrParcelas = array();

      if (empty($k00_tipo)) {
        throw new Exception("Campo k00_tipo é obrigatório.");
      }
            
      $sql  = " SELECT arrecad.k00_numpar,";
      $sql .= "        arrecad.k00_tipo,";
      $sql .= "        k00_descr,";
      $sql .= "       ( select array_accum(distinct a.k00_dtpaga) as k00_dtvenc from recibopaga a ";
      $sql .= "          inner join db_reciboweb on k99_numpre_n = a.k00_numnov ";
      $sql .= "          INNER JOIN reciboregistra on a.k00_numnov = reciboregistra.k146_numpre ";
      $sql .= "          where a.k00_numpre = arrecad.k00_numpre and a.k00_numpar = arrecad.k00_numpar and k99_numpar > 0 and k99_tipo in (5, 6, 8, 20, 21, 22, 23) ),
                      count(*)";
      $sql .= "FROM   arrecad ";
      $sql .= "INNER JOIN arretipo ";
      $sql .= "        ON arretipo.k00_tipo = arrecad.k00_tipo   ";
      $sql .= "       WHERE  arrecad.k00_tipo = $k00_tipo";
      $sql .= "       and ( select count(distinct a.k00_dtpaga) from recibopaga a ";
      $sql .= "                inner join db_reciboweb on k99_numpre_n = a.k00_numnov ";
      $sql .= "                INNER JOIN reciboregistra on a.k00_numnov = reciboregistra.k146_numpre ";
      $sql .= "                where a.k00_numpre = arrecad.k00_numpre and a.k00_numpar = arrecad.k00_numpar and k99_numpar > 0 and k99_tipo in (5, 6, 8, 20, 21, 22, 23) ) > 0 group by 1,2,3,4 order by 1,4;";

      $rs = db_query($sql);

      while ($parcela = pg_fetch_array($rs)) {
        $aux = array();
        $aux['k00_dtvenc'] = str_replace(array('{', '}'), "", $parcela['k00_dtvenc']);
        $aux['k00_numpar'] = $parcela['k00_numpar'];
        $aux['k00_tipo']   = $parcela['k00_tipo'];
        $aux['k00_descr']  = $parcela['k00_descr'];
        $arrParcelas[]     = $aux;
      }

      $oRetorno->parcelas = $arrParcelas;      

      break;
  }

  db_fim_transacao(false);

} catch (Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMensagem = $oErro->getMessage();
}

echo $oJson->stringify($oRetorno);
