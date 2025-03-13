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

class cl_empagedadosretmov
{
   // cria variaveis de erro 
    public $rotulo = null; 
    public $query_sql = null; 
    public $numrows = 0; 
    public $numrows_incluir = 0; 
    public $numrows_alterar = 0; 
    public $numrows_excluir = 0; 
    public $erro_status = null; 
    public $erro_sql = null; 
    public $erro_banco = null;  
    public $erro_msg = null;  
    public $erro_campo = null;  
    public $pagina_retorno = null; 
    /* Variáveis do Arquivo */
    public $e76_codret = 0; 
    public $e76_lote = null; 
    public $e76_movlote = null; 
    public $e76_codmov = 0; 
    public $e76_numbanco = null; 
    public $e76_dataefet_dia = null; 
    public $e76_dataefet_mes = null; 
    public $e76_dataefet_ano = null; 
    public $e76_dataefet = null; 
    public $e76_valorefet = 0; 
    public $e76_processado = 'f'; 
    public $e76_linhaarquivo = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 e76_codret = int8 = Código do retorno 
                 e76_lote = varchar(4) = Número do lote 
                 e76_movlote = varchar(5) = Movimento no lote 
                 e76_codmov = int4 = Movimento 
                 e76_numbanco = varchar(20) = Número do banco 
                 e76_dataefet = date = Efetivação crédito 
                 e76_valorefet = float8 = Valor efetivação crédito 
                 e76_processado = bool = Processado 
                 e76_linhaarquivo = varchar(700) = Linha Arquivo 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("empagedadosretmov"); 
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\")</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }

    public function atualizacampos($exclusao = false)
    {
     if($exclusao==false){
       $this->e76_codret = ($this->e76_codret == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_codret"]:$this->e76_codret);
       $this->e76_lote = ($this->e76_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_lote"]:$this->e76_lote);
       $this->e76_movlote = ($this->e76_movlote == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_movlote"]:$this->e76_movlote);
       $this->e76_codmov = ($this->e76_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_codmov"]:$this->e76_codmov);
       $this->e76_numbanco = ($this->e76_numbanco == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_numbanco"]:$this->e76_numbanco);
       if($this->e76_dataefet == ""){
         $this->e76_dataefet_dia = ($this->e76_dataefet_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_dataefet_dia"]:$this->e76_dataefet_dia);
         $this->e76_dataefet_mes = ($this->e76_dataefet_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_dataefet_mes"]:$this->e76_dataefet_mes);
         $this->e76_dataefet_ano = ($this->e76_dataefet_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_dataefet_ano"]:$this->e76_dataefet_ano);
         if($this->e76_dataefet_dia != ""){
            $this->e76_dataefet = $this->e76_dataefet_ano."-".$this->e76_dataefet_mes."-".$this->e76_dataefet_dia;
         }
       }
       $this->e76_valorefet = ($this->e76_valorefet == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_valorefet"]:$this->e76_valorefet);
       $this->e76_processado = ($this->e76_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["e76_processado"]:$this->e76_processado);
       $this->e76_linhaarquivo = ($this->e76_linhaarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_linhaarquivo"]:$this->e76_linhaarquivo);
     }else{
       $this->e76_codret = ($this->e76_codret == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_codret"]:$this->e76_codret);
       $this->e76_codmov = ($this->e76_codmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e76_codmov"]:$this->e76_codmov);
     }
   }

    public function incluir($e76_codret,$e76_codmov)
    {
      $this->atualizacampos();
     if($this->e76_lote == null ){ 
       $this->erro_sql = " Campo Número do lote não informado.";
       $this->erro_campo = "e76_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e76_movlote == null ){ 
       $this->erro_sql = " Campo Movimento no lote não informado.";
       $this->erro_campo = "e76_movlote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e76_dataefet == null ){ 
       $this->erro_sql = " Campo Efetivação crédito não informado.";
       $this->erro_campo = "e76_dataefet_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e76_valorefet == null ){ 
       $this->erro_sql = " Campo Valor efetivação crédito não informado.";
       $this->erro_campo = "e76_valorefet";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e76_processado == null ){ 
       $this->erro_sql = " Campo Processado não informado.";
       $this->erro_campo = "e76_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e76_linhaarquivo == null ){ 
       $this->erro_sql = " Campo Linha Arquivo não informado.";
       $this->erro_campo = "e76_linhaarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e76_codret = $e76_codret; 
       $this->e76_codmov = $e76_codmov; 
     if(($this->e76_codret == null) || ($this->e76_codret == "") ){ 
       $this->erro_sql = " Campo e76_codret não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e76_codmov == null) || ($this->e76_codmov == "") ){ 
       $this->erro_sql = " Campo e76_codmov não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagedadosretmov(
                                       e76_codret 
                                      ,e76_lote 
                                      ,e76_movlote 
                                      ,e76_codmov 
                                      ,e76_numbanco 
                                      ,e76_dataefet 
                                      ,e76_valorefet 
                                      ,e76_processado 
                                      ,e76_linhaarquivo 
                       )
                values (
                                $this->e76_codret 
                               ,'$this->e76_lote' 
                               ,'$this->e76_movlote' 
                               ,$this->e76_codmov 
                               ,'$this->e76_numbanco' 
                               ,".($this->e76_dataefet == "null" || $this->e76_dataefet == ""?"null":"'".$this->e76_dataefet."'")." 
                               ,$this->e76_valorefet 
                               ,'$this->e76_processado' 
                               ,'$this->e76_linhaarquivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados dos movimentos do arquivo retorno ($this->e76_codret."-".$this->e76_codmov) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados dos movimentos do arquivo retorno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados dos movimentos do arquivo retorno ($this->e76_codret."-".$this->e76_codmov) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->e76_codret."-".$this->e76_codmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e76_codret,$this->e76_codmov  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7274,'$this->e76_codret','I')");
         $resac = db_query("insert into db_acountkey values($acount,7277,'$this->e76_codmov','I')");
         $resac = db_query("insert into db_acount values($acount,1207,7274,'','".AddSlashes(pg_result($resaco,0,'e76_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7275,'','".AddSlashes(pg_result($resaco,0,'e76_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7276,'','".AddSlashes(pg_result($resaco,0,'e76_movlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7277,'','".AddSlashes(pg_result($resaco,0,'e76_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7278,'','".AddSlashes(pg_result($resaco,0,'e76_numbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7279,'','".AddSlashes(pg_result($resaco,0,'e76_dataefet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7280,'','".AddSlashes(pg_result($resaco,0,'e76_valorefet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,7315,'','".AddSlashes(pg_result($resaco,0,'e76_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1207,1011145,'','".AddSlashes(pg_result($resaco,0,'e76_linhaarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($e76_codret=null,$e76_codmov=null)
    {
      $this->atualizacampos();
     $sql = " update empagedadosretmov set ";
     $virgula = "";
     if(trim($this->e76_codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_codret"])){ 
       $sql  .= $virgula." e76_codret = $this->e76_codret ";
       $virgula = ",";
       if(trim($this->e76_codret) == null ){ 
         $this->erro_sql = " Campo Código do retorno não informado.";
         $this->erro_campo = "e76_codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e76_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_lote"])){ 
       $sql  .= $virgula." e76_lote = '$this->e76_lote' ";
       $virgula = ",";
       if(trim($this->e76_lote) == null ){ 
         $this->erro_sql = " Campo Número do lote não informado.";
         $this->erro_campo = "e76_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e76_movlote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_movlote"])){ 
       $sql  .= $virgula." e76_movlote = '$this->e76_movlote' ";
       $virgula = ",";
       if(trim($this->e76_movlote) == null ){ 
         $this->erro_sql = " Campo Movimento no lote não informado.";
         $this->erro_campo = "e76_movlote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e76_codmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_codmov"])){ 
       $sql  .= $virgula." e76_codmov = $this->e76_codmov ";
       $virgula = ",";
       if(trim($this->e76_codmov) == null ){ 
         $this->erro_sql = " Campo Movimento não informado.";
         $this->erro_campo = "e76_codmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e76_numbanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_numbanco"])){ 
       $sql  .= $virgula." e76_numbanco = '$this->e76_numbanco' ";
       $virgula = ",";
     }
     if(trim($this->e76_dataefet)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_dataefet_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e76_dataefet_dia"] !="") ){ 
       $sql  .= $virgula." e76_dataefet = '$this->e76_dataefet' ";
       $virgula = ",";
       if(trim($this->e76_dataefet) == null ){ 
         $this->erro_sql = " Campo Efetivação crédito não informado.";
         $this->erro_campo = "e76_dataefet_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e76_dataefet_dia"])){ 
         $sql  .= $virgula." e76_dataefet = null ";
         $virgula = ",";
         if(trim($this->e76_dataefet) == null ){ 
           $this->erro_sql = " Campo Efetivação crédito não informado.";
           $this->erro_campo = "e76_dataefet_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e76_valorefet)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_valorefet"])){ 
       $sql  .= $virgula." e76_valorefet = $this->e76_valorefet ";
       $virgula = ",";
       if(trim($this->e76_valorefet) == null ){ 
         $this->erro_sql = " Campo Valor efetivação crédito não informado.";
         $this->erro_campo = "e76_valorefet";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e76_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_processado"])){ 
       $sql  .= $virgula." e76_processado = '$this->e76_processado' ";
       $virgula = ",";
       if(trim($this->e76_processado) == null ){ 
         $this->erro_sql = " Campo Processado não informado.";
         $this->erro_campo = "e76_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e76_linhaarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e76_linhaarquivo"])){ 
       $sql  .= $virgula." e76_linhaarquivo = '$this->e76_linhaarquivo' ";
       $virgula = ",";
       if(trim($this->e76_linhaarquivo) == null ){ 
         $this->erro_sql = " Campo Linha Arquivo não informado.";
         $this->erro_campo = "e76_linhaarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e76_codret!=null){
       $sql .= " e76_codret = $this->e76_codret";
     }
     if($e76_codmov!=null){
       $sql .= " and  e76_codmov = $this->e76_codmov";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e76_codret,$this->e76_codmov));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7274,'$this->e76_codret','A')");
           $resac = db_query("insert into db_acountkey values($acount,7277,'$this->e76_codmov','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_codret"]) || $this->e76_codret != "")
             $resac = db_query("insert into db_acount values($acount,1207,7274,'".AddSlashes(pg_result($resaco,$conresaco,'e76_codret'))."','$this->e76_codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_lote"]) || $this->e76_lote != "")
             $resac = db_query("insert into db_acount values($acount,1207,7275,'".AddSlashes(pg_result($resaco,$conresaco,'e76_lote'))."','$this->e76_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_movlote"]) || $this->e76_movlote != "")
             $resac = db_query("insert into db_acount values($acount,1207,7276,'".AddSlashes(pg_result($resaco,$conresaco,'e76_movlote'))."','$this->e76_movlote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_codmov"]) || $this->e76_codmov != "")
             $resac = db_query("insert into db_acount values($acount,1207,7277,'".AddSlashes(pg_result($resaco,$conresaco,'e76_codmov'))."','$this->e76_codmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_numbanco"]) || $this->e76_numbanco != "")
             $resac = db_query("insert into db_acount values($acount,1207,7278,'".AddSlashes(pg_result($resaco,$conresaco,'e76_numbanco'))."','$this->e76_numbanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_dataefet"]) || $this->e76_dataefet != "")
             $resac = db_query("insert into db_acount values($acount,1207,7279,'".AddSlashes(pg_result($resaco,$conresaco,'e76_dataefet'))."','$this->e76_dataefet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_valorefet"]) || $this->e76_valorefet != "")
             $resac = db_query("insert into db_acount values($acount,1207,7280,'".AddSlashes(pg_result($resaco,$conresaco,'e76_valorefet'))."','$this->e76_valorefet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_processado"]) || $this->e76_processado != "")
             $resac = db_query("insert into db_acount values($acount,1207,7315,'".AddSlashes(pg_result($resaco,$conresaco,'e76_processado'))."','$this->e76_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e76_linhaarquivo"]) || $this->e76_linhaarquivo != "")
             $resac = db_query("insert into db_acount values($acount,1207,1011145,'".AddSlashes(pg_result($resaco,$conresaco,'e76_linhaarquivo'))."','$this->e76_linhaarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados dos movimentos do arquivo retorno não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e76_codret."-".$this->e76_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados dos movimentos do arquivo retorno não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e76_codret."-".$this->e76_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->e76_codret."-".$this->e76_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($e76_codret=null,$e76_codmov=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e76_codret,$e76_codmov));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7274,'$e76_codret','E')");
           $resac  = db_query("insert into db_acountkey values($acount,7277,'$e76_codmov','E')");
           $resac  = db_query("insert into db_acount values($acount,1207,7274,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7275,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7276,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_movlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7277,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_codmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7278,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_numbanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7279,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_dataefet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7280,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_valorefet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,7315,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1207,1011145,'','".AddSlashes(pg_result($resaco,$iresaco,'e76_linhaarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empagedadosretmov
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e76_codret)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e76_codret = $e76_codret ";
        }
        if (!empty($e76_codmov)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e76_codmov = $e76_codmov ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados dos movimentos do arquivo retorno não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e76_codret."-".$e76_codmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados dos movimentos do arquivo retorno não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e76_codret."-".$e76_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$e76_codret."-".$e76_codmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function sql_record($sql)
    {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:empagedadosretmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($e76_codret = null,$e76_codmov = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from empagedadosretmov ";
     $sql .= "      inner join empagedadosret  on  empagedadosret.e75_codret = empagedadosretmov.e76_codret";
     $sql .= "      inner join empagegera  on  empagegera.e87_codgera = empagedadosret.e75_codgera";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e76_codret)) {
         $sql2 .= " where empagedadosretmov.e76_codret = $e76_codret "; 
       } 
       if (!empty($e76_codmov)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " empagedadosretmov.e76_codmov = $e76_codmov "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

    public function sql_query_file($e76_codret = null,$e76_codmov = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empagedadosretmov ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e76_codret)){
         $sql2 .= " where empagedadosretmov.e76_codret = $e76_codret "; 
       } 
       if (!empty($e76_codmov)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " empagedadosretmov.e76_codmov = $e76_codmov "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

    function sql_query_ocorrencia($campos, $where = null, $orderBy = null, $groupBy = null)
    {

        $sql  =  "select {$campos} ";
        $sql .= " from empagedadosretmov";
        $sql .= "     inner join empagedadosretmovocorrencia on empagedadosretmovocorrencia.e02_empagedadosretmov =  empagedadosretmov.e76_codmov ";
        $sql .= "                                           and empagedadosretmovocorrencia.e02_empagedadosret    =  empagedadosretmov.e76_codret ";
        $sql .= "     inner join errobanco                   on empagedadosretmovocorrencia.e02_errobanco         =  errobanco.e92_sequencia ";
        if (!empty($where)) {
            $sql .= "where {$where}";
        }

        if (!empty($groupBy)) {
            $sql .= "group by {$groupBy}";
        }
        if (!empty($orderBy)) {
            $sql .= "order by {$orderBy}";
        }
        return $sql;
    }
}

?>
