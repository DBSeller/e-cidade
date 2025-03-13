<?php


namespace App\Domain\Financeiro\Contabilidade\Services\Relatorios;

use DBDate;
use DateTime;
use Exception;
use Carbon\Carbon;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use App\Domain\Financeiro\Contabilidade\Relatorios\EvolucaoDespesaXls;
use App\Domain\Financeiro\Contabilidade\Services\BalanceteDespesaService;

class EvolucaoDespesaService
{
    /**
     * Lista de IDs das instituições selecionadas
     * @var \Illuminate\Support\Collection
     */
    private $filtrarInstituicoes;
    
    /**
     * @var Carbon
     */
    private $filtroDataInicio;

    /**
     * @var Carbon
     */
    private $filtroDataFinal;

    /**
     * @var array
     */
    private $filtrarOrgaoUnidade = [];
     
    /**
     * @var string
     */
    private $filtrarOrgaoUnidadeOperador = '';
    
    /**
     * @var int
     */
    private $ano;

    /**
     * @var string[]
     */
    private $nomeInstituicoes = [];

    /**
     * @var array
     */
    private $mesesProcessar = [];

    /**
     * @var string
     */
    private $db_inst = '';

    /**
     * @var Instituicao
     */
    private $emissor;

    /**
     * @var string
     */
    private $enteFederativo;

    /**
     * @var EvolucaoDespesaXls
     */
    private $parser;

    /**
     * @var string
     */
    private $tipoPagamento;

    /**
     * @var array
     */
    private $dados = [];

    /**
     * @var int
     */
    private $exercicio;

    public function __construct()
    {
        $this->parser = new EvolucaoDespesaXls('storage/financeiro/modelo_evolucao_despesa.xlsx');
    }

    public function setFiltrosRequest(array $filtros)
    {
        $filtro = str_replace('\"', '"', $filtros['filtros']);
        $this->filtros = \JSON::create()->parse($filtro);
        $this->filtrarPor = "";
        $this->filtrarOrgaoUnidade = [];

        if (!empty($filtros['instituicoes'])) {
            $instituicoes = $filtros['instituicoes'];

            $this->filtrarInstituicoes = collect($instituicoes)->map(function ($instituicao) {
                $this->nomeInstituicoes[] = $instituicao->nome;
                return $instituicao->codigo;
            });
        }

        if (!empty($filtros['exercicio'])) {
            $this->exercicio = $filtros['exercicio'];
        }
        
        if (!empty($filtros['DB_instit'])) {
            $this->db_instituicao = $filtros['DB_instit'];
        }

        if (!empty($filtros['tipo_pagamento'])) {
            $this->tipoPagamento = $filtros['tipo_pagamento'];
        }

        if (sizeof($this->filtros->unidade->aUnidades) > 0) {
            $this->filtrarPor = 'unidades';
            $this->filtrarOrgaoUnidadeOperador = $this->filtros->unidade->operador;
            foreach ($this->filtros->unidade->aUnidades as $unidade) {
                $this->filtrarOrgaoUnidade[] = $unidade;
            }
        }

        if (sizeof($this->filtros->orgao->aOrgaos) > 0) {
            $this->filtrarPor = 'orgaos';
            $this->filtrarOrgaoUnidadeOperador = $this->filtros->orgao->operador;
            $this->filtrarOrgaoUnidade = []; // limpando possíveis filtros de unidades
            foreach ($this->filtros->orgao->aOrgaos as $orgao) {
                $this->filtrarOrgaoUnidade[] = $orgao;
            }
        }

        if (!empty($filtros['mes'])) {
            $this->filtroMes = $filtros['mes'];
            $this->mesesProcessar = $this->getMesesProcessar($filtros['mes']);
        }

        $this->emissor = \InstituicaoRepository::getInstituicaoByCodigo($this->db_instituicao);
        $this->processaEnteFederativo();
    }

    private function getTipoPagamento($linhaDados)
    {
        switch ($this->tipoPagamento) {
            case 'pago':
                return $linhaDados->pago;
            case 'liquidado':
                return $linhaDados->liquidado;
            case 'empenhado':
                return $linhaDados->empenhado_liquido;
            default:
                return $linhaDados->pago;
        }
    }

    private function somaUltimosDozeMeses($dado)
    {
        $total = 0;
        $range = range(1, 12);
        foreach ($range as $mesNumero) {
            if (key_exists('mes_'.$mesNumero, $dado)) {
                $total += $dado->{'mes_'.$mesNumero};
            } else {
                $total += 0;
            }
        }

        return $total;
    }

    public function emitir()
    {
        $this->processaDados();
        $this->organizaLinhas();
        $this->dados = [];

        foreach ($this->linhasOrganizadas as $section => $linhas) {
            $this->parser->addCollection($section, $linhas);
        }
        $this->linhasOrganizadas = [];

        foreach ($this->mesesProcessar as $mesProcessar) {
            $mes = "{$mesProcessar->nome_abreviado}/{$mesProcessar->ano}";
            $this->parser->setVariavel($mesProcessar->coluna, $mes);
        }

        $this->parser->setVariavel('instituicoes', implode(", ", $this->nomeInstituicoes));
        $this->parser->setVariavel('tipo_pagamento', $this->getDescricaoTipoPagamento($this->tipoPagamento));
        $this->parser->setVariavel('filtro_orgaos_unidades', $this->getDescricaoFiltroOrgaoUnidade());

        $mes1 = $this->mesesProcessar[0];
        $mes12 = $this->mesesProcessar[11];

        $mesesPeriodo = sprintf(
            '%s - %s',
            "{$mes1->nome}/{$mes1->ano}",
            "{$mes12->nome}/{$mes12->ano}"
        );
        
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setPeriodo(mb_strtoupper($this->mesesProcessar[11]->nome, 'ISO-8859-1'));
        $this->parser->setMesesPeriodo($mesesPeriodo);
        $this->mesesProcessar = [];
        $filename = $this->parser->gerar();
        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    public function processaDados()
    {
        $meses = $this->mesesProcessar;
        $mesFinal = array_pop($meses);

        $balanceteMesFinal = new BalanceteDespesaService();
        $sqlMesFinal = $balanceteMesFinal
                    ->setAno($mesFinal->ano)->setFiltrarInstituicoes($this->filtrarInstituicoes)
                    ->setFiltroDataInicio(Carbon::createFromFormat('Y-m-d', $mesFinal->data_inicio))
                    ->setFiltroDataFinal(Carbon::createFromFormat('Y-m-d', $mesFinal->data_fim))
                    ->sqlPrincipal();
        $dadosMesFinal = $balanceteMesFinal->execute($sqlMesFinal);
        
        foreach ($dadosMesFinal as $dado) {
            $hash = "{$dado->elemento}#{$dado->reduzido}";
            $dado->{$mesFinal->coluna} = $this->getTipoPagamento($dado);
            if (!array_key_exists($hash, $this->dados)) {
                $this->dados[$hash] = $dado;
            }
        }

        foreach ($meses as $mes) {
            $balanceteMensal = new BalanceteDespesaService();
            $sqlMensal = $balanceteMensal
                            ->setAno($mes->ano)->setFiltrarInstituicoes($this->filtrarInstituicoes)
                            ->setFiltroDataInicio(Carbon::createFromFormat('Y-m-d', $mes->data_inicio))
                            ->setFiltroDataFinal(Carbon::createFromFormat('Y-m-d', $mes->data_fim))
                            ->sqlPrincipal();
            $dadosMensal = $balanceteMensal->execute($sqlMensal);

            foreach ($dadosMensal as $dado) {
                $hash = "{$dado->elemento}#{$dado->reduzido}";
                if (!array_key_exists($hash, $this->dados)) {
                    $this->dados[$hash] = $dado;
                    $this->dados[$hash]->{$mes->coluna} = $this->getTipoPagamento($dado);
                } else {
                    $this->dados[$hash]->{$mes->coluna} = $this->getTipoPagamento($dado);
                }
            }
        }

        $this->dados = $this->organizaMeses();
        ksort($this->dados);
    }

    private function organizaMeses()
    {
        $range = range(1, 12);
        foreach ($this->dados as $dado) {
            foreach ($range as $mes) {
                if (!array_key_exists('mes_'.$mes, $dado)) {
                    $dado->{'mes_'.$mes} = 0;
                }
            }
        }

        return $this->dados;
    }

    private function getDescricaoFiltroOrgaoUnidade()
    {
        $retorno = "";
        $operador = $this->filtrarOrgaoUnidadeOperador == "in" ? 'Contendo' : 'Não Contendo';
        
        if (sizeof($this->filtrarOrgaoUnidade) == 0) {
            return '';
        }

        if ($this->filtrarPor == 'unidades') {
            $whereUnidades = "o41_anousu = ".$this->exercicio." 
                and concat(o41_orgao,'-' ,o41_unidade) in ('".implode("','", $this->filtrarOrgaoUnidade)."')";

            $orcUnidade = new \cl_orcunidade();
            $sqlUnidades = $orcUnidade
                            ->sql_query(
                                $this->exercicio,
                                null,
                                null,
                                "o41_descr as descricao",
                                "o41_orgao, o41_unidade",
                                $whereUnidades
                            );
            $rsUnidades = $orcUnidade->sql_record($sqlUnidades);
            $unidades = \db_utils::getCollectionByRecord($rsUnidades, false, false, false);
            foreach ($unidades as $unidade) {
                if (strlen($retorno) == 0) {
                    $retorno .= $unidade->descricao;
                } else {
                    $retorno .= ", ".$unidade->descricao;
                }
            }
            
            return "UNIDADES ($operador): ".$retorno;
        }
        
        if ($this->filtrarPor == 'orgaos') {
            $whereOrgaos = "o40_anousu = ".$this->exercicio." 
                and o40_orgao in (".implode(",", $this->filtrarOrgaoUnidade).")";
                
            $orcOrgao = new \cl_orcorgao();
            $sqlOrgaos = $orcOrgao
                            ->sql_query_file(
                                $this->exercicio,
                                null,
                                "o40_descr as descricao",
                                "1",
                                $whereOrgaos
                            );
            $rsOrgaos = $orcOrgao->sql_record($sqlOrgaos);
            $aOrgaos = \db_utils::getCollectionByRecord($rsOrgaos, false, false, false);
            foreach ($aOrgaos as $orgao) {
                if (strlen($retorno) == 0) {
                    $retorno .= $orgao->descricao;
                } else {
                    $retorno .= ", ".$orgao->descricao;
                }
            }

            return "ÓRGÃOS ($operador): ".$retorno;
        }

        return '';
    }

    private function getDescricaoTipoPagamento($key)
    {
        switch ($key) {
            case 'pago':
                return 'PAGOS NO MÊS';
            case 'liquidado':
                return 'LIQUIDADOS NO MÊS';
            case 'empenhado':
                return 'EMPENHADOS NO MÊS';
            default:
                return '';
        }
    }

    private function getMesesProcessar($mes)
    {
        $mesesProcessar = [];
        $exercicio = $this->exercicio;
        $mesFinal = $mes;
        
        $dataInical = new DateTime("{$exercicio}-01-01");
        $dia = cal_days_in_month(CAL_GREGORIAN, $mesFinal, $exercicio);
        $dataFinal = new DBDate("{$dia}/{$mesFinal}/{$exercicio}");
        if ($dataFinal->getMes() != 12) {
            $mesInicial = $dataFinal->getMes() + 1;
            $ano = $dataFinal->getAno() - 1;
            $dataInical = new DateTime("{$ano}-{$mesInicial}-01");
        }

        $meses = 1;
        $listaMeses = \DBDate::getMesesExtenso();
        while ($meses <= 12) {
            $mes = $dataInical->format('m');
            $ano = $dataInical->format('Y');
            $dia = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
            $mesesProcessar[] = (object)[
                'nome' => $listaMeses[(int)$mes],
                'nome_abreviado' => \DBDate::getMesAbreviado((int)$mes),
                'mes' => $mes,
                'ano' => $ano,
                'coluna' => "mes_{$meses}",
                'data_inicio' => $dataInical->format('Y-m-d'),
                'data_fim' => "{$ano}-{$mes}-{$dia}",
            ];

            $dataInical->modify('+1 month');
            $meses++;
        }

        return $mesesProcessar;
    }

    protected function processaEnteFederativo()
    {
        $this->enteFederativo = DemonstrativoFiscal::getEnteFederativo($this->emissor);
        if ($this->emissor->getTipo() != \Instituicao::TIPO_PREFEITURA) {
            $this->enteFederativo .= "\n" . $this->emissor->getDescricao();
        }
    }

    /**
     * Quando template é por session, esse metodo joga as linhas dentro de cada sessão
     */
    protected function organizaLinhas()
    {
        $this->linhasOrganizadas = [];
        
        if (empty($this->dados)) {
            throw new Exception("Sem registros para o filtro selecionado.", 403);
        }

        foreach ($this->dados as $dado) {
            if (sizeof($this->filtrarOrgaoUnidade) > 0
                && $this->filtrarPor == 'orgaos'
                && $this->matchOrgao($dado)
            ) {
                $this->preparaEadicionaLinha($dado);
            }

            if (sizeof($this->filtrarOrgaoUnidade) > 0
                && $this->filtrarPor == 'unidades'
                && $this->matchUnidade($dado)
            ) {
                $this->preparaEadicionaLinha($dado);
            }

            if (sizeof($this->filtrarOrgaoUnidade) == 0) {
                $this->preparaEadicionaLinha($dado);
            }
        }

        if (sizeof($this->linhasOrganizadas) == 0) {
            throw new Exception("Sem registros para o filtro selecionado.", 403);
        }
    }

    private function preparaEadicionaLinha($dado)
    {
        $dado->total_acumulado_12_meses = $this->somaUltimosDozeMeses($dado);
        $this->linhasOrganizadas['despesas'][] = $dado;
    }

    private function matchOrgao($linha)
    {
        return $this->filtrarOrgaoUnidadeOperador == 'in'
            ? in_array($linha->orgao, $this->filtrarOrgaoUnidade)
            : !in_array($linha->orgao, $this->filtrarOrgaoUnidade);
    }

    private function matchUnidade($linha)
    {
        foreach ($this->filtrarOrgaoUnidade as $orgaoUnidade) {
            return $this->filtrarOrgaoUnidadeOperador == 'in'
                ? $linha->orgao."-".$linha->unidade === $orgaoUnidade
                : $linha->orgao."-".$linha->unidade !== $orgaoUnidade;
        }

        return false;
    }
}
