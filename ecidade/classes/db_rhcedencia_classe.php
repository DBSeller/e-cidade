<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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
class cl_rhcedencia
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
    public $rh261_sequencial = 0; 
    public $rh261_regist = 0; 
    public $rh261_credencial = null; 
    public $rh261_onus = null; 
    public $rh261_ressarcimento = null; 
    public $rh261_datamovimentacao_dia = null; 
    public $rh261_datamovimentacao_mes = null; 
    public $rh261_datamovimentacao_ano = null; 
    public $rh261_datamovimentacao = null; 
    public $rh261_devolucao_dia = null; 
    public $rh261_devolucao_mes = null; 
    public $rh261_devolucao_ano = null; 
    public $rh261_devolucao = null; 
    public $rh261_numcgm = 0; 
    public $rh261_matorigemcedente = null; 
    public $rh261_servidorcedido = null; 
    public $rh261_indicadoconselho = 'f'; 
    public $rh261_tiporegimeprev = 0; 
    public $rh261_tiporegimeorigem = 0; 
    public $rh261_dtorigemadmissao_dia = null; 
    public $rh261_dtorigemadmissao_mes = null; 
    public $rh261_dtorigemadmissao_ano = null; 
    public $rh261_dtorigemadmissao = null; 
    public $rh261_codcategoriaorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh261_sequencial = int4 = Codigo de Refência da Tabela 
                 rh261_regist = int4 = Matrícula 
                 rh261_credencial = char(1) = credencial 
                 rh261_onus = char(1) = Ônus 
                 rh261_ressarcimento = char(1) = Ressarcimento 
                 rh261_datamovimentacao = date = Data Movimentação 
                 rh261_devolucao = date = Data Devolução 
                 rh261_numcgm = int4 = CGM Origem/Destino 
                 rh261_matorigemcedente = char(30) = Matrícula Origem no Orgão Cedente 
                 rh261_servidorcedido = char(1) = Servidor Cedido eSocial(S1200/S1 
                 rh261_indicadoconselho = bool = Servidor Indicado para Conselho 
                 rh261_tiporegimeprev = int4 = Tipo de Regime Previdenciário 
                 rh261_tiporegimeorigem = int4 = Tipo de Regime Trab. Origem 
                 rh261_dtorigemadmissao = date = Data de admissão origem 
                 rh261_codcategoriaorigem = int4 = Código de Categoria 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhcedencia"); 
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
       $this->rh261_sequencial = ($this->rh261_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_sequencial"]:$this->rh261_sequencial);
       $this->rh261_regist = ($this->rh261_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_regist"]:$this->rh261_regist);
       $this->rh261_credencial = ($this->rh261_credencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_credencial"]:$this->rh261_credencial);
       $this->rh261_onus = ($this->rh261_onus == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_onus"]:$this->rh261_onus);
       $this->rh261_ressarcimento = ($this->rh261_ressarcimento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_ressarcimento"]:$this->rh261_ressarcimento);
       if($this->rh261_datamovimentacao == ""){
         $this->rh261_datamovimentacao_dia = ($this->rh261_datamovimentacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao_dia"]:$this->rh261_datamovimentacao_dia);
         $this->rh261_datamovimentacao_mes = ($this->rh261_datamovimentacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao_mes"]:$this->rh261_datamovimentacao_mes);
         $this->rh261_datamovimentacao_ano = ($this->rh261_datamovimentacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao_ano"]:$this->rh261_datamovimentacao_ano);
         if($this->rh261_datamovimentacao_dia != ""){
            $this->rh261_datamovimentacao = $this->rh261_datamovimentacao_ano."-".$this->rh261_datamovimentacao_mes."-".$this->rh261_datamovimentacao_dia;
         }
       }
       if($this->rh261_devolucao == ""){
         $this->rh261_devolucao_dia = ($this->rh261_devolucao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_devolucao_dia"]:$this->rh261_devolucao_dia);
         $this->rh261_devolucao_mes = ($this->rh261_devolucao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_devolucao_mes"]:$this->rh261_devolucao_mes);
         $this->rh261_devolucao_ano = ($this->rh261_devolucao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_devolucao_ano"]:$this->rh261_devolucao_ano);
         if($this->rh261_devolucao_dia != ""){
            $this->rh261_devolucao = $this->rh261_devolucao_ano."-".$this->rh261_devolucao_mes."-".$this->rh261_devolucao_dia;
         }
       }
       $this->rh261_numcgm = ($this->rh261_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_numcgm"]:$this->rh261_numcgm);
       $this->rh261_matorigemcedente = ($this->rh261_matorigemcedente == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_matorigemcedente"]:$this->rh261_matorigemcedente);
       $this->rh261_servidorcedido = ($this->rh261_servidorcedido == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_servidorcedido"]:$this->rh261_servidorcedido);
       $this->rh261_indicadoconselho = ($this->rh261_indicadoconselho == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh261_indicadoconselho"]:$this->rh261_indicadoconselho);
       $this->rh261_tiporegimeprev = ($this->rh261_tiporegimeprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeprev"]:$this->rh261_tiporegimeprev);
       $this->rh261_tiporegimeorigem = ($this->rh261_tiporegimeorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeorigem"]:$this->rh261_tiporegimeorigem);
       if($this->rh261_dtorigemadmissao == ""){
         $this->rh261_dtorigemadmissao_dia = ($this->rh261_dtorigemadmissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao_dia"]:$this->rh261_dtorigemadmissao_dia);
         $this->rh261_dtorigemadmissao_mes = ($this->rh261_dtorigemadmissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao_mes"]:$this->rh261_dtorigemadmissao_mes);
         $this->rh261_dtorigemadmissao_ano = ($this->rh261_dtorigemadmissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao_ano"]:$this->rh261_dtorigemadmissao_ano);
         if($this->rh261_dtorigemadmissao_dia != ""){
            $this->rh261_dtorigemadmissao = $this->rh261_dtorigemadmissao_ano."-".$this->rh261_dtorigemadmissao_mes."-".$this->rh261_dtorigemadmissao_dia;
         }
       }
       $this->rh261_codcategoriaorigem = ($this->rh261_codcategoriaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_codcategoriaorigem"]:$this->rh261_codcategoriaorigem);
     }else{
       $this->rh261_sequencial = ($this->rh261_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh261_sequencial"]:$this->rh261_sequencial);
     }
   }

    public function incluir($rh261_sequencial)
    {
      $this->atualizacampos();
     if($this->rh261_regist == null ){ 
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "rh261_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh261_credencial == null ){ 
       $this->erro_sql = " Campo credencial não informado.";
       $this->erro_campo = "rh261_credencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh261_onus == null ){ 
       $this->erro_sql = " Campo Ônus não informado.";
       $this->erro_campo = "rh261_onus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh261_ressarcimento == null ){ 
       $this->erro_sql = " Campo Ressarcimento não informado.";
       $this->erro_campo = "rh261_ressarcimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh261_datamovimentacao == null ){ 
       $this->rh261_datamovimentacao = "null";
     }
     if($this->rh261_devolucao == null ){ 
       $this->rh261_devolucao = "null";
     }
     if($this->rh261_numcgm == null ){ 
       $this->rh261_numcgm = "0";
     }
     if($this->rh261_indicadoconselho == null ){ 
       $this->rh261_indicadoconselho = "f";
     }
     if($this->rh261_tiporegimeprev == null ){ 
       $this->rh261_tiporegimeprev = "0";
     }
     if($this->rh261_tiporegimeorigem == null ){ 
       $this->rh261_tiporegimeorigem = "0";
     }
     if($this->rh261_dtorigemadmissao == null ){ 
       $this->rh261_dtorigemadmissao = "null";
     }
     if($this->rh261_codcategoriaorigem == null ){ 
       $this->rh261_codcategoriaorigem = "0";
     }
     if($rh261_sequencial == "" || $rh261_sequencial == null ){
       $result = db_query("select nextval('rhcedencia_rh261_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhcedencia_rh261_sequencial_seq do campo: rh261_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh261_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhcedencia_rh261_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh261_sequencial)){
         $this->erro_sql = " Campo rh261_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh261_sequencial = $rh261_sequencial; 
       }
     }
     if(($this->rh261_sequencial == null) || ($this->rh261_sequencial == "") ){ 
       $this->erro_sql = " Campo rh261_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhcedencia(
                                       rh261_sequencial 
                                      ,rh261_regist 
                                      ,rh261_credencial 
                                      ,rh261_onus 
                                      ,rh261_ressarcimento 
                                      ,rh261_datamovimentacao 
                                      ,rh261_devolucao 
                                      ,rh261_numcgm 
                                      ,rh261_matorigemcedente 
                                      ,rh261_servidorcedido 
                                      ,rh261_indicadoconselho 
                                      ,rh261_tiporegimeprev 
                                      ,rh261_tiporegimeorigem 
                                      ,rh261_dtorigemadmissao 
                                      ,rh261_codcategoriaorigem 
                       )
                values (
                                $this->rh261_sequencial 
                               ,$this->rh261_regist 
                               ,'$this->rh261_credencial' 
                               ,'$this->rh261_onus' 
                               ,'$this->rh261_ressarcimento' 
                               ,".($this->rh261_datamovimentacao == "null" || $this->rh261_datamovimentacao == ""?"null":"'".$this->rh261_datamovimentacao."'")." 
                               ,".($this->rh261_devolucao == "null" || $this->rh261_devolucao == ""?"null":"'".$this->rh261_devolucao."'")." 
                               ,$this->rh261_numcgm 
                               ,'$this->rh261_matorigemcedente' 
                               ,'$this->rh261_servidorcedido' 
                               ,'$this->rh261_indicadoconselho' 
                               ,$this->rh261_tiporegimeprev 
                               ,$this->rh261_tiporegimeorigem 
                               ,".($this->rh261_dtorigemadmissao == "null" || $this->rh261_dtorigemadmissao == ""?"null":"'".$this->rh261_dtorigemadmissao."'")." 
                               ,$this->rh261_codcategoriaorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cedencia ($this->rh261_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cedencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cedencia ($this->rh261_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh261_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh261_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014120,'$this->rh261_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010896,1014120,'','".AddSlashes(pg_result($resaco,0,'rh261_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013999,'','".AddSlashes(pg_result($resaco,0,'rh261_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013968,'','".AddSlashes(pg_result($resaco,0,'rh261_credencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013969,'','".AddSlashes(pg_result($resaco,0,'rh261_onus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013970,'','".AddSlashes(pg_result($resaco,0,'rh261_ressarcimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013971,'','".AddSlashes(pg_result($resaco,0,'rh261_datamovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013972,'','".AddSlashes(pg_result($resaco,0,'rh261_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013973,'','".AddSlashes(pg_result($resaco,0,'rh261_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013974,'','".AddSlashes(pg_result($resaco,0,'rh261_matorigemcedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1013975,'','".AddSlashes(pg_result($resaco,0,'rh261_servidorcedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1014119,'','".AddSlashes(pg_result($resaco,0,'rh261_indicadoconselho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1014128,'','".AddSlashes(pg_result($resaco,0,'rh261_tiporegimeprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1014127,'','".AddSlashes(pg_result($resaco,0,'rh261_tiporegimeorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1014126,'','".AddSlashes(pg_result($resaco,0,'rh261_dtorigemadmissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010896,1014125,'','".AddSlashes(pg_result($resaco,0,'rh261_codcategoriaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh261_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhcedencia set ";
     $virgula = "";
     if(trim($this->rh261_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_sequencial"])){ 
       $sql  .= $virgula." rh261_sequencial = $this->rh261_sequencial ";
       $virgula = ",";
       if(trim($this->rh261_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo de Refência da Tabela não informado.";
         $this->erro_campo = "rh261_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh261_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_regist"])){ 
       $sql  .= $virgula." rh261_regist = $this->rh261_regist ";
       $virgula = ",";
       if(trim($this->rh261_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "rh261_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh261_credencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_credencial"])){ 
       $sql  .= $virgula." rh261_credencial = '$this->rh261_credencial' ";
       $virgula = ",";
       if(trim($this->rh261_credencial) == null ){ 
         $this->erro_sql = " Campo credencial não informado.";
         $this->erro_campo = "rh261_credencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh261_onus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_onus"])){ 
       $sql  .= $virgula." rh261_onus = '$this->rh261_onus' ";
       $virgula = ",";
       if(trim($this->rh261_onus) == null ){ 
         $this->erro_sql = " Campo Ônus não informado.";
         $this->erro_campo = "rh261_onus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh261_ressarcimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_ressarcimento"])){ 
       $sql  .= $virgula." rh261_ressarcimento = '$this->rh261_ressarcimento' ";
       $virgula = ",";
       if(trim($this->rh261_ressarcimento) == null ){ 
         $this->erro_sql = " Campo Ressarcimento não informado.";
         $this->erro_campo = "rh261_ressarcimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh261_datamovimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao_dia"] !="") ){ 
       $sql  .= $virgula." rh261_datamovimentacao = '$this->rh261_datamovimentacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao_dia"])){ 
         $sql  .= $virgula." rh261_datamovimentacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh261_devolucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_devolucao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh261_devolucao_dia"] !="") ){ 
       $sql  .= $virgula." rh261_devolucao = '$this->rh261_devolucao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh261_devolucao_dia"])){ 
         $sql  .= $virgula." rh261_devolucao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh261_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_numcgm"])){ 
        if(trim($this->rh261_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh261_numcgm"])){ 
           $this->rh261_numcgm = "0" ; 
        } 
       $sql  .= $virgula." rh261_numcgm = $this->rh261_numcgm ";
       $virgula = ",";
     }
     if(trim($this->rh261_matorigemcedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_matorigemcedente"])){ 
       $sql  .= $virgula." rh261_matorigemcedente = '$this->rh261_matorigemcedente' ";
       $virgula = ",";
     }
     if(trim($this->rh261_servidorcedido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_servidorcedido"])){ 
       $sql  .= $virgula." rh261_servidorcedido = '$this->rh261_servidorcedido' ";
       $virgula = ",";
     }
     if(trim($this->rh261_indicadoconselho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_indicadoconselho"])){ 
       $sql  .= $virgula." rh261_indicadoconselho = '$this->rh261_indicadoconselho' ";
       $virgula = ",";
     }
     if(trim($this->rh261_tiporegimeprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeprev"])){ 
        if(trim($this->rh261_tiporegimeprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeprev"])){ 
           $this->rh261_tiporegimeprev = "0" ; 
        } 
       $sql  .= $virgula." rh261_tiporegimeprev = $this->rh261_tiporegimeprev ";
       $virgula = ",";
     }
     if(trim($this->rh261_tiporegimeorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeorigem"])){ 
        if(trim($this->rh261_tiporegimeorigem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeorigem"])){ 
           $this->rh261_tiporegimeorigem = "0" ; 
        } 
       $sql  .= $virgula." rh261_tiporegimeorigem = $this->rh261_tiporegimeorigem ";
       $virgula = ",";
     }
     if(trim($this->rh261_dtorigemadmissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao_dia"] !="") ){ 
       $sql  .= $virgula." rh261_dtorigemadmissao = '$this->rh261_dtorigemadmissao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao_dia"])){ 
         $sql  .= $virgula." rh261_dtorigemadmissao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh261_codcategoriaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh261_codcategoriaorigem"])){ 
        if(trim($this->rh261_codcategoriaorigem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh261_codcategoriaorigem"])){ 
           $this->rh261_codcategoriaorigem = "0" ; 
        } 
       $sql  .= $virgula." rh261_codcategoriaorigem = $this->rh261_codcategoriaorigem ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh261_sequencial!=null){
       $sql .= " rh261_sequencial = $this->rh261_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh261_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014120,'$this->rh261_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_sequencial"]) || $this->rh261_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1014120,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_sequencial'))."','$this->rh261_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_regist"]) || $this->rh261_regist != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013999,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_regist'))."','$this->rh261_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_credencial"]) || $this->rh261_credencial != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013968,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_credencial'))."','$this->rh261_credencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_onus"]) || $this->rh261_onus != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013969,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_onus'))."','$this->rh261_onus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_ressarcimento"]) || $this->rh261_ressarcimento != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013970,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_ressarcimento'))."','$this->rh261_ressarcimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_datamovimentacao"]) || $this->rh261_datamovimentacao != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013971,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_datamovimentacao'))."','$this->rh261_datamovimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_devolucao"]) || $this->rh261_devolucao != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013972,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_devolucao'))."','$this->rh261_devolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_numcgm"]) || $this->rh261_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013973,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_numcgm'))."','$this->rh261_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_matorigemcedente"]) || $this->rh261_matorigemcedente != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013974,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_matorigemcedente'))."','$this->rh261_matorigemcedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_servidorcedido"]) || $this->rh261_servidorcedido != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1013975,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_servidorcedido'))."','$this->rh261_servidorcedido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_indicadoconselho"]) || $this->rh261_indicadoconselho != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1014119,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_indicadoconselho'))."','$this->rh261_indicadoconselho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeprev"]) || $this->rh261_tiporegimeprev != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1014128,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_tiporegimeprev'))."','$this->rh261_tiporegimeprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_tiporegimeorigem"]) || $this->rh261_tiporegimeorigem != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1014127,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_tiporegimeorigem'))."','$this->rh261_tiporegimeorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_dtorigemadmissao"]) || $this->rh261_dtorigemadmissao != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1014126,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_dtorigemadmissao'))."','$this->rh261_dtorigemadmissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh261_codcategoriaorigem"]) || $this->rh261_codcategoriaorigem != "")
             $resac = db_query("insert into db_acount values($acount,1010896,1014125,'".AddSlashes(pg_result($resaco,$conresaco,'rh261_codcategoriaorigem'))."','$this->rh261_codcategoriaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cedencia não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh261_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cedencia não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh261_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh261_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh261_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh261_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014120,'$rh261_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010896,1014120,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013999,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013968,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_credencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013969,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_onus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013970,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_ressarcimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013971,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_datamovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013972,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013973,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013974,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_matorigemcedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1013975,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_servidorcedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1014119,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_indicadoconselho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1014128,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_tiporegimeprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1014127,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_tiporegimeorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1014126,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_dtorigemadmissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010896,1014125,'','".AddSlashes(pg_result($resaco,$iresaco,'rh261_codcategoriaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhcedencia
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh261_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh261_sequencial = $rh261_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cedencia não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh261_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cedencia não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh261_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh261_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhcedencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh261_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhcedencia ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = rhcedencia.rh261_numcgm";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhcedencia.rh261_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh261_sequencial)) {
         $sql2 .= " where rhcedencia.rh261_sequencial = $rh261_sequencial "; 
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

    public function sql_query_cedencia($rh261_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

      $sql  = "select {$campos}";
      $sql .= "  from rhcedencia ";
      $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhcedencia.rh261_regist";
      $sql2 = "";
      if (empty($dbwhere)) {
        if (!empty($rh261_sequencial)) {
          $sql2 .= " where rhcedencia.rh261_sequencial = $rh261_sequencial "; 
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

    public function sql_query_file($rh261_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhcedencia ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh261_sequencial)){
         $sql2 .= " where rhcedencia.rh261_sequencial = $rh261_sequencial "; 
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
