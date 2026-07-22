<?php

class cl_matriz_saldo_contabil_lancamentos
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
    public $c133_ending_balance = 0; 
    public $c133_period_change_credit = 0; 
    public $c133_period_change_debit = 0; 
    public $c133_beginning_balance = 0; 
    public $c133_atributos = null; 
    public $c133_estrutural = null; 
    public $c133_matriz_saldo_contabil = 0; 
    public $c133_sequencial = 0; 
    public $c133_natureza = null; 
    public $c133_natureza_final = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 c133_ending_balance = float8 = Ending Balance 
                 c133_period_change_credit = float8 = Period Change Credit 
                 c133_period_change_debit = float8 = Period Change Debit 
                 c133_beginning_balance = float8 = Beginning Balance 
                 c133_atributos = varchar(255) = Atributos 
                 c133_estrutural = varchar(15) = Estrutural 
                 c133_matriz_saldo_contabil = int8 = Matriz Saldo Contábil 
                 c133_sequencial = int8 = Sequencial 
                 c133_natureza = varchar(1) = Natureza 
                 c133_natureza_final = char(1) = Natureza Final 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("matriz_saldo_contabil_lancamentos"); 
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
       $this->c133_ending_balance = ($this->c133_ending_balance == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_ending_balance"]:$this->c133_ending_balance);
       $this->c133_period_change_credit = ($this->c133_period_change_credit == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_period_change_credit"]:$this->c133_period_change_credit);
       $this->c133_period_change_debit = ($this->c133_period_change_debit == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_period_change_debit"]:$this->c133_period_change_debit);
       $this->c133_beginning_balance = ($this->c133_beginning_balance == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_beginning_balance"]:$this->c133_beginning_balance);
       $this->c133_atributos = ($this->c133_atributos == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_atributos"]:$this->c133_atributos);
       $this->c133_estrutural = ($this->c133_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_estrutural"]:$this->c133_estrutural);
       $this->c133_matriz_saldo_contabil = ($this->c133_matriz_saldo_contabil == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_matriz_saldo_contabil"]:$this->c133_matriz_saldo_contabil);
       $this->c133_sequencial = ($this->c133_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_sequencial"]:$this->c133_sequencial);
       $this->c133_natureza = ($this->c133_natureza == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_natureza"]:$this->c133_natureza);
       $this->c133_natureza_final = ($this->c133_natureza_final == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_natureza_final"]:$this->c133_natureza_final);
     }else{
       $this->c133_sequencial = ($this->c133_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c133_sequencial"]:$this->c133_sequencial);
     }
   }

    public function incluir($c133_sequencial)
    {
     if($this->c133_ending_balance === null ){
       $this->erro_sql = " Campo Ending Balance não informado.";
       $this->erro_campo = "c133_ending_balance";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_period_change_credit === null ){
       $this->erro_sql = " Campo Period Change Credit não informado. ({$this->c133_period_change_debit})";
       $this->erro_campo = "c133_period_change_credit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_period_change_debit === null ){
       $this->erro_sql = " Campo Period Change Debit não informado. ({$this->c133_period_change_debit})";
       $this->erro_campo = "c133_period_change_debit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_beginning_balance === null ){
       $this->erro_sql = " Campo Beginning Balance não informado.";
       $this->erro_campo = "c133_beginning_balance";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_atributos == null ){ 
       $this->erro_sql = " Campo Atributos não informado.";
       $this->erro_campo = "c133_atributos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_estrutural == null ){ 
       $this->erro_sql = " Campo Estrutural não informado.";
       $this->erro_campo = "c133_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_matriz_saldo_contabil == null ){ 
       $this->erro_sql = " Campo Matriz Saldo Contábil não informado.";
       $this->erro_campo = "c133_matriz_saldo_contabil";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_natureza == null ){ 
       $this->erro_sql = " Campo Natureza não informado.";
       $this->erro_campo = "c133_natureza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c133_natureza_final == null ){ 
       $this->erro_sql = " Campo Natureza Final não informado.";
       $this->erro_campo = "c133_natureza_final";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c133_sequencial == "" || $c133_sequencial == null ){
       $result = db_query("select nextval('matriz_saldo_contabil_lancamentos_c133_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matriz_saldo_contabil_lancamentos_c133_sequencial_seq do campo: c133_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c133_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matriz_saldo_contabil_lancamentos_c133_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c133_sequencial)){
         $this->erro_sql = " Campo c133_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c133_sequencial = $c133_sequencial; 
       }
     }
     if(($this->c133_sequencial == null) || ($this->c133_sequencial == "") ){ 
       $this->erro_sql = " Campo c133_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matriz_saldo_contabil_lancamentos(
                                       c133_ending_balance 
                                      ,c133_period_change_credit 
                                      ,c133_period_change_debit 
                                      ,c133_beginning_balance 
                                      ,c133_atributos 
                                      ,c133_estrutural 
                                      ,c133_matriz_saldo_contabil 
                                      ,c133_sequencial 
                                      ,c133_natureza 
                                      ,c133_natureza_final 
                       )
                values (
                                $this->c133_ending_balance 
                               ,$this->c133_period_change_credit 
                               ,$this->c133_period_change_debit 
                               ,$this->c133_beginning_balance 
                               ,'$this->c133_atributos' 
                               ,'$this->c133_estrutural' 
                               ,$this->c133_matriz_saldo_contabil 
                               ,$this->c133_sequencial 
                               ,'$this->c133_natureza' 
                               ,'$this->c133_natureza_final' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matriz Saldo Contábil Lançamentos ($this->c133_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matriz Saldo Contábil Lançamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matriz Saldo Contábil Lançamentos ($this->c133_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c133_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c133_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010465,'$this->c133_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010445,1010472,'','".AddSlashes(pg_result($resaco,0,'c133_ending_balance'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010471,'','".AddSlashes(pg_result($resaco,0,'c133_period_change_credit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010470,'','".AddSlashes(pg_result($resaco,0,'c133_period_change_debit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010469,'','".AddSlashes(pg_result($resaco,0,'c133_beginning_balance'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010468,'','".AddSlashes(pg_result($resaco,0,'c133_atributos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010467,'','".AddSlashes(pg_result($resaco,0,'c133_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010466,'','".AddSlashes(pg_result($resaco,0,'c133_matriz_saldo_contabil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010465,'','".AddSlashes(pg_result($resaco,0,'c133_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010473,'','".AddSlashes(pg_result($resaco,0,'c133_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010445,1010599,'','".AddSlashes(pg_result($resaco,0,'c133_natureza_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($c133_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update matriz_saldo_contabil_lancamentos set ";
     $virgula = "";
     if(trim($this->c133_ending_balance)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_ending_balance"])){ 
       $sql  .= $virgula." c133_ending_balance = $this->c133_ending_balance ";
       $virgula = ",";
       if(trim($this->c133_ending_balance) == null ){ 
         $this->erro_sql = " Campo Ending Balance não informado.";
         $this->erro_campo = "c133_ending_balance";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_period_change_credit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_period_change_credit"])){ 
       $sql  .= $virgula." c133_period_change_credit = $this->c133_period_change_credit ";
       $virgula = ",";
       if(trim($this->c133_period_change_credit) == null ){ 
         $this->erro_sql = " Campo Period Change Credit não informado.";
         $this->erro_campo = "c133_period_change_credit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_period_change_debit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_period_change_debit"])){ 
       $sql  .= $virgula." c133_period_change_debit = $this->c133_period_change_debit ";
       $virgula = ",";
       if(trim($this->c133_period_change_debit) == null ){ 
         $this->erro_sql = " Campo Period Change Debit não informado.";
         $this->erro_campo = "c133_period_change_debit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_beginning_balance)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_beginning_balance"])){ 
       $sql  .= $virgula." c133_beginning_balance = $this->c133_beginning_balance ";
       $virgula = ",";
       if(trim($this->c133_beginning_balance) == null ){ 
         $this->erro_sql = " Campo Beginning Balance não informado.";
         $this->erro_campo = "c133_beginning_balance";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_atributos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_atributos"])){ 
       $sql  .= $virgula." c133_atributos = '$this->c133_atributos' ";
       $virgula = ",";
       if(trim($this->c133_atributos) == null ){ 
         $this->erro_sql = " Campo Atributos não informado.";
         $this->erro_campo = "c133_atributos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_estrutural"])){ 
       $sql  .= $virgula." c133_estrutural = '$this->c133_estrutural' ";
       $virgula = ",";
       if(trim($this->c133_estrutural) == null ){ 
         $this->erro_sql = " Campo Estrutural não informado.";
         $this->erro_campo = "c133_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_matriz_saldo_contabil)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_matriz_saldo_contabil"])){ 
       $sql  .= $virgula." c133_matriz_saldo_contabil = $this->c133_matriz_saldo_contabil ";
       $virgula = ",";
       if(trim($this->c133_matriz_saldo_contabil) == null ){ 
         $this->erro_sql = " Campo Matriz Saldo Contábil não informado.";
         $this->erro_campo = "c133_matriz_saldo_contabil";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_sequencial"])){ 
       $sql  .= $virgula." c133_sequencial = $this->c133_sequencial ";
       $virgula = ",";
       if(trim($this->c133_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "c133_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_natureza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_natureza"])){ 
       $sql  .= $virgula." c133_natureza = '$this->c133_natureza' ";
       $virgula = ",";
       if(trim($this->c133_natureza) == null ){ 
         $this->erro_sql = " Campo Natureza não informado.";
         $this->erro_campo = "c133_natureza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c133_natureza_final)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c133_natureza_final"])){ 
       $sql  .= $virgula." c133_natureza_final = '$this->c133_natureza_final' ";
       $virgula = ",";
       if(trim($this->c133_natureza_final) == null ){ 
         $this->erro_sql = " Campo Natureza Final não informado.";
         $this->erro_campo = "c133_natureza_final";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c133_sequencial!=null){
       $sql .= " c133_sequencial = $this->c133_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c133_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010465,'$this->c133_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_ending_balance"]) || $this->c133_ending_balance != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010472,'".AddSlashes(pg_result($resaco,$conresaco,'c133_ending_balance'))."','$this->c133_ending_balance',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_period_change_credit"]) || $this->c133_period_change_credit != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010471,'".AddSlashes(pg_result($resaco,$conresaco,'c133_period_change_credit'))."','$this->c133_period_change_credit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_period_change_debit"]) || $this->c133_period_change_debit != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010470,'".AddSlashes(pg_result($resaco,$conresaco,'c133_period_change_debit'))."','$this->c133_period_change_debit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_beginning_balance"]) || $this->c133_beginning_balance != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010469,'".AddSlashes(pg_result($resaco,$conresaco,'c133_beginning_balance'))."','$this->c133_beginning_balance',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_atributos"]) || $this->c133_atributos != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010468,'".AddSlashes(pg_result($resaco,$conresaco,'c133_atributos'))."','$this->c133_atributos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_estrutural"]) || $this->c133_estrutural != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010467,'".AddSlashes(pg_result($resaco,$conresaco,'c133_estrutural'))."','$this->c133_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_matriz_saldo_contabil"]) || $this->c133_matriz_saldo_contabil != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010466,'".AddSlashes(pg_result($resaco,$conresaco,'c133_matriz_saldo_contabil'))."','$this->c133_matriz_saldo_contabil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_sequencial"]) || $this->c133_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010465,'".AddSlashes(pg_result($resaco,$conresaco,'c133_sequencial'))."','$this->c133_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_natureza"]) || $this->c133_natureza != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010473,'".AddSlashes(pg_result($resaco,$conresaco,'c133_natureza'))."','$this->c133_natureza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c133_natureza_final"]) || $this->c133_natureza_final != "")
             $resac = db_query("insert into db_acount values($acount,1010445,1010599,'".AddSlashes(pg_result($resaco,$conresaco,'c133_natureza_final'))."','$this->c133_natureza_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matriz Saldo Contábil Lançamentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c133_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Matriz Saldo Contábil Lançamentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c133_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c133_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($c133_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c133_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010465,'$c133_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010472,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_ending_balance'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010471,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_period_change_credit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010470,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_period_change_debit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010469,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_beginning_balance'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010468,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_atributos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010467,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010466,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_matriz_saldo_contabil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010465,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010473,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_natureza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010445,1010599,'','".AddSlashes(pg_result($resaco,$iresaco,'c133_natureza_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from matriz_saldo_contabil_lancamentos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c133_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c133_sequencial = $c133_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matriz Saldo Contábil Lançamentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c133_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Matriz Saldo Contábil Lançamentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c133_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c133_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matriz_saldo_contabil_lancamentos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($c133_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from matriz_saldo_contabil_lancamentos ";
     $sql .= "      inner join matriz_saldo_contabil  on  matriz_saldo_contabil.c132_sequencial = matriz_saldo_contabil_lancamentos.c133_matriz_saldo_contabil";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c133_sequencial)) {
         $sql2 .= " where matriz_saldo_contabil_lancamentos.c133_sequencial = $c133_sequencial "; 
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

    public function sql_query_file($c133_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from matriz_saldo_contabil_lancamentos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c133_sequencial)){
         $sql2 .= " where matriz_saldo_contabil_lancamentos.c133_sequencial = $c133_sequencial "; 
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
    /**
     * @param array $columns
     * @param array $where
     * @param array $order
     * @return string
     */
    public function sql($columns = array('*'), $where = array(), $order = array())
    {
        $columns = implode(', ', $columns);
        $where = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $order = $order ? 'ORDER BY ' . implode(', ', $order) : '';

        return "SELECT {$columns} FROM matriz_saldo_contabil_lancamentos {$where} {$order}";
    }

}
