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

use ECidade\RecursosHumanos\ESocial\Entity\RemuneracaoRPPS;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorOutrosVinculosService;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorOperadoraSaudeService;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorProcessosJudiciaisFolhaService;
use ECidade\RecursosHumanos\ESocial\Service\TrabalhoIntermitenteService;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorOperadoraSaudeRepository;
use ServidorRepository;
use Servidor;
use stdClass;
use CgmBase;
use CalculoFolha;
use EventoFinanceiroFolha;
use DBCompetencia;
use DBPessoal;

/**
 * Class remuneracaoRPPSService
 * @package ECidade\RecursosHumanos\ESocial\Service
 */
class RemuneracaoRPPSService
{
    private $remuneracaoRPPS;

    private $remuneracoesRPPS = [];

    const SALARIO = "SALARIO";
    const COMPLEMENTAR = "COMPLEMENTAR";
    const DECIMO = "DECIMO";
    const RESCISAO = "RESCISAO";
    const RESCISAOPOSTERIOR = "RESCISAOPOSTERIOR";
    // informacoes de rescisao que deveram ser enviadas nesse evento
    const RESCISAOENVIADA = "RESC";

    const TIPOSALARIO = 0;
    const TIPOCOMPLEMENTAR = 1;
    const TIPODECIMO = 2;
    const TIPORESCISAO = 3;
    const TIPORESCISAOPOSTERIOR = 4;
    const TIPORESCISAOENVIADA = 5;

    /**
     * @var int
     */
    private $anoCompetencia;

    /**
     * @var int
     */
    private $mesCompetencia;

    public function __construct($ano = '', $mes = '')
    {

        if (empty($ano)) {
            $this->anoCompetencia = DBPessoal::getAnoFolha();
        } else {
            $this->anoCompetencia = $ano;
        }

        if (empty($mes)) {
            $this->mesCompetencia = DBPessoal::getMesFolha();
        } else {
            $this->mesCompetencia = $mes;
        }
    }

    /**
     * @param CgmBase $cgm
     * @return remuneracaoRPPS
     * @throws \BusinessException
     * @throws \DBException
     */
    public function buscarPorCGM(CgmBase $cgm)
    {
        $servidores = ServidorRepository::getServidoresByCgm(
            $cgm,
            new DBCompetencia($this->anoCompetencia, $this->mesCompetencia)
        );
        $quantidadeServidores = 0;
        foreach ($servidores as $servidor) {
            $matricula = $servidor->getMatricula();
            $this->remuneracaoRPPS = new remuneracaoRPPS();
            $this->remuneracaoRPPS->setServidor($servidor);
            $this->buscarOutrosVinculos($matricula);
            $this->buscarPagamento($servidor);
            $this->buscarDadosTrabalhador($servidores[0]);
            $possuiPagamento = false;
            foreach ($this->remuneracaoRPPS->getPagamentos() as $pagamento) {
                $remuneracao = clone $this->remuneracaoRPPS;
                $remuneracao->setPagamentos([$pagamento]);
                $this->remuneracoesRPPS[] = $remuneracao;
                $possuiPagamento = true;
            }

            if ($possuiPagamento) {
                $quantidadeServidores += 1;
            }
        }

        if (sizeof($this->remuneracaoRPPS) > 0) {
            foreach ($this->remuneracaoRPPS as &$remuneracao) {
                $remuneracao->quantidadeServidor = $quantidadeServidores;
            }
        }

        return $this->remuneracoesRPPS;
    }


    /**
     * @param CgmBase $cgm
     * @return boolean
     * @throws \BusinessException
     * @throws \DBException
     */
    public function validaRPPSPorCGM(CgmBase $cgm)
    {
        if ($cgm instanceof \CgmJuridico) {
            return false;
        }
        $servidores = ServidorRepository::getServidoresByCgm(
            $cgm,
            new DBCompetencia($this->anoCompetencia, $this->mesCompetencia)
        );
        foreach ($servidores as $servidor) {
            // Validamos se e RPPS
            if (!$servidor->isRgps()) {
                return true;
            }
            if (!$servidor->is1200()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $matricula
     * @throws \Exception
     */
    private function buscarOutrosVinculos($matricula)
    {

        $parametros = new stdClass();
        $parametros->matricula = $matricula;

        $serviceOutrosVinculos = new ServidorOutrosVinculosService();
        $serviceOutrosVinculos->setAnoCompetencia($this->anoCompetencia);
        $serviceOutrosVinculos->setMesCompetencia($this->mesCompetencia);
        $servidorOutrosVinculos = $serviceOutrosVinculos->buscarOutrosVinculosPorMatriculaCompetencia($parametros);
        $this->remuneracaoRPPS->setServidorOutrosVinculos($servidorOutrosVinculos);
    }


    /**
     * @param Servidor $servidor
     * @throws \BusinessException
     * @throws \DBException
     */
    private function buscarPagamento(Servidor $servidor)
    {

        $calculoFinanceiroSalario = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
        $calculoFinanceiroComplementar = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR);
        $calculoFinanceiroDecimoTerceiro = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_13o);
        $calculoFinanceiroRescisao = $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_RESCISAO);

        /**
         * Índice:
         * 0 - CalculoFolha::CALCULO_SALARIO
         * 1 - CalculoFolha::CALCULO_COMPLEMENTAR
         * 2 - CalculoFolha::CALCULO_13
         * 3 - CalculoFolha::CALCULO_RESCISAO
         */
        $pagamentos = array();

        foreach ($calculoFinanceiroSalario->getEventosFinanceiros() as $eventoFinanceiro) {
            $rubrica = new stdClass();
            $rubrica->codigo = $eventoFinanceiro->getRubrica()->getCodigo();
            $rubrica->descricao = $eventoFinanceiro->getRubrica()->getDescricao();
            $rubrica->quantidade = $eventoFinanceiro->getQuantidade();
            $rubrica->valor = $eventoFinanceiro->getValor();
            $rubrica->tipo = $eventoFinanceiro->getRubrica()->getTipo();
            $rubrica->descricaoTipo = $eventoFinanceiro->getRubrica()->getTipo() == EventoFinanceiroFolha::PROVENTO
                ? "Provento" : "Desconto";
            $rubrica->decimoTerceiro = false;
            $rubrica->nomePagamento = self::SALARIO;
            $pagamentos[self::TIPOSALARIO][] = $rubrica;
        }

        foreach ($calculoFinanceiroComplementar->getEventosFinanceiros() as $eventoFinanceiro) {
            $rubrica = new stdClass();
            $rubrica->codigo = $eventoFinanceiro->getRubrica()->getCodigo();
            $rubrica->descricao = $eventoFinanceiro->getRubrica()->getDescricao();
            $rubrica->quantidade = $eventoFinanceiro->getQuantidade();
            $rubrica->valor = $eventoFinanceiro->getValor();
            $rubrica->tipo = $eventoFinanceiro->getRubrica()->getTipo();
            $rubrica->descricaoTipo = $eventoFinanceiro->getRubrica()->getTipo() == EventoFinanceiroFolha::PROVENTO
                ? "Provento" : "Desconto";
            $rubrica->decimoTerceiro = false;
            $rubrica->nomePagamento = self::COMPLEMENTAR;

            $pagamentos[self::TIPOCOMPLEMENTAR][] = $rubrica;
        }

        foreach ($calculoFinanceiroDecimoTerceiro->getEventosFinanceiros() as $eventoFinanceiro) {
            $rubrica = new stdClass();
            $rubrica->codigo = $eventoFinanceiro->getRubrica()->getCodigo();
            $rubrica->descricao = $eventoFinanceiro->getRubrica()->getDescricao();
            $rubrica->quantidade = $eventoFinanceiro->getQuantidade();
            $rubrica->valor = $eventoFinanceiro->getValor();
            $rubrica->tipo = $eventoFinanceiro->getRubrica()->getTipo();
            $rubrica->decimoTerceiro = true;
            $rubrica->descricaoTipo = $eventoFinanceiro->getRubrica()->getTipo() == EventoFinanceiroFolha::PROVENTO
                ? "Provento" : "Desconto";
            $rubrica->nomePagamento = self::DECIMO;
            $pagamentos[self::TIPODECIMO][] = $rubrica;
        }

        $dataObrigatoriedade = \DBPessoal::getDataFaseEsocial(3);
        // Se nao tiver configurada a data de obrigatoriedade, desconsideramos os dados
        if (empty($dataObrigatoriedade)) {
            throw new \BusinessException("Data da fase 3 não configurada.");
        }
        $dataFase3 = new \DBDate("2022-08-22");
        // Verificamos de a data da fase 3 do grupo 4 é inferior a data de obrigatoriedade, se for inferior
        //  a instituicao pertence ao grupo 2
        // Caso seja grupo 2, não devemos enviar as verbas rescisorias nesse evento, caso contrario, sera enviada
        $validacao = false;
        if ($dataObrigatoriedade >= $dataFase3) {
            $validacao = true;
        }
        if ($validacao || (!$validacao && $servidor->validaCategoriaRescisaoSemVinculo())) {
            foreach ($calculoFinanceiroRescisao->getEventosFinanceiros() as $eventoFinanceiro) {
                $rubrica = new stdClass();
                $rubrica->codigo = $eventoFinanceiro->getRubrica()->getCodigo();
                $rubrica->descricao = $eventoFinanceiro->getRubrica()->getDescricao();
                $rubrica->quantidade = $eventoFinanceiro->getQuantidade();
                $rubrica->valor = $eventoFinanceiro->getValor();
                $rubrica->tipo = $eventoFinanceiro->getRubrica()->getTipo();
                $rubrica->decimoTerceiro = false;
                $rubrica->descricaoTipo = $eventoFinanceiro->getRubrica()->getTipo() == EventoFinanceiroFolha::PROVENTO
                    ? "Provento" : "Desconto";
                $rubrica->nomePagamento = self::RESCISAO;

                if ($servidor->isRescindido()) {
                    $competenciasRescisao = $servidor->getCompetenciasPagamentosRescisao();
                    if (sizeof($competenciasRescisao) >= 1) {
                        foreach ($competenciasRescisao as $key => $value) {
                            if ($key > 0) {
                                if ($servidor->getAnoCompetencia() == (int) $value->anousu
                                    && $servidor->getMesCompetencia() == (int) $value->mesusu) {
                                        $rubrica->nomePagamento = self::RESCISAOPOSTERIOR;
                                        break;
                                }
                            } else {
                                // Caso a rescisao deva ser enviada no S1200 ao inves do S2299/S2399
                                if ($servidor->validaCategoriaRescisao()) {
                                    $rubrica->nomePagamento = self::RESCISAOENVIADA;
                                }
                            }
                        }
                    }
                }

                if ($servidor->validaCategoriaRescisao()) {
                    $pagamentos[self::TIPORESCISAOENVIADA][] = $rubrica;
                } else {
                    $pagamentos[self::TIPORESCISAO][] = $rubrica;
                }
            }
        }

        $this->remuneracaoRPPS->setPagamentos($pagamentos);
    }

    /**
     * @param Servidor $servidor
     */
    private function buscarDadosTrabalhador(Servidor $servidor)
    {
        $cgm = $servidor->getCgm();
        $dadosTrabalhador = new stdClass();
        $dadosTrabalhador->nome = $cgm->getNomeCompleto();
        $dadosTrabalhador->cpf = $cgm->getCpf();
        $dadosTrabalhador->nascimento = $cgm->getDataNascimento();

        $this->remuneracaoRPPS->setDadosTrabalhador($dadosTrabalhador);
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

    public function getAnoCompetencia()
    {
        return $this->anoCompetencia;
    }

    public function getMesCompetencia()
    {
        return $this->mesCompetencia;
    }

    public function retornaQuantidadeMatricula($matricula = null)
    {
        if (!empty($matricula)) {
            $sql = "
            with registro_cgm as (
                select
                    rh01_numcgm
                from
                    pessoal.rhpessoal
                where
                    rh01_regist = {$matricula} )
                select
                    count(*) as qtdServidores
                from
                    pessoal.rhpessoal
                where
                    rh01_numcgm in (
                    select
                        rh01_numcgm
                    from
                        registro_cgm ) ";
            $resultado = db_query($sql);
            if (pg_numrows($resultado) == 1) {
                return (int) \db_utils::fieldsMemory($resultado, 0)->qtdservidores;
            }
        }
        return 1;
    }

    public function buscarRemuneracaoPeriodoAnterior($matricula)
    {
        $reajuste = new stdClass();
        $reajuste->dtAcConv = "";
        $reajuste->tpAcConv = "";
        $reajuste->dsc = "";

        $reajusteSalarial  = new \cl_rhreajustesalarialesocial();
        $camposReajusteSalarial = "eso39_dataefeito, eso39_tipo, eso39_descricao";
        $whereReajuste = "eso39_matricula =  {$matricula} ";
        $whereReajuste .= "and extract(year from eso39_dataefeito) <= {$this->anoCompetencia}";
        $whereReajuste .= "and extract(month from eso39_dataefeito) <= {$this->mesCompetencia}";
        $ordemReajuste = "eso39_sequencial desc";
        $sqlReajusteSalarial  = $reajusteSalarial->sql_query(
            null,
            $camposReajusteSalarial,
            $ordemReajuste,
            $whereReajuste
        );
        $rsReajusteSalarial = db_query($sqlReajusteSalarial);

        if (pg_num_rows($rsReajusteSalarial) > 0) {
            $dadosReajuste = \db_utils::fieldsMemory($rsReajusteSalarial, 0);
            $reajuste->dtAcConv = $dadosReajuste->eso39_dataefeito;
            $reajuste->tpAcConv = $dadosReajuste->eso39_tipo;
            $reajuste->dsc = $dadosReajuste->eso39_descricao;
        }
        return $reajuste;
    }

    public function buscarPeriodoAnterior($matricula)
    {
        $grupoIdePeriodo = new stdClass();
        $grupoIdePeriodo->perRef = '';

        $reajusteSalarial  = new \cl_rhreajustesalarialesocial();
        $camposReajusteSalarial = "eso39_dataefeito, eso39_tipo, eso39_descricao";
        $whereReajuste = "eso39_matricula =  {$matricula} ";
        $whereReajuste .= "and extract(year from eso39_dataefeito) <= {$this->anoCompetencia}";
        $whereReajuste .= "and extract(month from eso39_dataefeito) <= {$this->mesCompetencia}";
        $ordemReajuste = "eso39_sequencial desc";
        $sqlReajusteSalarial  = $reajusteSalarial->sql_query(
            null,
            $camposReajusteSalarial,
            $ordemReajuste,
            $whereReajuste
        );

        $rsReajusteSalarial = db_query($sqlReajusteSalarial);

        if (pg_num_rows($rsReajusteSalarial) > 0) {
            $dadoCompetenciaPeriodo = \db_utils::fieldsMemory($rsReajusteSalarial, 0);
            $competenciaPeriodo = explode('-', $dadoCompetenciaPeriodo->eso39_dataefeito);
            if (!empty($competenciaPeriodo[1])) {
                $grupoIdePeriodo->perRef  = $competenciaPeriodo[0] . '-' . $competenciaPeriodo[1];
            }
        }
        return  $grupoIdePeriodo;
    }
}
