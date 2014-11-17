<?
global $conn;
$result = db_query("select descricao from db_itensmenu where funcao = '".basename($HTTP_SERVER_VARS['PHP_SELF'])."'");
if(pg_numrows($result) > 0)
  $str = pg_result($result,0,0);
else
  $str = basename($HTTP_SERVER_VARS['PHP_SELF']);

$result = db_query("select uol_id from db_usuariosonline 
  where uol_id = ".db_getsession("DB_id_usuario")."
  and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
  and uol_hora = ".db_getsession("DB_uol_hora"));
if(pg_numrows($result) == 0) {
  $hora = time();
  db_query($conn,"insert into db_usuariosonline 
    values(".db_getsession("DB_id_usuario").",
      ".$hora.",
      '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."',            
      '".db_getsession("DB_login")."',
      '".$str."',
      '".db_getsession("DB_nome_modulo")."',
      ".time().")") or die("Erro:(27) inserindo arquivo em db_usuariosonline: ".pg_errormessage());
  db_putsession("DB_uol_hora",$hora);
} else {
  db_query("update db_usuariosonline set  
    uol_arquivo = '".$str."',
    uol_inativo = ".time()."
    where uol_id = ".db_getsession("DB_id_usuario")."
    and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
    and uol_hora = ".db_getsession("DB_uol_hora")."
    ") or die("Erro(26) atualizando db_usuariosonline");
}
?>
