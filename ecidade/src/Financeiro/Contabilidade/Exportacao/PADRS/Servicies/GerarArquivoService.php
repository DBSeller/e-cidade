<?php


namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies;

/**
 * Class GerarPADService
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies
 */
class GerarArquivoService
{
    private $filePath;
    private $header;
    private $handle;

    public function __construct($fileName, $header)
    {
        $this->filePath = "tmp/{$fileName}";
        $this->header = $header;

        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        $this->handle = fopen($this->filePath, 'x+');
        $this->writeHeader();
    }

    public function writeHeader()
    {
        $this->writeLine([$this->header]);
        return $this->filePath;
    }



    /**
     * @param $registros
     * @return string
     */
    public function writeFooter($registros)
    {
        return $this->writeLine("FINALIZADOR" . str_pad($registros, 10, '0', STR_PAD_LEFT));
    }

    public function writeLine($dadoLayout)
    {
        if (!is_array($dadoLayout)) {
            $dadoLayout = [$dadoLayout];
        }

        fwrite($this->handle, implode('', $dadoLayout) . "\n");
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}
