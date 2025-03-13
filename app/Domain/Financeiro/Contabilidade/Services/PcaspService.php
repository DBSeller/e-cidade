<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Models\Conplano;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoAtributos;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoConta;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoContaBancaria;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoExercicio;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoExercicioSaldo;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoReduzido;
use App\Domain\Financeiro\Contabilidade\Models\Pcasp;
use App\Domain\Financeiro\Contabilidade\Resources\Pcasp\ReduzidosResource;
use App\Domain\Financeiro\Empenho\Models\Empagetipo;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Tesouraria\Models\Saltes;
use App\Domain\Financeiro\Tesouraria\Services\CadastrarReceitaExtraOrcamentariaService;
use DateTime;
use ECidade\Enum\Financeiro\Planejamento\TipoEnum;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PcaspService
{
    /**
     * Detalhamento Sistema: consistema
     */
    const SISTEMA_FINANCEIRO = 1;
    const SISTEMA_PATRIMONIAL = 2;
    const SISTEMA_ORCAMENTARIO = 3;
    const SISTEMA_COMPENSADO = 4;
    const SISTEMA_FINANCEIRO_BANCOS = 6;
    const SISTEMA_FINANCEIRO_CAIXA = 5;
    const SISTEMA_FINANCEIRO_EXTRA_ORCAMENTARIA = 7;

    /**
     * Sistema: consistemaconta
     */
    const SISTEMA_CONTA_ORCAMENTARIA = 1;
    const SISTEMA_CONTA_PATRIMONIAL = 2;
    const SISTEMA_CONTA_CONTROLE = 3;

    /**
     * Usado ao salvar conta extra-orçamentaria
     * @var integer
     */
    private $instituicaoLogada;
    /**
     * Usado ao salvar conta bancária
     * @var DateTime
     */
    private $dataSessao;

    /**
     * Retorna as contas do e-Cidade (conplano)
     * @param array $filtros
     * @return Conplano[]|Collection
     */
    public function getContasEcidade(array $filtros)
    {
        $contaBancaria = !empty($filtros['contasBancarias']);
        return Conplano::orderBy('c60_estrut')
            ->select('*')
            ->when(!empty($filtros['estrutural']), function ($query) use ($filtros) {
                $estrutural = new Estrutural($filtros['estrutural']);
                $ateNivel = $estrutural->getEstruturalAteNivel();
                $query->where('c60_estrut', 'like', "{$ateNivel}%");
            })
            ->when(!empty($filtros['exercicio']), function ($query) use ($filtros) {
                $query->where('c60_anousu', '=', $filtros['exercicio']);
            })
            // filtra apenas as contas analíticas
            ->when(!empty($filtros['apenasAnaliticas']), function ($query) use ($filtros) {
                $query->apenasAnaliticas();
            })
            // filtra pelo id da conta do governo no sistema (união ou estado)
            ->when(!empty($filtros['idContaVinculada']), function ($query) use ($filtros) {
                $query->contaVinculada($filtros['idContaVinculada']);
            })
            // filtra apenas as contas de caixa no e-cidade
            ->when(!empty($filtros['contasCaixa']), function ($query) use ($filtros) {
                $query->contasCaixa();
            })
            // filtra apenas as contas bancárias no e-cidade
            ->when(!empty($filtros['contasBancarias']), function ($query) use ($filtros) {
                $query->contasBancarias();
            })
            // filtra apenas as contas extra-orçamentárias no e-cidade
            ->when(!empty($filtros['contasExtrasOrcamentarias']), function ($query) use ($filtros) {
                $query->contasExtrasOrcamentarias();
            })
            ->when(!empty($filtros['outrasContas']), function ($query) use ($filtros) {
                $query->outrasContas();
            })
            ->when(!empty($filtros['tipoConta']), function ($query) use ($filtros) {
                $query->addSelect(DB::raw('exists(
                    select 1 from conplanoreduz where c61_codcon = c60_codcon and c61_anousu = c60_anousu
                ) as analitica'));
            })
            // retorna uma coluna com o estrutural da conta vinculada se houver
            ->when(!empty($filtros['temVinculoTipoPlano']), function ($query) use ($filtros) {
                // se informado o filtro "temVinculoPlano" verifica se a conta já possui vínculo com uma conta
                // do tipo de plano informardo
                $uniao = 'uniao is false';
                if ($filtros['temVinculoTipoPlano'] === VincularPcaspService::UNIAO) {
                    $uniao = 'uniao is true';
                }
                $column = sprintf(
                    '(
                        select conta
                          from contabilidade.pcaspconplano
                          join contabilidade.pcasp on pcasp_id = pcasp.id
                         where conplano_codigo = c60_codigo and %s
                    ) as conta_vinculada',
                    $uniao
                );
                $query->addSelect(DB::raw($column));
            })
            ->get()
            ->when(!empty($filtros['comPlanosGoverno']), function (Collection $contas) {
                return $contas->map(function (Conplano $conplano) {
                    $conplano->pcaspUniao;
                    $conplano->pcaspEstadual;
                    return $conplano;
                });
            })
            ->when(!empty($filtros['comReduzidos']), function (Collection $contas) use ($contaBancaria) {
                return $contas->map(function (Conplano $conplano) use ($contaBancaria) {
                    $conplano->reduzidos = ReduzidosResource::manutencao($conplano->getReduzidos(), $contaBancaria);
                    return $conplano;
                });
            });
    }

    public function getPlanoAnaliticaExercicio($exercicio)
    {
        $filtros = ['exercicio' => $exercicio, 'apenasAnaliticas' => 1];
        $contas = $this->getContasEcidade($filtros)
            ->map(function (Conplano $conplano) {
                $conplano->pcaspUniao;
                $conplano->pcaspEstadual;
                $conplano->reduzidos = $conplano->getReduzidos();
                return $conplano;
            });

        return $contas;
    }

    /**
     * Contas de caixa sempre deve salvar o:
     * - Detalhamento do Sistema (c60_codsis): "FINANCEIRO - CAIXA".
     * - Sistema (c60_consistemaconta): "P - Patrimonial"
     * @param array $dados
     * @return Conplano
     * @throws Exception
     */
    public function salvarContaCaixa(array $dados)
    {
        return $this->salvarConta($dados, self::SISTEMA_FINANCEIRO_CAIXA, self::SISTEMA_CONTA_PATRIMONIAL);
    }

    /**
     * Contas de caixa sempre deve salvar o:
     * - (c60_codsis): "FINANCEIRO - BANCOS".
     * - (c60_consistemaconta): "P - Patrimonial"
     * @param array $dados
     * @return Conplano
     * @throws Exception
     */
    public function salvarContaBancaria(array $dados)
    {
        $date = new DateTime();
        $date->setTimestamp(db_getsession("DB_datausu"));
        $this->dataSessao = $date;
        return $this->salvarConta($dados, self::SISTEMA_FINANCEIRO_BANCOS, self::SISTEMA_CONTA_PATRIMONIAL);
    }

    /**
     * @param array $dados
     * @return Conplano|array
     * @throws Exception
     */
    public function salvarContaExtraOrcamentaria(array $dados)
    {
        $this->instituicaoLogada = $dados['DB_instit'];

        $conplano = $this->salvarConta(
            $dados,
            self::SISTEMA_FINANCEIRO_EXTRA_ORCAMENTARIA,
            self::SISTEMA_CONTA_PATRIMONIAL
        );

        $this->criarReceitaTesouraria($conplano->reduzidos, $conplano->c60_descr, $conplano->c60_estrut);

        return $conplano;
    }

    public function salvarOutrasContas(array $dados)
    {
        $contaEstado = Pcasp::find($dados['contaEstado']);

        /**
         * Detalhamento do Sistema: - consistema
         * - ORÇAMENTÁRIO: contas dos grupos 5 e 6;
         * - COMPENSADO: contas dos grupos 7 e 8;
         * - PATRIMONIAL: demais grupos de contas, EXCETO contas de caixa, bancos ou extra orçamentários.
         *
         * Sistema: - consistemaconta
         * - O: Orçamentário:** contas dos grupos 5 e 6;
         * - C: Controle:** contas dos grupos 7 e 8;
         * - P: Patrimonial:** demais grupos de contas.
         */
        $classe = substr($contaEstado->conta, 1, 1);
        switch ($classe) {
            case 5:
            case 6:
                $sistema = self::SISTEMA_ORCAMENTARIO;
                $sistemaConta = self::SISTEMA_CONTA_ORCAMENTARIA;
                break;
            case 7:
            case 8:
                $sistema = self::SISTEMA_COMPENSADO; // COMPENSADO
                $sistemaConta = self::SISTEMA_CONTA_CONTROLE; // Controle
                break;
            default:
                $sistema = self::SISTEMA_PATRIMONIAL; // PATRIMONIAL
                $sistemaConta = self::SISTEMA_CONTA_PATRIMONIAL;
        }

        return $this->salvarConta($dados, $sistema, $sistemaConta);
    }

    /**
     * @param array $dados
     * @param integer $sistema Detalhamento do sistema (c60_codsis)
     * @param integer $sistemaConta Sistema (c60_consistemaconta)
     * @return Conplano
     * @throws Exception
     */
    protected function salvarConta(array $dados, $sistema, $sistemaConta)
    {
        $this->validaInclusao($dados);
        $exercicios = $this->exerciciosManutencao($dados['exercicio']);
        $contaEstado = Pcasp::find($dados['contaEstado']);

        $codigoConta = $dados['codcon'];
        if (empty($codigoConta)) {
            $codigoConta = $this->buscarProximoCodCon();
        }

        $contaAtualizada = [];
        foreach ($exercicios as $exercicio) {
            $conplano = new Conplano();
            if (!empty($dados['codcon'])) {
                $conplano = Conplano::query()
                    ->where('c60_anousu', $exercicio)
                    ->where('c60_codcon', $dados['codcon'])
                    ->first();
                if (is_null($conplano)) {
                    $conplano = new Conplano();
                }
            }

            $conplano->c60_anousu = $exercicio;
            $conplano->c60_codcon = $codigoConta;
            $conplano->c60_estrut = $dados['estrutural'];
            $conplano->c60_descr = $dados['nomeConta'];
            $conplano->c60_finali = $dados['funcionamento'];
            $conplano->c60_funcao = $dados['funcao'];
            $conplano->c60_saldocontinuo = $dados['transferenciaSaldo'] === 'S';

            switch ($contaEstado->natureza) {
                case 'D':
                    $conplano->c60_naturezasaldo = 1;
                    break;
                case 'C':
                    $conplano->c60_naturezasaldo = 2;
                    break;
                case 'C/D':
                default:
                    $conplano->c60_naturezasaldo = 3;
                    break;
            }

            $conplano->c60_identificadorfinanceiro = $contaEstado->indicador;
            if ($contaEstado->indicador === 'F/P') {
                $conplano->c60_identificadorfinanceiro = $dados['indicadorSuperavit'];
            }

            $conplano->c60_codsis = $sistema;
            $conplano->c60_consistemaconta = $sistemaConta;
            $conplano->c60_codcla = 1;

            $conplano->save();
            if ($conplano->c60_anousu == $dados['exercicio']) {
                $contaAtualizada = $conplano;
            }
        }
        $contaBancaria = $contaAtualizada->c60_codsis == self::SISTEMA_FINANCEIRO_BANCOS;
        $reduzidos = json_decode(str_replace('\"', '"', $dados['reduzidos']));
        $this->salvarReduzido($reduzidos, $contaAtualizada, $exercicios);
        $this->vincularConplanoPcasp($dados, $contaAtualizada);

        $contaAtualizada->reduzidos = ReduzidosResource::manutencao($contaAtualizada->getReduzidos(), $contaBancaria);

        return $contaAtualizada;
    }

    /**
     * Cria o vínculo do plano de contas do e-cidade com o plano da União e do Estado
     * @param $dados
     * @param $contaAtualizada
     */
    protected function vincularConplanoPcasp($dados, $contaAtualizada)
    {
        $contaUniao = Pcasp::find($dados['contaUniao']);
        $contaEstado = Pcasp::find($dados['contaEstado']);

        $vincular = new VincularPcaspService();
        $vincular->atualizarVinculoContas($contaUniao, [$contaAtualizada->c60_codigo]);
        $vincular->atualizarVinculoContas($contaEstado, [$contaAtualizada->c60_codigo]);
    }

    /**
     * Retorna o próximo codcon
     * @return integer
     */
    private function buscarProximoCodCon()
    {
        return Conplano::nextCodigoConta();
    }

    /**
     * Valida se o estrutural já não existe no e-cidade
     * @param array $dados
     * @return bool
     * @throws Exception
     */
    private function validaInclusao(array $dados)
    {
        $conta = Conplano::query()
            ->where('c60_estrut', $dados['estrutural'])
            ->where('c60_anousu', $dados['exercicio'])
            ->when(!empty($dados['codcon']), function ($query) use ($dados) {
                $query->where('c60_codcon', '!=', $dados['codcon']);
            })
            ->first();

        if (!is_null($conta)) {
            throw new Exception(sprintf(
                'Já existe no sistema uma conta cadastrada com o estrutural %s com o codcon %s.',
                $dados['estrutural'],
                $conta->c60_codcon
            ), 403);
        }

        return true;
    }


    /**
     * Retorna o último exercício do PPA ou o último exercício da conplano para clientes que não utilizam o planejamento
     * @return integer
     */
    private function buscaUltimoExercicio()
    {
        $plano = Planejamento::query()
            ->orderBy('pl2_ano_final', 'desc')
            ->where('pl2_tipo', TipoEnum::PPA)
            ->first();

        if (!is_null($plano)) {
            return $plano->pl2_ano_final;
        }

        return Conplano::selectRaw('max(c60_anousu)')->first()->max;
    }

    /**
     * Essa função cria ou atualiza os dados de um reduzido
     * Como toda alteração no plano de contas envolve a replicação dos dados para os anos seguintes,
     * foi necessário validar se para o ano seguinte o reduzido existe, e se sim altera-lo, caso contrário incluir novo
     *
     * @param array $reduzidos
     * @param Conplano $conplano
     * @param array $exercicios
     * @param bool $contaBancaria
     * @return ConplanoReduzido
     * @throws Exception
     */
    protected function salvarReduzido($reduzidos, $conplano, array $exercicios, $contaBancaria = false)
    {
        $codcon = $conplano->c60_codcon;
        $reduzidosAtualizados = [];
        // para cada reduzido da conta
        foreach ($reduzidos as $reduzido) {
            // se não existir o código do reduzido (inclusão de um novo) gera um novo código para criar novo reduzido
            $codigo = $reduzido->reduzido;
            if (empty($reduzido->reduzido)) {
                $codigo = ConplanoReduzido::nextReduzido();
            }

            foreach ($exercicios as $exercicio) {
                $conplanoreduz = $this->getInstanciaConplanoReduz($exercicio, $reduzido->reduzido);
                $this->validaInstituicaoReduzido($reduzido, $codcon, $exercicio);

                $conplanoreduz->c61_reduz = $codigo;
                $conplanoreduz->c61_codcon = $codcon;
                $conplanoreduz->c61_anousu = $exercicio;
                $conplanoreduz->c61_codigo = $reduzido->codigoRecuso;
                $conplanoreduz->c61_instit = $reduzido->codigoInstituicao;
                $conplanoreduz->save();
                $reduzidosAtualizados[] = $conplanoreduz;

                if ($contaBancaria) {
                    $this->vincularContaBancaria($conplanoreduz, $exercicio, $reduzido->id_contabancaria);
                }
            }

            if ($contaBancaria) {
                $this->vincularSaltes($conplano, $codigo, $reduzido->convenio, $reduzido->cheque);
            }
        }

        $this->salvarComplanoExe($reduzidosAtualizados);
        return $reduzidosAtualizados;
    }

    /**
     * Retorna a instância da conplano reduz para manutenção
     * @param $exercicio
     * @param $codigoReduzido
     * @return ConplanoReduzido
     */
    public function getInstanciaConplanoReduz($exercicio, $codigoReduzido = null)
    {
        $conplanoreduz = new ConplanoReduzido();
        if (!empty($codigoReduzido)) {
            // se informado o reduzido para o exercício, busca ele para edição
            $conplanoreduz = ConplanoReduzido::query()
                ->where('c61_reduz', $codigoReduzido)
                ->where('c61_anousu', $exercicio)
                ->first();

            if (is_null($conplanoreduz)) {
                $conplanoreduz = new ConplanoReduzido();
            }
        }

        return $conplanoreduz;
    }

    /**
     * Não deixa incluir mais de um reduzido para mesma conta e instituição
     * @param \stdClass $reduzido
     * @param integer $codcon
     * @param integer $exercicio
     * @throws Exception
     */
    protected function validaInstituicaoReduzido(\stdClass $reduzido, $codcon, $exercicio)
    {
        $existe = ConplanoReduzido::query()
            ->when(!empty($reduzido->reduzido), function ($query) use ($reduzido) {
                $query->where('c61_reduz', '!=', $reduzido->reduzido);
            })
            ->where('c61_anousu', $exercicio)
            ->where('c61_codcon', $codcon)
            ->where('c61_instit', $reduzido->codigoInstituicao)
            ->first();

        if (!is_null($existe)) {
            throw new Exception('Já existe um reduzido para instituição informada. ');
        }
    }

    /**
     * @param ConplanoReduzido[] $reduzidos
     */
    protected function salvarComplanoExe($reduzidos)
    {
        foreach ($reduzidos as $reduzido) {
            $conplanoExe = ConplanoExercicio::query()
                ->where('c62_reduz', $reduzido->c61_reduz)
                ->where('c62_anousu', $reduzido->c61_anousu)
                ->first();
            if (is_null($conplanoExe)) {
                $conplanoExe = new ConplanoExercicio();
            }
            $conplanoExe->c62_anousu = $reduzido->c61_anousu;
            $conplanoExe->c62_reduz = $reduzido->c61_reduz;
            $conplanoExe->c62_codrec = $reduzido->c61_codigo;
            $conplanoExe->save();
        }
    }

    protected function exerciciosManutencao($exercicio)
    {
        $ultimo = $this->buscaUltimoExercicio();
        $exercicios = [];
        for ($ano = $exercicio; $ano <= $ultimo; $ano++) {
            $exercicios[] = (int)$ano;
        }
        return $exercicios;
    }

    /**
     * Edita os estruturais das contas do plano PCASP
     * Se necessário cria a conta sintética no e-cidade para manter compatibilidade com o resto do sistema
     *
     * @param array $filtros
     * @return boolean
     */
    public function editarEstruturais(array $filtros)
    {
        $contasEditar = json_decode(str_replace('\"', '"', $filtros['contasEditar']));
        $exercicio = $filtros['exercicio'];

        $estruturais = collect($contasEditar)->map(function ($conta) use ($exercicio) {
            Conplano::where('c60_anousu', '>=', $exercicio)
                ->where('c60_codcon', $conta->codcon)
                ->update(['c60_estrut' => $conta->estruturalNovo]);
            return $conta->estruturalNovo;
        });

        // consiste se a alteração não deixou uma conta sem sua sintética
        $estruturais->each(function ($estrutural) use ($exercicio) {
            $contas = Conplano::where('c60_anousu', '>=', $exercicio)
                ->where('c60_estrut', $estrutural)
                ->get();

            $contas->each(function (Conplano $conta) {
                $this->validaInclusaoContaSintetica($conta);
            });
        });

        return true;
    }

    /**
     * @param Conplano $conta
     * @return bool
     */
    private function validaInclusaoContaSintetica(Conplano $conta)
    {
        $estruturalPai = (new Estrutural($conta->c60_estrut))->getEstruturalPai();
        $contaPai = Conplano::where('c60_anousu', $conta->c60_anousu)
            ->where('c60_estrut', $estruturalPai->getEstrutural())
            ->first();

        if (is_null($contaPai)) {
            $novaContaSintetica = new Conplano();
            $novaContaSintetica->c60_codcon = Conplano::nextCodigoConta();
            $novaContaSintetica->c60_anousu = $conta->c60_anousu;
            $novaContaSintetica->c60_estrut = $estruturalPai->getEstrutural();
            $novaContaSintetica->c60_descr = $conta->c60_descr;
            $novaContaSintetica->c60_finali = $conta->c60_finali;
            $novaContaSintetica->c60_codsis = $conta->c60_codsis;
            $novaContaSintetica->c60_codcla = $conta->c60_codcla;
            $novaContaSintetica->c60_consistemaconta = $conta->c60_consistemaconta;
            $novaContaSintetica->c60_identificadorfinanceiro = $conta->c60_identificadorfinanceiro;
            $novaContaSintetica->c60_naturezasaldo = $conta->c60_naturezasaldo;
            $novaContaSintetica->c60_funcao = $conta->c60_funcao;
            $novaContaSintetica->c60_saldocontinuo = $conta->c60_saldocontinuo;
            $novaContaSintetica->save();

            $this->validaInclusaoContaSintetica($novaContaSintetica);
        }

        return true;
    }

    /**
     * Excluí o reduzido se ele ainda não está sendo utilizado pelo sistema
     *
     * @param $codigoReduzido
     * @param $exercicio
     * @return boolean
     * @throws Exception
     */
    public function removerReduzido($codigoReduzido, $exercicio)
    {
        (new ConplanoReduzido())->podeExcluirReduzido($codigoReduzido, $exercicio);

        $reduzido = ConplanoReduzido::where('c61_reduz', $codigoReduzido)
            ->where('c61_anousu', $exercicio)
            ->first();

        ConplanoExercicioSaldo::where('c68_reduz', $reduzido->c61_reduz)
            ->where('c68_anousu', '>=', $exercicio)
            ->delete();

        ConplanoExercicio::where('c62_reduz', $reduzido->c61_reduz)
            ->where('c62_anousu', '>=', $exercicio)
            ->delete();

        ConplanoConta::where('c63_reduz', $reduzido->c61_reduz)
            ->where('c63_anousu', '>=', $exercicio)
            ->delete();

        ConplanoContaBancaria::where('c56_reduz', $reduzido->c61_reduz)
            ->where('c56_anousu', '>=', $exercicio)
            ->delete();


        $codigoConta = $reduzido->c61_codcon;
        ConplanoReduzido::where('c61_reduz', $codigoReduzido)
            ->where('c61_anousu', '>=', $exercicio)
            ->delete();

        // se não haver outros reduzidos vínculados na conta exclui a conta
        $outrosReduzidos = ConplanoReduzido::where('c61_codcon', $codigoConta)
            ->where('c61_anousu', $exercicio)
            ->first();
        if (is_null($outrosReduzidos)) {
            $this->excluirConta($codigoConta, $exercicio);
        }
        return true;
    }

    private function excluirConta($codigoConta, $exercicio)
    {
        $conplano = Conplano::where('c60_codcon', $codigoConta)
            ->where('c60_anousu', $exercicio)
            ->first();

        $conplano->validaExclusaoConta();

        $this->removerContaCorrente($codigoConta, $exercicio);

        ConplanoAtributos::where('c120_conplano', $codigoConta)
            ->where('c120_anousu', '>=', $exercicio)
            ->delete();

        Conplano::where('c60_codcon', $codigoConta)
            ->where('c60_anousu', '>=', $exercicio)
            ->delete();
    }

    /**
     * @param $codigoConta
     * @param $exercicio
     * @return void
     * @throws Exception
     */
    private function removerContaCorrente($codigoConta, $exercicio)
    {
        $service = new ContaCorrentePcaspService();
        $service->removerContaCorrente($codigoConta, $exercicio);
    }

    /**
     * @param ConplanoReduzido $conplanoreduz
     * @param integer $exercicio
     * @param integer $idContabancaria
     * @return ConplanoContaBancaria
     * @throws Exception
     */
    private function vincularContaBancaria(ConplanoReduzido $conplanoreduz, $exercicio, $idContabancaria)
    {
        $conta = ConplanoContaBancaria::where('c56_codcon', $conplanoreduz->c61_codcon)
            ->where('c56_reduz', $conplanoreduz->c61_reduz)
            ->where('c56_anousu', $exercicio)
            ->first();

        if (!is_null($conta) && $conta->c56_contabancaria != $idContabancaria) {
            if ($conta->c56_contabancaria == $idContabancaria) {
                return $conta;
            }
            // se o id da conta bancaria for diferente, exclui para incluir o novo
            $conta->delete();
        }

        $model = new ConplanoContaBancaria();
        $model->c56_sequencial = ConplanoContaBancaria::nextCodigo();
        $model->c56_contabancaria = $idContabancaria;
        $model->c56_anousu = $exercicio;
        $model->c56_reduz = $conplanoreduz->c61_reduz;
        $model->c56_codcon = $conplanoreduz->c61_codcon;
        $model->save();
        return $model;
    }

    private function vincularSaltes(Conplano $conta, $codigoReduzido, $convenio, $cheque)
    {
        $saltes = Saltes::where('k13_reduz', $codigoReduzido)->first();

        if (is_null($saltes)) {
            $date = $this->dataSessao;

            $saltes = new Saltes();
            // sim não faz sentido, mas o campo k13_conta e k13_reduz sempre tem o mesmo valor. Regra antiga
            $saltes->k13_conta = $codigoReduzido;
            $saltes->k13_reduz = $codigoReduzido;
            $saltes->k13_descr = substr($conta->c60_descr, 0, 40);
            $saltes->k13_saldo = 0;
            $saltes->k13_vlratu = null;
            $saltes->k13_ident = "";
            $saltes->k13_limite = null;
            $saltes->k13_dtimplantacao = $date->format('Y-m-d');

            $date->modify('-1 day');
            $saltes->k13_datvlr = $date->format('Y-m-d');

            $saltes->save();
        }

        $empageTipo = Empagetipo::where('e83_conta', $saltes->k13_conta)->first();
        if (is_null($empageTipo)) {
            $empageTipo = new Empagetipo();
            $empageTipo->e83_codtipo = Empagetipo::nextCodigo();
        }

        $empageTipo->e83_descr = $saltes->k13_descr;
        $empageTipo->e83_conta = $saltes->k13_conta;
        $empageTipo->e83_convenio = $convenio;
        $empageTipo->e83_sequencia = $cheque;
        $empageTipo->e83_codmod = 3;
        $empageTipo->e83_codigocompromisso = '00';
        $empageTipo->save();
    }

    /**
     * @param ConplanoReduzido[] $reduzidos
     * @param string $descricao
     * @param string $estrutural
     * @return boolean
     */
    private function criarReceitaTesouraria($reduzidos, $descricao, $estrutural)
    {
        $service = new CadastrarReceitaExtraOrcamentariaService();
        return $service->cadastrar($reduzidos, $this->instituicaoLogada, $descricao, $estrutural);
    }
}
