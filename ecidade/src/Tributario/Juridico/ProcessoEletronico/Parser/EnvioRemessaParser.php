<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Parser;


use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\RetornoRemessa;

/**
 * Class EnvioRemessaParser
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Parser
 */
class EnvioRemessaParser
{

    /**
     * Metodo responsavel por fazer parse para objeto RetornoRemessa
     *
     * @param $data
     * @return RetornoRemessa
     */
    public static function parse($data)
    {
        $oRetornoRemessa = new RetornoRemessa();

        $oRetornoRemessa->setStatus($data->sucesso);
        $oRetornoRemessa->setMensagem(utf8_decode($data->mensagem));
        $oRetornoRemessa->setRecibo($data->recibo);
        $oRetornoRemessa->setDataOperacao(self::formatDate($data->dataOperacao));
        $oRetornoRemessa->setProtocoloRecebimento($data->protocoloRecebimento);

        $parametros = array();

        foreach ($data->parametro as $param) {
            $parametros[$param->nome] = $param->valor;
        }

        if (!empty($parametros['ORGAO_DESTINO'])) {

            $oRetornoRemessa->setCartorio(self::getCartorio($parametros['ORGAO_DESTINO']));
            $oRetornoRemessa->setOrgao(utf8_decode($parametros['ORGAO_DESTINO']));
        }

        if (!empty($parametros['NUMERO_PROCESSO'])) {
            $oRetornoRemessa->setNumeroProcesso($parametros['NUMERO_PROCESSO']);
        }

        $oRetornoRemessa->setParametros($parametros);

        return $oRetornoRemessa;
    }

    /**
     * @param $strOrgao
     * @return string
     */
    private static function getCartorio($strOrgao)
    {
        $explod = explode('-', $strOrgao);
        $cartorio = trim($explod[0]);

        return $cartorio;
    }

    /**
     * Formata a data recebida do webservice
     *
     * @param $dataOperacao
     * @return bool|string
     */
    private static function formatDate($dataOperacao)
    {
        $strFormat  = substr($dataOperacao, 0, 8);
        $strFormat .= ' ' . substr($dataOperacao, 8, 14);

        return $strFormat;
    }


}