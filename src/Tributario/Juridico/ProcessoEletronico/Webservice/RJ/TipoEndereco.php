<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 21/06/18
 * Time: 11:21
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ;

/**
 * Class TipoEndereco
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ
 */
class TipoEndereco
{
    public $logradouro; // string
    public $numero; // string
    public $complemento; // string
    public $bairro; // string
    public $cidade; // string
    public $estado; // string
    public $pais; // string
    public $cep; // string
}