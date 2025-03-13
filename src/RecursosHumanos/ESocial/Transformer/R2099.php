<?php

namespace ECidade\RecursosHumanos\ESocial\Transformer;

use Exception;

class R2099 extends Sugestao
{
    private $cgmContribuinte;

    private $anoFechamento;

    private $mesFechamento;

    protected $deParaCamposSimples = array(
        'nmctt' => 'nmResp',
        'cpfctt' => 'cpfResp',
        'fonefixo' => 'telefone',
        'email' => 'email'
    );

    public function __construct($parametros)
    {
        $this->cgmContribuinte = $parametros->contribuinte;
        $this->anoFechamento = $parametros->ano;
        $this->mesFechamento = $parametros->mes;
    }

    protected function buscarValorCorrespondenteESocial($nomeCampo, $valor)
    {
    }

    protected function posProcessamento()
    {
    }

    protected function buscarDados()
    {
        $dao = new \cl_avaliacaogruporespostafechamentoefd();
        $sql = $dao->sqlDadosSugestaoContatoContribuinte($this->cgmContribuinte);
        return db_query($sql);
    }

    protected function possuiPreenchimento()
    {
        $where = "
            eso32_cgmcontribuinte = {$this->cgmContribuinte} 
            AND eso32_ano = {$this->anoFechamento}
            AND eso32_mes = {$this->mesFechamento}
        ";

        $dao = new \cl_avaliacaogruporespostafechamentoefd();
        $sql = $dao->sql_query_file(null, "*", null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos anteriores do formulário.");
        }

        if (pg_num_rows($rs) > 0) {
            return true;
        }

        return false;
    }
}
