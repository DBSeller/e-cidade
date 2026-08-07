<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\LayoutPad;
use Exception;
use Instituicao;

abstract class PadService
{
    /**
     * @var string
     */
    protected $header;
    /**
     * @var string
     */
    protected $fileName;


    /**
     * @var array
     */
    protected $dadosProcessados = [];

    /**
     * @var array
     */
    protected $dadosCSV = [];

    /**
     * @var Instituicao[]
     */
    protected $instituicoes = [];

    /**
     * seta o cabeçalho do arquivo
     * @param string $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return LayoutPad[]
     * @throws Exception
     */
    abstract protected function getDados();

    /**
     * @return LayoutPad
     */
    abstract protected function getBuilder();

    /**
     * @return bool
     * @throws Exception
     */
    public function processa()
    {
        $dump = new GerarArquivoService($this->fileName, $this->header);
        $linhas = 0;
        foreach ($this->getDados() as $dado) {
            $dadoLayout = $dado->parse($dado->toArray());
            $dump->writeLine($dadoLayout);
            $linhas ++;
        }

        $dump->writeFooter($linhas);
        return true;
    }

    /**
     * Retorna uma lista dos códigos das instituicoes separado por vírgula
     * @return string
     */
    protected function getListaInstituicoes()
    {
        return implode(', ', array_map(function (Instituicao $instituicao) {
            return $instituicao->getCodigo();
        }, $this->instituicoes));
    }
}
