<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\Pessoal\Sagres;

use ECidade\File\Csv\Dumper\Dumper;
use ECidade\File\Xml;
use Exception;

/**
 * Class ParseXML
 * @package ECidade\Financeiro\Contabilidade\Sagres
 */
class ParseXML
{
    /**
     * @var string
     */
    private $nomeArquivo;

    /**
     * @var string
     */
    private $caminhoArquivo;

    /**
     * @var string
     */
    private $extensao;

    /**
     * @var array
     */
    private $linhas = [];

    /**
     * @var array
     */
    private $prefixos = [
        'rpn',
        'rap',
        'nte',
        'fnt',
        'pub',
        'rgf',
        'dcp',
        'dcl',
        'ddc',
        'bfu',
        'boo',
        'reo',
        'rpps',
        'rppf',
        'rppp',
        'rcl',
        'mde',
        'ass',
    ];

    /**
     * ParseXML constructor.
     * @param string $nomeArquivo
     * @param string $caminhoArquivo
     * @param integer $ano
     */
    public function __construct($nomeArquivo, $caminhoArquivo, $extensao)
    {
        $this->nomeArquivo = $nomeArquivo;
        $this->caminhoArquivo = $caminhoArquivo;
        $this->extensao = $extensao;
    }


    /**
     * @throws Exception
     */
    private function parse()
    {
        if (empty($this->linhas)) {
            $dadosXML = Xml::xmlToArray($this->caminhoArquivo);
            $linhas = array_shift($dadosXML);
            $cabecalho = $this->criaCabecalho($linhas[0]);

            $this->linhas = array_merge([$cabecalho], $this->normalizaLinhas($linhas));
        }

        return $this->linhas;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray()
    {
        return $this->parse();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function dumpToCSV()
    {
        $linhas = $this->parse();
        $dump = new Dumper();
        $caminhoArquivo = 'tmp' . DS . $this->getNome() . '.csv';
        return $dump->dumpToFile($linhas, $caminhoArquivo);
    }

    public function getNome()
    {
        return str_replace(".{$this->extensao}", '', $this->nomeArquivo);
    }

    /**
     * @param $colunas
     * @return array
     */
    private function criaCabecalho($colunas)
    {
        $cabecalho = [];
        foreach ($colunas as $key => $valor) {
            $cabecalho[] = $this->getNomeCabecalho($key);
        }
        return $cabecalho;
    }

    /**
     * @param $string
     * @return false|string
     */
    private function getNomeCabecalho($string)
    {
        foreach ($this->prefixos as $prefixo) {
            if (strpos($string, $prefixo) === 0) {
                return substr($string, strlen($prefixo));
            }
        }

        return $string;
    }

    /**
     * @param $linhas
     * @return array|array[]
     */
    private function normalizaLinhas($linhas)
    {
        return array_map(function ($colunas) {
            $colunas = array_map(function ($coluna) {
                return array_shift($coluna);
            }, $colunas);
            return array_values($colunas);
        }, $linhas);
    }
}
