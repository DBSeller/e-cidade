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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_usuariosonline.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('libs/db_utils.php'));

$oJson   = new services_json();
$sName   = html_entity_decode(crossUrlDecode($_POST['string']));
$iTipo   = $_GET['tipo'];
$sSql    = '';
$iLinhas = 0;

if ($iTipo == 1) { // Medicamento

  $oDaoFarMaterSaude = new cl_far_matersaude();
  $sCampos           = 'far_matersaude.fa01_i_codigo as cod, matmater.m60_descr as label, far_matersaude.fa01_codigobarras as codigo_barras';
  $sWhere            = "m60_ativo = 't' and m60_descr like upper('".$sName."%')";
  $sSql              = $oDaoFarMaterSaude->sql_query_file(null, $sCampos, 'label', $sWhere);
  $rs                = $oDaoFarMaterSaude->sql_record($sSql);
  $iLinhas           = $oDaoFarMaterSaude->numrows;

} elseif ($iTipo == 2) { // Profissional com CGM (profissionais somente da rede)

  $oDaoMedicos = new cl_medicos();
  $sCampos     = 'distinct medicos.sd03_i_codigo as cod, cgm.z01_nome as label';
  $sWhere      = "sd27_c_situacao = 'A' and z01_nome LIKE upper('".$sName."%')";
  $sSql        = $oDaoMedicos->sql_query_ativo(null, $sCampos, 'label', $sWhere);
  $rs          = $oDaoMedicos->sql_record($sSql);
  $iLinhas     = $oDaoMedicos->numrows;

} elseif ($iTipo == 3) { // CGS


  $oDaoCgsUnd = new cl_cgs_und();
  $sCampos    = 'z01_i_cgsund as cod, z01_v_nome as label';
  $sWhere     = "    trim(z01_v_nome) like upper('$sName%') ";
  $sWhere    .= " and not exists (select 1 from cgs_und_ext ";
  $sWhere    .= "                  where cgs_und_ext.z01_i_cgsund = cgs_und.z01_i_cgsund ";
  $sWhere    .= "                    and (z01_b_inativo is true or z01_b_faleceu is true) ) ";
  $sSql       = $oDaoCgsUnd->sql_query_file(null, $sCampos, 'z01_v_nome limit 30', $sWhere);
  $rs         = $oDaoCgsUnd->sql_record($sSql);
  $iLinhas    = $oDaoCgsUnd->numrows;

} elseif ($iTipo == 4) { // Origem da receita

  $oDaoFarOrigemReceita = new cl_far_origemreceita();
  $sCampos              = ' fa40_i_codigo as cod, fa40_c_descr as label ';
  $dDataAtual           = date('Y-m-d', db_getsession('DB_datausu'));
  $sValidade            = " (fa40_d_validade is null or fa40_d_validade >= '$dDataAtual')";
  $sWhere               = " fa40_c_descr like upper('$sName%') and $sValidade";
  $sSql                 = $oDaoFarOrigemReceita->sql_query(null, $sCampos, 'fa40_c_descr', $sWhere);
  $rs                   = $oDaoFarOrigemReceita->sql_record($sSql);
  $iLinhas              = $oDaoFarOrigemReceita->numrows;

} elseif ($iTipo == 5) { // Profissionais da rede e fora da rede

  $oDaoMedicos = new cl_medicos();
  $sCampos  = 'distinct medicos.sd03_i_codigo as cod, ';
  $sCampos .= '         case ';
  $sCampos .= '           when sd03_i_tipo = 1 ';
  $sCampos .= '             then cgm.z01_nome ';
  $sCampos .= '           else ';
  $sCampos .= '             s154_c_nome ';
  $sCampos .= '          end as label ';
  $sWhere   = "z01_nome like upper('".$sName."%') or s154_c_nome like upper('".$sName."%')";
  $sSql     = $oDaoMedicos->sql_query_cgm_fora_rede(null, $sCampos, 'label', $sWhere);
  $rs       = $oDaoMedicos->sql_record($sSql);
  $iLinhas  = $oDaoMedicos->numrows;

} elseif ($iTipo == 6) {

  $oDaoEspecialidades  = new cl_rhcbo();
  $sCampos             = ' rh70_estrutural  as  cod, rh70_descr as label, rh70_sequencial, rh70_tipo ';
  $sWhere              = ' rh70_tipo = 4 ';
  $sWhere             .= ' and exists ( select * from sau_proccbo where sd96_i_cbo = rh70_sequencial ) ';
  $sWhere             .= " and rh70_descr like upper('".$sName."%')";
  $sOrder              = ' label ';
  $sSql                = $oDaoEspecialidades->sql_query(null, $sCampos, $sOrder, $sWhere);
  $rs                  = $oDaoEspecialidades->sql_record($sSql);
  $iLinhas             = $oDaoEspecialidades->numrows;

} elseif ($iTipo == 7) { // Departamento/almoxerifado

  $oDaoFarMaterSaude = new cl_db_depart();
  $sCampos           = 'db_depart.coddepto as cod, db_depart.descrdepto as label';
  $sWhere            = "descrdepto like upper('".$sName."%')";
  $sSql              = $oDaoFarMaterSaude->sql_query_file(null, $sCampos, 'label', $sWhere);
  $rs                = $oDaoFarMaterSaude->sql_record($sSql);
  $iLinhas           = $oDaoFarMaterSaude->numrows;
} elseif ($iTipo == 8) {
  $daoFarMaterSaude = new cl_far_matersaude();
  $campos = "fa01_i_codigo, m60_descr";
  $where = "m60_ativo = 't' and m60_descr like upper('{$sName}%')";
  $sql = $daoFarMaterSaude->sql_query_saldo(null, $campos, '', $where);
  $campos = "x.fa01_i_codigo AS cod, x.m60_descr||' saldo: '||sum(x.saldo) AS label, x.m60_descr as medicamento";
  $sql = "select {$campos} from ($sql) as x group by cod, medicamento order by label";
  $rs = $daoFarMaterSaude->sql_record($sql);
  $iLinhas = $daoFarMaterSaude->numrows;
}
$aRetorno = "";

if ($iLinhas > 0){

  $aRetorno = db_utils::getCollectionByRecord($rs, false, false, true);

}
echo $oJson->encode($aRetorno);

function crossUrlDecode($sSource) {

 // Troco os caracteres especiais por pelo coringa
 $aOrig   = array('á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù', 'ç',
                  'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Ô', 'Ã', 'Õ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ç'
                 );

 return str_replace($aOrig, '_', mb_convert_encoding($sSource, "ISO-8859-1", "UTF-8"));

}
?>