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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script src="scripts/classes/http/http.js"></script>
    <script>
        const arquivos = {
            Servidores: {cod: 1, label: 'Servidores', mensal: true},
            Matricula: {cod: 2, label: 'Matricula', mensal: true},
            Cargos: {cod: 3, label: 'Cargos', mensal: true},
            HistoricoFuncional: {cod: 4, label: 'Historico Funcional', mensal: true},
            CodigoVantagensDescontos: {cod: 5, label: 'Codigo Vantagens Descontos', mensal: true},
            FolhaPagamento: {cod: 6, label: 'Folha Pagamento', mensal: true},
            CodigoAgrupamentoFolhaPagamento: {cod: 7, label: 'Codigo Agrupamento Folha de Pagamento', mensal: true}
        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #files {
            display: flex;
        }
        .row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        #files.row {
            align-items: flex-start;
        }
        #files .row label {
            display: flex;
            align-items: center;
            text-align: initial;
        }
        #files .row label.disabled {
            color: #999;
        }
        #files h4 {
            margin-left: 5px;
            text-align: left;
        }
        .col {
            display: flex;
            flex-direction: column;
            padding: 0 10px;
        }

        .windowAux12 > div:nth-child(3) {
            overflow-y: auto;
        }
        #mes {
            height: 22px;
            padding-top: 0;
        }
        input[name="ano"] {
            width: 60px;
            height: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="" id="formGerarSagres">
        <input name="codigoTCE" value="0" type="hidden">
        <fieldset style="width: 725px;">
            <br/>
            <legend>Gerar SAGRES</legend>
            <div class="row">
                <strong style="padding-right: 12px">Data :&nbsp;&nbsp;</strong>
                <span id="ct_mensal">
                <?php $result1=array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
                    db_select("mes", $result1, true, 2);
                    $mes =  STR_PAD(DBPessoal::getMesFolha(), 2, "0", STR_PAD_LEFT);
                    echo "<script> document.getElementById('mes').value = "."'{$mes}'"." </script>";
                ?>
                </span>
                <span id="ct_anual">
                    <input name="ano" type="number" value="<?php echo db_getsession("DB_anousu")?>">
                </span>
            </div>
            
            <div class="row" style="margin-top: 2px">
                <b>Formatos:</b>
                <input name="txt" type="checkbox" checked>
                <label for="txt">TXT</label>
                <!-- <input name="csv" type="checkbox">
                <label for="csv">CSV</label> -->
            </div>
            <div class="row" style="margin-top: 2px">
                <b>Admitidos na competência atual:</b>
                <input name="admiss" type="checkbox" checked disabled>
            </div>

            <div class="container"> 
                <input type="button" value="Selecionar todos" id="marcarTodos">
            </div>

            <div id="files" class="row">
                <div id="col-0" class="col">
                    <h4>Arquivos</h4>
                </div>

                <div id="col-1" class="col">
                    <h4>&nbsp;</h4>
                </div>

                <div id="col-2" class="col">
                    <h4>&nbsp;</h4>
                </div>
            </div>
        </fieldset>
        <div class="row">
            <div class="container">
                <button type="button" id="gerarArquivo">
                    <i class="fa fa-file"></i> Gerar Arquivos
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">

    let countRow = 0;
    for (const file in arquivos) {
        let i = countRow++ % 3;

        const files = document.querySelector(`#col-${i}`);
        let row = document.createElement('div');
        let input = document.createElement('input');
        let label = document.createElement('label');

        row.classList.add('row');

        input.setAttribute('type', 'checkbox');
        input.setAttribute('name', 'relatorios[]');
        input.setAttribute('value', file);
        input.setAttribute('id', file);
        input.classList.add('cboRelatorio');

        label.setAttribute('for', file);
        label.innerHTML = arquivos[file].label;

        row.append(input);
        row.append(label);
        files.appendChild(row);
    }

    const formulario = document.getElementById('formGerarSagres');
    const btnMarcarTodos = document.getElementById('marcarTodos');
    const btnGerar = document.getElementById('gerarArquivo');
    const cboRelatorios = document.getElementsByName('relatorios[]');

    btnMarcarTodos.addEventListener('click', () => {
        const nomeBotao = btnMarcarTodos.getAttribute('value');
        const estado = nomeBotao === 'Selecionar todos';
        Array.from(cboRelatorios).forEach((cbo) => {
            cbo.checked = cbo.disabled ? false : estado;
            const relatorio = cbo.getAttribute('id');
        });
        btnMarcarTodos.value = estado ? 'Desmarcar todos' : 'Selecionar todos';
    });

    formulario.addEventListener('submit', (e) => { e.preventDefault(); });

    btnGerar.addEventListener('click', () => {
        var count = 0;
        Array.from(cboRelatorios).forEach((cbo) => {
            if (cbo.checked) {
                count++;
            }
        });
        if (count == 0) {
            alert("Selecione um Relatório.");
            return;
        }        
        const parametros = new FormData(formulario);
        parametros.append('exec', 'gerarSagres');
        HttpClient.post(
            'pes4_sagres.RPC.php',
            { body: parametros }
        )
        .then((response) => {
            console.log(response)
            if (response.erro) {
                alert(response.message);
                return;
            }

            var download = new DBDownload();
            download.addFile(response.zip, "Sagres.zip");
            response.arquivos.map((arquivo) => {
                download.addFile(arquivo.filePath, arquivo.fileName);
            });
            download.show();
        });
    });
    
</script>
</body>
</html>
