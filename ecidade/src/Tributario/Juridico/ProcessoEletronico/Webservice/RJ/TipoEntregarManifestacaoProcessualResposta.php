<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 21/06/18
 * Time: 14:12
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Webservice\RJ;


class TipoEntregarManifestacaoProcessualResposta
{
   public $sucesso; // boolean
  public $mensagem; // string
  public $protocoloRecebimento; // string
  public $dataOperacao; // string
  public $recibo; // base64Binary
  public $parametro; // tipoParametro
}