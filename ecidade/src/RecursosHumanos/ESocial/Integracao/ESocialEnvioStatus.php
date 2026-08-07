<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao;

/**
 * Class ESocialEnvio
 * @package ECidade\RecursosHumanos\ESocial\Integracao
 */
final class ESocialEnvioStatus
{
    const AGUARDANDO_ENVIO = 1;
    const AGUARDANDO_PROCESSAMENTO = 2;
    const AGUARDANDO_ENVIO_EMPREGADOR = 3;
    const PROCESSADO_SUCESSO = 4;
    const PROCESSADO_ERRO = 5;
    const ERRO_ENVIO = 6;
    const ERRO_PROCESSAMENTO = 7;
    const ERRO_CERTIFICADO = 8;
    const AGUARDANDO_ENVIO_DEPENDENCIAS = 9;
    const PROCESSADO_ADVERTENCIA = 12;

    /**
     * Retorna descricao do status
     *
     * @param $codigoStatus
     * @return string
     * @throws \Exception
     */
    public static function getStatusDescricao($codigoStatus)
    {
        $data = array(
            self::AGUARDANDO_ENVIO => 'Aguardando envio',
            self::AGUARDANDO_PROCESSAMENTO => 'Aguardando processamento',
            self::AGUARDANDO_ENVIO_EMPREGADOR => 'Aguardando envio layout empregador',
            self::PROCESSADO_SUCESSO => 'Processado com sucesso',
            self::PROCESSADO_ERRO => 'Processado com erros',
            self::ERRO_ENVIO => 'Erro no envio',
            self::ERRO_PROCESSAMENTO => 'Erro no processamento',
            self::ERRO_CERTIFICADO => 'Certificado inválido. Verifique a senha ou a data de expiração.',
            self::AGUARDANDO_ENVIO_DEPENDENCIAS => 'Aguardando envio de eventos precedentes.',
            self::PROCESSADO_ADVERTENCIA => 'Processado com advertência',
        );

        if (empty($data[$codigoStatus])) {
            throw new \Exception('Status não encontrado.');
        }

        return $data[$codigoStatus];
    }
}
