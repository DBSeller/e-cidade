<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
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

namespace ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial;

use cl_rhpessoalprocessojudicialesocial;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\ProcessoJudicial;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Servidor as ServidorProcesso;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\MudancaRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\UnicidadeRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\VinculoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PeriodoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository as ServidorRepositoryProcesso;
use ECidade\RecursosHumanos\Pessoal\Repository\DependenteRepository;
use ECidade\RecursosHumanos\ESocial\Service\ServidorService;
use Cedencia;
use ECidade\RecursosHumanos\RH\PontoEletronico\Contrato\Model\ContratoJornada;
use ECidade\RecursosHumanos\Pessoal\Model\ContratoEmergencial;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\DataPagamentoFolhaRepository;
use Exception;
use ServidorRepository as ServidorFolhaRepository;
use stdClass;
use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use Admissao;
use db_stdClass;

class ProcessoJudicialRepository
{
    /**
     * @var array
     */
    private $scopes = [];

    /**
     * @var
     */
    private $instituicao ;

    /**
    * @var array
    */
    private $deParaTipoDependente = [
        'C' => '01',
        'F' => '03',
        'P' => '09',
        'M' => '09',
        'A' => '09',
        'O' => '99'
    ];

    /**
     * @var array
     */
    private $deParaRegimeTrabalho = [
        1 => 2,
        2 => 1,
        3 => 2
    ];

    /**
     * @var array
     */
    private $deParaUnidadePagamento = [
        'H' => 1,
        'D' => 2,
        'S' => 3,
        'Q' => 4,
        'M' => 5,
        '0' => 5,
        '1' => 5
    ];

    /**
     *
     * @param int $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh270_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     *
     * @param string $numeroProcesso
     * @param string $operator
     * @return $this
     */
    public function scopeNumeroProcesso($numeroProcesso)
    {
        $this->scopes['numeroProcesso'] = "rh270_nrproctrab = '{$numeroProcesso}'";
        return $this;
    }

    /**
     *
     * @param string $numeroProcesso
     * @param string $operator
     * @return $this
     */
    public function scopeNumerosProcessos($numeroProcesso)
    {
        $this->scopes['numeroProcesso'] = "rh270_nrproctrab ilike '%{$numeroProcesso}%'";
        return $this;
    }

    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = [];

        return $this;
    }

    /**
     * @param array|int $ids
     * @return int
     * @throws Exception
     */
    public static function destroy($ids)
    {
        $count = 0;
        $ids = is_array($ids) ? $ids : func_get_args();

        $self = new self();

        foreach ($ids as $id) {
            $self->delete(self::find($id));
            $count++;
        }

        return $count;
    }

    /**
     * @param ProcessoJudicial|null $processo
     * @throws Exception
     */
    public function delete(ProcessoJudicial $processo = null)
    {
        $id = $processo instanceof ProcessoJudicial ? $processo->getSequencial() : null;

        $dao = new cl_rhpessoalprocessojudicialesocial;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ProcessoJudicial
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_rhpessoalprocessojudicialesocial;
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o processo judiciail.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ProcessoJudicial::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessojudicialesocial;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $processo = [];

        if (pg_num_rows($rs) === 0) {
            return $processo;
        }

        while ($processoItem = pg_fetch_array($rs)) {
            $processo[] = ProcessoJudicial::fromState($processoItem);
        }
        
        return $processo;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessojudicialesocial;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $processo = [];

        if (pg_num_rows($rs) === 0) {
            return $processo;
        }

        while ($processoItem = pg_fetch_array($rs)) {
            $processo[] = ProcessoJudicial::fromState($processoItem);
        }
        
        return $processo;
    }


    /**
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessojudicialesocial;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o processo");
        }

        $processo = [];

        if (pg_num_rows($rs) === 0) {
            return $processo;
        }

        while ($processoJudicial = pg_fetch_array($rs)) {
            $processo[] = ProcessoJudicial::fromState($processoJudicial);
        }

        return $processo;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessojudicialesocial;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ProcessoJudicial $processo
     * @return ProcessoJudicial
     * @throws Exception
     */
    public function save(ProcessoJudicial $processo)
    {
        $dao = new cl_rhpessoalprocessojudicialesocial;
        $dao->rh270_sequencial = $processo->getSequencial();
        $dao->rh270_origem = $processo->getOrigem();
        $dao->rh270_nrproctrab = $processo->getNumeroProcesso();
        $dao->rh270_obsproctrab = $processo->getObservacaoProcesso();
        $dao->rh270_dtsent = $processo->getDataSentenca();
        $dao->rh270_ufvara = $processo->getUfVara();
        $dao->rh270_codmunic = $processo->getCodigoMunicipio();
        $dao->rh270_idvara = $processo->getIdentificacaoVara();
        $dao->rh270_dtccp = $processo->getDataCelebracaoAcordo();
        $dao->rh270_tpccp = $processo->getAmbitoCelebracaoAcordo();
        $dao->rh270_cnpjccp = $processo->getCnpjSindicato();

        $dao->rh270_sequencial ? $dao->alterar($processo->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o processo." . $dao->erro_msg);
        }

        $processo->setSequencial($dao->rh270_sequencial);

        return $processo;
    }

    /**
     * @param $mes
     * @param $ano
     * @param $columns
     * @return array $resultado
     * @throws Exception
     */
    public function getCompetenciaProcessos($mes, $ano, $columns = ['*'])
    {

        $dao = new cl_rhpessoalprocessojudicialesocial;
        $where = " (Extract(month from rh270_dtsent::DATE) = {$mes} and " .
            "Extract(year from rh270_dtsent::DATE) = {$ano})";
        $where .= " or (Extract(month from rh270_dtccp::DATE) = {$mes} and " .
            "Extract(year from rh270_dtccp::DATE) = {$ano})";
        $sql = $dao->sql_query(null, implode(', ', $columns), null, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o processo judicial com data setenca nesta data.");
        }

        $processosDadosIniciais = [];

        if (pg_num_rows($rs) === 0) {
            return $processosDadosIniciais;
        }

        while ($processoJudicial = pg_fetch_array($rs)) {
            $processosDadosIniciais[] = ProcessoJudicial::fromState($processoJudicial);
        }

        foreach ($processosDadosIniciais as $processoDadoInicial) {
            $processos = ProcessoJudicialRepository::getDadosComplemetaresProcesso($processoDadoInicial);
        }

        return $processos;
    }


    /**
    * @param $selecao
     * @return array $resultado
     * @throws Exception
     */
    public function getSelecaoProcessos($selecao)
    {
        $anoCompetencia = CompetenciaHelper::get()->getAno();
        $mesCompetencia = CompetenciaHelper::get()->getMes();
        $processos = [];
        $servidores = ServidorFolhaRepository::getServidoresBySelecao($anoCompetencia, $mesCompetencia, $selecao);
        $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
        $processoJudicialRepository = new ProcessoJudicialRepository();
        if (!empty($servidores)) {
            foreach ($servidores as $servidor) {
                if (!empty($servidor->getDadosRescisao()->rh05_recis)) {
                    $servidorRepositoryProcesso->resetScopes();
                    $processosServidor = $servidorRepositoryProcesso->scopeMatricula($servidor->getMatricula())->get();
                    if (!empty($processosServidor)) {
                        foreach ($processosServidor as $processoServidor) {
                            $processoJudicialRepository->resetScopes();
                            $processosDadosIniciais = $processoJudicialRepository
                                ->scopeSequencial($processoServidor->getSequencialProcesso())
                                ->get();
                        }
                    }
                }
            }
        }
      
        foreach ($processosDadosIniciais as $processoDadoInicial) {
            $processos = ProcessoJudicialRepository::getDadosComplemetaresProcesso($processoDadoInicial);
        }

        return $processos;
    }

        /**
    * @param $selecao
     * @return array $resultado
     * @throws Exception
     */
    public function getMatriculaProcessos($listaServidor)
    {
        $processos = [];
        $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
        $processoJudicialRepository = new ProcessoJudicialRepository();
        foreach ($listaServidor as $servidor) {
            if (empty($servidor->getDataRescisao())) {
                continue;
            }
            $servidorRepositoryProcesso->resetScopes();
            $processosServidor = $servidorRepositoryProcesso->scopeMatricula($servidor->getMatricula())->get();
            if (!empty($processosServidor)) {
                foreach ($processosServidor as $processoServidor) {
                    $processoJudicialRepository->resetScopes();
                    $processosDadosIniciais = $processoJudicialRepository
                        ->scopeSequencial($processoServidor->getSequencialProcesso())
                        ->get();
                }
            }
        }
    
        foreach ($processosDadosIniciais as $processoDadoInicial) {
            $processos = ProcessoJudicialRepository::getDadosComplemetaresProcesso($processoDadoInicial);
        }
        return $processos;
    }


    /**
     * @param $sequencialProcesso
     * @return array $resultado
     * @throws Exception
     */
    public static function getProcessoServidor($sequencialProcesso)
    {
        $servidorProcessoRepository = new ServidorRepositoryProcesso();
        $processoServidor = $servidorProcessoRepository
            ->scopeSequencial($sequencialProcesso)
            ->get();
        if ($processoServidor) {
            $sequencialProcesso = $processoServidor[0]->getSequencialProcesso();
        }
        $processoDado = null;
        if ($sequencialProcesso) {
            $dao = new cl_rhpessoalprocessojudicialesocial;
            $where = " rh270_sequencial = {$sequencialProcesso} ";
            $sql = $dao->sql_query(null, '*', null, $where);
            $rs = db_query($sql);
            $processoJudicial = pg_fetch_array($rs);
            $processoDado = ProcessoJudicial::fromState($processoJudicial);
        }
        return $processoDado;
    }

    /**
     * @param ProcessoJudicial $processoDadoInicial
     * @return ProcessoJudicial $processos
     * @throws Exception
     */
    public function getDadosComplemetaresProcesso($processoDadoInicial)
    {
        $processos = [];
        $this->instituicao = db_getsession("DB_instit");

        $sevidorProcessoRepository = new ServidorRepositoryProcesso();
        $servidoresProcesso = $sevidorProcessoRepository
            ->scopeSequencialProcesso($processoDadoInicial->getSequencial())
            ->get();
        if (empty($servidoresProcesso)) {
            throw new Exception("Não há servidores vinculados a processo judicial. Favor revisar.");
        }

        foreach ($servidoresProcesso as $servidorProcesso) {
            $servidor = $servidorProcesso->getServidorFolha();
            $processoDado = clone $processoDadoInicial;
            $processoDado->setMatricula($servidor->getMatricula());
            $processoDado->setCpfServidor($servidor->getCgm()->getCpf());
            $processoDado->setNomeServidor($servidor->getCgm()->getNome());
            $processoDado->setDataNascimento($servidor->getCgm()->getDataNascimento());
            //Contrato de Trabalho
            $contratoRepositoy = new ContratoRepository();
            $contratos = $contratoRepositoy
                ->scopeSequencialServidor($servidorProcesso->getSequencial())
                ->get();

            foreach ($contratos as $contrato) {
                $servidoresProcesso = $contrato->getServidorProcesso();
                $remuneracoes = [];
                foreach ($servidoresProcesso as $servidorProcesso) {
                    if ($servidorProcesso->getMatricula() == $servidor->getMatricula()) {
                        $remuneracoes = $servidorProcesso->getRemuneracao();
                    }
                }
 
                // Informações do contrato de trabalho.
                $dadosInformacoesContrato = new stdClass;
                $dadosInformacoesContrato->tpContr = (int) $contrato->getTipoContrato();
                $dadosInformacoesContrato->indContr = $contrato->getIndicativoContrato();
                $dadosInformacoesContrato->dtAdmOrig = $contrato->getDataAdmissaoOrigem();
                if ($contrato->getTipoContrato() != 6 &&
                    $contrato->getIndicativoContrato() == 'S') {
                    $dadosInformacoesContrato->indReint = $contrato->getIndicativoReintegracao();
                }

                $dadosInformacoesContrato->indCateg = $contrato->getIndicativoCategoria();
                $dadosInformacoesContrato->indNatAtiv = $contrato->getIndicativoNaturezaAtividade();
                $dadosInformacoesContrato->indMotDeslig = $contrato->getIndicativoMotivoDesligamento();

                if ($contrato->getIndicativoContrato() == 'N' || $contrato->getTipoContrato() == 9) {
                    $dadosInformacoesContrato->matricula = $servidorProcesso->getMatricula();
                }
                $dadosInformacoesContrato->codCateg = (int) $servidorProcesso->getCodigoCategoria();
                // $servidor->temVinculoEmpregaticio() && $servidor->isAtivo()
                // todo validação trabalhado sem vínculo
                if (!$servidor->temVinculoEmpregaticio()) {
                    $dadosInformacoesContrato->dtInicio = $contrato->getDataAdmissaoOrigem();
                }
                //Informações complementares do contrato de trabalho.
                if ($contrato->getIndicativoContrato() == 'N') {
                    if (!in_array((int) $servidorProcesso->getCodigoCategoria(), [901, 903, 904])) {
                        if (!empty($servidorProcesso->getCodigoCBO())) {
                            if (!isset($dadosInformacoesContrato->infoCompl)) {
                                $dadosInformacoesContrato->infoCompl = new stdClass;
                            }
                            $dadosInformacoesContrato->infoCompl->codCBO = (int) $servidorProcesso->getCodigoCBO();
                        }
                    }
                }
                if (!in_array((int) $servidorProcesso->getCodigoCategoria(), [721, 722, 771, 901])) {
                    if ((int) $servidor->getVinculo()->getCodigoCategoria() < 315 ||
                        in_array((int) $servidorProcesso->getCodigoCategoria(), [401, 731, 734, 738])) {
                        if (!isset($dadosInformacoesContrato->infoCompl)) {
                            $dadosInformacoesContrato->infoCompl = new stdClass;
                        }
                        $dadosInformacoesContrato->infoCompl->natAtividade =
                            (int) $contrato->getNaturezaAtividade();
                        if (empty($contrato->getNaturezaAtividade())) {
                            //1-Trabalho urbano 2-Trabalho rural
                            $dadosInformacoesContrato->infoCompl->natAtividade = 1;
                        }
                    }
                }
                if ((int) $servidorProcesso->getCodigoCategoria() == 104) {
                    if (!isset($dadosInformacoesContrato->infoCompl)) {
                        $dadosInformacoesContrato->infoCompl = new stdClass;
                    }
                    $dadosInformacoesContrato->infoCompl->natAtividade = 1;
                }
                if ((int) $servidorProcesso->getCodigoCategoria() == 102) {
                    if (!isset($dadosInformacoesContrato->infoCompl)) {
                        $dadosInformacoesContrato->infoCompl = new stdClass;
                    }
                    $dadosInformacoesContrato->infoCompl->natAtividade = 2;
                }

                //Informações da remuneração e periodicidade de pagamento.
                if (!empty($remuneracoes)) {
                    if (!isset($dadosInformacoesContrato->infoCompl)) {
                        $dadosInformacoesContrato->infoCompl = new stdClass;
                    }
                    $dadosInformacoesContrato->infoCompl->remuneracao = $remuneracoes;
                }

                //Informações sobre o vínculo trabalhista.
                $vinculoRepository = new VinculoRepository;
                $vinculo = $vinculoRepository
                    ->scopeSequencialServidor($servidorProcesso->getSequencial())
                    ->get();
                if (!empty($vinculo)) {
                    if (!isset($dadosInformacoesContrato->infoCompl->infoVinc)) {
                        $dadosInformacoesContrato->infoCompl->infoVinc = new stdClass;
                    }
                    $dadosInformacoesContrato->infoCompl->infoVinc->tpRegTrab =
                        (int) $vinculo[0]->getRegimeTrabalhista();
                    $dadosInformacoesContrato->infoCompl->infoVinc->tpRegPrev =
                        (int) $vinculo[0]->getRegimePrevidenciario();
                    if ((int) $vinculo[0]->getRegimePrevidenciario() == 0) {
                        $dadosInformacoesContrato->infoCompl->infoVinc->tpRegPrev = 2;
                        if ((int) $servidorProcesso->getCodigoCategoria() == 901) {
                            $dadosInformacoesContrato->infoCompl->infoVinc->tpRegPrev = 1;
                        }
                    }
                    $dadosInformacoesContrato->infoCompl->infoVinc->dtAdm =
                        $vinculo[0]->getDataAdmissao();
//                    if (!empty($servidorProcesso->getTipoContratoTempoParcial())) {
                        $dadosInformacoesContrato->infoCompl->infoVinc->tmpParc =
                            (int) $vinculo[0]->getTempoParcial();
//                    }
                }

                // Duração do contrato de trabalho.
                if (isset($dadosInformacoesContrato->infoCompl->infoVinc->tpRegTrab)) {
                    if ($dadosInformacoesContrato->infoCompl->infoVinc->tpRegTrab == 1) {
                        $duracaoRepository = new DuracaoRepository;
                        $duracao = $duracaoRepository
                            ->scopeSequencialVinculo($vinculo[0]->getSequencial())
                            ->get();
                        if (!empty($duracao)) {
                            if (!isset($dadosInformacoesContrato->infoCompl->infoVinc)) {
                                $dadosInformacoesContrato->infoCompl->infoVinc = new stdClass;
                            }
                            if (!isset($dadosInformacoesContrato->infoCompl->infoVinc->duracao)) {
                                $dadosInformacoesContrato->infoCompl->infoVinc->duracao = new stdClass;
                            }
                            $dadosInformacoesContrato->infoCompl->infoVinc->duracao->tpContr =
                                (int) $duracao[0]->getTipoContrato();
                            if (!empty($duracao[0]->getDataTerminoContrato())) {
                                $dadosInformacoesContrato->infoCompl->infoVinc->duracao->dtTerm =
                                    $duracao[0]->getDataTerminoContrato();
                            }
                            if (!empty($duracao[0]->getClausulaAssecuratoria())) {
                                if ($duracao[0]->getTipoContrato() != 1) {
                                    $dadosInformacoesContrato->infoCompl->infoVinc->duracao->clauAssec =
                                        $duracao[0]->getClausulaAssecuratoria();
                                }
                            }
                            if (!empty($duracao[0]->getObjetoDeterminante())) {
                                $dadosInformacoesContrato->infoCompl->infoVinc->duracao->objDet =
                                    $duracao[0]->getObjetoDeterminante();
                            }
                        }
                    }
                }


                //Informações do desligamento.
                $desligamentoRepository = new DesligamentoRepository;
                $desligamento = $desligamentoRepository
                    ->scopeSequencialVinculo($vinculo[0]->getSequencial())
                    ->get();
                if (!empty($desligamento)) {
                    if (!isset($dadosInformacoesContrato->infoCompl->infoVinc)) {
                        $dadosInformacoesContrato->infoCompl->infoVinc = new stdClass;
                    }
                    if (!isset($dadosInformacoesContrato->infoCompl->infoVinc->infoDeslig)) {
                        $dadosInformacoesContrato->infoCompl->infoVinc->infoDeslig = new stdClass;
                    }
                    $dadosInformacoesContrato->infoCompl->infoVinc->infoDeslig->dtDeslig =
                        $desligamento[0]->getDataDesligamento();
                    $dadosInformacoesContrato->infoCompl->infoVinc->infoDeslig->mtvDeslig =
                        $desligamento[0]->getMotivoDesligamento();
                    if (!empty($desligamento[0]->getDataFimAvisoPrevioIdenizado())) {
                        $dadosInformacoesContrato->infoCompl->infoVinc->infoDeslig->dtProjFimAPI =
                            $desligamento[0]->getDataFimAvisoPrevioIdenizado();
                    }
                }

                // Informações de término de TSVE.
                if ($dadosInformacoesContrato->tpContr == 6) {
                    if (!isset($dadosInformacoesContrato->infoCompl)) {
                        $dadosInformacoesContrato->infoCompl = new stdClass;
                    }
                    if (!isset($dadosInformacoesContrato->infoCompl)) {
                        $dadosInformacoesContrato->infoCompl->infoTerm = new stdClass;
                    }
                    $dadosInformacoesContrato->infoCompl->infoTerm->dtTerm = $servidor->getDataDemissao();
                }

                // Informação do novo código de categoria e/ou da nova natureza da atividade,
                // no caso de reconhecimento judicial nesse sentido.
                $mudancaRepository = new MudancaRepository();
                $contratosMudanca = $mudancaRepository
                    ->scopeSequencialContrato($contrato->getSequencial())
                    ->get();

                $registrosMudanca = [];
                foreach ($contratosMudanca as $contratoMudanca) {
                    $dados = new stdClass;
                    $dados->codCateg = (int) $contratoMudanca->getCodigoCategoria();
                    $dados->natAtividade = (int) $contratoMudanca->getNaturezaAtividade();
                    $dados->dtMudCategAtiv = $contratoMudanca->getDataMudancaCategoria();
                    $registrosMudanca[] = $dados;
                }
                if (!empty($registrosMudanca)) {
                    if (!isset($dadosInformacoesContrato->mudCategAtiv)) {
                        $dadosInformacoesContrato->mudCategAtiv = new stdClass;
                    }
                    $dadosInformacoesContrato->mudCategAtiv = $registrosMudanca;
                }

                // Informações dos vínculos/contratos incorporados, no caso de reconhecimento de unicidade contratual.
                $unicidadeRepository = new UnicidadeRepository;
                $contratosUnicidade = $unicidadeRepository
                    ->scopeSequencialContrato($contrato->getSequencial())
                    ->get();
                $registrosUnicidade = [];
                foreach ($contratosUnicidade as $contratoUnicidade) {
                    $dados = new stdClass;
                    $dados->matUnic = $contratoUnicidade->getMatriculaUnicidade();
                    $dados->codCateg = (int) $contratoUnicidade->getCodigoCategoriaUnicidade();
                    $dados->dtInicio = $contratoUnicidade->getDataInicioUnicidade();
                    if (empty($dados->matUnic) &&
                        empty($dados->codCateg) &&
                        empty($dados->dtInicio)) {
                            continue;
                    }
                    $registrosUnicidade[] = $dados;
                }
                if (!empty($registrosUnicidade)) {
                    if (!isset($dadosInformacoesContrato->unicContr)) {
                        $dadosInformacoesContrato->unicContr = new stdClass;
                    }
                    $dadosInformacoesContrato->unicContr = $registrosUnicidade;
                }

                if (!isset($dadosInformacoesContrato->ideEstab)) {
                    $dadosInformacoesContrato->ideEstab = new stdClass;
                }

                $numeroInscricaoInstituicao = "";
                $tipoInscricao = 1;
                if ($servidorProcesso
                        ->getServidorFolha()
                        ->getLocalTrabalhoPrincial()) {
                    $tipoInscricao = $servidorProcesso
                        ->getServidorFolha()
                        ->getLocalTrabalhoPrincial()
                        ->getTipoInscricao();
                    $numeroInscricaoInstituicao = $servidorProcesso
                        ->getServidorFolha()
                        ->getLocalTrabalhoPrincial()
                        ->getNumeroInscricao();
                    if ($tipoInscricao == "" || empty($tipoInscricao)) {
                        $tipoInscricao = 1;
                        $numeroInscricaoInstituicao = $servidorProcesso
                            ->getServidorFolha()
                            ->getLocalTrabalhoPrincial()
                            ->getInstituicao()
                            ->getCNPJ();
                    }
                }

                if (empty($numeroInscricaoInstituicao)) {
                    $dadoInstituicao    = new db_stdClass();
                    $numeroInscricaoInstituicao  = $dadoInstituicao->getDadosInstit()->cgc;
                }
                $dadosInformacoesContrato->ideEstab->tpInsc = (int) $tipoInscricao;
                $dadosInformacoesContrato->ideEstab->nrInsc = $numeroInscricaoInstituicao;
                if ($dadosInformacoesContrato->ideEstab->tpInsc == 1
                    && strlen($dadosInformacoesContrato->ideEstab->nrInsc) != 14) {
                        $dadosInformacoesContrato->ideEstab->nrInsc = null;
                }

                // Informações dos períodos e valores decorrentes de processo trabalhista.
                $dadosInformacoesContrato->ideEstab->infoVlr = new stdClass;

                $competenciaInicial = explode('-', $contrato->getCompetenciaInicial());

                $competenciaDadoInicial = $competenciaInicial[1] . '-' . $competenciaInicial[0];

                $dadosInformacoesContrato->ideEstab->infoVlr->compIni = $competenciaDadoInicial;

                $competenciaFinal = explode('-', $contrato->getCompetenciaFinal());
                $competenciaDadoFinal = $competenciaFinal[1] . '-' . $competenciaFinal[0];
                $dadosInformacoesContrato->ideEstab->infoVlr->compFim = $competenciaDadoFinal;

                $remuneracoes = $dadosInformacoesContrato->infoCompl->remuneracao;

                $dados = [];
                foreach ($remuneracoes as $itemRemuneracao) {
                    $dadosDataRemuneracao = explode('-', $itemRemuneracao->dtRemun);
                    $competenciaRemuneracao = $dadosDataRemuneracao[0] . '-' . $dadosDataRemuneracao[1];
                    $validaCompetencia = $this->getValidacaoCompetenciaNoPeriodo(
                        $competenciaDadoInicial,
                        $competenciaDadoFinal,
                        $competenciaRemuneracao
                    );
                    if ($validaCompetencia) {
                        $chave = $dadosDataRemuneracao[0] . '-' . $dadosDataRemuneracao[1];
                        if (array_key_exists($chave, $dados)) {
                            $valorAtual = (double) $itemRemuneracao->vrSalFx;
                            $valorAnterior = (double) $dados[$chave]->vrSalFx;
                            $soma = strval($valorAtual + $valorAnterior);
                            $dados[$chave]->vrSalFx = $this->truncate($soma, 2);
                        } else {
                            $itemRemuneracao->vrSalFx = $this->truncate($itemRemuneracao->vrSalFx, 2);
                            $dados[$chave] = $itemRemuneracao;
                        }
                    }
                }
                $remuneracao = [];
                foreach ($dados as $dado) {
                    $remuneracao[] =  $dado;
                }
                $dadosInformacoesContrato->infoCompl->remuneracao = $remuneracao;

                $dadosInformacoesContrato->ideEstab->infoVlr->indReperc = (int) $contrato->getIndicativoRepercussao();
                if ($contrato->getIndicativoIndenizacaoSD() == 'S') {
                    $dadosInformacoesContrato->ideEstab->infoVlr->indenSD = $contrato->getIndicativoIndenizacaoSD();
                }
                if ($contrato->getIndenizacaoAbono() == 'S') {
                    $dadosInformacoesContrato->ideEstab->infoVlr->indenAbono = $contrato->getIndenizacaoAbono();
                }

                if ($contrato->getIndenizacaoAbono() == 'S') {
                    $abonoRepository = new AbonoRepository;
                    $contratosUnicidade = $abonoRepository
                        ->scopeSequencialContrato($contrato->getSequencial())
                        ->get();
                    $registrosAbono = [];
                    foreach ($registrosAbono as $abono) {
                        $dados = new stdClass;
                        $dados->anoBase = $abono->getAnoAbono();
                        $registrosAbono[] = $dados;
                    }
                    $dadosInformacoesContrato->ideEstab->infoVlr->abono = new stdClass;
                    $dadosInformacoesContrato->ideEstab->infoVlr->abono = $registrosAbono;
                }

                // Identificação do período ao qual se referem as bases de cálculo.
                if ($contrato->getIndicativoRepercussao() == 1) {
                    $servidorPeriodoRepository = new PeriodoRepository();
                    $periodos = $servidorPeriodoRepository
                        ->scopeSequencialContrato($contrato->getSequencial())
                        ->get();
                    $registrosPeriodos = [];
                    foreach ($periodos as $itemPeriodo) {
                        $dados = new stdClass;
                        $dados->perRef = $itemPeriodo->getPeriodo();
                        $dados->baseCalculo = new stdClass;
                        $dados->baseCalculo->vrBcCpMensal = (float) $itemPeriodo
                            ->getValorBasePrevidenciaMensal();
                        $dados->baseCalculo->vrBcCp13 = (float) $itemPeriodo->getValorBasePrevidenciaMensal13();
                        $grauExposicao = (int) $itemPeriodo->getGrauExposicao();
                        $grauExposicao = in_array($grauExposicao, [0, 1, 5]) ? 1 : $grauExposicao;
        
                        $codigoCategoria = (int) $itemPeriodo
                            ->getProcessoContrato()[0]
                            ->getServidorProcesso()[0]
                            ->getCodigoCategoria();
                        if (($codigoCategoria > 300 && $codigoCategoria < 400) ||
                            in_array($codigoCategoria, [731, 734, 738])) {
                            if (!empty($grauExposicao)) {
                                $dados->baseCalculo->infoAgNocivo = new stdClass;
                                $dados->baseCalculo->infoAgNocivo->grauExp = $grauExposicao;
                            }
                        }
                        
                        if (!empty($itemPeriodo->getValorBaseFGTSProcesso())) {
                                $dados->infoFGTS = new stdClass;
                                $dados->infoFGTS->vrBcFGTSProcTrab = (float) $itemPeriodo->getValorBaseFGTSProcesso();
                                $dados->infoFGTS->vrBcFGTSSefip = (float) $itemPeriodo->getValorBaseFGTSSefip();
                                $dados->infoFGTS->vrBcFGTSDecAnt = (float) $itemPeriodo
                                    ->getValorBaseFGTSDeclaradaAnteriormente();
                        }
                        $registrosPeriodos[] = $dados;
                    }
                    $dadosInformacoesContrato->ideEstab->infoVlr->idePeriodo = new stdClass;
                    $dadosInformacoesContrato->ideEstab->infoVlr->idePeriodo = $registrosPeriodos;
                }
            }

            //REGRAS
            if (isset($dadosInformacoesContrato->infoCompl->infoVinc->duracao->tpContr)) {
                $tipoContrato = $dadosInformacoesContrato->infoCompl->infoVinc->duracao->tpContr;
                $tipoRegimeTrabalhista = $dadosInformacoesContrato->infoCompl->infoVinc->tpRegTrab;
                if ($tipoContrato !=6 && $tipoRegimeTrabalhista == 2) {
                    if (isset($dadosInformacoesContrato->infoCompl->remuneracao)) {
                        unset($dadosInformacoesContrato->infoCompl->remuneracao);
                    }
                }
            }
            if (!isset($dadosInformacoesContrato->infoCompl->infoVinc->duracao->tpContr) &&
                isset($dadosInformacoesContrato->infoCompl->remuneracao)) {
                unset($dadosInformacoesContrato->infoCompl->remuneracao);
            }
            if (!empty($dadosInformacoesContrato)) {
                $dadosContratos[0] = $dadosInformacoesContrato;
                $processoDado->setInformacaoContratoTrabalho($dadosContratos);
                $processos[] = $processoDado;
            }
        }
        return $processos;
    }

    /**
     * @return codigosMunicipioIBGE[]
     * @throws Exception
     */
    public static function getListaCodigoMunicipioIBGE()
    {
        $dao = new \cl_cadendermunicipiosistema();
        $campos = ['db125_codigosistema as codigoIBGE'];
        $where = 'db125_db_sistemaexterno = 4';
        $order = 'db125_codigosistema';

        $sql = $dao->sql_query(null, implode(', ', $campos), $order, $where);

        $rs = db_query($sql);

        $listaMunicipioIBGE = [];

        if (pg_num_rows($rs) === 0) {
            return $listaMunicipioIBGE;
        }

        $codigosMunicipiosIBGE = \db_utils::getCollectionByRecord($rs);

        foreach ($codigosMunicipiosIBGE as $codigoMunicipioIBGE) {
            $listaMunicipioIBGE[] = $codigoMunicipioIBGE->codigoibge;
        }

        return $listaMunicipioIBGE;
    }

    /**
     * @param $val, $f
     */
    private function truncate($val, $f = "0")
    {
        if (($p = strpos($val, '.')) !== false) {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }
        return $val;
    }

     /**
     * @param $competenciaInicial, $competenciaFinal, $competenciaComparada
     * @throws Exception
     * Formato AAAA-MM
     */
    private function getValidacaoCompetenciaNoPeriodo($competenciaInicial, $competenciaFinal, $competenciaComparada)
    {
        $retorna = false;
        $ano = false;
        $mes = false;
        $mesmoAnoFinal = false;
        $mesmoAnoInicial = false;
        $anoInicial = explode('-', $competenciaInicial)[0];
        $anoFinal = explode('-', $competenciaFinal)[0];
        $anoComparado = explode('-', $competenciaComparada)[0];
        $mesInicial = explode('-', $competenciaInicial)[1];
        $mesFinal = explode('-', $competenciaFinal)[1];
        $mesComparado = explode('-', $competenciaComparada)[1];
        if ($anoComparado > $anoInicial) {
            $ano = true;
        }
        if ($anoComparado == $anoInicial) {
            $ano = true;
            $mesmoAnoInicial = true;
        }
        if ($ano && ($anoComparado < $anoFinal)) {
            $mes = true;
        }
        if ($ano && ($anoComparado == $anoFinal)) {
            $mes = true;
            $mesmoAnoFinal = true;
        }
        if (($mesmoAnoInicial && ($mesComparado < $mesInicial))) {
            $mes = false;
        }

        if (($mesmoAnoFinal && ($mesComparado > $mesFinal))) {
            $mes = false;
        }
        if ($mes & $ano) {
            $retorna = true;
        }
        return $retorna;
    }
}
