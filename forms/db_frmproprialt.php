<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
<script>
<?if(isset($j42_matric)){?>
function js_trocaid(valor){
  location.href="cad1_proprialt.php?j42_matric=<?=$j42_matric?>&j42_numcgm="+valor+"&j01_tipoimovel=<?=$j01_tipoimovel?>";
}
<?}?>
function js_opcoes(){
 var valor= document.form1.j42_numcgm.value;
 var num=document.form1.lista.options.length;
 for(i=0; i<num; i++){
     lista=document.form1.lista.options[i].value;
      if(valor==lista){
        alert("Numero de Matricula já cadastrada!");
        document.form1.j42_numcgm.value="";
        document.form1.j42_numcgm.focus();
        document.form1.z01_nome.value="";
        return false;
        break;
      }
 }
 return  parent.js_veripros("propri");
 return true;
}

</script>

  <fieldset>

    <legend><b>Outros proprietários:</b></legend>

    <table border="0" width="790">
    <tr>
      <td nowrap title="<?=@$Tj42_matric?>">
        <?=@$Lj42_matric?>
      </td>
      <td> 
<?
  db_input('j42_matric',10,$Ij42_matric,true,'text',3," onchange='js_pesquisaj42_matric(false);'");
  db_input('z01_nome',86,$Ij01_numcgm,true,'text',3,'','z01_nomematri');
?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=@$Tj42_numcgm?>">
<?
  db_ancora($Lj42_numcgm,' js_cgm(true); ',(isset($j42_numalt)?3:1));
?>
      </td>
      <td> 
<?
  db_input('j42_numcgm',10,$Ij42_numcgm,true,'text',(isset($j42_numalt)?3:1),"onchange='js_cgm(false)'");
  db_input('z01_nome',86,$Iz01_nome,true,'text',3,"");
?>
      </td>
    </tr>
    <?php 
    $j01_tipoimovel = isset($j01_tipoimovel) ? $j01_tipoimovel : $tipoImovel;
    if ($j01_tipoimovel == "2") { 
      if (empty($j166_sequencial)) {
        $j166_sequencial="";
      }
    ?>
    <tr>
      <td><strong>Percentual de Posse (%):</strong></td>
      <td>
        <input type="hidden" name="j166_sequencial" id="j166_sequencial" value="<?=$j166_sequencial?>">
        <?php
        db_input('j166_percentual',10,$Ij166_percentual,true,'text',1,"","","","",5);
        ?>
      </td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="2">
      <center>
<?

  if($outros==true){  
    $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,"","propri.*#cgm.z01_nome"));
    $num = $clpropri->numrows;
    if(isset($j42_matric)){
      echo "<select name='lista' onchange='js_trocaid(this.value)' size='".($num>10?10:($num)+1)."'>";
      $xx="";
      $cgmpropri="";
      for($i=0;$i<$num;$i++){  
        db_fieldsmemory($result,$i);
	$cgmpropri.=$xx.$j42_numcgm;
	$xx="#";
        if(isset($j42_numalt)){
          if($j42_numcgm!=$j42_numalt){  
            echo "<option  value='".$j42_numcgm."'>$z01_nome</option>";         
          }
        }else{  
          echo "<option  value='".$j42_numcgm."'>$z01_nome</option>";         
        }
      }
      echo "</select>"; 
    } 
  }  
?>

      </center>
      </td>
    </tr>  
    <tr><td><input name="cgmpropri" type="hidden" value="<?=@$cgmpropri?>"> </td></tr>

  </table>

</fieldset>

<br />

<input name="incluir" type="submit" value="Incluir" <?=(!isset($j42_numalt)?"":"disabled")?> <?=($outros==true?"onclick='return js_opcoes()'":"onclick='return  parent.js_veripros(\"propri\")'")?>>
<input name="excluir" type="submit" id="excluir" value="Excluir" <?=(isset($j42_numalt)?"":"disabled")?> >               
<input name="atualizar" type="submit" id="atualizar" value="Atualizar" <?=(isset($j42_numalt)?"":"disabled")?> >               

<script>

$('j42_matric').classList.add('field-size2');
$('z01_nomematri').classList.add('field-size9');
$('j42_numcgm').classList.add('field-size2');
$('z01_nome').classList.add('field-size9');
<?php if ($j01_tipoimovel == 2) { ?>
  $('j166_percentual').classList.add('field-size2');
<?php } ?>


function js_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_propri','func_nome','func_nome.php?funcao_js=parent.js_mostra1|0|1&testanome=true','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_propri','func_nome','func_nome.php?pesquisa_chave='+document.form1.j42_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false,0);
  }
}
function js_mostra1(chave1,chave2){
  document.form1.j42_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.j42_numcgm.focus();
    document.form1.j42_numcgm.value="";
  }
}
</script>