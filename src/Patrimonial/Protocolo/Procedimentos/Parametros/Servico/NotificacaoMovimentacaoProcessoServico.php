<?php

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Servico;

use Agenda;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo\NotificacaoMovimentacaoProcesso;
use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio\NotificacaoMovimentacaoProcessoRepositorio;
use Job;
use stdClass;

/**
 * Class NotificacaoMovimentacaoProcessoServico
 * @package ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Servico
 */
class NotificacaoMovimentacaoProcessoServico
{
    /**
     * @var NotificacaoMovimentacaoProcessoRepositorio
     */
    private $notificacaoMovimentacaoProcessoRepositorio;

    /**
     * NotificacaoMovimentacaoProcessoServico constructor.
     * @param NotificacaoMovimentacaoProcessoRepositorio $notificacaoMovimentacaoProcessoRepositorio
     */
    public function __construct(NotificacaoMovimentacaoProcessoRepositorio $notificacaoMovimentacaoProcessoRepositorio)
    {
        $this->notificacaoMovimentacaoProcessoRepositorio = $notificacaoMovimentacaoProcessoRepositorio;
    }

    /**
     * @param stdClass $atributos
     * @throws \DBException
     */
    public function salvar(stdClass $atributos)
    {
        $notificacaoMovimentacaoProcesso = new NotificacaoMovimentacaoProcesso(
            $atributos->notificarReceberProcesso,
            $atributos->notificarDataVencimento,
            $atributos->assunto,
            $atributos->mensagem,
            $atributos->diasPrazoMovimentacao,
            $atributos->tipoprazo,
            $atributos->usuarioremetente
        );
        $notificacaoMovimentacaoProcesso->setSequencial($atributos->sequencial);
        $notificacaoMovimentacaoProcesso->setPermitirNotificarDepartamento($atributos->permitirNotificarDepartamento);

        $this->notificacaoMovimentacaoProcessoRepositorio->alterar($notificacaoMovimentacaoProcesso);

        if (!file_exists('jobs/configuracoes/taskManager/fila/NotificacaoMovimentacaoProcesso.task.xml')) {
            $job = new Job();
            $time = new \DateTime();
            $job->setNome('NotificacaoMovimentacaoProcesso');
            $job->setCodigoUsuario(1);
            $job->setDescricao('Notificacao de Movimentacao do Processo');
            $job->setNomeClasse('NotificacaoMovimentacaoProcessoTask');
            $job->setTipoPeriodicidade(Agenda::PERIODICIDADE_DIARIA);
            $job->adicionarPeriodicidade('0600');
            $job->setCaminhoPrograma('model/contrato/mensageria/NotificacaoMovimentacaoProcessoTask.model.php');
            $job->salvar();
        }
    }

    /**
     * @return \_db_fields|stdClass
     * @throws \DBException
     */
    public function buscar()
    {
        return $this->notificacaoMovimentacaoProcessoRepositorio->buscar();
    }
}
