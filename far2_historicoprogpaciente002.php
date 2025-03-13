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

use ECidade\Pdf\Pdf;

include(modification("libs/db_stdlib.php"));
include(modification("libs/db_conecta.php"));

function novoNome(Pdf $pdf, $nome)
{
    $cor = '0';
    $pdf->setfont('arial', 'B', 11);

    $pdf->ln(5);
    $pdf->cell(190, 15, $nome, 0, 1, 'L', $cor);
}

function novoCabecalho(Pdf $pdf)
{
    $cor = '0';
    $pdf->setfont('arial', 'B', 9);

    $pdf->cell(95, 5, 'Medicamento', 1, 0, 'C', $cor);
    $pdf->cell(15, 5, 'Req.', 1, 0, 'C', $cor);
    $pdf->cell(20, 5, 'Lote', 1, 0, 'C', $cor);
    $pdf->cell(20, 5, 'Data', 1, 0, 'C', $cor);
    $pdf->cell(15, 5, 'Quant.', 1, 0, 'C', $cor);
    $pdf->cell(25, 5, 'Posologia', 1, 1, 'C', $cor);

}

function novaLinha(Pdf $pdf, $medicamento, $req, $lote, $data, $quantidade, $posologia)
{
    $cor = '0';
    $pdf->setfont('arial', '', 9);

    $pdf->cell(95, 5, $medicamento, 1, 0, 'L', $cor);
    $pdf->cell(15, 5, $req, 1, 0, 'C', $cor);
    // quebra linha(booleano),posicionamento do texto(L,C,R),cor)
    $pdf->cell(20, 5, $lote, 1, 0, 'C', $cor);
    $pdf->cell(20, 5, $data, 1, 0, 'C', $cor);
    $pdf->cell(15, 5, $quantidade, 1, 0, 'R', $cor);
    $pdf->cell(25, 5, $posologia, 1, 1, 'C', $cor);
}

function verificaQuebra(Pdf $pdf, $linhasNaPagina)
{
    if ($linhasNaPagina >= 47) {
        $pdf->AddPage('P');
        return 0;
    }
    return $linhasNaPagina;
}

function imprimirTotal(Pdf $pdf, $totalRequisicoes, $totalPacientes)
{
    $pdf->setFont('arial', 'B', 8);
    $pdf->cell(185, 3, "", 0, 1, 'R');
    $pdf->cell(185, 5, "Total de requisições atendidas: {$totalRequisicoes}", 0, 1, 'R');
    $pdf->cell(185, 5, "Total de pacientes atendidos: {$totalPacientes}", 0, 1, 'R');
}

/**
 * @return stdClass[]
 * @throws BusinessException
 */
function buscarDados()
{
    $programas = $_GET['programas'];

    $where = [];
    $where[] = "fa04_d_data BETWEEN '{$_GET['periodo_inicio']}' AND '{$_GET['periodo_fim']}'";

    /**
     * Traz somente os paciente com o medicamencamento continuado que estão na ação programática, dentro das datas
     * de inicio e fim cadastrados no controle do medicamento, considerando a data do atendimento
     */
    $where[] = " fa06_i_matersaude IN (
    SELECT fa10_i_medicamento
    FROM (
      SELECT DISTINCT fa10_i_medicamento
      FROM far_controlemed
      INNER JOIN far_controle ON fa11_i_codigo = fa10_i_controle
      WHERE fa10_i_programa in ({$programas})
        AND fa10_i_medicamento = fa06_i_matersaude
        AND fa10_d_dataini <= fa04_d_data
        AND (fa10_d_datafim is null OR fa10_d_datafim >= fa04_d_data)
        AND fa11_i_cgsund = fa04_i_cgsund
    ) AS xx
  )";

    $where = implode(' AND ', $where);

    $sql = "SELECT
            trim(fa04_i_cgsund || '-' || z01_v_nome || ' CPF - ' ||coalesce(z01_v_cgccpf,' ')) AS nome,
            trim(fa06_i_matersaude||' - '|| m60_descr) AS medicamento, fa07_i_matrequi, fa04_d_data, m77_lote,
            substring(fa06_t_posologia,1,10) AS Posologia, fa06_f_quant
          FROM far_retiradaitens
          INNER JOIN far_retirada ON fa06_i_retirada = fa04_i_codigo
          INNER JOIN cgs_und ON z01_i_cgsund = fa04_i_cgsund
          INNER JOIN far_matersaude ON fa06_i_matersaude = fa01_i_codigo
          INNER JOIN matmater ON matmater.m60_codmater = far_matersaude.fa01_i_codmater
          INNER JOIN matunid ON matunid.m61_codmatunid = matmater.m60_codmatunid
          LEFT JOIN far_retiradarequi ON fa04_i_codigo = fa07_i_retirada
          LEFT JOIN far_retiradaitemlote ON fa06_i_codigo = fa09_i_retiradaitens
          LEFT JOIN matestoqueitemlote ON fa09_i_matestoqueitem = m77_matestoqueitem
          WHERE {$where}
          ORDER BY m60_descr, z01_v_nome, fa04_d_data DESC;";

    $result = db_query($sql);

    if (pg_num_rows($result) == 0) {
        throw new \BusinessException('Nenhum registro encontrado!');
    }

    return \db_utils::getCollectionByRecord($result);
}

function escreverPdf(Pdf $pdf, $dados)
{
    $numPacientes = 0;
    $linhasNaPagina = 0;
    $nomeAtual = '';
    $numRequisicoes = 0;
    $requisicaoAtual = '';

    foreach ($dados as $dado) {
        if ($nomeAtual != $dado->nome) {
            $nomeAtual = $dado->nome;
            $numPacientes++;
            $linhasNaPagina += 6;
            $linhasNaPagina = verificaQuebra($pdf, $linhasNaPagina);
            novoNome($pdf, $numPacientes . '. ' . $dado->nome);

            if ($linhasNaPagina == 0) {
                $linhasNaPagina = 5;
            } else {
                $linhasNaPagina -= 1;
            }

            novoCabecalho($pdf);
        }

        if ($linhasNaPagina == 0) {
            $pdf->ln(5);
            novoCabecalho($pdf);
            $linhasNaPagina = 1;
        }

        if ($requisicaoAtual != $dado->fa07_i_matrequi) {
            $requisicaoAtual = $dado->fa07_i_matrequi;
            $numRequisicoes++;
        }

        novaLinha(
            $pdf,
            $dado->medicamento,
            $dado->fa07_i_matrequi,
            $dado->m77_lote,
            db_formatar($dado->fa04_d_data, 'd'),
            $dado->fa06_f_quant,
            $dado->posologia
        );

        $linhasNaPagina++;
        $linhasNaPagina = verificaQuebra($pdf, $linhasNaPagina);
    }

    imprimirTotal($pdf, $numRequisicoes, $numPacientes);
}

try {
    $nomes_programas = str_replace(',', ', ', $nomes_programas);
    $pdf = new Pdf();

    $pdf->addTitulo("Histórico de retiradas por Programa / Paciente");
    $pdf->addTitulo('');
    $pdf->addTitulo(utf8_decode($nomes_programas));
    $pdf->addTitulo('');
    $pdf->addTitulo('Ordem:');
    $pdf->addTitulo('  1 - Nome do usuario');
    $pdf->addTitulo('  2 - Medicamento');
    $pdf->addTitulo('  3 - Data');

    $pdf->init();
    $pdf->aliasNbPages();
    $pdf->setfillcolor(223);
    $pdf->setfont('arial', '', 11);

    escreverPdf($pdf, buscarDados());

    $pdf->output();
} catch (\Exception $e) {
    echo "<table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'>
                <b>{$e->getMessage()}<br>
                  <input type='button' value='Fechar' onclick='window.close()'>
                </b>
              </font>
            </td>
          </tr>
        </table>";
    exit;
}
