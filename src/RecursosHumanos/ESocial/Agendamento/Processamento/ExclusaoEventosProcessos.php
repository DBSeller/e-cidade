<?php
namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use BusinessException;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ExclusaoRepository;
use Exception;
use Instituicao;
use CgmRepository;

// phpcs:disable
require_once(modification('libs/db_stdlib.php'));
// phpcs:enable

/**
 * Class ExclusaoEventos
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class ExclusaoEventosProcessos extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * Constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws Exception
     */
    public function processar()
    {
        if (empty($this->dataPreenchidaInicio) && empty($this->dataPreenchidaFim)) {
            throw new BusinessException("Período não informado.");
        }
        if (empty($this->dataPreenchidaInicio)) {
            throw new BusinessException("Data inicial do período não informada.");
        }
        if (empty($this->dataPreenchidaFim)) {
            throw new BusinessException("Data final do período não informada.");
        }

        $alteracao = false;
        $exclusaoRepository = new ExclusaoRepository;
        $dadosExclusao = $exclusaoRepository
            ->scopeDataExclusao($this->dataPreenchidaInicio, '>=', $this->dataPreenchidaFim, '<=')
            ->get();

        $formatter = FormatterFactory::get(Tipo::S3500);
        $formatter->setEmpregador(CgmRepository::getByCodigo($this->cgm));
        $dadosPreenchimentoFormatados = $formatter->formatar($dadosExclusao);

        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }

        foreach ($dadosPreenchimentoFormatados as $oDados) {
            $evento = new Evento(TIPO::S3500, $this->cgm, $oDados->referencia, $oDados);

            if ($evento->adicionarFila(false, $validaMd5)) {
                $alteracao = true;
            }
        }
        return $alteracao;
    }
}
