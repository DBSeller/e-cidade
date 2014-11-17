<?
$sess = 0;
if(!session_is_registered("DB_modulo"))
  $sess = 1;
if(!session_is_registered("DB_nome_modulo"))
  $sess = 1;
if(!session_is_registered("DB_anousu"))
  $sess = 1;
if(!session_is_registered("DB_instit"))
  $sess = 1;
if(!session_is_registered("DB_uol_hora"))
  $sess = 1;
if($sess == 1) {
  session_destroy();
  echo "Sessão Inválida!(14)<br>Feche seu navegador e faça login novamente.<Br>\n";
  exit;
}

/*$arquivo = pg_exec("select id_item,funcao as arquivo
                    from db_itensmenu
		    where funcao like '".basename($HTTP_SERVER_VARS["SCRIPT_FILENAME"])."%'"); 
$numrows = pg_numrows($arquivo);
if($numrows > 0) {
  $str = "";
  $c = "";
  for($i = 0;$i < $numrows;$i++) {
    $str .= $c.pg_result($arquivo,$i,"id_item");
    $c = ",";
  }
  /*
  $result = pg_exec("select id_item
  		   from db_permissao
  		   where ( id_usuario = ".db_getsession("DB_id_usuario")." or id_usuario in (select id_perfil from db_permherda where id_usuario = ".db_getsession("DB_id_usuario")."))
 		   and anousu = ".db_getsession("DB_anousu")."
 		   and id_item in(".$str.")"); 
  if(pg_numrows($result) == 0) {
      // para o usuario dbseller, não destroy a seção...
      if (db_getsession("DB_id_usuario")!=1)
         session_destroy();
      
    ?>
	<html>
    <body>
      <CENTER><BR><BR><BR>
        <h1>ACESSO NAO PERMITIDO</h1>
      </CENTER>
    </body>
    </html>
    <?
    exit;
  }
  
}*/
?>
