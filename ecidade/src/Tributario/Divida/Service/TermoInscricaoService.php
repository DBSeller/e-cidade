<?php

namespace ECidade\Tributario\Divida\Service;

class TermoInscricaoService
{
    public static function salvar($numpre, $oDivida, $receita, $dataInsc, $usuario, $instit)
    {
        $termoInscricaoRegra = new \cl_termoinscrreg();
        $where = "v01_numpre = $numpre";
        $sql = $termoInscricaoRegra->sql_query('', 'v92_termo', '', $where);
        $rs = $termoInscricaoRegra->sql_record($sql);
        if ($termoInscricaoRegra->numrows>0) {
            $v92_termo = \db_utils::fieldsMemory($rs, 0)->v92_termo;
        } else {
            $termoInscricao = new \cl_termoinscr();
            $termoInscricao->v92_dtinsc = $dataInsc;
            $termoInscricao->v92_usuario = $usuario;
            $termoInscricao->v92_instit = $instit;
            $termoInscricao->incluir(null);
            if ($termoInscricao->erro_status == 0) {
                throw new \Exception('Erro ao salvar termo inscrição!');
            }
            $v92_termo = $termoInscricao->v92_termo;
        }
          
        $dt_venc_data = $oDivida->v01_dtvenc;
        $dataInsc = $oDivida->v01_dtinsc;
        $val = $oDivida->v01_vlrhis;
        $dt_oper_data = $oDivida->v01_dtoper;
        $data = strtotime($oDivida->v01_dtinsc);
        $ano = date("Y", $data);

        if ($receita == '') {
            $clproced = new \cl_proced();
            $where = "v03_codigo = $oDivida->v01_proced";
            $sql = $clproced->sql_query('', 'v03_receit', '', $where);
            $rs = $clproced->sql_record($sql);
            $receita = \db_utils::fieldsMemory($rs, 0)->v03_receit;
        }

        $sqlDebitosTermo =
        "select
        fc_corre($receita, '$dt_venc_data', $val, '$dataInsc', '$ano', '$dt_venc_data') as valor_corrigido,
        fc_multa($receita, '$dt_venc_data', '$dataInsc', '$dt_oper_data', '$ano') as multa,
        fc_juros($receita, '$dt_venc_data', '$dataInsc', '$dt_oper_data', false, '$ano') as juros  
        from
            divida
        where
            v01_coddiv = $oDivida->v01_coddiv";
                                    
        $rsDebitosTermo = db_query($sqlDebitosTermo);
        $oDadosTermo = \db_utils::fieldsMemory($rsDebitosTermo, 0);
        
        $valorMulta = $oDadosTermo->valor_corrigido * $oDadosTermo->multa;
        $valorJuros = $oDadosTermo->valor_corrigido * $oDadosTermo->juros;

        $termoInscricaoRegra->v93_termo    = $v92_termo;
        $termoInscricaoRegra->v93_coddiv   = $oDivida->v01_coddiv;
        $termoInscricaoRegra->v93_vlrhis   = $val;
        $termoInscricaoRegra->v93_vlrcor   = $oDadosTermo->valor_corrigido;
        $termoInscricaoRegra->v93_vlrjur   = $valorJuros;
        $termoInscricaoRegra->v93_vlrmul   = $valorMulta;
        
        $termoInscricaoRegra->incluir(null);

        return true;
    }

    // Habilitar a funcão se a alteração de dívida alterar o termo

    /* public static function alterar($oDivida, $receita)
    {
        $termoInscricaoRegra = new \cl_termoinscrreg();
        $dt_venc_data = $oDivida->v01_dtvenc;
        $dataInsc = $oDivida->v01_dtinsc;
        $val = $oDivida->v01_vlrhis;
        $dt_oper_data = $oDivida->v01_dtoper;
        $data = strtotime($oDivida->v01_dtinsc);
        $ano = date("Y", $data);

        $sqlDebitosTermo =
        "select
        fc_corre($receita, '$dt_venc_data', $val, '$dataInsc', '$ano', '$dt_venc_data') as valor_corrigido,
        fc_multa($receita, '$dt_venc_data', '$dataInsc', '$dt_oper_data', '$ano') as multa,
        fc_juros($receita, '$dt_venc_data', '$dataInsc', '$dt_oper_data', false, '$ano') as juros
        from
            divida
        where
            v01_coddiv = $oDivida->v01_coddiv";

        $rsDebitosTermo = db_query($sqlDebitosTermo);
        $oDadosTermo = \db_utils::fieldsMemory($rsDebitosTermo, 0);

        $valorMulta = $oDadosTermo->valor_corrigido * $oDadosTermo->multa;
        $valorJuros = $oDadosTermo->valor_corrigido * $oDadosTermo->juros;

        $termoInscricaoRegra->v93_coddiv   = $oDivida->v01_coddiv;
        $termoInscricaoRegra->v93_vlrhis   = $val;
        $termoInscricaoRegra->v93_vlrcor   = $oDadosTermo->valor_corrigido;
        $termoInscricaoRegra->v93_vlrjur   = $valorJuros;
        $termoInscricaoRegra->v93_vlrmul   = $valorMulta;

        $termoInscricaoRegra->alterar($termoInscricaoRegra->v93_coddiv);

        return true;
    } */

    // Habilitar novamente a funcão se a rotina de exclusão de dívida for reativada

    /* public static function excluir($v01_coddiv)
    {
        $termoInscricaoRegra = new \cl_termoinscrreg();
        $termoInscricaoRegra->excluir($v01_coddiv);

        return true;
    } */
}
