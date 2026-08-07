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

class cl_associadoservicos
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
    public $fm12_codigo = 0; 
    public $fm12_tpservico = 0; 
    public $fm12_descricao = null; 
    public $fm12_autorizacao = 'f'; 
    public $fm12_odontograma = 'f'; 
    public $fm12_idademin = 0; 
    public $fm12_idademax = 0; 
    public $fm12_validadeini_dia = null; 
    public $fm12_validadeini_mes = null; 
    public $fm12_validadeini_ano = null; 
    public $fm12_validadeini = null; 
    public $fm12_validadefim_dia = null; 
    public $fm12_validadefim_mes = null; 
    public $fm12_validadefim_ano = null; 
    public $fm12_validadefim = null; 
    public $fm12_masculino = 'f'; 
    public $fm12_feminino = 'f'; 
    public $fm12_situacao = 'f'; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 fm12_codigo = int4 = Código do Serviço Prestado 
                 fm12_tpservico = int4 = Código do Tipo de Serviço 
                 fm12_descricao = varchar = Descrição do Serviço 
                 fm12_autorizacao = bool = Necessita Autorização do Serviço 
                 fm12_odontograma = bool = Preencher Odontograma 
                 fm12_idademin = int4 = Idade Mínima 
                 fm12_idademax = int4 = Idade Máxima 
                 fm12_validadeini = date = Validade Inicial 
                 fm12_validadefim = date = Validade Final 
                 fm12_masculino = bool = Masculino 
                 fm12_feminino = bool = Feminino 
                 fm12_situacao = bool = Situação do Serviço 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("associadoservicos"); 
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
       $this->fm12_codigo = ($this->fm12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_codigo"]:$this->fm12_codigo);
       $this->fm12_tpservico = ($this->fm12_tpservico == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_tpservico"]:$this->fm12_tpservico);
       $this->fm12_descricao = ($this->fm12_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_descricao"]:$this->fm12_descricao);
       $this->fm12_autorizacao = ($this->fm12_autorizacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm12_autorizacao"]:$this->fm12_autorizacao);
       $this->fm12_odontograma = ($this->fm12_odontograma == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm12_odontograma"]:$this->fm12_odontograma);
       $this->fm12_idademin = ($this->fm12_idademin == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_idademin"]:$this->fm12_idademin);
       $this->fm12_idademax = ($this->fm12_idademax == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_idademax"]:$this->fm12_idademax);
       if($this->fm12_validadeini == ""){
         $this->fm12_validadeini_dia = ($this->fm12_validadeini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_validadeini_dia"]:$this->fm12_validadeini_dia);
         $this->fm12_validadeini_mes = ($this->fm12_validadeini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_validadeini_mes"]:$this->fm12_validadeini_mes);
         $this->fm12_validadeini_ano = ($this->fm12_validadeini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_validadeini_ano"]:$this->fm12_validadeini_ano);
         if($this->fm12_validadeini_dia != ""){
            $this->fm12_validadeini = $this->fm12_validadeini_ano."-".$this->fm12_validadeini_mes."-".$this->fm12_validadeini_dia;
         }
       }
       if($this->fm12_validadefim == ""){
         $this->fm12_validadefim_dia = ($this->fm12_validadefim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_validadefim_dia"]:$this->fm12_validadefim_dia);
         $this->fm12_validadefim_mes = ($this->fm12_validadefim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_validadefim_mes"]:$this->fm12_validadefim_mes);
         $this->fm12_validadefim_ano = ($this->fm12_validadefim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_validadefim_ano"]:$this->fm12_validadefim_ano);
         if($this->fm12_validadefim_dia != ""){
            $this->fm12_validadefim = $this->fm12_validadefim_ano."-".$this->fm12_validadefim_mes."-".$this->fm12_validadefim_dia;
         }
       }
       $this->fm12_masculino = ($this->fm12_masculino == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm12_masculino"]:$this->fm12_masculino);
       $this->fm12_feminino = ($this->fm12_feminino == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm12_feminino"]:$this->fm12_feminino);
       $this->fm12_situacao = ($this->fm12_situacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm12_situacao"]:$this->fm12_situacao);
     }else{
       $this->fm12_codigo = ($this->fm12_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm12_codigo"]:$this->fm12_codigo);
     }
   }

    public function incluir($fm12_codigo)
    {
      $this->atualizacampos();
     if($this->fm12_tpservico == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Serviço não informado.";
       $this->erro_campo = "fm12_tpservico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_descricao == null ){ 
       $this->erro_sql = " Campo Descrição do Serviço não informado.";
       $this->erro_campo = "fm12_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_autorizacao == null ){ 
       $this->erro_sql = " Campo Necessita Autorização do Serviço não informado.";
       $this->erro_campo = "fm12_autorizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_odontograma == null ){ 
       $this->erro_sql = " Campo Preencher Odontograma não informado.";
       $this->erro_campo = "fm12_odontograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_idademin == null ){ 
       $this->erro_sql = " Campo Idade Mínima não informado.";
       $this->erro_campo = "fm12_idademin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_idademax == null ){ 
       $this->erro_sql = " Campo Idade Máxima não informado.";
       $this->erro_campo = "fm12_idademax";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_masculino == null ){ 
       $this->erro_sql = " Campo Masculino não informado.";
       $this->erro_campo = "fm12_masculino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_feminino == null ){ 
       $this->erro_sql = " Campo Feminino não informado.";
       $this->erro_campo = "fm12_feminino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm12_situacao == null ){ 
       $this->erro_sql = " Campo Situação do Serviço não informado.";
       $this->erro_campo = "fm12_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fm12_codigo == "" || $fm12_codigo == null ){
       $result = db_query("select nextval('associadoservicos_fm12_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: associadoservicos_fm12_codigo_seq do campo: fm12_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fm12_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from associadoservicos_fm12_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fm12_codigo)){
         $this->erro_sql = " Campo fm12_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fm12_codigo = $fm12_codigo; 
       }
     }
     if(($this->fm12_codigo == null) || ($this->fm12_codigo == "") ){ 
       $this->erro_sql = " Campo fm12_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into associadoservicos(
                                       fm12_codigo 
                                      ,fm12_tpservico 
                                      ,fm12_descricao 
                                      ,fm12_autorizacao 
                                      ,fm12_odontograma 
                                      ,fm12_idademin 
                                      ,fm12_idademax 
                                      ,fm12_validadeini 
                                      ,fm12_validadefim 
                                      ,fm12_masculino 
                                      ,fm12_feminino 
                                      ,fm12_situacao 
                       )
                values (
                                $this->fm12_codigo 
                               ,$this->fm12_tpservico 
                               ,'$this->fm12_descricao' 
                               ,'$this->fm12_autorizacao' 
                               ,'$this->fm12_odontograma' 
                               ,$this->fm12_idademin 
                               ,$this->fm12_idademax 
                               ,".($this->fm12_validadeini == "null" || $this->fm12_validadeini == ""?"null":"'".$this->fm12_validadeini."'")." 
                               ,".($this->fm12_validadefim == "null" || $this->fm12_validadefim == ""?"null":"'".$this->fm12_validadefim."'")." 
                               ,'$this->fm12_masculino' 
                               ,'$this->fm12_feminino' 
                               ,'$this->fm12_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "associadoservicos ($this->fm12_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "associadoservicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "associadoservicos ($this->fm12_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fm12_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm12_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,264300007,'$this->fm12_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,239833994,264300007,'','".AddSlashes(pg_result($resaco,0,'fm12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,53692564,'','".AddSlashes(pg_result($resaco,0,'fm12_tpservico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,194860450,'','".AddSlashes(pg_result($resaco,0,'fm12_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,150027067,'','".AddSlashes(pg_result($resaco,0,'fm12_autorizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,82956483,'','".AddSlashes(pg_result($resaco,0,'fm12_odontograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,150589080,'','".AddSlashes(pg_result($resaco,0,'fm12_idademin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,187660452,'','".AddSlashes(pg_result($resaco,0,'fm12_idademax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,213279410,'','".AddSlashes(pg_result($resaco,0,'fm12_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,280956939,'','".AddSlashes(pg_result($resaco,0,'fm12_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,214518977,'','".AddSlashes(pg_result($resaco,0,'fm12_masculino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,180837347,'','".AddSlashes(pg_result($resaco,0,'fm12_feminino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,239833994,157817432,'','".AddSlashes(pg_result($resaco,0,'fm12_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($fm12_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update associadoservicos set ";
     $virgula = "";
     if(trim($this->fm12_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_codigo"])){ 
       $sql  .= $virgula." fm12_codigo = $this->fm12_codigo ";
       $virgula = ",";
       if(trim($this->fm12_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Serviço Prestado não informado.";
         $this->erro_campo = "fm12_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_tpservico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_tpservico"])){ 
       $sql  .= $virgula." fm12_tpservico = $this->fm12_tpservico ";
       $virgula = ",";
       if(trim($this->fm12_tpservico) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Serviço não informado.";
         $this->erro_campo = "fm12_tpservico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_descricao"])){ 
       $sql  .= $virgula." fm12_descricao = '$this->fm12_descricao' ";
       $virgula = ",";
       if(trim($this->fm12_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição do Serviço não informado.";
         $this->erro_campo = "fm12_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_autorizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_autorizacao"])){ 
       $sql  .= $virgula." fm12_autorizacao = '$this->fm12_autorizacao' ";
       $virgula = ",";
       if(trim($this->fm12_autorizacao) == null ){ 
         $this->erro_sql = " Campo Necessita Autorização do Serviço não informado.";
         $this->erro_campo = "fm12_autorizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_odontograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_odontograma"])){ 
       $sql  .= $virgula." fm12_odontograma = '$this->fm12_odontograma' ";
       $virgula = ",";
       if(trim($this->fm12_odontograma) == null ){ 
         $this->erro_sql = " Campo Preencher Odontograma não informado.";
         $this->erro_campo = "fm12_odontograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_idademin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_idademin"])){ 
       $sql  .= $virgula." fm12_idademin = $this->fm12_idademin ";
       $virgula = ",";
       if(trim($this->fm12_idademin) == null ){ 
         $this->erro_sql = " Campo Idade Mínima não informado.";
         $this->erro_campo = "fm12_idademin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_idademax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_idademax"])){ 
       $sql  .= $virgula." fm12_idademax = $this->fm12_idademax ";
       $virgula = ",";
       if(trim($this->fm12_idademax) == null ){ 
         $this->erro_sql = " Campo Idade Máxima não informado.";
         $this->erro_campo = "fm12_idademax";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_validadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_validadeini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fm12_validadeini_dia"] !="") ){ 
       $sql  .= $virgula." fm12_validadeini = '$this->fm12_validadeini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fm12_validadeini_dia"])){ 
         $sql  .= $virgula." fm12_validadeini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fm12_validadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_validadefim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fm12_validadefim_dia"] !="") ){ 
       $sql  .= $virgula." fm12_validadefim = '$this->fm12_validadefim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fm12_validadefim_dia"])){ 
         $sql  .= $virgula." fm12_validadefim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fm12_masculino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_masculino"])){ 
       $sql  .= $virgula." fm12_masculino = '$this->fm12_masculino' ";
       $virgula = ",";
       if(trim($this->fm12_masculino) == null ){ 
         $this->erro_sql = " Campo Masculino não informado.";
         $this->erro_campo = "fm12_masculino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_feminino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_feminino"])){ 
       $sql  .= $virgula." fm12_feminino = '$this->fm12_feminino' ";
       $virgula = ",";
       if(trim($this->fm12_feminino) == null ){ 
         $this->erro_sql = " Campo Feminino não informado.";
         $this->erro_campo = "fm12_feminino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm12_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm12_situacao"])){ 
       $sql  .= $virgula." fm12_situacao = '$this->fm12_situacao' ";
       $virgula = ",";
       if(trim($this->fm12_situacao) == null ){ 
         $this->erro_sql = " Campo Situação do Serviço não informado.";
         $this->erro_campo = "fm12_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fm12_codigo!=null){
       $sql .= " fm12_codigo = $this->fm12_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm12_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,264300007,'$this->fm12_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_codigo"]) || $this->fm12_codigo != "")
             $resac = db_query("insert into db_acount values($acount,239833994,264300007,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_codigo'))."','$this->fm12_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_tpservico"]) || $this->fm12_tpservico != "")
             $resac = db_query("insert into db_acount values($acount,239833994,53692564,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_tpservico'))."','$this->fm12_tpservico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_descricao"]) || $this->fm12_descricao != "")
             $resac = db_query("insert into db_acount values($acount,239833994,194860450,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_descricao'))."','$this->fm12_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_autorizacao"]) || $this->fm12_autorizacao != "")
             $resac = db_query("insert into db_acount values($acount,239833994,150027067,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_autorizacao'))."','$this->fm12_autorizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_odontograma"]) || $this->fm12_odontograma != "")
             $resac = db_query("insert into db_acount values($acount,239833994,82956483,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_odontograma'))."','$this->fm12_odontograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_idademin"]) || $this->fm12_idademin != "")
             $resac = db_query("insert into db_acount values($acount,239833994,150589080,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_idademin'))."','$this->fm12_idademin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_idademax"]) || $this->fm12_idademax != "")
             $resac = db_query("insert into db_acount values($acount,239833994,187660452,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_idademax'))."','$this->fm12_idademax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_validadeini"]) || $this->fm12_validadeini != "")
             $resac = db_query("insert into db_acount values($acount,239833994,213279410,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_validadeini'))."','$this->fm12_validadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_validadefim"]) || $this->fm12_validadefim != "")
             $resac = db_query("insert into db_acount values($acount,239833994,280956939,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_validadefim'))."','$this->fm12_validadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_masculino"]) || $this->fm12_masculino != "")
             $resac = db_query("insert into db_acount values($acount,239833994,214518977,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_masculino'))."','$this->fm12_masculino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_feminino"]) || $this->fm12_feminino != "")
             $resac = db_query("insert into db_acount values($acount,239833994,180837347,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_feminino'))."','$this->fm12_feminino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm12_situacao"]) || $this->fm12_situacao != "")
             $resac = db_query("insert into db_acount values($acount,239833994,157817432,'".AddSlashes(pg_result($resaco,$conresaco,'fm12_situacao'))."','$this->fm12_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "associadoservicos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm12_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "associadoservicos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fm12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($fm12_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fm12_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,264300007,'$fm12_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,239833994,264300007,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,53692564,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_tpservico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,194860450,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,150027067,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_autorizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,82956483,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_odontograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,150589080,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_idademin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,187660452,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_idademax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,213279410,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,280956939,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,214518977,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_masculino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,180837347,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_feminino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,239833994,157817432,'','".AddSlashes(pg_result($resaco,$iresaco,'fm12_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from associadoservicos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fm12_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fm12_codigo = $fm12_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "associadoservicos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fm12_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "associadoservicos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fm12_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$fm12_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:associadoservicos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($fm12_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from associadoservicos ";
     $sql .= "      inner join associadotiposservicos  on  associadotiposservicos.fm09_codigo = associadoservicos.fm12_tpservico";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm12_codigo)) {
         $sql2 .= " where associadoservicos.fm12_codigo = $fm12_codigo "; 
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

    public function sql_query_file($fm12_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from associadoservicos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm12_codigo)){
         $sql2 .= " where associadoservicos.fm12_codigo = $fm12_codigo "; 
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
