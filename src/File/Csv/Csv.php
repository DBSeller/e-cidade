<?php
namespace ECidade\File\Csv;

use Exception;

class Csv
{
    protected $delimiter = ',';
    protected $enclosure = '"';

    /**
     * Seta as váriaveis de controle para escrita do csv
     *
     * @param string $delimiter delimitador de colunas
     * @param string $enclosure enclosure das strings complexas
     */
    public function setCsvControl($delimiter = ';', $enclosure = '"')
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    /**
     * Para uso mais performático e com menos uso de memória, use a classe LerCsv
     *
     * @param $file
     * @return array
     * @throws Exception
     */
    public function readFromFile($file)
    {
        $handle = fopen($file, "r");
        if ($handle === false) {
            throw new Exception("Arquivo {$file} não pode ser lido", 406);
        }

        $dados = [];
        while (($data = fgetcsv($handle, 0, $this->delimiter, $this->enclosure)) !== false) {
            $dados[] = $data;
        };

        return $dados;
    }
}
