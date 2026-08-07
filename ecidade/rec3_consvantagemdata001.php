<?php
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css" rel="stylesheet">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
  <script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
  <div class="container">
    <div id="divtable">
      <table id="table"></table>
    </div>
  </div>
</body>

</html>
<script>
  const url = '<?= ECIDADE_REQUEST_PATH ?>';
  const routers = {
    'concessaocalculolog': url + '/v4/api/recursos-humanos/rh/concessaodireitos/concessaocalculolog',
  };

  var $table = jQuery('#table')
  const columns = [{
      align: 'center',
      title: 'Assentamento',
      field: 'h12_descr',
      sortable: true
    },
    {
      field: 'h16_dtconc',
      title: 'Data Inicio',
      sortable: true,
      align: 'center',
      formatter: FormatterData
    },
    {
      field: 'h16_dtterm',
      title: 'Data Fim',
      sortable: true,
      align: 'center',
      formatter: FormatterData
    },
    {
      field: 'rh502_condicao',
      align: 'center',
      title: 'Ação',
      sortable: true,
    }
  ];

  function FormatterData(value) {
    if (value) {
      resultado = value.split("-");
      dataoriginal = resultado[2] + '/' + resultado[1] + '/' + resultado[0]
    } else {
      dataoriginal = '------'
    }

    return [
      '<p>' + dataoriginal + '</p>'
    ].join('')
  }

  const data = {
    rh507_concessaocalculo: <?= $rh507_concessaocalculo ?>,
    acao : <?= $acao ?>
  };
  const dado = new FormData;
  for (index in data) {
    dado.append(index, data[index]);
  }
  HttpClient.post(routers.concessaocalculolog, {
      body: dado
    })
    .then((res) => {
      if (res.hasOwnProperty('data')) {
        $table.bootstrapTable({
          columns,
          height: 300,
          data: res.data,
        })
      }
    });
</script>