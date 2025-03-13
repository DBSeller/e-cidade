<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
?>
<body bgcolor="#FFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="setInterval('js_data()',1000)">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40%" align="left" valign="middle" class="tab" id="st" style='display:none;'></td>
    <td width="40%" align="left" valign="middle" class="tab" id="ctnDepartamentos" >
      <select id="listaDepartamentos" onchange="alteraDepartamento();">

      </select>
    </td>
    <td width="5%"  align="left" valign="middle" class="tab"><strong>Fonte:</strong></td>
    <td width="35%" align="left" valign="middle" class="tab"><input id="sRotinaEmExecucao" onClick="this.select();"  name="sRotinaEmExecucao" type="text" style="border:none;background-color:transparent;width:80%;font-weight:bold;color:red"/></td>
    <td width="5%"  align="left" valign="middle" class="tab" ><strong>Data:</strong></td>
    <td width="5%"  align="left" valign="middle" class="tab" id="dtatual"></td>
    <td width="5%"  align="left" valign="middle" class="tab" ><strong>Exercício:</strong></td>
    <td width="5%"  align="left" valign="middle" class="tab" id="dtanousu"></td>
  </tr>
</table>
<div name='logtext' id='logtext' style='visibility:hidden'></div>
</body>


<script type="text/javascript">

  var lCarregouDepartamentos = false;
  function carregaDepartamentos( iDepartamento ) {

    if ( !lCarregouDepartamentos ) {

      lCarregouDepartamentos = true;
      buscaDepartamentosUsuario();
    }
    $('listaDepartamentos').value = iDepartamento;
  }

  function buscaDepartamentosUsuario() {

    var oParametros  = {};
    oParametros.exec = 'buscaDepartamentosLiberadosUsuario';
    oParametros.lOrdemNomeDepartamento = true;

    var oRequest          = {};
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.asynchronous = false;
    oRequest.onComplete   = function(oAjax) {

      var oRetorno = JSON.parse(oAjax.responseText);

      $('listaDepartamentos').options.length = 0;
      oRetorno.aDepartamentos.each(function(oDepartamento) {

        var sNomeDepartamento = oDepartamento.coddepto + ' - ' + oDepartamento.descrdepto.urlDecode();
        $('listaDepartamentos').add( new Option(sNomeDepartamento, oDepartamento.coddepto) );
      });
    }
    new Ajax.Request('con1_departamentos.RPC.php', oRequest);
  }

  function alteraDepartamento(iCodigoDepartamento) {

    var iDepartamento = $F('listaDepartamentos');
    parent.corpo.location.href = "modulos.php?coddepto="+iDepartamento+"&retorno=true&nomedepto="
  }

</script>
