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


  if (!empty($_POST)) {

    $conf = $_POST['conf'];

    $sFileContent  = "ConPrefeitura_host={$conf['Origem']['host']} \n";
    $sFileContent .= "ConPrefeitura_port={$conf['Origem']['port']} \n";
    $sFileContent .= "ConPrefeitura_dbname={$conf['Origem']['database']} \n";
    $sFileContent .= "ConPrefeitura_user={$conf['Origem']['login']} \n";
    $sFileContent .= "ConPrefeitura_password={$conf['Origem']['password']}  \n\n";

    $sFileContent .= "ConDestino_host={$conf['Destino']['host']} \n";
    $sFileContent .= "ConDestino_port={$conf['Destino']['port']} \n";
    $sFileContent .= "ConDestino_dbname={$conf['Destino']['database']} \n";
    $sFileContent .= "ConDestino_user={$conf['Destino']['login']} \n";
    $sFileContent .= "ConDestino_password={$conf['Destino']['password']}  \n\n";

    $rsIniFile = fopen('libs/db_config.ini', 'w');
    fwrite($rsIniFile, $sFileContent);
    fclose($rsIniFile);

    /**
     * libs/config.ini
     */
    $sConfiguracoes       = "exercicioBase={$conf['exercicioBase']}";
    $lSalvarConfiguracoes = file_put_contents('libs/config.ini', $sConfiguracoes);

    $saved = true;

  } else {

    $aIniFile = parse_ini_file('libs/db_config.ini');

    $conf = array(
      'Origem'     => array(
        'host'     => $aIniFile['ConPrefeitura_host'],
        'port'     => $aIniFile['ConPrefeitura_port'],
        'database' => $aIniFile['ConPrefeitura_dbname'],
        'login'    => $aIniFile['ConPrefeitura_user'],
        'password' => $aIniFile['ConPrefeitura_password']
      ),
      'Destino' => array(
        'host'     => $aIniFile['ConDestino_host'],
        'port'     => $aIniFile['ConDestino_port'],
        'database' => $aIniFile['ConDestino_dbname'],
        'login'    => $aIniFile['ConDestino_user'],
        'password' => $aIniFile['ConDestino_password']
      )
    );

    /**
     * libs/config.ini
     */
    $aConfiguracoes = parse_ini_file('libs/config.ini');
    $conf['exercicioBase'] = $aConfiguracoes['exercicioBase'];
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Configura&ccedil;&atilde;o arquivo de conex&atilde;o</title>
</head>
<body>

  <style type="text/css">

    body {
      background-color: #EFEFEF;
    }

    * {
      font-family: Arial;
    }

    form {
      margin: auto;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      position: absolute;
      width: 400px;
      font-size: 14px;
      height: 500px;
    }

    form div.input {
      line-height: 30px;
      height: 30px;
    }

    form div.input label {
      font-weight: bold;
      margin: 0px 3px;
      float: left;
      clear: both;
      width: 20%;
      text-align: right;
    }

    form div.input label::after{
      content: ":";
    }

    form div.input input, 
    form div.input select {
      height: 20px;
      line-height: 20px;
      border: 1px solid #CCC;
      border-radius: 3px;
      -moz-border-radius: 3px;
      outline: none;
      padding: 2px 3px;
      margin: 2px 0;
      width: 70%;
    }

    form div.input input[type="checkbox"] {
      height: 30px;
      border: none;
      padding-top: 0;
    } 

    form div.input select {
      -moz-box-sizing: content-box;
      -webkit-box-sizing: ni
content-box;
      box-sizing: content-box;
    }

    form div.input input:focus {
      box-shadow: 0px 0px 3px 0px #DDD;  
    }

    form div.buttons {
      height: 40px;
      text-align: center;
    }

    div.buttons {
      margin: 10px 0;
      text-align: center;
    }

    div.buttons button,
    div.buttons a {
      height: 25px;
      margin: 2px 0;
      padding: 3px;
    }

  </style>

  <form method="post">
    
    <fieldset>
      <legend>Configurar base origem</legend>

      <div class="input">
        <label>Host</label>
        <input type="text" name="conf[Origem][host]" value="<?php echo $conf['Origem']['host']; ?>"/>
      </div>

      <div class="input">
        <label>Port</label>
        <input type="text" name="conf[Origem][port]" value="<?php echo $conf['Origem']['port']; ?>"/>
      </div>

      <div class="input">
        <label>Database</label>
        <input type="text" name="conf[Origem][database]" value="<?php echo $conf['Origem']['database']; ?>"/>
      </div>

      <div class="input">
        <label>Login</label>
        <input type="text" name="conf[Origem][login]" value="<?php echo $conf['Origem']['login']; ?>"/>
      </div>

      <div class="input">
        <label>Password</label>
        <input type="text" name="conf[Origem][password]" value="<?php echo $conf['Origem']['password']; ?>"/>
      </div>

    </fieldset>

    <fieldset>
      <legend>Configurar base destino</legend>

      <div class="input">
        <label>Host</label>
        <input type="text" name="conf[Destino][host]" value="<?php echo $conf['Destino']['host']; ?>"/>
      </div>

      <div class="input">
        <label>Port</label>
        <input type="text" name="conf[Destino][port]" value="<?php echo $conf['Destino']['port']; ?>"/>
      </div>

      <div class="input">
        <label>Database</label>
        <input type="text" name="conf[Destino][database]" value="<?php echo $conf['Destino']['database']; ?>"/>
      </div>

      <div class="input">
        <label>Login</label>
        <input type="text" name="conf[Destino][login]" value="<?php echo $conf['Destino']['login']; ?>"/>
      </div>

      <div class="input">
        <label>Password</label>
        <input type="text" name="conf[Destino][password]" value="<?php echo $conf['Destino']['password']; ?>"/>
      </div>

    </fieldset>

    <fieldset>
      <legend>Outros</legend>

      <div class="input">
        <label>Ano base</label>
        <input type="text" name="conf[exercicioBase]" value="<?php echo $conf['exercicioBase']; ?>"/>
      </div>

    </fieldset>

    <div class="buttons">
      <button type="submit" value="enviar">Salvar</button>      
    </div>

  </form>

  <?php if (isset($saved)) {?> 
    <script type="text/javascript">alert('Configuração salva com sucesso.')</script>
  <?php } ?>
</body>
</html>