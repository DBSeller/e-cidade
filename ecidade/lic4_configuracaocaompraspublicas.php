<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-default">
  <div class="container">  
    
    <fieldset >
      <legend>Configuração Integração Compras Públicas</legend>  
    <form name="form1" method="post">
      <table>
        <tr>
          <td>
            <b><label for="url">URL:</label></b>
          </td>
          <td>
            <input type="text" id="url" name="url" placeholder="Link de acesso ao Compras Públicas" size="40"/> 
          </td>
        </tr>        
        <tr>
          <td>
            <b><label for="token">Token:</label></b>
          </td>
          <td>
            <input type="text" id="token" name="token" placeholder="Token de identificação do comprador" size="40"/>
          <td>
        </tr>            
      </table>
      <button type="button" id="btnSalvar" name="btnSalvar" class="btn btn-light" style="margin-left: 4px">
            <i class="far fa-save"></i> 
            Salvar           
            </button>
    </form>
    </fieldset>
  </div> 

<?php db_menu() ?>

<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
  
  const sRPC      = 'lic4_integracaocompraspublicas.RPC.php'
  const formData = new FormData()
        formData.append('acao', 'buscaConfiguracao')
        HttpClient.post(sRPC, 
                        {body: formData,
                         reportMessage: 'Consultando Configuração ...'
                        }
                       )
        .then(function(response) {

          $('url').value   = response.dados.url==null?'':response.dados.url
          $('token').value = response.dados.token ==null?'':response.dados.token
        })
        

  const btnSalvar = $('btnSalvar')
  btnSalvar.on('click', () => {

    let url   = $F('url')
    let token = $F('token')
    if(url == '') {

      alert("Campo URL deve ser preenchido")
      return false;
    }

    if(token == '') {
      
      alert("Campo Token deve ser preenchido")
      return false;
    }

    const formData = new FormData()
          formData.append('acao', 'salvarConfiguracao')
          formData.append('url', url)
          formData.append('token', token)
    
    HttpClient.post(sRPC, 
                   {body: formData,
                    reportMessage: 'Salvando Configuração ...'
                   }
                   )
    .then(function(response) {
        
          if (response.erro) {
            
            alert(response.mensagem)
            return false
          }
      
        alert(response.mensagem)         
      })
  })
</script>
</body>
</html>