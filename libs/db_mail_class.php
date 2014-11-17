<?
class mail {
  
  public $sClass     = 0;
  public $sUserMail  = '';
  public $sPassMail  = '';
  public $sHostMail  = '';
  public $sPortMail  = 0;
  public $sEmailFrom = '';
  public $sEmailTo   = ''; 
  public $sMsg       = '';
  
  
  function __construct() {
    /**
     * Declaramos as variáveis da classe de acordo com o que está configurado no arquivo config.mail.php
     */
     include_once('libs/config.mail.php');
    
     $this->sUserMail        = $sUser;
     $this->sPassMail        = $sPass;
     $this->sHostMail        = $sHost;
     $this->sPortMail        = (int)$sPort;
    
     $oConfigDBpref    = db_utils::getDao("configdbpref");
     $rsConfigDBpref   = $oConfigDBpref->sql_record($oConfigDBpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
     $this->sEmailFrom = db_utils::fieldsMemory($rsConfigDBpref,0)->w13_emailadmin; 
  }
  
  function setUserMail($sUserMail) {
    $this->sUserMail = $sUserMail;
  }
  
  function setPassMail($sPassMail) {
    $this->sPassMail = $sPassMail;
  }  
  
  function setHostMail($sHostMail) {
    $this->sHostMail = $sHostMail;
  }  
  
  function setPortMail($sPortMail) {
    $this->sPortMail = (int)$sPortMail;
  }  
  
  function setsEmailFrom($sEmailFrom) {
    $this->sEmailFrom = $sEmailFrom;    
  }

  function setsEmailTo($sEmailTo) {
    $this->sEmailTo = $sEmailTo;    
  }
  
  function setsClass($sClass) {
    $this->sClass = $sClass;
  }

  function setsMsg($sMsg) {
    $this->sMsg = $sMsg;
  }  
  
  function setsSubject($sSubject) {
    $this->sSubject = $sSubject;
  }
  
  function Send() {
    
    switch($this->sClass) {
      
      case 1:
        include("mail/db_smtp1_class.php");
        $oClassMail = new Smtp();
        
        try {
          
          $oClassMail->Send($this->sEmailTo,$this->sEmailFrom,$this->sSubject,$this->sMsg);
          return "Uma mensagem foi encaminha para o e-mail informado";
          
        } catch (Exception $eException){
          return "00 - Erro ao enviar E-mail. ".$eException->getMessage();
        }
      break;
        
      default:
        include("mail/db_smtp2_class.php");
        
        try {
          
          $oClassMail = new Smtp();
          $oClassMail->Delivery('relay');
          $oClassMail->Relay($this->sHostMail, $this->sUserMail, $this->sPassMail, $this->sPortMail, 'login', false);
          $oClassMail->From($this->sEmailFrom);
          $oClassMail->AddTo($this->sEmailTo);
          $oClassMail->Html($this->sMsg);
          $oClassMail->Send($this->sSubject);
          return "Uma mensagem foi encaminha para o e-mail informado"; 
                    
        } catch (Exception $eException){
          return "01 - Erro ao enviar E-mail. ".$eException->getMessage();
        }
        
      break;
            
    }
  }
  
  function Close($connection) {
    
    try {
      fclose($connection);       
    } catch (Exception $eException){
      return "02 - Erro ao fechar conexão";    
    }
    
  }
  
}
?>