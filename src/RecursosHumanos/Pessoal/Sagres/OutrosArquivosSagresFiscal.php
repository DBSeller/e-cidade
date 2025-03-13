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

use Instituicao;

/**
 * Class OutrosArquivosSagresFiscal
 * @package ECidade\RecursosHumanos\Pessoal\Sagres
 */
abstract class OutrosArquivosSagresFiscal
{
    /**
     * @var integer
     */
    protected $ano;
    /**
     * @var object
     */
    protected $params;
    /**
     * @var array
     */
    protected $codigoInstituicoes = [];
    /**
     * @var integer
     */
    protected $codigoTCE;
    protected $linhas;
    protected $nameFile;

    public function __construct($params, array $codigoInstituicoes, $ano, $codigoTCE)
    {
        $this->params = $params;
        $this->codigoInstituicoes = $codigoInstituicoes;
        $this->codigoTCE = $codigoTCE;
        $this->ano = $ano;

        $oInstituicao = new Instituicao(db_getsession("DB_instit"));

        $codUG = $oInstituicao->getTribInst();

        $nomeArquivo = static::TAG;

        if ($nomeArquivo == "CodigoVantagensDescontos") {
            $nomeArquivo = "Codigo_VantagensDescontos";
        }

        $this->nameFile = $codUG.$this->params->dataSQL->mes.$this->params->dataSQL->ano.$nomeArquivo;
    }

    abstract protected function processar();

    public function emitirTXT()
    {
        $nomedoarquivo = 'tmp' . DS . $this->nameFile . '.txt';
        $fp = fopen($nomedoarquivo, "w");

        foreach ($this->linhas as $linha) {
            foreach ($this->colunasTemplate as $tag) {
                $linha = (array)$linha;
                if (array_key_exists($tag, $linha)) {
                    $linhaLimpa = str_replace(
                        "?",
                        " ",
                        mb_convert_encoding(($this->corrigeString($linha[$tag])), "Windows-1252", "UTF-8")
                    );
                    fputs($fp, "$linhaLimpa");
                } else {
                    $sLinha = utf8_encode("---");
                    fputs($fp, "$sLinha");
                }
            }
            fputs($fp, "\n");
        }

        fclose($fp);
        return $nomedoarquivo;
    }

    public function emitirCSV()
    {
        $nomedoarquivo = 'tmp' . DS . $this->nameFile . '.csv';
        $fp = fopen($nomedoarquivo, "w");
        fputs($fp, implode(';', $this->colunasTemplate)."\n");

        foreach ($this->linhas as $linha) {
            foreach ($this->colunasTemplate as $tag) {
                $linha = (array)$linha;
                if (array_key_exists($tag, $linha)) {
                    fputs($fp, "$linha[$tag];");
                } else {
                    fputs($fp, "---;");
                }
            }
            fputs($fp, "\n");
        }

        fclose($fp);
        return $nomedoarquivo;
    }

    public function emitirArquivos($formatos)
    {
        $files = [];
        $this->linhas = $this->processar();

        // if ($formatos['csv']) {
        //     $files[$this->nameFile.'.csv'] = $this->emitirCSV();
        // }

        if ($formatos['txt']) {
            $files[$this->nameFile.'.txt'] = $this->emitirTXT();
        }
        return $files;
    }

    public function corrigeString($string)
    {
        return preg_replace(
            array("/(á|à|ã|â|ä|ã)/",
                "/(Á|À|Â|Ä|Ã)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/",
                "/(ç)/",
                "/(Ç)/"),
            explode(" ", "a A e E i I o O u U n N c C"),
            $string
        );
    }
}
