<?php

namespace ECidade\File\Csv\Dumper;

use ECidade\File\Csv\Csv;

/**
 * Escreve um arquivo csv a partir de um array de strings
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package ECidade\File\Dumper
 *
 * @example
 *  use ECidade\File\Csv\Dumper\CSV;
 *
 *  $content = array (
 *      "Dado aleatório",
 *      12254,
 *      array("Cód Siconf", "2018/01", ""),
 *      array("Conta", "IC1", "TIPO1", "IC2", "TIPO2", "IC3", "TIPO3", "IC4", "TIPO4", "Valor", "Tipo_valor"),
 *  );
 *
 *  $cvs = new Dumper();
 *  $stringCsv = $cvs->dump($content);
 *  echo "<pre>";
 *  echo($stringCsv);
 *  echo "</pre>";
 *  -------------------------
 *  "Dado aleatório"
 *  12254
 *  "Cód Siconf";2018/01;
 *  Conta;IC1;TIPO1;IC2;TIPO2;IC3;TIPO3;IC4;TIPO4;Valor;Tipo_valor
 */
class Dumper extends Csv
{
    /**
     * Escreve o conteúdo do csv em um arquivo
     *
     * @param array $content
     * @param string $file arquivo onde será escrito o csv
     * @return string
     */
    public function dumpToFile($content, $file = null)
    {
        if (is_null($file)) {
            $file = time() . '.csv';
        }

        file_put_contents($file, $this->dump($content));

        return $file;
    }

    /**
     * Realiza o dump do conteudo informado em uma string csv
     *
     * @param array $content
     * @return string
     */
    public function dump($content)
    {
        $handle = fopen('php://memory', 'rb+');

        foreach ($content as $line) {
            if (!is_array($line)) {
                $line = array($line);
            }
            fputcsv($handle, $line, $this->delimiter, $this->enclosure);
        }

        rewind($handle);
        $output = stream_get_contents($handle);
        fclose($handle);

        return $output;
    }

    /**
     * Lê um arquivo csv e o coloca em um array.
     * @param string $caminhoCsv
     * @return array
     */
    public function ler($caminhoCsv)
    {
        $arquivo = fopen($caminhoCsv, 'r');
        $linhas = array();

        while (!feof($arquivo)) {
            $linhas[] = fgetcsv($arquivo, 0, $this->delimiter, $this->enclosure);
        }

        fclose($arquivo);
        return $linhas;
    }
}
