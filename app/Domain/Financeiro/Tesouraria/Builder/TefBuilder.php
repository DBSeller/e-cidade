<?php


namespace App\Domain\Financeiro\Tesouraria\Builder;

use App\Domain\Financeiro\Tesouraria\Mappers\TefMapper;

use DBNumber;
use ECidade\File\Csv\Csv;
use ECidade\Financeiro\Tesouraria\Models\LinhaTef;
use Exception;

class TefBuilder
{
    /**
     * @var TefMapper
     */
    private $mapper;
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var LinhaTef[]
     */
    private $collection = [];

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function setMapper(TefMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function build()
    {
        $data = $this->readCsv();
        $header = $data[0];
        unset($data[0]);

        foreach ($data as $line) {
            if (!$this->validLine($line)) {
                continue;
            }
            $this->collection[] = LinhaTef::fromState($this->mapper->parse($line, $header));
        }

        return $this->collection;
    }

    /**
     * Lê o arquivo, e retorna as linhas em um array
     * @return array
     * @throws Exception
     */
    private function readCsv()
    {
        $csv = new Csv();
        return $csv->readFromFile($this->filePath);
    }

    /**
     * Valida se a coluna Autorização esta vazia.
     * Só retornamos como válida as linhas que possuem o código de autorização
     * @param $line
     * @return bool
     */
    private function validLine($line)
    {
        $valueKeyColumn = trim($line[$this->mapper->getCollumnKey()]);
        if (empty($valueKeyColumn)) {
            return false;
        }

        return  true;
    }
}
