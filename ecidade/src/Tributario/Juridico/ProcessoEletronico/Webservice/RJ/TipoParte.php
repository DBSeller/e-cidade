<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 21/06/18
 * Time: 13:45
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ;


class TipoParte
{
    public $pessoa; // tipoPessoa
    public $advogado; // tipoRepresentanteProcessual
    public $pessoaProcessualRelacionada; // tipoParte
    public $relacionamentoProcessual; // modalidadeRelacionamentoProcessual
    public $intimacaoPendente; // int
    public $assistenciaJudiciaria; // boolean
}