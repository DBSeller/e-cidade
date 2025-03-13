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

class cl_bnccreferencial
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
    public $ed168_codigo = 0; 
    public $ed168_ensino = null; 
    public $ed168_etapa = null; 
    public $ed168_codigohabilidade = null; 
    public $ed168_codigoreferencial = null; 
    public $ed168_habilidade = null; 
    public $ed168_ano = 0; 
    public $ed168_objeto_conhecimento = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed168_codigo = int4 = Código 
                 ed168_ensino = varchar(2) = Ensino BNCC 
                 ed168_etapa = varchar(100) = Etapa BNCC 
                 ed168_codigohabilidade = varchar(10) = Código BNCC 
                 ed168_codigoreferencial = varchar(20) = Código Referencial 
                 ed168_habilidade = text = Habilidade do referencial 
                 ed168_ano = int4 = Ano 
                 ed168_objeto_conhecimento = text = Objeto de Conhecimento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("bnccreferencial"); 
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
       $this->ed168_codigo = ($this->ed168_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_codigo"]:$this->ed168_codigo);
       $this->ed168_ensino = ($this->ed168_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_ensino"]:$this->ed168_ensino);
       $this->ed168_etapa = ($this->ed168_etapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_etapa"]:$this->ed168_etapa);
       $this->ed168_codigohabilidade = ($this->ed168_codigohabilidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_codigohabilidade"]:$this->ed168_codigohabilidade);
       $this->ed168_codigoreferencial = ($this->ed168_codigoreferencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_codigoreferencial"]:$this->ed168_codigoreferencial);
       $this->ed168_habilidade = ($this->ed168_habilidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_habilidade"]:$this->ed168_habilidade);
       $this->ed168_ano = ($this->ed168_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_ano"]:$this->ed168_ano);
       $this->ed168_objeto_conhecimento = ($this->ed168_objeto_conhecimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_objeto_conhecimento"]:$this->ed168_objeto_conhecimento);
     }else{
       $this->ed168_codigo = ($this->ed168_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed168_codigo"]:$this->ed168_codigo);
     }
   }

    public function incluir($ed168_codigo)
    {
      $this->atualizacampos();
     if($this->ed168_ensino == null ){ 
       $this->erro_sql = " Campo Ensino BNCC não informado.";
       $this->erro_campo = "ed168_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed168_codigohabilidade == null ){ 
       $this->erro_sql = " Campo Código BNCC não informado.";
       $this->erro_campo = "ed168_codigohabilidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed168_codigoreferencial == null ){ 
       $this->erro_sql = " Campo Código Referencial não informado.";
       $this->erro_campo = "ed168_codigoreferencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed168_habilidade == null ){ 
       $this->erro_sql = " Campo Habilidade do referencial não informado.";
       $this->erro_campo = "ed168_habilidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed168_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed168_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed168_codigo == "" || $ed168_codigo == null ){
       $result = db_query("select nextval('bnccreferencial_ed168_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bnccreferencial_ed168_codigo_seq do campo: ed168_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed168_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bnccreferencial_ed168_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed168_codigo)){
         $this->erro_sql = " Campo ed168_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed168_codigo = $ed168_codigo; 
       }
     }
     if(($this->ed168_codigo == null) || ($this->ed168_codigo == "") ){ 
       $this->erro_sql = " Campo ed168_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bnccreferencial(
                                       ed168_codigo 
                                      ,ed168_ensino 
                                      ,ed168_etapa 
                                      ,ed168_codigohabilidade 
                                      ,ed168_codigoreferencial 
                                      ,ed168_habilidade 
                                      ,ed168_ano 
                                      ,ed168_objeto_conhecimento 
                       )
                values (
                                $this->ed168_codigo 
                               ,'$this->ed168_ensino' 
                               ,'$this->ed168_etapa' 
                               ,'$this->ed168_codigohabilidade' 
                               ,'$this->ed168_codigoreferencial' 
                               ,'$this->ed168_habilidade' 
                               ,$this->ed168_ano 
                               ,'$this->ed168_objeto_conhecimento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Referencial Curricular Estadual ($this->ed168_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Referencial Curricular Estadual já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Referencial Curricular Estadual ($this->ed168_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed168_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed168_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011771,'$this->ed168_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010614,1011771,'','".AddSlashes(pg_result($resaco,0,'ed168_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1011772,'','".AddSlashes(pg_result($resaco,0,'ed168_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1011773,'','".AddSlashes(pg_result($resaco,0,'ed168_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1011774,'','".AddSlashes(pg_result($resaco,0,'ed168_codigohabilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1011775,'','".AddSlashes(pg_result($resaco,0,'ed168_codigoreferencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1011776,'','".AddSlashes(pg_result($resaco,0,'ed168_habilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1011780,'','".AddSlashes(pg_result($resaco,0,'ed168_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010614,1013803,'','".AddSlashes(pg_result($resaco,0,'ed168_objeto_conhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed168_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update bnccreferencial set ";
     $virgula = "";
     if(trim($this->ed168_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_codigo"])){ 
       $sql  .= $virgula." ed168_codigo = $this->ed168_codigo ";
       $virgula = ",";
       if(trim($this->ed168_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed168_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed168_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_ensino"])){ 
       $sql  .= $virgula." ed168_ensino = '$this->ed168_ensino' ";
       $virgula = ",";
       if(trim($this->ed168_ensino) == null ){ 
         $this->erro_sql = " Campo Ensino BNCC não informado.";
         $this->erro_campo = "ed168_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed168_etapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_etapa"])){ 
       $sql  .= $virgula." ed168_etapa = '$this->ed168_etapa' ";
       $virgula = ",";
     }
     if(trim($this->ed168_codigohabilidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_codigohabilidade"])){ 
       $sql  .= $virgula." ed168_codigohabilidade = '$this->ed168_codigohabilidade' ";
       $virgula = ",";
       if(trim($this->ed168_codigohabilidade) == null ){ 
         $this->erro_sql = " Campo Código BNCC não informado.";
         $this->erro_campo = "ed168_codigohabilidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed168_codigoreferencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_codigoreferencial"])){ 
       $sql  .= $virgula." ed168_codigoreferencial = '$this->ed168_codigoreferencial' ";
       $virgula = ",";
       if(trim($this->ed168_codigoreferencial) == null ){ 
         $this->erro_sql = " Campo Código Referencial não informado.";
         $this->erro_campo = "ed168_codigoreferencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed168_habilidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_habilidade"])){ 
       $sql  .= $virgula." ed168_habilidade = '$this->ed168_habilidade' ";
       $virgula = ",";
       if(trim($this->ed168_habilidade) == null ){ 
         $this->erro_sql = " Campo Habilidade do referencial não informado.";
         $this->erro_campo = "ed168_habilidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed168_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_ano"])){ 
       $sql  .= $virgula." ed168_ano = $this->ed168_ano ";
       $virgula = ",";
       if(trim($this->ed168_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed168_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed168_objeto_conhecimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed168_objeto_conhecimento"])){ 
       $sql  .= $virgula." ed168_objeto_conhecimento = '$this->ed168_objeto_conhecimento' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed168_codigo!=null){
       $sql .= " ed168_codigo = $this->ed168_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed168_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011771,'$this->ed168_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_codigo"]) || $this->ed168_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011771,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_codigo'))."','$this->ed168_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_ensino"]) || $this->ed168_ensino != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011772,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_ensino'))."','$this->ed168_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_etapa"]) || $this->ed168_etapa != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011773,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_etapa'))."','$this->ed168_etapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_codigohabilidade"]) || $this->ed168_codigohabilidade != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011774,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_codigohabilidade'))."','$this->ed168_codigohabilidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_codigoreferencial"]) || $this->ed168_codigoreferencial != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011775,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_codigoreferencial'))."','$this->ed168_codigoreferencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_habilidade"]) || $this->ed168_habilidade != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011776,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_habilidade'))."','$this->ed168_habilidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_ano"]) || $this->ed168_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1011780,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_ano'))."','$this->ed168_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed168_objeto_conhecimento"]) || $this->ed168_objeto_conhecimento != "")
             $resac = db_query("insert into db_acount values($acount,1010614,1013803,'".AddSlashes(pg_result($resaco,$conresaco,'ed168_objeto_conhecimento'))."','$this->ed168_objeto_conhecimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Referencial Curricular Estadual não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed168_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Referencial Curricular Estadual não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed168_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed168_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed168_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed168_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011771,'$ed168_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011771,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011772,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011773,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011774,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_codigohabilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011775,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_codigoreferencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011776,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_habilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1011780,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010614,1013803,'','".AddSlashes(pg_result($resaco,$iresaco,'ed168_objeto_conhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from bnccreferencial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed168_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed168_codigo = $ed168_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Referencial Curricular Estadual não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed168_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Referencial Curricular Estadual não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed168_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed168_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:bnccreferencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed168_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from bnccreferencial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed168_codigo)) {
         $sql2 .= " where bnccreferencial.ed168_codigo = $ed168_codigo "; 
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

    public function sql_query_file($ed168_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from bnccreferencial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed168_codigo)){
         $sql2 .= " where bnccreferencial.ed168_codigo = $ed168_codigo "; 
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

}
