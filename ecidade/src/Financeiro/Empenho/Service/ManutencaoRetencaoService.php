<?php

namespace ECidade\Financeiro\Empenho\Service;

use App\Domain\Financeiro\Empenho\Models\AquisicaoProducaoRuralProcessos;
use App\Domain\Financeiro\Empenho\Models\RetencaoReceitasProdutorRural;
use App\Domain\Financeiro\Empenho\Services\TipoAquisicaoProducaoRuralService;
use App\Domain\Financeiro\Empenho\Services\TipoServicoObraService;
use App\Domain\Integracoes\EFDReinf\Models\EFDReinfConfiguracao;
use App\Domain\Integracoes\EFDReinf\Services\ConfiguracaoService;
use cl_empnota;
use cl_retencaoreceitasadicionais;
use DBDate;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;

class ManutencaoRetencaoService
{
    /**
     * Retencoes liberadas para manutencao
     *
     * @var array
     */
    private static $retencaoTipoCalcEnable = [4];

    /**
     * Configuracao do EFD-REINF
     *
     * @var EFDReinfConfiguracao|false
     */
    private $efdConfig = false;

    /**
     * Construtor
     */
    public function __construct()
    {
        $instit = db_getsession('DB_instit');
        $this->efdConfig = ConfiguracaoService::getInstance($instit);
    }

    /**
     * Consulta de retencoes com status das notas
     *
     * @param object|null $filters filtro das retencoes
     * @param int $instituicao id da instituicao
     * @return object | null
     */
    public function getRetencoes($filters, $instituicao = 1)
    {
        switch ($filters->evento) {
            case 'r2010':
                return $this->retencaoR2010($filters, $instituicao);
                break;
            case 'r2055':
                return $this->retencaoR2055($filters, $instituicao);
                break;
        }
    }

    /**
     * Retencoes para o evento R2010
     */
    private function retencaoR2010($filters, $instituicao)
    {
        $query = DB::table('empenho.empnota')
        ->select(
            'cgmprestador.z01_numcgm as identificador_prestador',
            'cgmprestador.z01_nome as nome_prestador',
            'cgmprestador.z01_cgccpf as cnpj_prestador',
            'retencaotiporec.e21_descricao as rentencao_tipo',
            'emptiposervicoobra.e154_tipo as indicativo_obra_tipo',
            'emptiposervicoobra.e154_label as indicativo_obra_descricao',
            'emptiposervicoobra.e154_cno as indicativo_obra_cno',
            'empnota.e69_codnota as codigo_nota',
            'empnota.e69_numero as numero_nota',
            'empnota.e69_serienota as serie_nota',
            'empnota.e69_dtnota as data_emissao',
            'tiposerviconotafiscal.e18_sequencial as referencia_tipo_servico',
            'tiposerviconotafiscal.e18_descricao as referencia_tipo_servico_desc',
            'retencaoreceitas.e23_sequencial as retencao_sequencial',
            'retencaoreceitas.e23_valorretencao as valor_retencao',
            'retencaoreceitas.e23_valorbase as valor_base_retido',
            'retencaoreceitasadicionais.e19_sequencial as receitasadicionais_sequencial',
            'retencaoreceitasadicionais.e19_valornaoretidoprincipal as valor_nao_retido_principal',
            'retencaoreceitasadicionais.e19_valorservico15 as valor_servicos_15',
            'retencaoreceitasadicionais.e19_valorservico20 as valor_servicos_20',
            'retencaoreceitasadicionais.e19_valorservico25 as valor_servicos_25',
            'retencaoreceitasadicionais.e19_valoradicional as valor_adicional',
            'retencaoreceitasadicionais.e19_valornaoretidoadicional as valor_nao_retido_adicional',
            'empnotaele.e70_vlrliq as valor_nota_liq',
            'empempenho.e60_numemp as empenho',
            'retencaoreceitasadicionais.e19_indvalorbase as indicativo_valor_base',
            'pc60_indicativocprb as indicativo_cprb'
        )
        ->selectRaw(
            "(select sum(b.e70_vlrliq)
		        from empnota a
		        inner join empnotaele b on b.e70_codnota = a.e69_codnota
		        left join pagordemnota c on a.e69_codnota = c.e71_codnota and c.e71_anulado is false
		        inner join empempenho d on d.e60_numemp = a.e69_numemp
		        where
		        d.e60_numcgm = cgmprestador.z01_numcgm and
		        a.e69_numero = empnota.e69_numero and
		        a.e69_codnota <> empnota.e69_codnota
	        ) as notas_nao_retidas"
        )
        ->selectRaw("concat(empempenho.e60_codemp, '/', empempenho.e60_anousu) as empenho_numero")
        ->selectRaw(
            "(case when pc60_indicativocprb is true
            then cast('3,5' as varchar) else retencaotiporec.e21_aliquota::varchar end) as aliquota"
        )
        ->join(
            'pagordemnota',
            'empnota.e69_codnota',
            '=',
            DB::Raw('pagordemnota.e71_codnota and pagordemnota.e71_anulado is false')
        )
        ->join('empnotaele', 'empnotaele.e70_codnota', '=', 'empnota.e69_codnota')
        ->join('pagordem', 'e71_codord', '=', 'pagordem.e50_codord')
        ->join('retencaopagordem', 'pagordem.e50_codord', '=', 'retencaopagordem.e20_pagordem')
        ->join('retencaoreceitas', 'retencaopagordem.e20_sequencial', '=', 'retencaoreceitas.e23_retencaopagordem')
        ->join('retencaotiporec', 'retencaotiporec.e21_sequencial', '=', 'retencaoreceitas.e23_retencaotiporec')
        ->join('empempenho', 'empempenho.e60_numemp', '=', 'pagordem.e50_numemp')
        ->join('db_config', 'db_config.codigo', '=', 'empempenho.e60_instit')
        ->join('cgm as cgmcontribuinte', 'cgmcontribuinte.z01_numcgm', '=', 'db_config.numcgm')
        ->join('retencaoempagemov', 'e27_retencaoreceitas', '=', 'e23_sequencial')
        ->join('empagemov', 'e81_codmov', '=', 'e27_empagemov')
        ->leftJoin('pagordemconta', 'pagordemconta.e49_codord', 'pagordem.e50_codord')
        ->leftJoin(
            'cgm as cgmprestador',
            'cgmprestador.z01_numcgm',
            '=',
            DB::Raw("coalesce(pagordemconta.e49_numcgm, empempenho.e60_numcgm)")
        )
        ->leftJoin('pcforne', 'pcforne.pc60_numcgm', '=', 'cgmprestador.z01_numcgm')
        ->leftJoin('emptiposervicoobra', 'emptiposervicoobra.e154_numemp', '=', 'empempenho.e60_numemp')
        ->leftJoin(
            'retencaoreceitasadicionais',
            'retencaoreceitas.e23_sequencial',
            '=',
            'retencaoreceitasadicionais.e19_retencaoreceitas'
        )
        ->leftJoin(
            'tiposerviconotafiscal',
            'tiposerviconotafiscal.e18_sequencial',
            '=',
            'retencaoreceitasadicionais.e19_tiposerviconotafiscal'
        );

        /**
         * Adiciona os filtros na consulta
         */
        if ($filters) {
            if ($filters->nota) {
                $nota = trim($filters->nota);
                $query->whereRaw('trim(empnota.e69_numero) = ?', [$nota]);
            }

            if ($filters->cgm) {
                $query->where('cgmprestador.z01_numcgm', '=', $filters->cgm);
            }

            if ($filters->periodo) {
                $dataIni = empty($filters->periodo[0]) ? false : DBDate::converter($filters->periodo[0]);
                $dataFim = empty($filters->periodo[1]) ? false : DBDate::converter($filters->periodo[1]);

                if ($dataIni && $dataFim) {
                    $query->whereBetween('empnota.e69_dtnota', [$dataIni, $dataFim]);
                } elseif ($dataIni && $dataFim === false) {
                    $query->where('empnota.e69_dtnota', '>=', $dataIni);
                } elseif ($dataFim && $dataIni === false) {
                    $query->where('empnota.e69_dtnota', '<=', $dataFim);
                }
            }
        }

        // caso possua filtro de orgaoUnidade
        $query->when($filters->orgaoUnidade, function ($q) use ($filters) {
            $orgao   = $filters->orgaoUnidade->orgao;
            $unidade = $filters->orgaoUnidade->unidade;

            $autorizado = $this->checkOrgaoUnidadeUsuario($orgao);
            if (!$autorizado) {
                throw new Exception('Orgão ou Unidade não autorizado para pesquisa.');
            }

            $q->selectRaw(
                "concat(o40_orgao, ' - ', o40_descr, ' / ', o41_unidade, ' - ', o41_descr) as orgao_unidade"
            )
            ->join(
                'orcdotacao',
                'empempenho.e60_coddot',
                '=',
                DB::Raw('orcdotacao.o58_coddot and empempenho.e60_anousu = orcdotacao.o58_anousu')
            )
            ->join(
                'orcunidade',
                'o58_unidade',
                '=',
                DB::Raw('o41_unidade and o58_anousu = o41_anousu and o58_orgao = o41_orgao')
            )
            ->join(
                'orcorgao',
                'o41_orgao',
                '=',
                DB::Raw('o40_orgao and o41_anousu = o40_anousu')
            );

            if ($orgao) {
                $q->where('o58_orgao', '=', $orgao);
            }

            if ($unidade) {
                $q->where('o58_unidade', '=', $unidade);
            }
        });

        $query->where('e60_instit', '=', $instituicao);
        $query->where('retencaoreceitas.e23_ativo', '=', true);
        $query->whereNull('e81_cancelado');
        $query->where('retencaotiporec.e21_retencaotipocalc', '=', 4);
        $result = $query->orderBy('empnota.e69_dtnota', 'desc')->get();

        return $result;
    }


    /**
     * Retencoes para o evento R2055
     */
    private function retencaoR2055($filters, $instituicao)
    {
        $query = DB::table('empenho.empnota')
        ->select(
            'e69_codnota as nota',
            'e69_dtnota as data_nota',
            'e69_numero as nfnumero',
            'e60_numemp as empenho',
            'z01_numcgm as cgm',
            'z01_nome as prestador',
            'z01_cgccpf as cgccpf',
            'retencaoreceitasprodutorrural.*',
            'e70_vlrliq as vlrBruto',
            'emptipoaquisicaoproducaorural.e159_tipo as indAqProd'
        )
        ->selectRaw("concat(empempenho.e60_codemp, '/', empempenho.e60_anousu) as empenho_numero")
        ->join('empnotaele', 'empnotaele.e70_codnota', '=', 'empnota.e69_codnota')
        ->join('empempenho', 'e69_numemp', '=', 'e60_numemp')
        ->join('pcforne', 'pc60_numcgm', '=', 'e60_numcgm')
        ->join('cgm', 'z01_numcgm', '=', 'e60_numcgm')
        ->join('cgmtipoempresa', 'z01_numcgm', '=', 'z03_numcgm')
        ->join(
            'pagordemnota',
            'empnota.e69_codnota',
            '=',
            DB::Raw('pagordemnota.e71_codnota and pagordemnota.e71_anulado is false')
        )
        ->leftJoin('retencaoreceitasprodutorrural', 'e158_empnota', '=', 'e69_codnota')
        ->leftJoin('emptipoaquisicaoproducaorural', 'e159_empempenho', '=', 'e60_numemp');

        if ($filters) {
            if ($filters->nota) {
                $query->where('empnota.e69_numero', '=', $filters->nota);
            }

            if ($filters->cgm) {
                $query->where('cgm.z01_numcgm', '=', $filters->cgm);
            }

            if ($filters->periodo) {
                $dataIni = empty($filters->periodo[0]) ? false : DBDate::converter($filters->periodo[0]);
                $dataFim = empty($filters->periodo[1]) ? false : DBDate::converter($filters->periodo[1]);

                if ($dataIni && $dataFim) {
                    $query->whereBetween('empnota.e69_dtnota', [$dataIni, $dataFim]);
                } elseif ($dataIni && $dataFim === false) {
                    $query->where('empnota.e69_dtnota', '>=', $dataIni);
                } elseif ($dataFim && $dataIni === false) {
                    $query->where('empnota.e69_dtnota', '<=', $dataFim);
                }
            }
        }

        // caso possua filtro de orgaoUnidade
        $query->when($filters->orgaoUnidade, function ($q) use ($filters) {
            $orgao   = $filters->orgaoUnidade->orgao;
            $unidade = $filters->orgaoUnidade->unidade;

            $autorizado = $this->checkOrgaoUnidadeUsuario($orgao);
            if (!$autorizado) {
                throw new Exception('Orgão ou Unidade não autorizado para pesquisa.');
            }

            $q->selectRaw(
                "concat(o40_orgao, ' - ', o40_descr, ' / ', o41_unidade, ' - ', o41_descr) as orgao_unidade"
            )
            ->join(
                'orcdotacao',
                'empempenho.e60_coddot',
                '=',
                DB::Raw('orcdotacao.o58_coddot and empempenho.e60_anousu = orcdotacao.o58_anousu')
            )
            ->join(
                'orcunidade',
                'o58_unidade',
                '=',
                DB::Raw('o41_unidade and o58_anousu = o41_anousu and o58_orgao = o41_orgao')
            )
            ->join(
                'orcorgao',
                'o41_orgao',
                '=',
                DB::Raw('o40_orgao and o41_anousu = o40_anousu')
            );

            if ($orgao) {
                $q->where('o58_orgao', '=', $orgao);
            }

            if ($unidade) {
                $q->where('o58_unidade', '=', $unidade);
            }
        });

        $query->where('e60_instit', '=', $instituicao);
        $query->whereIn('z03_tipoempresa', [35, 4120]);
        $result = $query->orderBy('empnota.e69_dtnota', 'desc')->get();

        return $result;
    }

    /**
     * Consulta os tipos de rentecoes habilitados a pesquisa
     *
     * @param int $db_instit codigo da instituicao
     * @return object | null
     */
    public function getRetencaoTipos($db_instit)
    {
        $oDaoRetencao = new \cl_retencaotipocalc;
        $sSQLRetencao = $oDaoRetencao->sql_query(
            null,
            "*",
            "e32_sequencial",
            "e32_sequencial in (" . implode(',', self::$retencaoTipoCalcEnable) . ')'
        );
        $rsRetencao = $oDaoRetencao->sql_record($sSQLRetencao);
        return \db_utils::getCollectionByRecord($rsRetencao);
    }

    /**
     * Consulta os tipos de serviços de nota fiscal
     */
    public static function getTipoServicoNota()
    {
        $query  = DB::table('empenho.tiposerviconotafiscal');
        $result = $query->get();

        return $result;
    }

    /**
     * Salva dodos
     *
     * @param object $fields
     * @return bool
     */
    public function save($fields)
    {
        switch ($fields->evento) {
            case 'r2010':
                try {
                    $this->saveDadosTipoServicoObra($fields);
                    $this->saveNotaFiscal($fields);
                    $this->saveDadosAdcionais($fields);
                    return true;
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
                break;

            case 'r2055':
                try {
                    $this->saveDadosTipoAquisicaoProdutorRural($fields);
                    $this->saveDadosRetencaoProducaoRural($fields);
                    $this->saveDadosProcessoProducaoRural($fields);
                    return true;
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
                break;

            default:
                throw new Exception('Evento não informado');
                break;
        }
    }

    /**
     * salva alteracoes da nota fiscal
     *
     * @param object $dados
     * @return bool
     */
    private function saveNotaFiscal($dados)
    {
        $empnota = new cl_empnota();

        $empnota->e69_codnota   = $dados->codigo_nota;
        $empnota->e69_numero    = $dados->numero_nota;
        $empnota->e69_serienota = $dados->serie_nota;

        $empnota->alterar($dados->codigo_nota);

        if ($empnota->erro_status == 0) {
            $msg  = "Erro ao alterar dados da nota fiscal.\n";
            $msg .= "Erro Técnico: {$empnota->erro_msg}";
            throw new Exception($msg);
            return false;
        }
    }

    /**
     * Salva alterações das retencoes adicionais
     *
     * @param object $dados
     * @return bool
     */
    private function saveDadosAdcionais($dados)
    {
        // evita duplicidade
        $receitaAdicional = DB::table('retencaoreceitasadicionais')->where(
            'e19_retencaoreceitas',
            '=',
            $dados->retencao_sequencial
        )->first(['e19_sequencial']);

        $receitasAdicionais = new cl_retencaoreceitasadicionais;
        $receitasAdicionais->e19_retencaoreceitas      = $dados->retencao_sequencial;
        $receitasAdicionais->e19_tiposerviconotafiscal = $dados->referencia_tipo_servico;

        $receitasAdicionais->e19_valornaoretidoprincipal = floatval($dados->valor_nao_retido_principal);
        $receitasAdicionais->e19_valornaoretidoadicional = floatval($dados->valor_nao_retido_adicional);

        $receitasAdicionais->e19_valorservico15 = floatval($dados->valor_servicos_15);
        $receitasAdicionais->e19_valorservico20 = floatval($dados->valor_servicos_20);
        $receitasAdicionais->e19_valorservico25 = floatval($dados->valor_servicos_25);

        $receitasAdicionais->e19_indvalorbase = ($dados->indicativo_valor_base == "true") ? true : false;

        if ($receitaAdicional) {
            $receitasAdicionais->alterar($receitaAdicional->e19_sequencial);
        } else {
            $receitasAdicionais->incluir(null);
        }

        if ($receitasAdicionais->erro_status == 0) {
            $msg  = "Erro - Não foi possível incluir dados adicionais na Retencao {$dados->retencao_sequencial}.\n";
            $msg .= "Erro Técnico: {$receitasAdicionais->erro_msg}";
            throw new Exception($msg);
            return false;
        }
    }

    /**
     * Salva alteracoes do tipo de serviço em obra
     *
     * @param object $dados
     * @return bool
     */
    private function saveDadosTipoServicoObra($dados)
    {
        $tiposervicoobra = new TipoServicoObraService;
        $tiposervicoobra->setNumemp($dados->empenho);
        $tiposervicoobra->setTipo($dados->indicativo_obra_tipo);
        $tiposervicoobra->setCNO($dados->indicativo_obra_cno);

        try {
            $tiposervicoobra->save();
        } catch (Exception $e) {
            $msg  = "Erro - Não foi possível incluir indicativo de obra";
            throw new Exception($msg . "\n{$e->getMessage()}");
            return false;
        }
    }

    /**
     * Tipo de aquisição de produção rural
     *
     * @param object $dados
     * @return boolean
     */
    private function saveDadosTipoAquisicaoProdutorRural($dados)
    {
        // validation
        if (!isset($dados->indAqProd) && !isset($dados->empenho) && empty($dados->empenho)) {
            throw new Exception("Campos obrigatórios não informados.");
            return false;
        }

        try {
            $tipoAquisicaoProducaoRural = new TipoAquisicaoProducaoRuralService;
            $tipoAquisicaoProducaoRural->setNumemp($dados->empenho);
            $tipoAquisicaoProducaoRural->setTipo($dados->indAqProd);
            $tipoAquisicaoProducaoRural->save();
        } catch (Exception $e) {
            $msg  = "Erro - Não foi possível incluir indicativo de Aquisição";
            throw new Exception($msg . "\n{$e->getMessage()}");
            return false;
        }
    }

    /**
     * Dados da retencao de producao rural
     *
     * @param object $dados
     * @return boolean
     */
    private function saveDadosRetencaoProducaoRural($dados)
    {
        // validation
        if (empty($dados->nota)) {
            throw new Exception("Nota de liquidação deve ser informada");
            return false;
        }

        $retencaoReceitasProdutorRural = RetencaoReceitasProdutorRural::where('e158_empnota', $dados->nota)->first();
        if (empty($retencaoReceitasProdutorRural)) {
            $retencaoReceitasProdutorRural = new RetencaoReceitasProdutorRural;
            $retencaoReceitasProdutorRural->e158_empnota  = $dados->nota;
        }

        $retencaoReceitasProdutorRural->e158_vlrrat   = floatval($dados->e158_vlrrat);
        $retencaoReceitasProdutorRural->e158_vlrsenar = floatval($dados->e158_vlrsenar);
        $retencaoReceitasProdutorRural->e158_vlrcp    = floatval($dados->e158_vlrcp);

        try {
            $retencaoReceitasProdutorRural->save();
        } catch (Exception $e) {
            $msg  = "Erro - Não foi possível incluir indicativo de Aquisição";
            throw new Exception($msg . "\n{$e->getMessage()}");
            return false;
        }
    }

    /**
     * Dados do processo
     *
     * @param object $dados
     * @return boolean
     */
    private function saveDadosProcessoProducaoRural($dados)
    {
        if ($dados->processos) {
            $retencao = RetencaoReceitasProdutorRural::where('e158_empnota', $dados->nota)->first(['e158_sequencial']);
            if (empty($retencao)) {
                throw new Exception("Não foi encontrado retenção de produtor rural para salvar os dados dos processos");
                return false;
            }

            foreach ($dados->processos as $item) {
                $processo = new AquisicaoProducaoRuralProcessos;

                if ($item->id) {
                    $processo = AquisicaoProducaoRuralProcessos::find($item->id);
                    if (!$processo) {
                        continue;
                    }
                }

                $processo->e157_retencaoreceitasprodutorrural = $retencao->e158_sequencial;
                $processo->e157_nrprocjud    = $item->numero;
                $processo->e157_vlrcpnret    = floatval($item->cp);
                $processo->e157_vlrratnret   = floatval($item->rat);
                $processo->e157_vlrsenarnret = floatval($item->senar);

                try {
                    $processo->save();
                } catch (Exception $e) {
                    $msg  = "Erro - Não foi possível incluir o processo {$processo->e157_vlrcpnret}";
                    throw new Exception($msg . "\n{$e->getMessage()}");
                }
            }
        }

        if ($dados->processosToRemove) {
            if (is_array($dados->processosToRemove)) {
                AquisicaoProducaoRuralProcessos::destroy($dados->processosToRemove);
            }
        }
    }

    /**
     * Orgaos e unidades do usuario
     *
     * @param array $fields
     * @return object|false
     */
    private function checkOrgaoUnidadeUsuario($orgao)
    {
        $ano  = db_getsession('DB_anousu');
        $user = db_getsession('DB_id_usuario');

        $permission = DB::table('db_permemp')
        ->join('db_usupermemp', 'db21_codperm', '=', 'db20_codperm')
        ->join(
            'orcorgao',
            'db20_anousu',
            '=',
            DB::Raw('o40_anousu and o40_orgao = db20_orgao')
        )
        ->where([
            ['o40_orgao',  '=', $orgao],
            ['o40_anousu', '=', $ano],
            ['db21_id_usuario', '=', $user]
        ])
        ->first(['db20_codperm']);

        return $permission;
    }
}
