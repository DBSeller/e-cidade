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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Serviços de Informática Ltda</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
</head>
<body>
<div class="container" style="width: 25%;">
    <form id="configuracoes">
        <fieldset>
            <legend>Configurações</legend>
            <table>
                <tbody>
                <tr>
                    <td>
                        <label for="exibirBotaoEsocialParaOsUsuarios">
                            <strong>Exibir botão eSocial para os usuários:</strong>
                        </label>
                    </td>
                    <td>
                        <select name="exibirBotaoEsocialParaOsUsuarios"
                                id="exibirBotaoEsocialParaOsUsuarios">
                            <option value="true">Sim</option>
                            <option value="false">Não</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <input type="submit" value="Salvar">
    </form>
</div>
<script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
<script rel="script" type="text/javascript" src="scripts/strings.js"></script>
<script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
<script rel="script" type="text/javascript">
    (() => {
        var configuracao = {
            sequencial: null,
            exibirBotaoESocialParaOsUsuarios: true,
        };

        const configuracoes = document.querySelector('#configuracoes');
        const exibirBotaoESocialParaOsUsuarios = document.querySelector('#exibirBotaoEsocialParaOsUsuarios');

        const requisicao = (mensagem, corpo) => {
            js_divCarregando(mensagem, 'mensagem');

            return fetch('eso04_esocial_configuracao.RPC.php', {
                method: 'POST',
                body: corpo,
                credentials: 'include',
            }).then(resposta => {
                js_removeObj('mensagem');

                return resposta.json();
            }).then(resposta => {
                if (resposta.erro) {
                    alert(resposta.mensagem);
                    throw resposta.mensagem;
                }

                return resposta;
            });
        };

        const buscar = () => {
            const corpo = new FormData();
            corpo.append('acao', 'buscar');

            requisicao('Carregando Configurações...', corpo).then(resposta => {
                configuracao = resposta.configuracao;
                exibirBotaoESocialParaOsUsuarios.value = resposta.configuracao.exibirBotaoESocialParaOsUsuarios;
            });
        };

        const salvar = evento => {
            evento.preventDefault();

            const corpo = new FormData();
            corpo.append('acao', 'salvar');
            corpo.append('configuracao', JSON.stringify(configuracao));

            requisicao('Salvando Configurações...', corpo).then(resposta => {
                alert(resposta.mensagem);

                if (!resposta.erro) {
                    configuracao = resposta.configuracao;
                    exibirBotaoESocialParaOsUsuarios.value = resposta.configuracao.exibirBotaoESocialParaOsUsuarios;
                }
            });
        };

        configuracoes.addEventListener('submit', salvar);

        exibirBotaoESocialParaOsUsuarios.addEventListener('change', () => {
            configuracao.exibirBotaoESocialParaOsUsuarios = exibirBotaoESocialParaOsUsuarios.value;
        });

        buscar();
    })();
</script>
</body>
</html>
