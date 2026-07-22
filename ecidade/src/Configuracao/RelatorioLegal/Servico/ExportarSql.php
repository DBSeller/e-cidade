<?php

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use Exception;
use ECidade\Configuracao\RelatorioLegal\Repositorio\InformacaoComplementarLancamentoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaInformacaoComplementarRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaEstruturalRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;

/**
 * Class ExportarSql
 * @package ECidade\Configuracao\RelatorioLegal\Servico
 */
class ExportarSql extends Exportar
{
    const STRING = 'string';
    const BOOLEAN = 'boolean';

    /**
     * @var int
     */
    private $codigoRelatorio;
    /**
     * @var array
     */
    private $dePara = array(
        'periodo' => array(
            'o114_sequencial' => 'sequencial',
            'o114_descricao' => 'descricao',
            'o114_qdtporano' => 'quantidadePorAno',
            'o114_diainicial' => 'diaInicial',
            'o114_mesinicial' => 'mesInicial',
            'o114_diafinal' => 'diaFinal',
            'o114_mesfinal' => 'mesFinal',
            'o114_sigla' => 'sigla',
            'o114_ordem' => 'ordem',
        ),
        'orcparamseqcoluna' => array(
            'o115_sequencial' => 'sequencial',
            'o115_anousu' => 'ano',
            'o115_descricao' => 'descricao',
            'o115_tipo' => 'tipo',
            'o115_valoresdefault' => 'default',
            'o115_nomecoluna' => 'nome',
            'o115_formula' => 'formula',
            'o115_origem' => 'origem',
            'o115_relatorio' => 'relatorio'
        ),
        'orcparamrel' => array(
            'o42_codparrel' => 'sequencial',
            'o42_descrrel' => 'descricao',
            'o42_orcparamrelgrupo' => 'grupo',
            'o42_notapadrao' => 'notaPadrao',
        ),
        'orcparamrelperiodos' => array(
            'o113_sequencial' => 'sequencial',
            'o113_periodo' => 'periodo',
            'o113_orcparamrel' => 'relatorio',
        ),
        'orcparamseq' => array(
            'o69_codparamrel' => 'relatorio',
            'o69_codseq' => 'ordem',
            'o69_descr' => 'descricao',
            'o69_grupo' => 'grupo',
            'o69_grupoexclusao' => 'grupoExclusao',
            'o69_nivel' => 'nivel',
            'o69_libnivel' => 'liberaNivel',
            'o69_librec' => 'liberaRecurso',
            'o69_libsubfunc' => 'liberaSubFuncao',
            'o69_libfunc' => 'liberaFuncao',
            'o69_verificaano' => 'verificaAno',
            'o69_labelrel' => 'label',
            'o69_manual' => 'manual',
            'o69_totalizador' => 'totalizadora',
            'o69_ordem' => 'ordem',
            'o69_nivellinha' => 'nivelLinha',
            'o69_observacao' => 'observacao',
            'o69_desdobrarlinha' => 'desdobra',
            'o69_origem' => 'origem',
        ),
        'orcparamseqorcparamseqcoluna' => array(
            'o116_sequencial' => 'sequencial',
            'o116_codseq' => 'linha',
            'o116_codparamrel' => 'relatorio',
            'o116_orcparamseqcoluna' => 'coluna',
            'o116_ordem' => 'ordem',
            'o116_periodo' => 'periodo',
            'o116_formula' => 'formula',
        ),
        'orcparamseqcolunaestruturais' => array(
            'o158_sequencial' => 'sequencial',
            'o158_exclusao' => 'exclusao',
            'o158_estrutural' => 'estrutural',
            'o158_orcparamseqcoluna' => 'codigo_coluna',
            'o158_ano' => 'ano',

        ),
        'orcparamseqfiltropadrao' => array(
            'o132_sequencial' => 'sequencial',
            'o132_orcparamrel' => 'relatorio',
            'o132_orcparamseq' => 'ordemLinha',
            'o132_anousu' => 'ano',
            'o132_filtro' => 'filtro',
        ),
        'orcparamseqinfocomplementar' => array(
            'o157_sequencial' => 'sequencial',
            'o157_valor' => 'valor',
            'o157_conplanoinfocomplementar' => 'informacaoComplementar',
            'o157_relatorio' => 'relatorio',
            'o157_linha' => 'linha',
            'o157_padrao' => 'padrao',
            'o157_infocomplementarlancamento' => 'lancamento'
        ),
        'orcparamseqinfocomplementarlancamento' => array(
            'o102_sequencial' => 'sequencial',
            'o102_exclusao' => 'exclusao'
        )
    );

    /**
     * @var array
     */
    private $camposParse = array(
        'orcparamrel' => array(
            'descricao' => self::STRING,
            'notaPadrao' => self::STRING,
        ),
        'periodo' => array(
            "descricao" => self::STRING,
            "sigla" => self::STRING,
        ),
        'orcparamseqcoluna' => array(
            'descricao' => self::STRING,
            'default' => self::STRING,
            'nome' => self::STRING,
            'formula' => self::STRING,
        ),
        'orcparamseq' => array(
            'descricao' => self::STRING,
            'label' => self::STRING,
            'observacao' => self::STRING,
            'liberaNivel' => self::BOOLEAN,
            'liberaRecurso' => self::BOOLEAN,
            'liberaSubFuncao' => self::BOOLEAN,
            'liberaFuncao' => self::BOOLEAN,
            'verificaAno' => self::BOOLEAN,
            'manual' => self::BOOLEAN,
            'totalizadora' => self::BOOLEAN,
            'desdobra' => self::BOOLEAN,
        ),
        'orcparamseqorcparamseqcoluna' => array(
            'formula' => self::STRING,
        ),
        'orcparamseqcolunaestruturais' => array(
            'exclusao' => self::BOOLEAN,
            'estrutural' => self::STRING,
        ),
        'orcparamseqfiltropadrao' => array(
            'filtro' => self::STRING,
        ),
        'orcparamseqinfocomplementar' => array(
            'valor' => self::STRING,
            'padrao' => self::BOOLEAN
        ),
        'orcparamseqinfocomplementarlancamento' => array(
            'exclusao' => self::BOOLEAN
        )
    );

    /**
     * @var array
     */
    private $dados = array();

    /**
     * @return int
     */
    public function getCodigoRelatorio()
    {
        return $this->codigoRelatorio;
    }

    /**
     * @param int $codigoRelatorio
     * @return ExportarSql
     */
    public function setCodigoRelatorio($codigoRelatorio)
    {
        $this->codigoRelatorio = $codigoRelatorio;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function exportar()
    {
        $this->getDados();
        $this->processar();
        $this->arquivo = "tmp/relatorio_legal_{$this->relatorio->getSequencial()}_" . time() . ".sql";

        file_put_contents($this->arquivo, implode("\n", $this->dados));
    }

    private function adicionarVinculoPeriodo()
    {
        foreach ($this->dadosProcessados->relatorio['periodos'] as $periodo) {
            $periodo['sequencial'] = "nextval('orcparamrelperiodos_o113_sequencial_seq')";
            $periodo['periodo'] = $periodo['periodo']['sequencial'];
            $this->dados[] = $this->montarQuery('orcparamrelperiodos', $periodo);
        }
    }

    /**
     * @param InformacaoComplementarLancamento $informacaoComplementarLancamento
     */
    private function adicionarInfoComplementar($informacaoComplementarLancamento)
    {
        $informacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();
        $informacoesComplementares = $informacaoComplementarRepositorio
            ->scopeInformacaoComplementarLancamento($informacaoComplementarLancamento)
            ->get();

        foreach ($informacoesComplementares as $informacaoComplementar) {
            $dadosInsert = array(
                'sequencial' => "nextval('orcparamseqinfocomplementar_o157_sequencial_seq')",
                'valor' => $informacaoComplementar->getValor(),
                'informacaoComplementar' => $informacaoComplementar->getInformacaoComplementar(),
                'relatorio' => $informacaoComplementar->getRelatorio()->getSequencial(),
                'linha' => $informacaoComplementar->getLinha()->getLinha(),
                'padrao' => $informacaoComplementar->isPadrao(),
                'lancamento' => "currval('orcparamseqinfocomplementarlancamento_o102_sequencial_seq')"
            );
            $this->dados[] = $this->montarQuery('orcparamseqinfocomplementar', $dadosInsert);
        }
    }

    /**
     * @param array $linha
     */
    private function adicionarInfoComplementarLancamento($linha)
    {
        $infoComplementarLancamentoRepository = new InformacaoComplementarLancamentoRepositorio();
        $infosComplementaresLancamentos = $infoComplementarLancamentoRepository
            ->setUseJoin(true)
            ->scopeRelatorio($this->relatorio)
            ->scopeLinha(LinhaRegistry::get($this->relatorio, $linha['linha']))
            ->get(array("distinct orcparamseqinfocomplementarlancamento.*"));

        foreach ($infosComplementaresLancamentos as $infoComplementarLancamento) {
            $dadosInsert = array(
                "sequencial" => "nextval('orcparamseqinfocomplementarlancamento_o102_sequencial_seq')",
                "exclusao" => $infoComplementarLancamento->isExclusao()
            );
            $this->dados[] = $this->montarQuery('orcparamseqinfocomplementarlancamento', $dadosInsert);
            $this->adicionarInfoComplementar($infoComplementarLancamento);
        }
    }

    /**
     * @param array $linha
     */
    private function adicionarFiltroPadrao($linha)
    {
        foreach ($linha['filtroPadrao'] as $filtroPadrao) {
            $filtroPadrao['sequencial'] = "nextval('orcparamelementospadrao_o132_sequencial_seq')";
            $montarQuery = $this->montarQuery('orcparamseqfiltropadrao', $filtroPadrao);
            $this->dados[] = $montarQuery;
        }
    }

    private function adicionarLinha()
    {
        foreach ($this->dadosProcessados->relatorio['linhas'] as $linha) {
            $linha['descricao'] = substr($linha['descricao'], 0, 60);
            $this->dados[] = $this->montarQuery('orcparamseq', $linha);
        }

        foreach ($this->dadosProcessados->relatorio['linhas'] as $linha) {
            $this->adicionarInfoComplementarLancamento($linha);
            $this->adicionarFiltroPadrao($linha);
        }
    }

    /**
     * @param Coluna $coluna
     */
    private function adicionarColunaEstruturais(Coluna $coluna)
    {
        $colunaEstruturalRepositorio = new ColunaEstruturalRepositorio();
        $colunasEstruturias = $colunaEstruturalRepositorio->scopeColuna($coluna)->get();

        foreach ($colunasEstruturias as $colunaEstrutural) {
            $dadosInsert = array(
                'sequencial' => "nextval('orcparamseqcolunaestruturais_o158_sequencial_seq')",
                'exclusao' => $colunaEstrutural->isExclusao(),
                'estrutural' => $colunaEstrutural->getEstrutural(),
                'codigo_coluna' => "currval('orcparamseqcoluna_o115_sequencial_seq')",
                'ano' => $colunaEstrutural->getAno()
            );
            $this->dados[] = $this->montarQuery('orcparamseqcolunaestruturais', $dadosInsert);
        }
    }

    /**
     * @param Coluna $coluna
     */
    private function adicionarVinculoLinhaColuna(Coluna $coluna)
    {
        $linhaColunaRepositorio = new LinhaColunaRepositorio();
        $linhasColunas = $linhaColunaRepositorio
            ->scopeColuna($coluna)
            ->scopeRelatorio($this->relatorio)
            ->get();

        foreach ($linhasColunas as $linhaColuna) {
            $dadosInsert = array(
                'sequencial' => "nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq')",
                'linha' => $linhaColuna->getLinha()->getOrdem(),
                'relatorio' => $this->relatorio->getSequencial(),
                'coluna' => "currval('orcparamseqcoluna_o115_sequencial_seq')",
                'ordem' => $linhaColuna->getOrdem(),
                'periodo' => $linhaColuna->getPeriodo(),
                'formula' => $linhaColuna->getFormula()
            );
            $this->dados[] = $this->montarQuery('orcparamseqorcparamseqcoluna', $dadosInsert);
        }
    }

    private function adicionarColuna()
    {
        $campos = array("DISTINCT orcparamseqcoluna.*");
        $colunaRepositorio = new ColunaRepositorio();
        $colunas = $colunaRepositorio
            ->setUseJoin(true)
            ->scopeRelatorioLinhaColuna($this->relatorio)
            ->get($campos);

        foreach ($colunas as $coluna) {
            $dadosInsert = array(
                'sequencial' => "nextval('orcparamseqcoluna_o115_sequencial_seq')",
                'ano' => $coluna->getAno(),
                'descricao' => $coluna->getDescricao(),
                'tipo' => $coluna->getTipo(),
                'default' => $coluna->getDefault(),
                'nome' => $coluna->getNome(),
                'formula' => $coluna->getFormula(),
                'origem' => $coluna->getOrigem(),
                'relatorio' => $this->relatorio->getSequencial()
            );
            $this->dados[] = $this->montarQuery('orcparamseqcoluna', $dadosInsert);
            $this->adicionarColunaEstruturais($coluna);
            $this->adicionarVinculoLinhaColuna($coluna);
        }
    }

    protected function processar()
    {
        parent::processar();

        if ($this->exportarRelatorio) {
            $this->dados[] = $this->montarQuery('orcparamrel', $this->dadosProcessados->relatorio);
            $this->adicionarVinculoPeriodo();
            $this->adicionarLinha();
            $this->adicionarColuna();
        }
    }

    /**
     * @param $tabela
     * @param array $dados
     * @return string
     */
    private function montarQuery($tabela, array $dados)
    {
        $values = array();
        $campos = $this->dePara[$tabela];

        foreach ($this->dePara[$tabela] as $campo) {
            $valor = $dados[$campo];

            if (isset($this->camposParse[$tabela]) && array_key_exists($campo, $this->camposParse[$tabela])) {
                if ($this->camposParse[$tabela][$campo] == self::STRING) {
                    $values[] = "'" . pg_escape_string($valor) . "'";
                } else {
                    $values[] = $valor ? "'t'" : "'f'";
                }
            } else {
                $values[] = $valor;
            }
        }

        return "insert into {$tabela} (" . implode(', ', array_keys($campos)) . ") values (" . implode(
            ', ',
            $values
        ) . ");";
    }
}
