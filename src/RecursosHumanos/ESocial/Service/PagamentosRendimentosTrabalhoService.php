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

use BusinessException;
use ECidade\RecursosHumanos\ESocial\Entity\PagamentosRendimentosTrabalho;
use ECidade\RecursosHumanos\ESocial\Entity\RemuneracaoRGPS;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\RecursosHumanos\ESocial\Repository\PagamentosRendimentosTrabalho as PagamentosRendimentosTrabalhoRepository;
use ECidade\RecursosHumanos\Pessoal\Service\DataPagamentoFolhaService;
use ECidade\V3\Extension\Registry;
use ServidorRepository;
use Servidor;
use stdClass;
use DBCompetencia;
use CgmFisico;
use InstituicaoRepository;
use CalculoFolha;
use DBDate;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Class PagamentosRendimentosTrabalhoService
 * @package ECidade\RecursosHumanos\ESocial\Service
 */
class PagamentosRendimentosTrabalhoService
{
    /**
     * @var int
     */
    private $anoCompetencia;
    /**
     * @var int
     */
    private $mesCompetencia;
    /**
     * @var Instituicao
     */
    private $instituicaoSessao;
    /**
     * @var DBCompetencia|null
     */
    private $competencia;
    /**
     * @var array
     */
    private $rubricasValidas;
    /**
     * @var string
     */
    private $rubricaPensaoAlimenticia;

    /**
     * @var array
     */
    private $rubricasPensao = [];

    private $qtdServidores;

    /**
     * @var array
     */
    private $deParaTipoPagamento = [
        Tipo::S1200_API => 1,
        Tipo::S2299_API => 2,
        Tipo::S2399_API => 3,
        Tipo::S1202_API => 4,
        Tipo::S1207_API => 5
    ];

    /**
     * @var bool
     */
    private $isDecimoTerceiro = false;

    /**
     * @param CgmFisico $cgm
     * @return PagamentosRendimentosTrabalho|null
     * @throws \DBException
     */
    public function buscarPorCGM(CgmFisico $cgm, $tipoEvento, $servidores = null)
    {
        if (empty($this->anoCompetencia) || empty($this->mesCompetencia)) {
            throw new Exception("Competência não informada.");
        }

        $this->instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();
        $this->competencia = new DBCompetencia($this->anoCompetencia, $this->mesCompetencia);
        if (empty($servidores)) {
            $servidores = ServidorRepository::getServidoresByCgm($cgm, $this->competencia);
        } else {
            foreach ($servidores as $servidor) {
                $matriculas[] = $servidor->getMatricula();
            }
            $servidores = ServidorRepository::getServidoresByMatriculas(
                $this->anoCompetencia,
                $this->mesCompetencia,
                $matriculas,
                $this->instituicaoSessao->getCodigo()
            );
        }

        $pagamentos = array();

        foreach ($servidores as $indice => $servidor) {
            $this->addPagamentosRendimentos($servidor, $pagamentos, $tipoEvento);
           // $this->validaQuantidadeServidores($servidor);
        }

        $pagamentosRendimentosTrabalho = null;

        if (!empty($pagamentos)) {
            $pagamentosRendimentosTrabalho = new PagamentosRendimentosTrabalho();
            $pagamentosRendimentosTrabalho->setCPFBeneficiente($cgm->getCPF());
            $pagamentosRendimentosTrabalho->setPagamentos($pagamentos);
        }

        return $pagamentosRendimentosTrabalho;
    }

    /**
     *Adiciona os rendimentos dos eventos S-2299 ou S-2399.
     * @param $servidor
     * @param $pagamentos
     */
    private function addPagamentosRendimentos($servidor, &$pagamentos, $tipoEvento)
    {
        $pagamento = new stdClass();
        if ($servidor->isRpps()) {
            $tipoEvento = TIPO::S1202_API;
        } else {
            $tipoEvento = TIPO::S1200_API;
        }
        if ($servidor->isPensionista() || !$servidor->isAtivo()) {
            $tipoEvento = TIPO::S1207_API;
        }
        $pagamento->tpPgto = $this->deParaTipoPagamento[$tipoEvento];
        $pagamento->dtPgto = $this->buscarDataPagamentoFolha();

        $this->buscarDetalhamentoPagamentos($servidor, $pagamento);
        // Busca o pagamento do DECIMO TERCEIRO
        if ($this->mesCompetencia == 12 && !$servidor->isRescindidoCompetencia()) {
            $pagamentoDecimo = new stdClass();
            $pagamentoDecimo->tpPgto = $this->deParaTipoPagamento[$tipoEvento];
            // TODO
            $pagamentoDecimo->dtPgto = $this->buscarDataPagamentoFolha();
            $this->buscarDetalhamentoPagamentos($servidor, $pagamentoDecimo, true);
            if (!empty($pagamento)) {
                foreach ($pagamentoDecimo as $key => $value) {
                    $pagamento[$key] = $pagamentoDecimo[$key];
                }
            } else {
                if (!empty($pagamentoDecimo)) {
                    $pagamento = $pagamentoDecimo;
                }
            }
        }

        if (!empty($pagamentos)) {
            foreach ($pagamento as $key => $value) {
                $pagamentos[$key] = $pagamento[$key];
            }
        } else {
            if (!empty($pagamento)) {
                $pagamentos = $pagamento;
            }
        }
    }

    /**
     * @param int $anoCompetencia
     */
    public function setAnoCompetencia($anoCompetencia)
    {
        $this->anoCompetencia = $anoCompetencia;
    }

    /**
     * @param int $mesCompetencia
     */
    public function setMesCompetencia($mesCompetencia)
    {
        $this->mesCompetencia = $mesCompetencia;
    }

    /**
     * @return int
     */
    public function getAnoCompetencia()
    {
        return $this->anoCompetencia;
    }

    /**
     * @return int
     */
    public function getMesCompetencia()
    {
        return $this->mesCompetencia;
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
     * Define qual tipo de data de pagamento
     * @param string $tipoDataPagamento
     */
    public function setTipoDataPagamento($tipoDataPagamento)
    {
        $this->tipoDataPagamento = $tipoDataPagamento;
    }

    /**
     * Retorna a data de rescisão do servidor
     * @param $servidor
     * @return string
     */
    private function buscarDataPagamentoRescisao($servidor)
    {
        $dataRescisao = $servidor->getDataPagamentoRescisao();
        if (!empty($dataRescisao)) {
            $dataRescisao = $dataRescisao->format('Y-m-d');
        }
        return $dataRescisao;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function buscarDataPagamentoFolha()
    {
        $dataPagamentoService = new DataPagamentoFolhaService();
        $dataPagamentoService->setAnoCompetencia($this->anoCompetencia);
        $dataPagamentoService->setMesCompetencia($this->mesCompetencia);
        $stdParametros = new stdClass();
        $stdParametros->instituicao = $this->instituicaoSessao->getCodigo();

        $dataPagamentos = $dataPagamentoService->buscarDataPagamentoInstituicaoCompetencia($stdParametros);
        if (empty($dataPagamentos)) {
            throw new BusinessException("Folha de pagamento encontra-se aberta, sem informação da data de pagamento.");
        }
        return $dataPagamentos[0]->getDataPagamento()->getDate();
    }

    /**
     * Busca e monta os dados do detalhamento dos pagamentos de acordo com o layout do evento.
     * @param $servidor
     * @param $pagamento
     */
    private function buscarDetalhamentoPagamentos($servidor, &$pagamento, $decimo = false)
    {
        $identificadorRescisao = $servidor->getMatricula() . 'RESC' . $this->anoCompetencia . $this->mesCompetencia;

        $identificadorRGPS = $servidor->getCgm()->getCodigo() . $this->anoCompetencia . $this->mesCompetencia;
        $pagamentos = [];
        $matricula =  $servidor->getMatricula();

        $tipoPagamento = [
            1 => ['recibo' => (object) ['layout'=> 'S-1200', 'referencia'=> $identificadorRGPS],
                'identificador' => "{$matricula}SAL{$this->anoCompetencia}{$this->mesCompetencia}"],
            2 => ['recibo' => (object) ['layout' => 'S-2299', 'referencia'=> $identificadorRescisao],
                'identificador' => $identificadorRescisao],
            3 => ['recibo' => (object) ['layout'=> 'S-2399', 'referencia'=> $identificadorRescisao],
                'identificador' =>  "RescTSVE_{$this->anoCompetencia}{$this->mesCompetencia}"],
            4 => ['recibo' => (object) ['layout'=> 'S-1200', 'referencia'=> $identificadorRGPS],
                'identificador' => "{$matricula}SAL{$this->anoCompetencia}{$this->mesCompetencia}"],
            5 => ['recibo' => (object) ['layout'=> 'S-1200', 'referencia'=> $identificadorRGPS],
                'identificador' => "{$matricula}SAL{$this->anoCompetencia}{$this->mesCompetencia}"]
        ];
        $pagamento->ideDmDev = $tipoPagamento[$pagamento->tpPgto]['identificador'];
        $calculoFinanceiroDecimo = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_13o);
        // alterada validacao de 0 para -0.01 pois as vezes no calculo o retorno vem em
        // notacao cienticia negativa em valor
        // menor que -0.01 devido aos valores calculados, sendo considerado apenas o valor 0 no final

        if ($calculoFinanceiroDecimo->getValorLiquido() >= -0.01 &&
            !empty($calculoFinanceiroDecimo->getMovimentacoes())) {
            if ($this->mesCompetencia != 12 || $decimo) {
                $pagamentoDecimo = clone $pagamento;
                $pagamentoDecimo->ideDmDev = "{$matricula}ADIANT13{$this->anoCompetencia}{$this->mesCompetencia}";
                $pagamentoDecimo->perRef = "{$this->anoCompetencia}-{$this->mesCompetencia}";
                if ($this->isDecimoTerceiro) {
                    $pagamentoDecimo->perRef = "{$this->anoCompetencia}";
                    $pagamentoDecimo->dtPgto = '2022-12-20';
                    $pagamentoDecimo->ideDmDev = "{$matricula}DECIMO{$this->anoCompetencia}{$this->mesCompetencia}";
                    if ($servidor->isRpps()) {
                        $pagamentoDecimo->ideDmDev = "{$matricula}DECIMO{$this->mesCompetencia}";
                    }
                    $pagamentoDecimo->ideDmDev .= "2";
                }
                $pagamentoDecimo->vrLiq = $this->truncar($calculoFinanceiroDecimo->getValorLiquido());
                $pagamentos[$pagamentoDecimo->ideDmDev] = $pagamentoDecimo;
            }
        }

        $calculoFinanceiroSalario = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
        if ($calculoFinanceiroSalario->getValorLiquido() >= -0.01 && !$decimo &&
            !empty($calculoFinanceiroSalario->getMovimentacoes())) {
            $pagamentoSalario = clone $pagamento;
            $pagamentoSalario->vrLiq = $this->truncar($calculoFinanceiroSalario->getValorLiquido());
            $pagamentoSalario->ideDmDev = "{$matricula}SAL{$this->anoCompetencia}{$this->mesCompetencia}";
            $pagamentoSalario->perRef = "{$this->anoCompetencia}-{$this->mesCompetencia}";
            $pagamentos[$pagamentoSalario->ideDmDev] = $pagamentoSalario;
        }

        $calculoFinanceiroComplementar = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR);
        if ($calculoFinanceiroComplementar->getValorLiquido() >= -0.01 && !$decimo &&
            !empty($calculoFinanceiroComplementar->getMovimentacoes())) {
            $pagamentoComplementar = clone $pagamento;
            $pagamentoComplementar->ideDmDev = "{$matricula}COMP{$this->anoCompetencia}{$this->mesCompetencia}";
            $pagamentoComplementar->vrLiq = $this->truncar($calculoFinanceiroComplementar->getValorLiquido());
            $pagamentoComplementar->perRef = "{$this->anoCompetencia}-{$this->mesCompetencia}";
            $pagamentos[$pagamentoComplementar->ideDmDev] = $pagamentoComplementar;
        }

        $calculoFinanceiroRescisao = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_RESCISAO);
        if ($calculoFinanceiroRescisao->getValorLiquido() >= -0.01 && !$decimo &&
            !empty($calculoFinanceiroRescisao->getMovimentacoes())) {
            $competenciasRescisao = $servidor->getCompetenciasPagamentosRescisao();
            if ($competenciasRescisao[0]->anousu == $this->anoCompetencia
                && $competenciasRescisao[0]->mesusu == $this->mesCompetencia
            ) {
                $pagamentoRescisao = clone $pagamento;
                $pagamentoRescisao->ideDmDev = "{$matricula}RESC{$this->anoCompetencia}{$this->mesCompetencia}";
                $pagamentoRescisao->vrLiq = $this->truncar($calculoFinanceiroRescisao->getValorLiquido());
                $pagamentoRescisao->perRef = "{$this->anoCompetencia}-{$this->mesCompetencia}";
                $pagamentos[$pagamentoRescisao->ideDmDev] = $pagamentoRescisao;
                $dataObrigatoriedade = \DBPessoal::getDataFaseEsocial(3);
                // Se nao tiver configurada a data de obrigatoriedade, desconsideramos os dados
                if (empty($dataObrigatoriedade)) {
                    return false;
                }
                $dataFase3 = new DBDate("2022-08-22");
                // Verificamos de a data da fase 3 do grupo 4 é inferior a data de obrigatoriedade, se for inferior
                //  a instituicao pertence ao grupo 2
                if ($dataObrigatoriedade < $dataFase3) {
                    $pagamentoRescisao->ideDmDev = $servidor->getMatricula()
                        . $this->anoCompetencia . $this->mesCompetencia;
                    if ($servidor->temVinculoEmpregaticio()) {
                        $pagamentoRescisao->tpPgto =  $this->deParaTipoPagamento[TIPO::S2299_API];
                    } else {
                        $pagamentoRescisao->tpPgto =  $this->deParaTipoPagamento[TIPO::S2399_API];
                    }
                }
            } else {
                $pagamentoReposicao = clone $pagamento;
                $pagamentoReposicao->ideDmDev = "{$matricula}RESPOS{$this->anoCompetencia}{$this->mesCompetencia}";
                $pagamentoReposicao->vrLiq = $this->truncar($calculoFinanceiroRescisao->getValorLiquido());
                $pagamentoReposicao->perRef = "{$this->anoCompetencia}-{$this->mesCompetencia}";
                $pagamentos[$pagamentoReposicao->ideDmDev] = $pagamentoReposicao;
            }
        }
        if (!empty($pagamentos)) {
            $this->qtdServidores += 1;
            $pagamento = $pagamentos;
        } else {
            $pagamento = [];
        }
    }

    /**
     * Busca na api o número do recibo para os eventos S-2299 e S-2399 de acordo com a referencia informada
     * @param string $evento
     * @param string $referencia
     * @return string|null
     */
    private function buscarRecibo($evento, $referencia)
    {

        $body = new stdClass();
        $body->idReferencia = $referencia;
        $body->idEvento = $evento;
        $body->inscricaoEmpregador = $this->instituicaoSessao->getCNPJ();

        $service = new ESocial(Registry::get('app.config'), Recurso::CONSULTA_RECIBO);
        $service->setDados($body);

        $dados = $service->request('GET');
        if ($dados) {
            $dados = array_pop($dados);
            $dadosUltimoRecibo = $dados->recibo[0];
            return $dadosUltimoRecibo->numero;
        }

        return null;
    }

    public function setDecimoTerceiro()
    {
        $this->isDecimoTerceiro = true;
    }

    public function truncar($valor)
    {
        $valor = abs(round($valor, 6));
        $novoValor = (string) $valor;
        $novoValor = explode(".", $novoValor);
        if (sizeof($novoValor) > 1) {
            if (strlen($novoValor[1]) > 2) {
                $novoValor[1] = substr($novoValor[1], 0, 2);
                $valor = (float)($novoValor[0] . "." . $novoValor[1]);
            }
        }
        return $valor;
    }

    public function getQuantidadeServidores()
    {
        return $this->qtdServidores;
    }

    /** Metodo devera ser refeito futuramente pois estamos processando as folhas 2 vezes */
    private function validaQuantidadeServidores($servidor)
    {
        if (!empty($servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_13o))
            || !empty($servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_RESCISAO))
            || !empty($servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO))
            || !empty($servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR))
        ) {
            $this->qtdServidores += 1;
        }
    }

    private function retornaQuantidadeServidores($numeroCgm = null)
    {
        if (!empty($numeroCgm)) {
            $sql = "select count(*) as qtdServidores from pessoal.rhpessoal where rh01_numcgm = {$numeroCgm} ";
            $resultado = db_query($sql);
            if (pg_numrows($resultado) == 1) {
                return (int) \db_utils::fieldsMemory($resultado, 0)->qtdservidores;
            }
        }
        return 1;
    }
}
