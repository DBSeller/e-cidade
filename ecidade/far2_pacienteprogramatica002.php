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

include(modification("libs/db_stdlib.php"));
include(modification("libs/db_conecta.php"));

function novoPrograma(ECidade\Pdf\Pdf $pdf, $nome)
{
    $cor = '0';
    $pdf->setfont('arial', 'B', 11);

    $pdf->ln(5);
    $pdf->cell(280, 15, $nome, 0, 1, 'C', $cor);
}

function novoCabecalho(ECidade\Pdf\Pdf $pdf)
{
    $cor = '0';
    $pdf->setfont('arial', 'B', 7);

    $pdf->cell(15, 5, 'CGS', 1, 0, 'C', $cor);
    $pdf->cell(65, 5, 'Nome do Usuario', 1, 0, 'C', $cor);
    $pdf->cell(22, 5, 'Cartao SUS', 1, 0, 'C', $cor);
    $pdf->cell(20, 5, 'CPF', 1, 0, 'C', $cor);
    $pdf->cell(45, 5, 'Endereco', 1, 0, 'C', $cor);
    $pdf->cell(15, 5, 'Num', 1, 0, 'C', $cor);
    $pdf->cell(25, 5, 'Complemento', 1, 0, 'C', $cor);
    $pdf->cell(38, 5, 'Bairro', 1, 0, 'C', $cor);
    $pdf->cell(35, 5, 'Municipio', 1, 1, 'C', $cor);
}

function novoCabecalhoTotal(ECidade\Pdf\Pdf $pdf)
{
    $cor = '0';
    $pdf->setfont('arial', 'B', 7);
    

    $pdf->cell(50, 5, 'Código', 1, 0, 'C', true);
    $pdf->cell(178, 5, 'Ação Programática', 1, 0, 'C', true);
    $pdf->cell(50, 5, 'Quantidade Pacientes', 1, 1, 'C', true);
}

function novaLinha($pdf, $cgs, $usuario, $sus, $cpf, $endereco, $numero, $complemento, $bairro, $municipio)
{
    $cor = '0';
    $pdf->setfont('arial', '', 7);

    $pdf->cell(15, 5, $cgs, 1, 0, 'C', $cor);
    $pdf->cellAdapt(7, 65, 5, $usuario, 1, 0, 'L', $cor);
    $pdf->cell(22, 5, $sus, 1, 0, 'C', $cor);
    $pdf->cell(20, 5, $cpf, 1, 0, 'L', $cor);
    $pdf->cellAdapt(7, 45, 5, $endereco, 1, 0, 'L', $cor);
    $pdf->cell(15, 5, $numero, 1, 0, 'C', $cor);
    $pdf->cellAdapt(7, 25, 5, $complemento, 1, 0, 'L', $cor);
    $pdf->cellAdapt(7, 38, 5, $bairro, 1, 0, 'L', $cor);
    $pdf->cellAdapt(7, 35, 5, $municipio, 1, 1, 'L', $cor);
}

function novaLinhaTotal(ECidade\Pdf\Pdf $pdf, $programa, $total, $codigo)
{
    $cor = '0';
    $pdf->setfont('arial', '', 7);

    $pdf->cell(50, 5, $codigo, 1, 0, 'C', $cor);
    $pdf->cell(178, 5, $programa, 1, 0, 'L', $cor);
    $pdf->cell(50, 5, $total, 1, 1, 'C', $cor);


}

function buscarDados()
{
    $order = 'programa, z01_v_nome';
    if ($_GET['ordem'] == 2) {
        $order = 'programa, z01_i_cgsund';
    }

    $dataSistema = date('Y-m-d', db_getsession('DB_datausu'));

    $sql = "SELECT z01_i_cgsund, s115_c_cartaosus, z01_v_nome, z01_v_cgccpf, z01_v_ender, z01_i_numero, z01_v_compl,
                z01_v_bairro, z01_v_munic, programa
          FROM (SELECT DISTINCT fa11_i_cgsund, fa12_c_descricao AS programa
                FROM far_controlemed
                INNER JOIN far_controle ON fa11_i_codigo = fa10_i_controle
                INNER JOIN far_programa ON fa10_i_programa = fa12_i_codigo
                WHERE fa10_i_programa IN ({$_GET['programas']})
                  AND fa10_d_dataini <= '{$dataSistema}'
                  AND (fa10_d_datafim is null OR fa10_d_datafim >= '{$dataSistema}')
          ) AS xx
          INNER JOIN cgs_und ON xx.fa11_i_cgsund = z01_i_cgsund
          INNER JOIN cgs ON z01_i_numcgs = z01_i_cgsund
          LEFT JOIN cgs_cartaosus ON s115_i_cgs= z01_i_numcgs AND s115_c_tipo= 'D' ORDER BY {$order};";

    $result = db_query($sql);

    if (pg_num_rows($result) == 0) {
        throw new \BusinessException('Nenhum registro encontrado!');
    }

    return \db_utils::getCollectionByRecord($result);
}

function buscarTotalPorPrograma()
{
    $dataSistema = date('Y-m-d', db_getsession('DB_datausu'));

    $sql = "SELECT COUNT(*) AS total, programa, codigo
        FROM (SELECT DISTINCT fa11_i_cgsund, fa12_c_descricao AS programa, fa12_i_codigo AS codigo
          FROM far_controlemed
          INNER JOIN far_controle ON fa11_i_codigo = fa10_i_controle
          INNER JOIN far_programa ON fa10_i_programa = fa12_i_codigo
          WHERE fa10_i_programa IN ({$_GET['programas']})
            AND fa10_d_dataini <= '{$dataSistema}'
            AND (fa10_d_datafim is null OR fa10_d_datafim >= '{$dataSistema}')) AS xx
        GROUP BY codigo, programa
        ORDER BY programa";

    $result = db_query($sql);

    if (pg_num_rows($result) == 0) {
        throw new \BusinessException('Nenhum registro encontrado!');
    }

    return \db_utils::getCollectionByRecord($result);
}

function buscarTotalPorPaciente()
{
    $dataSistema = date('Y-m-d', db_getsession('DB_datausu'));

    $sql = "SELECT COUNT(*) AS total
        FROM (SELECT DISTINCT fa11_i_cgsund
        FROM far_controlemed
        INNER JOIN far_controle ON fa11_i_codigo = fa10_i_controle
        INNER JOIN far_programa ON fa10_i_programa = fa12_i_codigo
        WHERE fa10_i_programa IN ({$_GET['programas']})
            AND fa10_d_dataini <= '{$dataSistema}'
            AND (fa10_d_datafim is null OR fa10_d_datafim >= '{$dataSistema}')) AS xx";

    $result = db_query($sql);

    if (pg_num_rows($result) == 0) {
        throw new \BusinessException('Nenhum registro encontrado!');
    }

    return \db_utils::fieldsMemory($result, 0)->total;
}

function escreverPacientes($pdf, $dados)
{
    $pdf->Addpage('L'); // L deitado
    $pdf->setfillcolor(223);
    $pdf->setfont('arial', '', 11);

    $linhasNaPagina = 0;
    $programaAtual = '';

    foreach ($dados as $dado) {
        if ($programaAtual != $dado->programa) {
            $linhasNaPagina += 5;
            if (($linhasNaPagina + 4) >= 29) {
                $pdf->AddPage('L');
                $linhasNaPagina = 5;
            }
            $programaAtual = $dado->programa;
            novoPrograma($pdf, $dado->programa);
            novoCabecalho($pdf);
        }
        if ($linhasNaPagina >= 29) {
            $pdf->AddPage('L');
            novoCabecalho($pdf);
            $linhasNaPagina = 1;
        }

        novaLinha(
            $pdf,
            $dado->z01_i_cgsund,
            $dado->z01_v_nome,
            $dado->s115_c_cartaosus,
            $dado->z01_v_cgccpf,
            $dado->z01_v_ender,
            $dado->z01_i_numero,
            $dado->z01_v_compl,
            $dado->z01_v_bairro,
            $dado->z01_v_munic
        );
        $linhasNaPagina++;
    }
}

function escreverTotalizador(ECidade\Pdf\Pdf $pdf, $dados)
{
    $pdf->Addpage('L'); // L deitado
    $pdf->setfillcolor(223);
    $pdf->setfont('arial', 'B', 11);
    $pdf->cell(280, 10, 'Total de Paciente por Ação Programática', 0, 1, "C", 0);
    novoCabecalhoTotal($pdf);

    foreach ($dados as $dado) {
        if ($pdf->getAvailableHeight() < 5) {
            $pdf->Addpage('L');
            novoCabecalhoTotal($pdf);
        }

        novaLinhaTotal($pdf, $dado->programa, $dado->total, $dado->codigo);
    }

    $totalPaciente = buscarTotalPorPaciente();

    $pdf->setfont('arial', 'B', 7);
    $pdf->cell(270, 15, "Total geral de Pacientes contemplados: {$totalPaciente}", 0, 1, "R");
}

try {
    $pdf = new ECidade\Pdf\Pdf();
    $pdf->AliasNbPages();
    $pdf->exibeHeader();
    $pdf->mostrarRodape();
    $pdf->mostrarEmissor();
    $pdf->mostrarTotalDePaginas();

    $pdf->addTitulo('Lista Pacientes por Programa');
    $pdf->addTitulo('');
    $pdf->addTitulo('Ordem:');
    $pdf->addTitulo($_GET['ordem'] == 2 ? '1 - CGS' : '1 - Nome');
    $pdf->addTitulo('');
    $pdf->addTitulo('');

    if (!isset($_GET['somenteTotalizador'])) {
        escreverPacientes($pdf, buscarDados());
    }

    escreverTotalizador($pdf, buscarTotalPorPrograma());

    $pdf->output();
} catch (\Exception $e) {
    echo "<table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'>
                <b>{$e->getMessage()}.<br>
                  <input type='button' value='Fechar' onclick='window.close()'>
                </b>
              </font>
            </td>
          </tr>
        </table>";
    exit;
}
