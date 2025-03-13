<?php

namespace App\Domain\Patrimonial\Licitacoes\Services;

use App\Domain\Configuracao\Instituicao\Model\DBConfig;
use App\Domain\Patrimonial\Licitacoes\Models\Licitacao;
use App\Domain\Patrimonial\Licitacoes\Models\LicitacaoTramita;
use App\Domain\Patrimonial\Licitacoes\Relatorios\LogImportacaoTramita;
use ECidade\File\Csv\LerCsv;
use ECidade\File\Txt\LerTxt;
use ECidade\Pdf\Pdf;
use Exception;

class ImportarLicitacoesTramitaService
{
    /**
     * as const abaixo identifica as colunas do layout do tramita
     */
    const UNIDADE = 'CHAVE SAGRES';
    const LICITACAO = 'NUMERO LICITACAO';
    const EXERCICIO = 'EXERCICIO';
    const MODALIDADE = 'MODALIDADE';

    private $marcaInicioLeitura = '<licitacao>';
    private $marcaFimLeitura = '</licitacao>';

    /**
     * Esse array faz um mapa das colunas usadas e das posições de cada coluna assim como o tamanho dela
     * @var \int[][]
     */
    protected $mapaColunas = [
        self::UNIDADE => [0, 6],
        self::LICITACAO => [6, 5],
        self::EXERCICIO => [11, 4],
        self::MODALIDADE => [15, 2]
    ];

    private $deSagresParaInstituicao;

    protected $licitacoesNaoEncontradas = [];
    protected $licitacoesEncontradas = [];

    public function importar(array $dados)
    {
        $this->mapeiaInstituicoes();
        $dadosArquivo = parseStringJson($dados['file']);
        $this->processaArquivo($dadosArquivo->path);
    }

    /**
     * @param $filepath
     * @throws Exception
     */
    private function processaArquivo($filepath)
    {
        $csv = new LerTxt($filepath);
        $linha = $csv->read();

        $linhaAnalizar = 0;
        foreach ($linha as $dadosLicitacao) {
            if ($dadosLicitacao === $this->marcaInicioLeitura) {
                continue;
            }
            if ($dadosLicitacao === $this->marcaFimLeitura) {
                break;
            }

            $linhaAnalizar++;

            $sagres = $this->parseData($this->mapaColunas[self::UNIDADE], $dadosLicitacao);
            $numero = $this->parseData($this->mapaColunas[self::LICITACAO], $dadosLicitacao);
            $exercicio = $this->parseData($this->mapaColunas[self::EXERCICIO], $dadosLicitacao);
            $modalidade = $this->parseData($this->mapaColunas[self::MODALIDADE], $dadosLicitacao);

            if (!array_key_exists($sagres, $this->deSagresParaInstituicao)) {
                throw new Exception(sprintf(
                    'O código do sagres %s encontrado no arquivo não encontra-se no e-cidade',
                    $sagres
                ));
            }

            $this->processarLicitacao($sagres, $numero, $exercicio, $modalidade);
        }
    }

    private function mapeiaInstituicoes()
    {
        DBConfig::query()->select(['codigo', 'tribinst', 'nomeinst'])->get()->each(function (DBConfig $instituicao) {
            $this->deSagresParaInstituicao[$instituicao->tribinst] = $instituicao;
        });
    }

    private function parseData(array $posicoes, $string)
    {
        return substr($string, $posicoes[0], $posicoes[1]);
    }

    private function buscaLicitacao($codigoInstituicao, $numero, $exercicio, $modalidade)
    {
        return Licitacao::query()
            ->whereHas('modalidade', function ($query) use ($modalidade) {
                return $query->where('l03_codcom', '=', (int)$modalidade);
            })
            ->where('l20_instit', '=', $codigoInstituicao)
            ->where('l20_numero', '=', $numero)
            ->where('l20_anousu', '=', $exercicio)
            ->first();
    }

    private function stdLicitacao($sagres, $numero, $exercicio, $modalidade)
    {
        $instituicao = $this->deSagresParaInstituicao[$sagres];
        return (object)[
            'sagres' => $sagres,
            'instituicao' => sprintf('%s - %s', $instituicao->codigo, $instituicao->nomeinst),
            'licitacao' => "$numero/$exercicio",
            "modalidade" => $modalidade
        ];
    }

    /**
     * @param $sagres
     * @param $numero
     * @param $exercicio
     * @param $modalidade
     */
    private function processarLicitacao($sagres, $numero, $exercicio, $modalidade)
    {
        $instituicao = $this->deSagresParaInstituicao[$sagres];
        $licitacao = $this->buscaLicitacao($instituicao->codigo, $numero, $exercicio, $modalidade);

        if (is_null($licitacao)) {
            $this->licitacoesNaoEncontradas[] = $this->stdLicitacao($sagres, $numero, $exercicio, $modalidade);
            return;
        }

        $this->licitacoesEncontradas[] = $this->stdLicitacao($sagres, $numero, $exercicio, $modalidade);
        $this->mapearLicitacao($licitacao);
    }

    private function mapearLicitacao(Licitacao $licitacao)
    {
        $mapeado = LicitacaoTramita::query()->where('licitacao_id', '=', $licitacao->l20_codigo)->get()->count() > 0;
        if ($mapeado) {
            return true;
        }

        $tramita = new LicitacaoTramita();
        $tramita->licitacao_id = $licitacao->l20_codigo;
        $tramita->save();
    }

    public function log()
    {
        $relatorio = new LogImportacaoTramita();
        $relatorio->setLicitacoesEncontradas($this->licitacoesEncontradas);
        $relatorio->setLicitacoesNaoEncontradas($this->licitacoesNaoEncontradas);
        return $relatorio->emitir();
    }
}
