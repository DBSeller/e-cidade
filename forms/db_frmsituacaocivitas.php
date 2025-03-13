<?php
/**
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
?>
<form name="form1" method="post" action="">
    <fieldset style="width: 900px">
        <legend>Situação de Envios Recadastramento</legend>
        <table class="form-container">
            <tr>
                <td>
                    <label for="dataInicio">Data Envio:</label>
                </td>
                <td>
                    <?php
                    $anoAtual = date("Y",db_getsession("DB_datausu"));
                    $diaAtual = date("d",db_getsession("DB_datausu"));
                    $mesAtual = date("m",db_getsession("DB_datausu"));

                    db_inputdata('dataInicio',$diaAtual,$mesAtual,$anoAtual,true,'text',1,"");
                    echo "à";
                    db_inputdata('dataFim',$diaAtual,$mesAtual,$anoAtual,true,'text',1,"");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="button" id="consultar" value="Consultar" />
    <div id="containerEventos">
        <fieldset>
            <legend>Envios</legend>
            <div id="gridEnvios" />
        </fieldset>
    </div>
</form>
