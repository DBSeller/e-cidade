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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh196_arquivo");

$listaSobrescreverArquivo = array(0 => 'Não', 1 => 'Sim');

if (isset($processar)) {
    $matriculas = json_decode($matriculas, true);

    try {
        $oDataPeriodoInicio = new \DBDate($periodoInicio);
        $oDataPeriodoFim = new \DBDate($periodoFim);

        $periodoInicio = $oDataPeriodoInicio->getDate();
        $periodoFim = $oDataPeriodoFim->getDate();

        $where = array(
          "rh228_instituicao = " . db_getsession("DB_instit"),
          "rh229_data between '{$periodoInicio}' AND '{$periodoFim}'"
        );

        if (!empty($matriculas)) {
            $matriculas = implode(',', $matriculas);
            $where[] = "rh229_matricula in({$matriculas})";
        }

        $sSqlDadosRelatorio = "SELECT rh01_regist as matricula, 
                                  z01_nome as nome, 
                                  rh229_data as data, 
                                  rh229_hora as hora,
                                  rh16_pis as pis
                            FROM recursoshumanos.pontoeletronicoarquivoimportacaoregistro
                                 INNER JOIN  recursoshumanos.pontoeletronicoarquivoimportacao b 
                                         ON  b.rh228_sequencial = recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_pontoeletronicoarquivoimportacao 
                                 INNER JOIN rhpessoal ON rh229_matricula = rh01_regist 
                                 INNER JOIN cgm ON z01_numcgm = rh01_numcgm 
                                 INNER JOIN rhpesdoc ON rh01_regist = rh16_regist
                           WHERE " . implode(" AND ", $where) . "
                        GROUP BY rh01_regist, z01_nome, rh229_data, rh229_hora, rh16_pis
                        ORDER BY rh229_data, rh229_hora";

        $camposRelatorio = array(
          "matricula",
          "nome",
          "data",
          "pis",
          "case when ( select 
                    distinct to_char((x.data || ' ' || x.hora)::timestamp + '1 minute'::interval, 'hh24:mi') 
                  from 
                    recursoshumanos.pontoeletronicoarquivoimportacaoregistro aa 
                    inner join recursoshumanos.pontoeletronicoarquivoimportacao bb on aa.rh229_pontoeletronicoarquivoimportacao = bb.rh228_sequencial 
                  where 
                        aa.rh229_matricula = x.matricula 
                    and aa.rh229_data      = x.data 
                    and (x.data || ' ' || x.hora)::timestamp + '1 minute'::interval = (aa.rh229_data || ' ' || aa.rh229_hora)::timestamp
                ) is null 
            then substr(x.hora::varchar, 1, 5) 
            else (select 
                    distinct to_char((x.data || ' ' || x.hora)::timestamp + '1 minute'::interval, 'hh24:mi') 
                  from 
                    recursoshumanos.pontoeletronicoarquivoimportacaoregistro aa 
                    inner join recursoshumanos.pontoeletronicoarquivoimportacao bb on aa.rh229_pontoeletronicoarquivoimportacao = bb.rh228_sequencial 
                  where 
                        aa.rh229_matricula = x.matricula 
                    and aa.rh229_data      = x.data 
                    and (x.data || ' ' || x.hora)::timestamp + '1 minute'::interval = (aa.rh229_data || ' ' || aa.rh229_hora)::timestamp
                ) end as hora "
        );

        $sSqlDadosRelatorio = " SELECT DISTINCT " . implode(', ', $camposRelatorio) . " 
                                  FROM ( {$sSqlDadosRelatorio} ) AS x 
                              ORDER BY data, hora";

        $rsDadosRelatorio = db_query($sSqlDadosRelatorio);
        $qtdeRegistros = pg_num_rows($rsDadosRelatorio);
        if ($qtdeRegistros == 0) {
            throw new BusinessException("Não há servidores para esta seleção.");
        }

        $dadosPrimeiroRegistro = db_utils::fieldsMemory($rsDadosRelatorio, 0);
        $dadosUltimoRegistro = db_utils::fieldsMemory($rsDadosRelatorio, $qtdeRegistros - 1);

        $dataInicio = \DateTime::createFromFormat('Y-m-d', $dadosPrimeiroRegistro->data);
        $dataFim = \DateTime::createFromFormat('Y-m-d', $dadosUltimoRegistro->data);

        $dadosRelatorio = array();
        $totalLinhas = pg_num_rows($rsDadosRelatorio);

        $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();

        $header = "0000000001135893999000120000000000000";
        $header .= str_pad($oInstituicao->getDescricao(), 150, " ", STR_PAD_RIGHT);
        $header .= str_pad(9, 17, "9",
          STR_PAD_LEFT);   //FIXO 99999999999999999 para validar na importação apenas arquivos compilados
        $header .= $dataInicio->format('dmY');
        $header .= $dataFim->format('dmY');
        $header .= date('dmY');
        $header .= date('Hi');
        $header .= PHP_EOL;

        $footer = "999999999";
        $footer .= "000000000";
        $footer .= str_pad($qtdeRegistros, 9, "0", STR_PAD_LEFT);
        $footer .= "000000000";
        $footer .= "000000000";
        $footer .= "9";

        $nomeArquivo = "tmp/marcacoes_compilacao_" . time() . ".txt";

        file_put_contents($nomeArquivo, $header);

        for ($i = 0; $i < $totalLinhas; $i++) {
            $dados = db_utils::fieldsMemory($rsDadosRelatorio, $i);
            $data = \DateTime::createFromFormat("Y-m-d H:i", "{$dados->data} {$dados->hora}");

            // Sequencial
            $linha = '';
            $linha .= str_pad(($i + 1), 9, "0", STR_PAD_LEFT);

            // FIXO 3
            $linha .= "3";

            // Data
            $linha .= $data->format('dmY');

            // Hora
            $linha .= $data->format('Hi');

            // FIXO 0
            $linha .= "0";

            // PIS
            $linha .= $dados->pis;

            $linha .= PHP_EOL;
            file_put_contents($nomeArquivo, $linha, FILE_APPEND);
        }

        file_put_contents($nomeArquivo, $footer, FILE_APPEND);
        unset($processar);
        download($nomeArquivo);
    } catch (\Exception $e) {
        $msgErro = $e->getMessage();
    }
}

function download($arquivo)
{
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream;");
    header("Content-Length:" . filesize($arquivo));
    header("Content-disposition: attachment; filename=" . str_replace('tmp/', '', $arquivo));
    header("Pragma: no-cache");
    header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    header("Expires: 0");
    readfile($arquivo);
    flush();
}
?>

<html>
    <head>
        <meta http-equiv="Expires" CONTENT="0">
        <?php
        db_app::load(array(
            "scripts.js",
            "datagrid.widget.js",
            "strings.js",
            "dates.js",
            "prototype.js",
            "AjaxRequest.js",
            "widgets/DBLookUp.widget.js",
            "widgets/Input/DBInput.widget.js",
            "widgets/Input/DBInputDate.widget.js",
            "widgets/DBLancador.widget.js",
            "estilos.css",
            "grid.style.css",
            "classes/recursoshumanos/Efetividade/PeriodoEfetividade.js"
        ));
        ?>
        <style type="text/css"></style>
    </head>
    <body>
        <div class="container" style="width: 500px !important;">
            <form method="POST" id="importarArquivo" class="form-container">
                <input type="hidden" name="matriculas" id="matriculas" value="" />
                <fieldset>
                    <legend>Compilar Arquivos do Ponto Eletrônico</legend>
                    <table class="form-container">
                        <tr>
                            <td>
                                <label for="periodoInicio">Período:</label>
                            </td>
                            <td id="linhaPeriodoEfetividade" class="field-size-max"></td>
                        </tr>

                        <tr id="linhaMatricula">
                            <td id="matricula" colspan="2"></td>
                        </tr>
                    </table>

                </fieldset>
                <input type="submit" id="processar" name="processar" value="Processar"/>
            </form>
        </div>
        <script type="text/javascript">
            var oPeriodoEfetividade = new PeriodoEfetividade();
            oPeriodoEfetividade.__initDataSugerida(<?=DBPessoal::getCompetenciaFolha()->getAno()?>,<?=DBPessoal::getCompetenciaFolha()->getMes() - 1?>);
            oPeriodoEfetividade.show($('linhaPeriodoEfetividade'));

            $('periodoInicio').name = 'periodoInicio';
            $('periodoFim').name = 'periodoFim';

            var oLancadorMatricula = new DBLancador('oLancadorMatricula');
            oLancadorMatricula.setLabelAncora('Matrícula:');
            oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
            oLancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
            oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
            oLancadorMatricula.setTextoFieldset('Matrículas');
            oLancadorMatricula.setGridHeight(150);
            oLancadorMatricula.adicionarItensPrimeiraPosicao(true);

            oLancadorMatricula.show($('matricula'));

            $('importarArquivo').addEventListener('submit', function(e) {
                var aMatriculas = [];

                oLancadorMatricula.getRegistros().each(function(matricula) {
                    aMatriculas.push(parseInt(matricula.sCodigo));
                });

                if (!aMatriculas) {
                    e.preventDefault();
                }
                $('matriculas').value = JSON.stringify(aMatriculas);
            });

        </script>
        <?php
        if (!empty($msgErro)) {
            db_msgbox($msgErro);
        }
        ?>
    </body>
</html>
