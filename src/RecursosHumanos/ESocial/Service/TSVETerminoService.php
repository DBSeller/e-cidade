<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Service;

use ECidade\RecursosHumanos\Pessoal\Repository\ServidorOperadoraSaudeRepository;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorOperadoraSaudeService;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorOutrosVinculosService;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorProcessosJudiciaisFolhaService;
use ECidade\RecursosHumanos\ESocial\Entity\TSVETermino;

use BusinessException;
use CalculoFolha;
use DBPessoal;
use EventoFinanceiroFolha;
use Exception;
use Rubrica;
use Servidor;
use stdClass;

/**
 * Class TSVETerminoService
 * @package ECidade\RecursosHumanos\ESocial\Service
 */
class TSVETerminoService
{
    /**
     * @var TSVETermino
     */
    private $tsveTermino;

    /**
     * @var int
     */
    private $anoCompetencia;

    /**
     * @var int
     */
    private $mesCompetencia;

    /**
     * @var Servidor
     */
    private $servidor;

    /**
     * @var array
     */
    private $rubricasValidas;

    /**
     * @var boolean
     */
    private $possuiNaturezaRubricaSaude = false;

    /**
     * TSVETerminoService constructor.
     */
    public function __construct()
    {
        $this->anoCompetencia = DBPessoal::getAnoFolha();
        $this->mesCompetencia = DBPessoal::getMesFolha();
    }

    /**
     * Define quais são as rubricas válidas para este evento
     * @param array $rubricasValidas
     */
    public function setRubricasValidas($rubricasValidas)
    {
        $this->rubricasValidas = $rubricasValidas;
    }

    /**
     * @param Servidor $servidor
     * @return TSVETermino|null
     * @throws BusinessException
     * @throws \DBException
     */
    public function buscarDadosPorServidor(Servidor $servidor)
    {
        if (!$servidor->isRescindidoCompetencia()) {
            return null;
        }

        $this->servidor = $servidor;

        $this->tsveTermino = new TSVETermino();
        $this->tsveTermino->setServidor($this->servidor);
        $this->buscarOutrosVinculos();
        $this->buscarPlanoSaude();
        $this->buscarProcessosJudiciais();

        return $this->tsveTermino;
    }

    /**
     * @throws Exception
     */
    private function buscarOutrosVinculos()
    {
        $parametros = new stdClass();
        $parametros->matricula = $this->servidor->getMatricula();

        $serviceOutrosVinculos = new ServidorOutrosVinculosService();
        $serviceOutrosVinculos->setAnoCompetencia($this->anoCompetencia);
        $serviceOutrosVinculos->setMesCompetencia($this->mesCompetencia);
        $this->tsveTermino->setServidorOutrosVinculos(
            $serviceOutrosVinculos->buscarOutrosVinculosPorMatriculaCompetencia($parametros)
        );
    }

    /**
     * @throws Exception
     */
    private function buscarPlanoSaude()
    {
        if (!$this->possuiNaturezaRubricaSaude) {
            return;
        }

        $repositoryServidorOperadoraSaudeRepository = new ServidorOperadoraSaudeRepository();
        $servidorOperadorasSaude = $repositoryServidorOperadoraSaudeRepository->scopeServidor($this->servidor)
          ->scopeAno($this->anoCompetencia)
          ->scopeMes($this->mesCompetencia)
          ->get();

        foreach ($servidorOperadorasSaude as $servidorOperadoraSaude) {
            $servidorOperadoraSaudeService = new ServidorOperadoraSaudeService();
            $servidorOperadoraSaude->setServidorOperadoraSaudeDependente(
                $servidorOperadoraSaudeService->dependentes($servidorOperadoraSaude)
            );
        }

        $this->tsveTermino->setPlanoSaude($servidorOperadorasSaude);
    }

    /**
     * @throws Exception
     */
    private function buscarProcessosJudiciais()
    {
        $parametros = new stdClass();
        $parametros->matricula = $this->servidor->getMatricula();

        $processosJudiciaisService = new ServidorProcessosJudiciaisFolhaService();
        $processosJudiciaisService->setAnoCompetencia($this->anoCompetencia);
        $processosJudiciaisService->setMesCompetencia($this->mesCompetencia);

        $this->tsveTermino->setProcessosJudiciais(
            $processosJudiciaisService->buscarProcessosJudiciaisPorMatriculaCompetencia($parametros)
        );
    }

    /**
     * @throws BusinessException
     * @throws \DBException
     */
    private function buscarPagamentos()
    {
        $calculoRescisao = $this->servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_RESCISAO);
        $pagamentos = array();

        foreach ($calculoRescisao->getEventosFinanceiros() as $eventoFinanceiro) {
            if (!array_key_exists($eventoFinanceiro->getRubrica()->getCodigo(), $this->rubricasValidas)) {
                continue;
            }

            if ($eventoFinanceiro->getRubrica()->getTipo() == Rubrica::TIPO_BASE) {
                continue;
            }

            if ($this->rubricasValidas[$eventoFinanceiro->getRubrica()->getCodigo()]->natrubr == 9219) {
                $this->possuiNaturezaRubricaSaude = true;
            }

            $rubrica = new stdClass();
            $rubrica->codigo = $eventoFinanceiro->getRubrica()->getCodigo();
            $rubrica->descricao = $eventoFinanceiro->getRubrica()->getDescricao();
            $rubrica->quantidade = $eventoFinanceiro->getQuantidade();
            $rubrica->valor = $eventoFinanceiro->getValor();
            $rubrica->tipo = $eventoFinanceiro->getRubrica()->getTipo();
            $rubrica->descricaoTipo = $eventoFinanceiro->getRubrica()->getTipo()
                == EventoFinanceiroFolha::PROVENTO ? "Provento" : "Desconto";

            $pagamentos[] = $rubrica;
        }

        $this->tsveTermino->setPagamentos($pagamentos);
    }

    /**
     * @return int
     */
    public function getAnoCompetencia()
    {
        return $this->anoCompetencia;
    }

    /**
     * @param int $anoCompetencia
     */
    public function setAnoCompetencia($anoCompetencia)
    {
        $this->anoCompetencia = $anoCompetencia;
    }

    /**
     * @return int
     */
    public function getMesCompetencia()
    {
        return $this->mesCompetencia;
    }

    /**
     * @param int $mesCompetencia
     */
    public function setMesCompetencia($mesCompetencia)
    {
        $this->mesCompetencia = $mesCompetencia;
    }
}
