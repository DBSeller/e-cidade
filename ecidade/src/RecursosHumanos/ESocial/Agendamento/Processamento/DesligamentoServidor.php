<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use CgmRepository;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;
use InstituicaoRepository;
use ServidorRepository;
use stdClass;

// phpcs:disable
require_once(modification('libs/db_stdlib.php'));
// phpcs:enable

/**
 * Class Desligamento
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class DesligamentoServidor extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * LotacaoTributaria constructor.
     * @param $iCgm
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

        if (empty($this->getMesCompetencia()) && !empty($this->getAnoCompetencia())) {
            throw new \BusinessException("Mês da competência, não informado.");
        }

        if (!empty($this->getMesCompetencia()) && empty($this->getAnoCompetencia())) {
            throw new \BusinessException("Ano da competência, não informado.");
        }

        /** Criamos as propriedades internas de ano e mes, devido aos cases de competencia e a necessidade de busca
         *  e necessidade de busca por ano e mes
         */

        $codigoInstituicao = db_getsession("DB_instit");
        $ano = $this->anoCompetencia;
        $mes = $this->mesCompetencia;
        if (empty($ano)) {
            $ano = CompetenciaHelper::get()->getAno();
        }
        if (empty($mes)) {
            $mes = CompetenciaHelper::get()->getMes();
        }

        if (empty($this->servidores)) {
            if (!empty($this->selecao)) {
                try {
                    $this->servidores = ServidorRepository::getServidoresBySelecao(
                        $ano,
                        $mes,
                        $this->selecao,
                        $codigoInstituicao
                    );
                } catch (Exception $e) {
                    throw new \DBException("Ocorrêu um erro ao buscar as informações da seleção informada.");
                }
            } else {
                if (!empty($this->anoCompetencia) && !empty($this->mesCompetencia)) {
                    $this->servidores = ServidorRepository::getServidoresPorCompetenciaRescisao(
                        $this->anoCompetencia,
                        $this->mesCompetencia,
                        $codigoInstituicao
                    );
                } else {
                    $this->servidores = ServidorRepository::getServidoresPorCompetenciaRescisao(
                        $ano,
                        $mes,
                        $codigoInstituicao
                    );
                }
            }
        }

        if (sizeof($this->servidores) == 0) {
            throw new \BusinessException("Nenhuma matrícula encontrada para o filtro informado");
        }

        $bAlteracao = false;
        $oFormatter = FormatterFactory::get(Tipo::S2299);
        $oFormatter->setEmpregador(CgmRepository::getByCodigo($this->cgm));
        $oFormatter->setIgnoraValidacao($this->getIgnoraValidacao());
        $aDadosPreenchimentoDesligamento = $oFormatter->formatar($this->servidores);
        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }
        foreach ($aDadosPreenchimentoDesligamento as $iIndice => $oDados) {
            $oEvento = new Evento(Tipo::S2299, $this->cgm, $oDados->referencia, $oDados);
            $oEvento->iContador = $iIndice;

            if ($oEvento->adicionarFila(false, $validaMd5)) {
                $bAlteracao = true;
            }
        }

        return $bAlteracao;
    }
}
