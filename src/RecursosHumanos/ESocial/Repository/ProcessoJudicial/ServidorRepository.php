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

use cl_rhpessoalprocessoservidor;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Servidor as ServidorProcesso;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\MudancaRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\UnicidadeRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PeriodoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoIRRFComplementarRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoIRRFRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\AdvogadoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DependenteRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PensaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\RetencaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ValorRetencaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DeducaoSuspensaRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\VinculoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DuracaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ObservacaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\EstatutarioRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\DataPagamentoFolhaRepository;
use Exception;
use DBDate;
use cl_cgm;
use cl_rhpessoal;
use stdClass;

class ServidorRepository
{
       /**
     * @var array
     */
    private $scopes = [];


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
     * @param int $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh271_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialProcesso($sequencialProcesso, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh271_sequencialprocesso {$operator} {$sequencialProcesso}";
        return $this;
    }


    /**
     * @param array $matricula
     * @param string $operator
     * @return $this
     */
    public function scopeMatriculas($matricula)
    {
        $this->scopes['matricula'] = "rh271_matricula IN ({$matricula})";
        return $this;
    }

    /**
     * @param int $matricula
     * @param string $operator
     * @return $this
     */
    public function scopeMatricula($matricula, $operator = '=')
    {
        $this->scopes['matricula'] = "rh271_matricula {$operator} {$matricula}";
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
     * @param $key
     * @return $this
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

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
     * @param ServidorProcesso|null $processo
     * @throws Exception
     */
    public function delete(ServidorProcesso $servidor = null)
    {
        $id = $servidor instanceof ServidorProcesso ? $servidor->getSequencial() : null;
        $dao = new cl_rhpessoalprocessoservidor;

        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o servidor.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ServidorProcesso
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) servidor(es).");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ServidorProcesso::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ServidorProcesso[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $processo = array();

        if (pg_num_rows($rs) === 0) {
            return $processo;
        }

        while ($processoServidor = pg_fetch_array($rs)) {
            $processo[] = ServidorProcesso::fromState($processoServidor);
        }

        return $processo;
    }

    /**
     * @return ServidorProcesso[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o servidor");
        }

        $processo = [];

        if (pg_num_rows($rs) === 0) {
            return $processo;
        }

        while ($processoServidor = pg_fetch_array($rs)) {
            $processo[] = ServidorProcesso::fromState($processoServidor);
        }

        return $processo;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) servidor(s).");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ServidorProcesso $servidor
     * @return ServidorProcesso
     * @throws Exception
     */
    public function save(ServidorProcesso $servidor)
    {
        $dao = new cl_rhpessoalprocessoservidor;
        $dao->rh271_sequencial = $servidor->getSequencial();
        $dao->rh271_sequencialprocesso = $servidor->getSequencialProcesso();
        $dao->rh271_matricula = $servidor->getMatricula();
        $dao->rh271_instit = $servidor->getCodigoInstituicao();
        $dao->rh271_codcateg = $servidor->getCodigoCategoria();

        $dao->rh271_sequencial  ? $dao->alterar($servidor->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o servidor." . $dao->erro_msg);
        }

        $servidor->setSequencial($dao->rh271_sequencial);

        return $servidor;
    }

    /**
     * @param ServidorProcesso
     * @return int
     */
    public static function getExisteSequencialServidorProcesso(ServidorProcesso $servidor)
    {
        $sequencialProcesso = $servidor->getSequencialProcesso();
        $matricula = $servidor->getMatricula();
        $instituição = $servidor->getCodigoInstituicao();
        $codigoCategoria = $servidor->getCodigoCategoria();

        $where = [
            "rh271_sequencialprocesso  = {$sequencialProcesso}",
            "rh271_matricula = {$matricula}",
            "rh271_codcateg = {$codigoCategoria}",
            "rh271_instit = {$instituição}",
        ];
       

        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query(null, 'rh271_sequencial', null, implode(' AND ', $where));

        $rs = db_query($sql);

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        $resultado = pg_fetch_array($rs);

        return $resultado['rh271_sequencial'];
    }

    /**
     * @param int | $sequencialProcesso
     */
    public static function getServidoresProcessos($sequencialProcesso)
    {
        $campos = [
            "rh271_sequencial as sequencial",
            "rh271_matricula as matricula"
        ];
        
        $where = [
            "rh271_sequencialprocesso  = {$sequencialProcesso}",
        ];
       

        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $where));

        $rs = db_query($sql);

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        $resultado = [];
        for ($row = 0; $row < pg_num_rows($rs); $row++) {
            $resultado[] = \db_utils::fieldsMemory($rs, $row);
        }

        return $resultado;
    }

    /**
     * @return ServidorProcesso | null
     */
    public function getServidor()
    {
        $dao = new cl_rhpessoalprocessoservidor;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        $processo = null;

        if (pg_num_rows($rs)) {
            $servidorProcesso = pg_fetch_array($rs);
            $processo = ServidorProcesso::fromState($servidorProcesso);
        }

        return $processo;
    }

    /**
     * @return $servidorVinculadoExluido
     */
    public function setServidorVinculadoExcluir(
        $sequencialServidorExcluir = null,
        $sequencialServidorProcesso = null,
        $matricula = null
    ) {

        $servidorVinculadoExluido = true;

        $servidorVinculo = $this
            ->scopeSequencial($sequencialServidorExcluir)
            ->scopeSequencialProcesso($sequencialServidorProcesso)
            ->scopeMatricula($matricula)
            ->getServidor();

        if (!empty($servidorVinculo)) {
            $contratoRepository = new ContratoRepository();
            $contratos = $contratoRepository
                ->scopeSequencialServidor($servidorVinculo->getSequencial())
                ->get();
            if (!empty($contratos)) {
                foreach ($contratos as $contrato) {
                    $mudancaRepository = new MudancaRepository();
                    $mudancas = $mudancaRepository
                        ->scopeSequencialContrato($contrato->getSequencial())
                        ->get();
                    if (!empty($mudancas)) {
                        foreach ($mudancas as $mudanca) {
                            $mudancaRepository->delete($mudanca);
                        }
                    }
    
                    $unicidadeRepository = new UnicidadeRepository();
                    $unicidades = $unicidadeRepository
                        ->scopeSequencialContrato($contrato->getSequencial())
                        ->get();
                    if (!empty($unicidades)) {
                        foreach ($unicidades as $unicidade) {
                            $unicidadeRepository->delete($unicidade);
                        }
                    }
    
                    $periodoRepository = new PeriodoRepository();
                    $periodos = $periodoRepository
                        ->scopeSequencialContrato($contrato->getSequencial())
                        ->get();
                    if (!empty($periodos)) {
                        foreach ($periodos as $periodo) {
                            $periodoRepository->delete($periodo);
                        }
                    }
    
                    $anoAbonoRepository = new AbonoRepository();
                    $anoAbonos = $anoAbonoRepository
                        ->scopeSequencialContrato($contrato->getSequencial())
                        ->get();
                    if (!empty($anoAbonos)) {
                        foreach ($anoAbonos as $anoAbono) {
                            $anoAbonoRepository->delete($anoAbono);
                        }
                    }
    
                    $remuneracaoRepository = new RemuneracaoRepository();
                    $remuneracoes = $remuneracaoRepository
                        ->scopeSequencialVinculo($contrato->getSequencial())
                        ->get();
                    if (!empty($remuneracoes)) {
                        foreach ($remuneracoes as $remuneracao) {
                            $remuneracaoRepository->delete($remuneracao);
                        }
                    }

                    $contratoRepository->delete($contrato);
                }
            }

            
            $vinculoRepository = new VinculoRepository();
            $vinculos = $vinculoRepository
                ->scopeSequencialServidor($servidorVinculo->getSequencial())
                ->get();
            if (!empty($vinculos)) {
                foreach ($vinculos as $vinculo) {
                    $duracaoRepository = new DuracaoRepository();
                    $duracoes = $duracaoRepository
                        ->scopeSequencialVinculo($vinculo->getSequencial())
                        ->get();
                    if (!empty($duracoes)) {
                        foreach ($duracoes as $duracao) {
                            $duracaoRepository->delete($duracao);
                        }
                    }

                    $observacaoRepository = new ObservacaoRepository();
                    $observacoes = $observacaoRepository
                        ->scopeSequencialVinculo($vinculo->getSequencial())
                        ->get();
                    if (!empty($observacoes)) {
                        foreach ($observacoes as $observacao) {
                            $observacaoRepository->delete($observacao);
                        }
                    }

                    $estatutarioRepository = new EstatutarioRepository();
                    $estatutarios = $estatutarioRepository
                        ->scopeSequencialVinculo($vinculo->getSequencial())
                        ->get();
                    if (!empty($estatutarios)) {
                        foreach ($estatutarios as $estatutario) {
                            $estatutarioRepository->delete($estatutario);
                        }
                    }

                    $desligamentoRepository = new DesligamentoRepository();
                    $desligamentos = $desligamentoRepository
                        ->scopeSequencialVinculo($vinculo->getSequencial())
                        ->get();
                    if (!empty($desligamentos)) {
                        foreach ($desligamentos as $desligamento) {
                            $desligamentoRepository->delete($desligamento);
                        }
                    }
                    $vinculoRepository->delete($vinculo);
                }
            }


            //S-2501 - Início
            $tributoIRRFRepository = new TributoIRRFRepository();
            $tributoIRRFs = $tributoIRRFRepository
                ->scopeSequencialServidor($servidorVinculo->getSequencial())
                ->get();

            if (!empty($tributoIRRFs)) {
                foreach ($tributoIRRFs as $tributoIRRF) {
                    $advogadoRepository = new AdvogadoRepository();
                    $advogados = $advogadoRepository
                        ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                        ->get();
                    if (!empty($advogados)) {
                        foreach ($advogados as $advogado) {
                            $advogadoRepository->delete($advogado);
                        }
                    }

                    $dependenteRepository = new DependenteRepository();
                    $dependentes = $dependenteRepository
                        ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                        ->get();
                    if (!empty($dependentes)) {
                        foreach ($dependentes as $dependente) {
                            $dependenteRepository->delete($dependente);
                        }
                    }

                    $pensaoRepository = new PensaoRepository();
                    $pensoes = $pensaoRepository
                        ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                        ->get();
                    if (!empty($pensoes)) {
                        foreach ($pensoes as $pensao) {
                            $pensaoRepository->delete($pensao);
                        }
                    }

                    $retencaoRepository = new RetencaoRepository();
                    $retencoes = $retencaoRepository
                        ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                        ->get();
                    if (!empty($retencoes)) {
                        foreach ($retencoes as $retencao) {
                            $valorRetencaoRepository = new ValorRetencaoRepository();
                            $valoresRetencao = $valorRetencaoRepository
                                ->scopeSequencialRetencao($retencao->getSequencial())
                                ->get();
                            if (!empty($valoresRetencao)) {
                                foreach ($valoresRetencao as $valorRetencao) {
                                    $deducaoSuspensaRepository = new DeducaoSuspensaRepository();
                                    $deducaoSuspensas = $deducaoSuspensaRepository
                                        ->scopeSequencialValorRetencao($valorRetencao->getSequencial())
                                        ->get();
                                    if (!empty($deducaoSuspensas)) {
                                        foreach ($deducaoSuspensas as $deducaoSuspensa) {
                                            $suspensaoPensaoRepository = new SuspensaoPensaoRepository();
                                            $suspensaoPensoes = $suspensaoPensaoRepository
                                                ->scopeSequencialDeducaoSuspensa($deducaoSuspensa->getSequencial())
                                                ->get();
                                            if (!empty($suspensaoPensoes)) {
                                                foreach ($suspensaoPensoes as $suspensaoPensao) {
                                                    $suspensaoPensaoRepository->delete($suspensaoPensao);
                                                }
                                            }
                                            $deducaoSuspensaRepository->delete($deducaoSuspensa);
                                        }
                                    }
                                    $valorRetencaoRepository->delete($valorRetencao);
                                }
                            }
                            $retencaoRepository->delete($retencao);
                        }
                    }
                    $tributoIRRFRepository->delete($tributoIRRF);
                }
            }

            $tributoIRRFComplementarRepository = new TributoIRRFComplementarRepository();
            $tributoIRRFComplementares = $tributoIRRFComplementarRepository
                ->scopeSequencialServidor($servidorVinculo->getSequencial())
                ->get();

            if (!empty($tributoIRRFComplementares)) {
                foreach ($tributoIRRFComplementares as $tributoIRRFComplementar) {
                    $tributoIRRFComplementarRepository->delete($tributoIRRFComplementar);
                }
            }
            //S-2501 - Fim

            if (!empty($servidorVinculo)) {
                $this->resetScopes();
                $this->delete($servidorVinculo);
            }
        }

        return $servidorVinculadoExluido;
    }

    /**
     * @param string $cpf
     * @return ServidorProcesso
     * @throws Exception
     */
    public static function getProcessosPorCPF($cpf = null)
    {
        $processos = [];

        if (empty($cpf)) {
            throw new Exception('CPF não informado.');
        }

        $daoCgm = new cl_cgm();
        $whereCgm = "z01_cgccpf = {$cpf}";
        $sqlCgm = $daoCgm->sql_query_file(null, 'z01_numcgm as cgm', null, $whereCgm);
        $rsCgm = db_query($sqlCgm);

        if (!$rsCgm) {
            throw new Exception('Erro ao buscar o CPF do CGM.');
        }

        if (pg_num_rows($rsCgm) == 0) {
            throw new Exception("CPF {$cpf} não encontrado.");
        }

        $cgm = \db_utils::fieldsMemory($rsCgm, 0)->cgm;
        
        $daoPessoal = new cl_rhpessoal();
        $whereMatricula = "rh01_numcgm = {$cgm}";
        $sqlMatricula = $daoPessoal->sql_query_file(null, 'rh01_regist as matricula', null, $whereMatricula);
        $rsMatricula = db_query($sqlMatricula);

        for ($row = 0; $row < pg_num_rows($rsMatricula); $row++) {
            $matricula[] = \db_utils::fieldsMemory($rsMatricula, $row)->matricula;
        }

        $daoProcesso = new cl_rhpessoalprocessoservidor;
        $listaMatricula = implode(' , ', $matricula);
        $whereProcesso = "rh271_matricula in ({$listaMatricula})";
        $campos = [
            'rh271_sequencial',
            'rh271_sequencialprocesso',
            'rh271_matricula',
            'rh271_instituicao',
            'rh271_codcateg'
        ];
        $sqlProcesso = $daoProcesso->sql_query(null, implode(' , ', $campos), null, $whereProcesso);
        $rsProcesso = db_query($sqlProcesso);

        if (pg_num_rows($rsProcesso) === 0) {
            return $processos;
        }

        while ($processoServidor = pg_fetch_array($rsProcesso)) {
            $processos[] = ServidorProcesso::fromState($processoServidor);
        }

        return $processos;
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

    public function getRemuneracaoServidor(ServidorProcesso $servidorProcesso)
    {
        $servidorMovimentacaoRepository = new ServidorMovimentacaoRepository();
        $remuneracoes = $servidorMovimentacaoRepository
            ->scopeMatricula($servidorProcesso->getMatricula())
            ->get();
        
        $registros = [];
        $contagem = 0;

        foreach (array_reverse($remuneracoes) as $remuneracao) {
            $dataPagamentoRepository = new DataPagamentoFolhaRepository();
            $dataPagamentoRemuneracao = $dataPagamentoRepository
                ->scopeAno($remuneracao->getAno())
                ->scopeMes($remuneracao->getMes())
                ->get();
            $dataDesligamento = $servidorProcesso->getDataDemissao();

            if (!empty($dataPagamentoRemuneracao)) {
                $dataPagamento = $dataPagamentoRemuneracao[0]->getDataPagamento()->getDate();
                if (strtotime($dataPagamento) > strtotime($dataDesligamento)) {
                    continue;
                }
            }
            if (empty($dataPagamentoRemuneracao)) {
                $dataPagamento = $remuneracao->getAno() . '-' .
                    str_pad($remuneracao->getMes(), 2, '0', STR_PAD_LEFT)  . '-' . '05';
                if (strtotime($dataPagamento) > strtotime($dataDesligamento)) {
                    continue;
                }
            } else {
                $dataPagamento = $dataPagamentoRemuneracao[0]->getDataPagamento()->getDate();
            }
            $dados = new stdClass;
            $dados->dtRemun = $dataPagamento;

            $dados->vrSalFx = (float) $this->truncate($remuneracao->getSalario(), 2);
            $dados->undSalFixo = (int) $this->deParaUnidadePagamento[$remuneracao->getTipoSalario()];
            $registros[] = $dados;
            $contagem += 1;

            if ($contagem == 99) {
                break;
            }
        }
        return $registros;
    }
}
