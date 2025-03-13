<?php


/**
 * Class cl_arquivo_tef
 * @property $id
 * @property $numero_autorizacao
 * @property $numero_cv
 * @property $cartao
 * @property $data_venda
 * @property $data_vencimento
 * @property $parcela
 * @property $total_parcelas
 * @property $valor_original
 * @property $valor_bruto
 * @property $valor_descontos
 * @property $valor_liquido

 */
class cl_linha_tef extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('caixa.linha_tef');
        $this->setSalvarAccount(false);
    }

    public function sql_query_operacoesrealizadas($sCampos, $sWhere = null, $sOrderBy = null) {

        $sSql  = "select {$sCampos} ";
        $sSql .= " from linha_tef ";
        $sSql .= " JOIN operacoesrealizadastef ON k198_codigoaprovacao = lpad(numero_autorizacao, 6, '0') ";
        $sSql .= "                            and k198_nsuautorizadora = numero_cv";
        if (!empty($sWhere)) {
          $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrderBy)) {
          $sSql .= " order by {$sOrderBy} ";
        }

        return $sSql;
      }


}
