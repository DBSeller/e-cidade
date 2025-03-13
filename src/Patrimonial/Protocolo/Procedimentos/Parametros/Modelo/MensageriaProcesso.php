<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo;

use DBException;
use DBseller\Mensageria\Library\Cliente;
use ECidade\Patrimonial\Protocolo\Modelo\Processo;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio\NotificacaoMovimentacaoProcessoRepositorio;
use ECidade\Patrimonial\Protocolo\Repositorio\ProcessoRepositorio;
use Instituicao;

/**
 * Class MensageriaProcesso
 * @package ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo
 */
class MensageriaProcesso
{
    /**
     * @param $codigoProcesso
     * @param bool $cancelamento
     * @param \DBDepartamento|null $departamentoDestino
     * @return bool|null
     */
    public static function enviar($codigoProcesso, $cancelamento = false, \DBDepartamento $departamentoDestino = null)
    {
        try {
            $caminhoPlugin = 'plugins/mensageria';
            $instalado = is_dir($caminhoPlugin) && file_exists($caminhoPlugin . '/Manifest.xml');

            return $instalado ? self::enviarNotificacao($codigoProcesso, $cancelamento, $departamentoDestino) : null;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $codigoProcesso
     * @param $cancelamento
     * @param \DBDepartamento|null $departamentoDestino
     * @return bool
     * @throws DBException
     */
    private static function enviarNotificacao($codigoProcesso, $cancelamento, $departamentoDestino = null)
    {
        require_once modification('integracao_externa/mensageria/DBSeller/Mensageria/Library/Cliente.php');

        $repoNotificacao = new NotificacaoMovimentacaoProcessoRepositorio();
        $notificacao = $repoNotificacao->buscar();

        if ($notificacao->p101_notificarreceberprocesso == 'f') {
            return true;
        }

        $instituicao = new Instituicao();
        $prefeitura = $instituicao->getDadosPrefeitura();
        $sistema = 'e-cidade.' . strtolower($prefeitura->getMunicipio());
        $processo = ProcessoRepositorio::encontrar($codigoProcesso);
        $codigoUsuario = $processo->getCodigoUsuario();

        if ($cancelamento) {
            $envio = self::prepararEnvioCancelamento($processo, $notificacao, $sistema, $departamentoDestino);
        } else if (!empty($departamentoDestino) && empty($codigoUsuario)) {
            $envio = self::prepararEnvioTranferenciaParaDepartamento($processo, $notificacao, $departamentoDestino, $sistema);
            if (!$envio) {
                return false;
            }
        } else {
            $envio = self::prepararEnvioTransferenciaParaUsuario($processo, $notificacao, $sistema);
        }

        if (!empty($envio->destinatariosNotificados)) {
            self::realizarEnvio($envio->assunto, $envio->mensagemPadrao, $envio->diasprazo, $envio->numero, $envio->iAno, $envio->destinatariosNotificados, $sistema);
        }
        return true;
    }

    /**
     * @param Processo $processo
     * @param \stdClass $notificacao
     * @param string $sistema
     * @param \DBDepartamento|null $departamentoDestino
     * @return \stdClass
     * @throws DBException
     * @throws \BusinessException
     */
    private static function prepararEnvioCancelamento(Processo $processo, \stdClass $notificacao, $sistema, $departamentoDestino = null)
    {
        $envio = new \stdClass();
        $envio->assunto = 'Cancelamento da Transferência';
        $envio->mensagemPadrao = 'A transferência do processo [numero]/[ano] foi cancelada. Desconsidere qualquer notificação referente à mesma.';
        $envio->diasprazo = $notificacao->p101_diasprazo;
        $envio->numero = $processo->getNumero();
        $envio->iAno = $processo->getAno();

        if (empty($departamentoDestino)) {
            $envio->destinatariosNotificados = array(array('sLogin' => $processo->getLogin(), 'sSistema' => $sistema));
        } else {
            if (static::verificarDepartamento($notificacao)) {
                $destinatariosEncontrados = ProcessoRepositorio::getUsuariosComAcessoEmDepartamentoMenu($departamentoDestino->getCodigo());

                $envio->destinatariosNotificados = array();
                foreach ($destinatariosEncontrados as $dadosLinhas) {
                    $envio->destinatariosNotificados[] = array(
                        'sLogin' => $dadosLinhas->login,
                        'sSistema' => $sistema
                    );
                }
            }
        }

        return $envio;
    }

    /**
     * @param Processo $processo
     * @param \stdClass $notificacao
     * @param $sistema
     * @return \stdClass
     */
    private static function prepararEnvioTransferenciaParaUsuario(Processo $processo, \stdClass $notificacao, $sistema)
    {
        $envio = new \stdClass();
        $envio->assunto = 'Transferência de Processo - [numero]/[ano]';
        $envio->mensagemPadrao = 'O processo [numero]/[ano] foi transferido para você e deve ser movimentado em [dias] dias.';
        $envio->diasprazo = $notificacao->p101_diasprazo;
        $envio->numero = $processo->getNumero();
        $envio->iAno = $processo->getAno();
        $envio->destinatariosNotificados = array(array('sLogin' => $processo->getLogin(), 'sSistema' => $sistema));

        return $envio;
    }

    /**
     * @param Processo $processo
     * @param \stdClass $notificacao
     * @param \DBDepartamento $departamentoDestino
     * @param string $sistema
     * @return \stdClass
     * @throws DBException
     * @throws \BusinessException
     * @throws \ParameterException
     */
    private static function prepararEnvioTranferenciaParaDepartamento(Processo $processo, \stdClass $notificacao, \DBDepartamento $departamentoDestino, $sistema)
    {
        if (static::verificarDepartamento($notificacao)) {
            if (empty($departamentoDestino)) {
                throw new \ParameterException('Não foi possível processar o envio da notificação pois o departamento não foi informado.');
            }

            $envio = new \stdClass();
            $envio->assunto = 'Transferência de Processo - [numero]/[ano]';
            $envio->mensagemPadrao = 'O processo [numero]/[ano] foi transferido para o seu departamento e deve ser movimentado em [dias] dias.';
            $envio->diasprazo = $notificacao->p101_diasprazo;
            $envio->numero = $processo->getNumero();
            $envio->iAno = $processo->getAno();

            // Precisamos do processo protocolo para buscar o tipo processo
            $processoprotocolo = new \processoProtocolo($processo->getCodigo());
            $tipoProcesso = $processoprotocolo->getTipoProcesso();
            if (!empty($tipoProcesso)) {
                $dias = ProcessoRepositorio::getDiasAndamentoPadrao($tipoProcesso, $departamentoDestino->getCodigo());
                if (!empty($dias)) {
                    $envio->diasprazo = $dias[0]->p53_dias;
                }
            }

            $destinatariosEncontrados = ProcessoRepositorio::getUsuariosComAcessoEmDepartamentoMenu($departamentoDestino->getCodigo());

            $envio->destinatariosNotificados = array();
            foreach ($destinatariosEncontrados as $dadosLinhas) {
                $envio->destinatariosNotificados[] = array(
                    'sLogin' => $dadosLinhas->login,
                    'sSistema' => $sistema
                );
            }

            return $envio;
        } else {
            return false;
        }
    }

    /**
     * @param string $assunto
     * @param string $mensagemPadrao
     * @param integer $diasprazo
     * @param integer $numero
     * @param integer $iAno
     * @param array $destinatariosNotificados
     * @param string $sistema
     */
    private static function realizarEnvio($assunto, $mensagemPadrao, $diasprazo, $numero, $iAno, $destinatariosNotificados, $sistema)
    {
        $conteudo = str_replace(
            array('[numero]', '[ano]', '[dias]'),
            array($numero, $iAno, $diasprazo),
            $mensagemPadrao
        );

        $assunto = str_replace(
            array('[numero]', '[ano]', '[dias]'),
            array($numero, $iAno, $diasprazo),
            $assunto
        );

        $mensagem = array(
            'iTipo' => Cliente::TIPO_NOTIFICACAO,
            'sAssunto' => $assunto,
            'sConteudo' => $conteudo,
            'aDestinatarios' => $destinatariosNotificados
        );


        Cliente::enviar(db_getsession('DB_login'), $sistema, $mensagem);
    }

    private static function verificarDepartamento(\stdClass $notificacao)
    {
        return true;
    }
}
