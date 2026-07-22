<?php


namespace ECidade\Educacao\Secretaria\BNCC\Service;

/**
 * Class PlanilhaHabilidadeService
 * @package ECidade\Educacao\Secretaria\BNCC\Service
 */
abstract class PlanilhaHabilidadeService
{
    /**
     * linhas do arquivo
     * @var array
     */
    protected $linhas = [];

    /**
     * @var string
     */
    protected $tabela;

    /**
     * @var string
     */
    protected $sequence;

    /**
     * @var array
     */
    protected $colunas = [];

    /**
     * @var array
     */
    protected $dados = [];

    /**
     * @param array $linhas
     */
    public function setLinhas(array $linhas)
    {
        $this->linhas = $linhas;
    }

    /**
     * @return string
     */
    public function getFileDump()
    {
        $dump = sprintf("insert into %s (%s) values ", $this->tabela, implode(', ', $this->colunas));

        $values = [];
        foreach ($this->dados as $dado) {
            $values[] = sprintf('(%s)', implode(', ', $dado));
        }

        $dump .= implode(",\n", $values);
        $dump .= ';';

        $output = 'tmp/dump_' . time() . '.sql' ;
        file_put_contents($output, $dump);

        return $output;
    }

    /**
     * @param $coluna
     * @return false|string
     */
    protected function extractCodigo($coluna)
    {
        $pos1 = strpos($coluna, '(');
        $pos2 = strpos($coluna, ')');

        return substr($coluna, $pos1+1, $pos2-1);
    }

    /**
     * @param string $linha
     * @return string|string[]
     */
    protected function removeQuebraLinha($linha)
    {
        $linha = str_replace("\n", ' ', trim($linha)); // remove quebra de linha
        $linha = preg_replace('/\s{2,}/', ' ', $linha); // remove espaços duplicados
        return $linha;
    }
}
