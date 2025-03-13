<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 21/06/18
 * Time: 11:19
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ;


class TipoPessoa
{
    public $outroNome; // string
    public $documento; // tipoDocumentoIdentificacao
    public $endereco; // tipoEndereco
    public $pessoaRelacionada; // tipoRelacionamentoPessoal
    public $pessoaVinculada; // tipoPessoa
    public $tipoPessoa; // tipoQualificacaoPessoa
    public $numeroDocumentoPrincipal; // string
    public $cidadeNatural; // string
    public $nacionalidade; // string
    public $estadoNatural; // string
    public $dataObito; // string
    public $sexo; // modalidadeGeneroPessoa
    public $nome; // string
    public $nomeGenitor; // string
    public $dataNascimento; // string
    public $nomeGenitora; // string

}