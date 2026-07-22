<?php

class cl_avaliacaogruporespostarhpessoal
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
    public $eso02_sequencial = 0;
    public $eso02_avaliacaogruporesposta = 0;
    public $eso02_rhpessoal = 0;
    public $eso02_empregador = 0;
    public $eso02_avaliacao = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 eso02_sequencial = int4 = Código 
                 eso02_avaliacaogruporesposta = int4 = Resposta 
                 eso02_rhpessoal = int4 = Matrícula 
                 eso02_empregador = int8 = Empregador 
                 eso02_avaliacao = int8 = Avaliação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostarhpessoal");
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
         echo "<script>alert(\"" . $this->erro_msg . "\")</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }

    public function atualizacampos($exclusao = false)
    {
     if($exclusao==false){
       $this->eso02_sequencial = ($this->eso02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"]:$this->eso02_sequencial);
       $this->eso02_avaliacaogruporesposta = ($this->eso02_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_avaliacaogruporesposta"]:$this->eso02_avaliacaogruporesposta);
       $this->eso02_rhpessoal = ($this->eso02_rhpessoal == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_rhpessoal"]:$this->eso02_rhpessoal);
         $this->eso02_empregador = ($this->eso02_empregador == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso02_empregador"] : $this->eso02_empregador);
         $this->eso02_avaliacao = ($this->eso02_avaliacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso02_avaliacao"] : $this->eso02_avaliacao);
     }else{
       $this->eso02_sequencial = ($this->eso02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"]:$this->eso02_sequencial);
     }
   }

    public function incluir($eso02_sequencial)
    {
      $this->atualizacampos();
        if ($this->eso02_avaliacaogruporesposta == null) {
       $this->erro_sql = " Campo Resposta não informado.";
       $this->erro_campo = "eso02_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
        if ($this->eso02_rhpessoal == null) {
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "eso02_rhpessoal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
        if ($this->eso02_empregador == null) {
            $this->erro_sql = " Campo Empregador não informado.";
            $this->erro_campo = "eso02_empregador";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso02_avaliacao == null) {
            $this->erro_sql = " Campo Avaliação não informado.";
            $this->erro_campo = "eso02_avaliacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
     if($eso02_sequencial == "" || $eso02_sequencial == null ){
         $result = db_query("select nextval('avaliacaogruporespostarhpessoal_eso02_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
           $this->erro_sql = "Verifique o cadastro da sequencia: avaliacaogruporespostarhpessoal_eso02_sequencial_seq do campo: eso02_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
           return false;
       }
         $this->eso02_sequencial = pg_result($result, 0, 0);
     }else{
       $result = db_query("select last_value from avaliacaogruporespostarhpessoal_eso02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso02_sequencial)){
         $this->erro_sql = " Campo eso02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
           $this->eso02_sequencial = $eso02_sequencial;
       }
     }
        if (($this->eso02_sequencial == null) || ($this->eso02_sequencial == "")) {
       $this->erro_sql = " Campo eso02_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporespostarhpessoal(
                                       eso02_sequencial 
                                      ,eso02_avaliacaogruporesposta 
                                      ,eso02_rhpessoal 
                                      ,eso02_empregador 
                                      ,eso02_avaliacao 
                       )
                values (
                                $this->eso02_sequencial 
                               ,$this->eso02_avaliacaogruporesposta 
                               ,$this->eso02_rhpessoal 
                               ,$this->eso02_empregador 
                               ,$this->eso02_avaliacao 
                      )";
        $result = db_query($sql);
        if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor ($this->eso02_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vincula uma resposta de avaliação a um servidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor ($this->eso02_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso02_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21792,'$this->eso02_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3924,21792,'','".AddSlashes(pg_result($resaco,0,'eso02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3924,21793,'','".AddSlashes(pg_result($resaco,0,'eso02_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3924,21794,'','".AddSlashes(pg_result($resaco,0,'eso02_rhpessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3924,1010309,'','" . AddSlashes(pg_result($resaco, 0,
                   'eso02_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
           $resac = db_query("insert into db_acount values($acount,3924,1010308,'','" . AddSlashes(pg_result($resaco, 0,
                   'eso02_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
       }
     }
     return true;
    }

    public function alterar($eso02_sequencial = null)
    {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostarhpessoal set ";
     $virgula = "";
        if (trim($this->eso02_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"])) {
       $sql  .= $virgula." eso02_sequencial = $this->eso02_sequencial ";
       $virgula = ",";
            if (trim($this->eso02_sequencial) == null) {
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "eso02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
        if (trim($this->eso02_avaliacaogruporesposta) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_avaliacaogruporesposta"])) {
       $sql  .= $virgula." eso02_avaliacaogruporesposta = $this->eso02_avaliacaogruporesposta ";
       $virgula = ",";
            if (trim($this->eso02_avaliacaogruporesposta) == null) {
         $this->erro_sql = " Campo Resposta não informado.";
         $this->erro_campo = "eso02_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
        if (trim($this->eso02_rhpessoal) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_rhpessoal"])) {
       $sql  .= $virgula." eso02_rhpessoal = $this->eso02_rhpessoal ";
       $virgula = ",";
            if (trim($this->eso02_rhpessoal) == null) {
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "eso02_rhpessoal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
        if (trim($this->eso02_empregador) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_empregador"])) {
            $sql .= $virgula . " eso02_empregador = $this->eso02_empregador ";
            $virgula = ",";
            if (trim($this->eso02_empregador) == null) {
                $this->erro_sql = " Campo Empregador não informado.";
                $this->erro_campo = "eso02_empregador";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso02_avaliacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso02_avaliacao"])) {
            $sql .= $virgula . " eso02_avaliacao = $this->eso02_avaliacao ";
            $virgula = ",";
            if (trim($this->eso02_avaliacao) == null) {
                $this->erro_sql = " Campo Avaliação não informado.";
                $this->erro_campo = "eso02_avaliacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
     $sql .= " where ";
     if($eso02_sequencial!=null){
       $sql .= " eso02_sequencial = $this->eso02_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso02_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21792,'$this->eso02_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_sequencial"]) || $this->eso02_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3924,21792,'".AddSlashes(pg_result($resaco,$conresaco,'eso02_sequencial'))."','$this->eso02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_avaliacaogruporesposta"]) || $this->eso02_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,3924,21793,'".AddSlashes(pg_result($resaco,$conresaco,'eso02_avaliacaogruporesposta'))."','$this->eso02_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_rhpessoal"]) || $this->eso02_rhpessoal != "")
             $resac = db_query("insert into db_acount values($acount,3924,21794,'".AddSlashes(pg_result($resaco,$conresaco,'eso02_rhpessoal'))."','$this->eso02_rhpessoal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_empregador"]) || $this->eso02_empregador != "") {
                 $resac = db_query("insert into db_acount values($acount,3924,1010309,'" . AddSlashes(pg_result($resaco,
                         $conresaco,
                         'eso02_empregador')) . "','$this->eso02_empregador'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
             }
             if (isset($GLOBALS["HTTP_POST_VARS"]["eso02_avaliacao"]) || $this->eso02_avaliacao != "") {
                 $resac = db_query("insert into db_acount values($acount,3924,1010308,'" . AddSlashes(pg_result($resaco,
                         $conresaco,
                         'eso02_avaliacao')) . "','$this->eso02_avaliacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
             }
         }
       }
     }
     $result = db_query($sql);
        if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vincula uma resposta de avaliação a um servidor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
           $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
        }
    }

    public function excluir($eso02_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($eso02_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
            }
            if (($resaco != false) || ($this->numrows!=0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac  = db_query("insert into db_acountkey values($acount,21792,'$eso02_sequencial','E')");
                    $resac  = db_query("insert into db_acount values($acount,3924,21792,'','".AddSlashes(pg_result($resaco,$iresaco,'eso02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3924,21793,'','".AddSlashes(pg_result($resaco,$iresaco,'eso02_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,3924,21794,'','".AddSlashes(pg_result($resaco,$iresaco,'eso02_rhpessoal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac = db_query("insert into db_acount values($acount,3924,1010309,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso02_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,3924,1010308,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso02_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from avaliacaogruporespostarhpessoal
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso02_sequencial)){
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " eso02_sequencial = $eso02_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Vincula uma resposta de avaliação a um servidor não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$eso02_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Vincula uma resposta de avaliação a um servidor não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$eso02_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$eso02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostarhpessoal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
    }

    public function sql_query($eso02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostarhpessoal ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostarhpessoal.eso02_empregador";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = avaliacaogruporespostarhpessoal.eso02_rhpessoal";
        $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaogruporespostarhpessoal.eso02_avaliacao";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostarhpessoal.eso02_avaliacaogruporesposta";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
        $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso02_sequencial)) {
           $sql2 .= " where avaliacaogruporespostarhpessoal.eso02_sequencial = $eso02_sequencial ";
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

    public function sql_query_file($eso02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostarhpessoal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso02_sequencial)) {
           $sql2 .= " where avaliacaogruporespostarhpessoal.eso02_sequencial = $eso02_sequencial ";
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

  public function buscaRespostasPorPerguntaMatricula ($iCodigoPergunta = null, $iMatricula = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostarhpessoal ";
     // $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostarhpessoal.eso02_cgm";
     $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso02_avaliacaogruporesposta";
     $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
     $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
     $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
     $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
     $sql2 = "";
     if (empty($dbwhere)) {

       $sql2 .=" where ";
       $aWhere = array();

       if (!empty($iCodigoPergunta)) {
         $aWhere[] = " db103_sequencial = {$iCodigoPergunta} ";
       }
       if(!empty($iMatricula)){
        $aWhere[] = "eso02_rhpessoal = {$iMatricula}";
        // $aWhere[] = "eso02_avaliacaogruporesposta = (select max(eso02_avaliacaogruporesposta) from avaliacaogruporespostarhpessoal where eso02_rhpessoal = {$iMatricula})";
        $aWhere[] = "eso02_avaliacaogruporesposta = (select max(eso02_avaliacaogruporesposta)
                      from avaliacaogruporespostarhpessoal
                        inner join avaliacaogruporesposta on db107_sequencial = eso02_avaliacaogruporesposta
                        inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                        inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                        inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                        inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
                    where db103_sequencial = {$iCodigoPergunta} and eso02_rhpessoal = {$iMatricula})";
       }
       $sql2 .= implode("and ", $aWhere);

     } else if (!empty($dbwhere)) {
       $sql2 = " where {$dbwhere}";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

    /**
     * @param array $campos
     * @param array $where
     * @param array $groupBy
     * @return string
     */
    public function sql_avaliacao_preenchida(
        array $campos = array('*'),
        array $where = array(),
        array $groupBy = array()
    )
    {
        $campos = implode(', ', $campos);
        $where = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $groupBy = $groupBy ? 'GROUP BY ' . implode(', ', $groupBy) : '';

        $sql = "
            SELECT {$campos}
            FROM avaliacaogruporespostarhpessoal
            {$where}
            {$groupBy}
        ";

        return $sql;
    }
}
