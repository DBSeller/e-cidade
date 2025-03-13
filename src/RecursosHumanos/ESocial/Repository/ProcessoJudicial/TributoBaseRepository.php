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

use cl_rhprocessotributobase;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoBase;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository as ServidorRepositoryProcesso;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoContribuicaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoIRRFRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\V3\Extension\Registry;
use Exception;
use DBDate;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\DeducaoSuspensa;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\ValorRetencao;
use stdClass;

class TributoBaseRepository
{
    /**
     * @var array
     */
    private $scopes = [];

    /**
     * @param string $ano
     * @param string $mes
     * @return $this
     */
    public function scopeCompetenciaContemplado($ano = "", $mes = "")
    {
        $this->scopes['contemplado'] = "trim(rh288_peref) = '{$ano}-{$mes}'";
        return $this;
    }

    /**
     * @param string $ano
     * @param string $mes
     * @return $this
     */
    public function scopeCompetenciaPagamento($ano = "", $mes = "")
    {
        $this->scopes['pagamento'] = "trim(rh288_pagamento) = '{$ano}-{$mes}'";
        return $this;
    }

    /**
     * @param string $periodoReferencia
     * @param string $periodoPagamento
     * @return $this
     */
    public function scopePeriodos($periodoReferencia = '', $periodoPagamento = '')
    {
        $this->scopes['periodos'] = "trim(rh288_peref) = '{$periodoReferencia}' and " .
            "trim(rh288_pagamento) = '{$periodoPagamento}'";
        return $this;
    }


    /**
     * @param int $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh288_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $sequencialprocessoservidor
     * @return $this
     */
    public function scopeSequencialServidor($sequencialServidor, $operator = '=')
    {
        $this->scopes['servidor'] = "
            rh288_sequencialprocessoservidor {$operator} {$sequencialServidor}
        ";

        return $this;
    }

    /**
     * @param $matricula
     * @return $this
     */
    public function scopeMatriculaServidor($matricula, $operator = '=')
    {
        $this->scopes['matricula'] = "
            rh271_matricula {$operator} '{$matricula}'
        ";

        if ($operator == 'IN') {
            $this->scopes['matricula'] = "
                rh271_matricula IN ({$matricula})
            ";
        }
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
     * @param TributoBase|null $tributoBase
     * @throws Exception
     */
    public function delete(TributoBase $tributoBase = null)
    {
        $id = $tributoBase instanceof TributoBase ? $tributoBase->getSequencial() : null;

        $dao = new cl_rhprocessotributobase;

        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o tributo base mensal do servidor.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|TributoBase
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessotributobase;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);
 
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o tributo base mensal do servidor.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return TributoBase::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return TributoBase[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhprocessotributobase;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $tributoBase = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoBase;
        }

        while ($tributoBaseItem = pg_fetch_array($rs)) {
            $unicidade[] = TributoBase::fromState($tributoBaseItem);
        }
        
        return $tributoBase;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessotributobase;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $tributoBase = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoBase;
        }

        while ($tributoBaseItem = pg_fetch_array($rs)) {
            $tributoBase[] = TributoBase::fromState($tributoBaseItem);
        }
        
        return $tributoBase;
    }


    /**
     * @return TributoBase[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhprocessotributobase;
        $campos =  [
            'rh288_sequencial',
            'rh288_sequencialprocessoservidor',
            'rh288_peref',
            'rh288_pagamento',
            'rh288_vrbccpmensal',
            'rh288_vrbccp13',
            'rh288_vrrendirrf',
            'rh288_vrrendirrf13',
            'rh288_observacao',
            'rh270_nrproctrab',
            'rh271_matricula'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $tributoBase = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoBase;
        }

        while ($tributoBaseProcesso = pg_fetch_array($rs)) {
            $tributoBase[] = TributoBase::fromState($tributoBaseProcesso);
        }

        return $tributoBase;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhprocessotributobase;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) tributo(s) base do servidor(es).");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param TributoBase $tributoBase
     * @return TributoBase
     * @throws Exception
     */
    public function save(TributoBase $tributoBase)
    {
        $dao = new cl_rhprocessotributobase;
        $dao->rh288_sequencial                  = $tributoBase->getSequencial();
        $dao->rh288_sequencialprocessoservidor  = $tributoBase->getSequencialProcessoServidor();
        $dao->rh288_peref                       = $tributoBase->getCompetencia();
        $dao->rh288_pagamento                   = $tributoBase->getPagamento();
        $dao->rh288_vrbccpmensal                = $tributoBase->getValorBaseMensal();
        $dao->rh288_vrbccp13                    = $tributoBase->getValorBaseMensal13();
        $dao->rh288_vrrendirrf                  = $tributoBase->getValorBaseIRRF();
        $dao->rh288_vrrendirrf13                = $tributoBase->getValorBaseIRRF13();
        $dao->rh288_observacao                  = $tributoBase->getObservacao();
 
        $dao->rh288_sequencial ? $dao->alterar($tributoBase->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado a tributo base do servidor."
                . $dao->erro_msg);
        }

        $tributoBase->setSequencial($dao->rh288_sequencial);

        return $tributoBase;
    }

    /**
     * @param TributoBase $tributoBase
     * @return stdClass  dadosTributosProcesso
     * @throws Exception
     */
    public function dadosPreechimento(TributoBase $tributoBase, $indiceBase)
    {
        $dadosTributosProcesso = new stdClass();

        $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
        $processosServidor = $servidorRepositoryProcesso
            ->scopeSequencial($tributoBase->getSequencialProcessoServidor())
            ->get();

        $dadosIdeProc = new stdClass();
        $dadosIdeProc->nrProcTrab = $tributoBase->getNumeroProcesso();
        $dadosIdeProc->perApurPgto = $tributoBase->getPagamento();
        if (!empty($tributoBase->getObservacao())) {
            $dadosIdeProc->obs = $tributoBase->getObservacao();
        }
        $dadosTributosProcesso->ideProc = $dadosIdeProc;

        foreach ($processosServidor as $indiceSevidor => $processoServidor) {
            $servidor = $processoServidor->getServidorFolha();
            $dadosTributosProcesso->referencia = $servidor->getCgm()->getCpf() . '-' .
                str_replace('-', '', $tributoBase->getPagamento()) . '-' .
                str_replace('-', '', $tributoBase->getCompetencia());
            $dadosTributosProcesso->ideTrab[$indiceSevidor] = new stdClass();
            //Identificação do trabalhador.
            $dadosTributosProcesso->ideTrab[$indiceSevidor]->cpfTrab = $servidor->getCgm()->getCpf();
            // Identificação do período e da base de cálculo dos tributos.
            $dadosCalcTrib = new stdClass();
            $dadosCalcTrib->perRef = $tributoBase->getCompetencia();
            $dadosCalcTrib->vrBcCpMensal = (double) $tributoBase->getValorBaseMensal();
            $dadosCalcTrib->vrBcCp13 = (double) $tributoBase->getValorBaseMensal13();
            $dadosTributosProcesso->ideTrab[$indiceSevidor]->calcTrib[$indiceBase] = $dadosCalcTrib;
            
            $tributoContribuicaoRepository = new TributoContribuicaoRepository();
            $tributosContribuicao = $tributoContribuicaoRepository
                ->scopeSequencialBase($tributoBase->getSequencial())
                ->get();
            foreach ($tributosContribuicao as $indiceContribuicao => $tributoContribuicao) {
                // Informações das contribuições sociais devidas à Previdência Social e Outras Entidades e Fundos ...
                $dadosContribuicao = new stdClass();
                $dadosContribuicao->tpCR = $tributoContribuicao->getCodigoReceita();
                $dadosContribuicao->vrCR = (double) $tributoContribuicao->getValorContribuicao();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->calcTrib[$indiceBase]
                    ->infoCRContrib[$indiceContribuicao]
                        = $dadosContribuicao;
            }
            
            $tributoIRRFRepository = new TributoIRRFRepository();
            $tributosIRRF = $tributoIRRFRepository
                ->scopeSequencialServidor($processoServidor->getSequencial())
                ->get();
            foreach ($tributosIRRF as $indiceIRRF => $tributoIRRF) {
                // Informações de Imposto de Renda, por Código de Receita - CR.
                $dadosIRRF = new stdClass();
                $dadosIRRF->tpCR = str_pad($tributoIRRF->getCodigoReceita(), 6, '0', STR_PAD_LEFT);
                $dadosIRRF->vrCR = (double) $tributoIRRF->getValorIRRF();
                // Informações complementares, vinculadas ao infoCRIRRF/tpCR ...
                $dadosIRRF->infoIR = new stdClass();
                $dadosIRRF->infoIR->vrRendTrib = (double) $tributoIRRF->getValorRendimentoTributavel();
                $dadosIRRF->infoIR->vrRendTrib13 = (double) $tributoIRRF->getValorRendimentoTributavel13();
                $dadosIRRF->infoIR->vrRendMoleGrave = (double) $tributoIRRF->getValorRendimentoMolestia();
                $dadosIRRF->infoIR->vrRendIsen65 = (double) $tributoIRRF->getValorIsenta65();
                $dadosIRRF->infoIR->vrJurosMora = (double) $tributoIRRF->getValorJurosMora();
                $dadosIRRF->infoIR->vrRendIsenNTrib = (double) $tributoIRRF->getValorRendimentoIsento();
                $dadosIRRF->infoIR->descIsenNTrib = $tributoIRRF->getDescricaoIsento();
                $dadosIRRF->infoIR->vrPrevOficial = (double) $tributoIRRF->getValorPrevidenciaOficial();
                // Informações complementares relativas a Rendimentos Recebidos Acumuladamente - RRA.
                $dadosIRRF->infoIR->infoRRA = new stdClass();
                $dadosIRRF->infoIR->infoRRA->descRRA = $tributoIRRF->getDescricaoRendimentoAcumula();
                $dadosIRRF->infoIR->infoRRA->qtdMesesRRA = (int) $tributoIRRF->getQuantidadeMesAcumula();
                // Detalhamento das despesas com processo judicial.
                $dadosIRRF->infoIR->infoRRA->despProcJud = new stdClass();
                $dadosIRRF->infoIR->infoRRA->despProcJud->vlrDespCustas =
                    (double) $tributoIRRF->getValorDespesaCusta();
                $dadosIRRF->infoIR->infoRRA->despProcJud->vlrDespAdvogados =
                    (double) $tributoIRRF->getValorDespesaAdvogados();

                // Identificação dos advogados.
                $advogadoRepository = new AdvogadoRepository();
                $advogados = $advogadoRepository
                    ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                    ->get();
                foreach ($advogados as $indiceAdvogado => $advogado) {
                    $dadosIRRF->infoIR->ideAdv[$indiceAdvogado] = new stdClass();
                    $dadosIRRF->infoIR->ideAdv[$indiceAdvogado]->tpInsc = (int) $advogado->getTipoInscricao();
                    $dadosIRRF->infoIR->ideAdv[$indiceAdvogado]->nrInsc = $advogado->getNumeroInscricao();
                    $dadosIRRF->infoIR->ideAdv[$indiceAdvogado]->vlrAdv = (double) $advogado->getValorDespesa();
                }

                // Dedução do rendimento tributável relativa a dependentes.
                $dependenteRepository = new DependenteRepository();
                $dependentes = $dependenteRepository
                    ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                    ->get();
                foreach ($dependentes as $indiceDependente => $dependente) {
                    $dadosIRRF->infoIR->idedDepen[$indiceDependente] = new stdClass();
                    $dadosIRRF->infoIR->idedDepen[$indiceDependente]->tpRend =
                        (int) $dependente->getTipoRendimento();
                    $dadosIRRF->infoIR->idedDepen[$indiceDependente]->cpfDep =
                        $dependente->getCpfDependente();
                    $dadosIRRF->infoIR->idedDepen[$indiceDependente]->vlrDeducao =
                        (double) $dependente->getValorDeducao();
                }

                // Informação dos beneficiários da pensão alimentícia.
                $pensaoRepository = new PensaoRepository();
                $pensoes = $pensaoRepository
                    ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                    ->get();
                foreach ($pensoes as $indicePensao => $pensao) {
                    $dadosIRRF->infoIR->penAlim[$indicePensao] = new stdClass();
                    $dadosIRRF->infoIR->penAlim[$indicePensao]->tpRend = (int) $pensao->getTipoRendimento();
                    $dadosIRRF->infoIR->penAlim[$indicePensao]->cpfDep = $pensao->getCpfPensao();
                    $dadosIRRF->infoIR->penAlim[$indicePensao]->vlrPensao = (double) $pensao->getValorPensao();
                }

                // Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais.
                $retencaoRepository = new RetencaoRepository();
                $retencoes = $retencaoRepository
                    ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                    ->get();
                foreach ($retencoes as $indiceRetencao => $retencao) {
                    $dadosIRRF->infoIR->infoProcRet[$indiceRetencao] = new stdClass();
                    $dadosIRRF->infoIR->infoProcRet[$indiceRetencao]->tpProcRet =
                        (int) $retencao->getTipoProcesso();
                    $dadosIRRF->infoIR->infoProcRet[$indiceRetencao]->nrProcRet =
                        $retencao->getNumeroProcesso();
                    $dadosIRRF->infoIR->infoProcRet[$indiceRetencao]->codSusp =
                        $retencao->getCodigoIndicativoSuspensao();

                    // Informações de valores relacionados a não retenção de tributos ou a depósitos judiciais.
                    $valorRetencaoRepository = new ValorRetencaoRepository();
                    $valoresRetencao = $valorRetencaoRepository
                        ->scopeSequencialRetencao($retencao->getSequencial())
                        ->get();
                    foreach ($valoresRetencao as $indiceValorRetencao => $valorRetencao) {
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao] =
                                new stdClass();
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao]
                            ->indApuracao =
                                (int) $valorRetencao->getIndicativoApuracao();
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao]
                            ->vlrNRetido =
                                (double) $valorRetencao->getValorRetencao();
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao]
                            ->vlrDepJud =
                                (double) $valorRetencao->getValorDepositoJudicial();
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao]
                            ->vlrCmpAnoCal =
                                (double) $valorRetencao->getValorCompensacaoAno();
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao]
                            ->vlrCmpAnoAnt =
                                (double) $valorRetencao->getValorCompensacaoAnoAnterior();
                        $dadosIRRF
                            ->infoIR
                            ->infoProcRet[$indiceRetencao]
                            ->infoValores[$indiceValorRetencao]
                            ->vlrRendSusp =
                                (double) $valorRetencao->getValorRendimentoSuspenso();
                        // Detalhamento das deduções com exigibilidade suspensa.
                        $deducaoSuspensaRepository = new DeducaoSuspensaRepository();
                        $deducoesSuspensa = $deducaoSuspensaRepository
                            ->scopeSequencialValorRetencao($valorRetencao->getSequencial())
                            ->get();
                        foreach ($deducoesSuspensa as $indiceDeducaoSuspensa => $deducaoSuspensa) {
                            $dadosIRRF
                                ->infoIR
                                ->infoProcRet[$indiceRetencao]
                                ->infoValores[$indiceValorRetencao]
                                ->dedSusp[$indiceDeducaoSuspensa] =
                                    new stdClass();
                            $dadosIRRF
                                ->infoIR
                                ->infoProcRet[$indiceRetencao]
                                ->infoValores[$indiceValorRetencao]
                                ->dedSusp[$indiceDeducaoSuspensa]
                                ->indTpDeducao =
                                    (int) $deducaoSuspensa->getTipoDeducao();
                            $dadosIRRF
                                ->infoIR
                                ->infoProcRet[$indiceRetencao]
                                ->infoValores[$indiceValorRetencao]
                                ->dedSusp[$indiceDeducaoSuspensa]
                                ->vlrDedSusp =
                                    (double) $deducaoSuspensa->getTipoDeducao();

                            //Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia
                            $suspensaoPensaoRepository = new SuspensaoPensaoRepository();
                            $suspensoesPensao = $suspensaoPensaoRepository
                                ->scopeSequencialDeducaoSuspensa($deducaoSuspensa->getSequencial())
                                ->get();
                            foreach ($suspensoesPensao as $indiceSuspensaoPensao => $suspensaoPensao) {
                                $dadosIRRF
                                    ->infoIR
                                    ->infoProcRet[$indiceRetencao]
                                    ->infoValores[$indiceValorRetencao]
                                    ->dedSusp[$indiceDeducaoSuspensa]
                                    ->benefPen[$indiceSuspensaoPensao] =
                                        new stdClass();
                                $dadosIRRF
                                    ->infoIR
                                    ->infoProcRet[$indiceRetencao]
                                    ->infoValores[$indiceValorRetencao]
                                    ->dedSusp[$indiceDeducaoSuspensa]
                                    ->benefPen[$indiceSuspensaoPensao]
                                    ->cpfDep =
                                        $suspensaoPensao->getCpfDependente();
                                $dadosIRRF
                                    ->infoIR
                                    ->infoProcRet[$indiceRetencao]
                                    ->infoValores[$indiceValorRetencao]
                                    ->dedSusp[$indiceDeducaoSuspensa]
                                    ->benefPen[$indiceSuspensaoPensao]
                                    ->vlrDepenSusp =
                                        (double) $suspensaoPensao->getCpfDependente();
                            }
                        }
                    }
                }
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoCRIRRF[$indiceIRRF] =
                        $dadosIRRF;
            }
            // Informações relacionadas à retenção na fonte, aos rendimentos tributáveis e não tributáveis,
            // deduções e/ou isenções, etc., de acordo com a legislação aplicada ao imposto de renda.
            $tributoIRRFComplementarRepository = new TributoIRRFComplementarRepository();
            $tributoIRRFComplementares = $tributoIRRFComplementarRepository
                ->scopeSequencialServidor($processoServidor->getSequencial())
                ->get();
            $dataLaudo = "";
            $listaDataLaudo = [];
            $indiceDataLaudo = 0;
            foreach ($tributoIRRFComplementares as $indicetributoIRRFComplementar => $tributoIRRFComplementares) {
                $indiceDataLaudo = array_search($tributoIRRFComplementares->getDataLaudo(), $listaDataLaudo);
                if (!is_numeric($indiceDataLaudo)) {
                    $indiceDataLaudo = $indicetributoIRRFComplementar;
                    $listaDataLaudo[$indiceDataLaudo] = $tributoIRRFComplementares->getDataLaudo();
                    $dadosTributosProcesso
                        ->ideTrab[$indiceSevidor]
                        ->infoIRComplem[$indiceDataLaudo] =
                            new stdClass();
                }
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->dtLaudo =
                        $tributoIRRFComplementares->getDataLaudo();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar] =
                        new stdClass();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar]
                    ->cpfDep =
                        $tributoIRRFComplementares->getCpfDependente();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar]
                    ->dtNascto =
                        $tributoIRRFComplementares->getDataNascimento();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar]
                    ->nome =
                        $tributoIRRFComplementares->getNome();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar]
                    ->depIRRF =
                        $tributoIRRFComplementares->getIRRFDependenteTributavel();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar]
                    ->tpDep =
                        $tributoIRRFComplementares->getTipoDependente();
                $dadosTributosProcesso
                    ->ideTrab[$indiceSevidor]
                    ->infoIRComplem[$indiceDataLaudo]
                    ->infoDep[$indicetributoIRRFComplementar]
                    ->descrDep =
                        $tributoIRRFComplementares->getDescricaoDependencia();
            }
        }

        return $dadosTributosProcesso;
    }
}
