<?php

namespace App\Domain\Financeiro\Contabilidade\Builder;

use App\Domain\Financeiro\Contabilidade\Contracts\PlanoContasPcaspInterface;
use Carbon\Carbon;
use Exception;

class PcaspBuilder
{
    const UNIAO = 'uniao';
    /**
     * @var PlanoContasPcaspInterface
     */
    private $layout;
    /**
     * Contém os dados de uma linha do CSV
     * @var array
     */
    private $linha = [];

    /**
     * @var integer
     */
    private $exercicio;
    /**
     * Tipo do plano: uniao | UF
     * @var string
     */
    private $plano;

    /**
     * Mapa dos possíveis valores para a Natureza de Saldo.
     * - Informar como consta na planilha, mas sempre em CAPSLOCK
     * @var string[]
     */
    protected $deParaNaturezaSaldo = [
        'D' => 'D',
        'D/C' => 'C/D',
        'C' => 'C',
        'C/D' => 'C/D',
    ];

    /**
     * Mapa dos possíveis valores para definir se a conta é sintética
     * - Informar como consta na planilha, mas sempre em CAPSLOCK
     * @var array
     */
    protected $deParaSintetica = [
        'SINTÉTICO' => true,
        'ANALÍTICO' => false,
    ];

    /**
     * Mapa dos possíveis valores para definir o atributo de superavit financeiro
     * - Informar como consta na planilha, mas sempre em CAPSLOCK
     * @var array
     */
    protected $deParaIndicador = [
        '' => null,
        '-' => null,
        'NÃOSEAPLICA' => null,
        'F' => 'F',
        'P' => 'P',
        'P/F' => 'F/P',
        'F/P' => 'F/P',
    ];


    protected $informacaoComplementar = 'PO';

    /**
     * @param PlanoContasPcaspInterface $layout
     * @return PcaspBuilder
     */
    public function addLayout(PlanoContasPcaspInterface $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Adiciona uma linha do CSV com os dados das colunas
     * @param array $linha
     * @return PcaspBuilder
     */
    public function addLinha(array $linha)
    {
        $this->linha = $linha;
        return $this;
    }

    public function addExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
        return $this;
    }

    public function addTipoPlano($plano)
    {
        $this->plano = $plano;
        return $this;
    }

    public function build()
    {
        $dado = [];
        $colunasMapeadas = $this->layout->colunasMapper();
        foreach ($this->layout->colunasImportar() as $indexColuna) {
            $dado[$colunasMapeadas[$indexColuna]] = $this->linha[$indexColuna];
        }

        $dado['exercicio'] = $this->exercicio;
        $dado['uniao'] = $this->plano === self::UNIAO;
        $dado['nome'] = str_replace("\n", '', $dado['nome']);
        $dado['funcao'] = str_replace("\n", '', $dado['funcao']);
        $dado['conta'] = str_replace('.', '', $dado['conta']);
        $dado['natureza'] = $this->naturezaSaldo($dado['natureza']);
        $dado['sintetica'] = $this->sintetica($dado['sintetica']);
        $dado['indicador'] = $this->indicador($dado['indicador']);

        if (!$dado['sintetica'] && is_null($dado['indicador'])) {
            $dado['indicador'] = 'N';
        }

        $dado['desdobramento1'] = $dado['desdobramento1'] === ''? '00' : $dado['desdobramento1'];
        $dado['desdobramento2'] = $dado['desdobramento2'] === ''? '00' : $dado['desdobramento2'];
        $dado['desdobramento3'] = $dado['desdobramento3'] === ''? '00' : $dado['desdobramento3'];

        $dado['informacoescomplementares'] = $this->tratarInformacoesComplementares($dado);
        $dado['created_at'] = Carbon::now();
        $dado['updated_at'] = Carbon::now();

        return $dado;
    }


    /**
     * Recebe a natureza de saldo conforme apresentado no arquivo e retorna o padrão esperado para persistência
     * @param string $natureza
     * @return string
     * @throws Exception
     */
    private function naturezaSaldo($natureza)
    {
        $natureza = $this->normalize($natureza);
        if (!array_key_exists($natureza, $this->deParaNaturezaSaldo)) {
            throw new Exception(sprintf('Tipo de natureza de saldo "%s" não mapeado.', $natureza));
        }
        return $this->deParaNaturezaSaldo[$natureza];
    }

    /**
     * Recebe o "Nível de detalhamento" (se a conta é sintética ou analítica) conforme apresentado no arquivo
     * e retorna um booleam
     *
     * @param string $string
     * @return boolean
     * @throws Exception
     */
    private function sintetica($strings)
    {
        $string = $this->normalize($strings);
        if (!array_key_exists($string, $this->deParaSintetica)) {
            throw new Exception(sprintf(
                'Não foi mapeado a string "%s" (%s) para realizar o "de para" para identificar se a conta é %s.',
                $string,
                'sintética ou analitica',
                $strings
            ));
        }
        return $this->deParaSintetica[$string];
    }

    /**
     * Recebe o "Indicador de superávit" conforme arquivo e retorna o padrão correto para persistência.
     * @param $indicador
     * @return null|string
     * @throws Exception
     */
    private function indicador($indicador)
    {
        $indicador = $this->normalize($indicador);
        if (!array_key_exists($indicador, $this->deParaIndicador)) {
            throw new Exception(sprintf('Indicador de Superávit "%s" não mapeado.', $indicador));
        }
        return $this->deParaIndicador[$indicador];
    }

    /**
     * @param $string
     * @return string
     */
    private function normalize($string)
    {
        return mb_strtoupper(str_replace(' ', '', trim($string)), 'ISO-8859-1');
    }

    /**
     * @param array $dado
     * @return string
     */
    private function tratarInformacoesComplementares(array $dado)
    {
        // se esta não tem a informação das informações complementares
        if (empty($dado['informacoescomplementares'])) {
            return null;
        }

        // se o valor não inicar com PO
        if (strpos($dado['informacoescomplementares'], $this->informacaoComplementar) !== 0) {
            return null;
        }

        $informacoes = explode('-', str_replace(' ', '', $dado['informacoescomplementares']));
        return implode(',', $informacoes);
    }
}
