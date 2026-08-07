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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout;

use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro00Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro10Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro20Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro30Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro40Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro50Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Registro60Mapper;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro00;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro10;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro20;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro30;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro40;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro50;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro60;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro99;

class Layout
{
    /**
     * @var Registro00
     */
    private $registro00;

    /**
     * @var Registro10
     */
    private $registro10;
    /**
     * @var Registro20[]
     */
    private $registros20 = array();

    /**
     * @var Registro30[]
     */
    private $registros30 = array();

    /**
     * @var Registro40[]
     */
    private $registros40 = array();

    /**
     * @var Registro50[]
     */
    private $registros50 = array();

    /**
     * @var Registro60[]
     */
    private $registros60 = array();

    /**
     * @var array
     */
    private $dadosArquivo = array();

    /**
     * @var string
     */
    private $filePath;

    public function __construct()
    {
        $this->filePath = "tmp/matricula_inicial_" . time() . ".txt";
    }

    /**
     * @return string
     */
    public function gerarArquivo()
    {
        $this->parse();
        $this->dumpToFile();
        return $this->filePath;
    }


    /**
     * @param Registro00 $registro00
     * @return Layout
     */
    public function setRegistro00($registro00)
    {
        $this->registro00 = $registro00;
        return $this;
    }

    /**
     * @param Registro10 $registro10
     * @return Layout
     */
    public function setRegistro10(Registro10 $registro10)
    {
        $this->registro10 = $registro10;
        return $this;
    }

    /**
     * @param Registro20[] $registros20
     * @return Layout
     */
    public function setRegistros20(array $registros20)
    {
        $this->registros20 = $registros20;
        return $this;
    }

    /**
     * @param Registro30[] $registros30
     * @return Layout
     */
    public function setRegistros30(array $registros30)
    {
        $this->registros30 = $registros30;
        return $this;
    }

    /**
     * @param Registro40[] $registros40
     * @return Layout
     */
    public function setRegistros40(array $registros40)
    {
        $this->registros40 = $registros40;
        return $this;
    }

    /**
     * @param Registro50[] $registros50
     * @return Layout
     */
    public function setRegistros50(array $registros50)
    {
        $this->registros50 = $registros50;
        return $this;
    }

    /**
     * @param Registro60[] $registros60
     * @return Layout
     */
    public function setRegistros60(array $registros60)
    {
        $this->registros60 = $registros60;
        return $this;
    }

    private function parse()
    {
        $this->parseRegistro00();
        $this->parseRegistro10();
        $this->parseRegistro20();
        $this->parseRegistro30();
        $this->parseRegistro40();
        $this->parseRegistro50();
        $this->parseRegistro60();
        $this->parseRegistro99();
    }

    private function parseRegistro00()
    {
        $mapper = new Registro00Mapper();
        $this->dadosArquivo[] = $mapper->parse($this->registro00->toArray());
    }

    private function parseRegistro10()
    {
        $mapper = new Registro10Mapper();
        $this->dadosArquivo[] = $mapper->parse($this->registro10->toArray());
    }

    private function parseRegistro20()
    {
        $this->parseCollection(new Registro20Mapper(), $this->registros20);
    }

    private function parseRegistro30()
    {
        $this->parseCollection(new Registro30Mapper(), $this->registros30);
    }

    private function parseRegistro40()
    {
        $this->parseCollection(new Registro40Mapper(), $this->registros40);
    }

    private function parseRegistro50()
    {
        $this->parseCollection(new Registro50Mapper(), $this->registros50);
    }

    private function parseRegistro60()
    {
        $this->parseCollection(new Registro60Mapper(), $this->registros60);
    }

    private function parseRegistro99()
    {
        $this->getRegistro99();
    }

    /**
     * @param Mapper $mapper
     * @param Registro20[]|Registro30[]|Registro40[]|Registro50[]|Registro60[] $registros
     */
    private function parseCollection(Mapper $mapper, array $registros) {
        foreach ($registros as $registro) {
            $this->dadosArquivo[] = $mapper->parse($registro->toArray());
        }
    }

    private function dumpToFile()
    {
        $handle = fopen("{$this->filePath}", 'x+');

        foreach ($this->dadosArquivo as $dados) {
            if (!is_array($dados)) {
                $dados = array($dados);
            }

            fwrite($handle, implode('|', $dados) . "\n");
        }

        fclose($handle);
    }

    /**
     * Método criado para geração da linha final do arquivo do censo, segundo regra:
     *
     * "35. Deve haver um registro 99 (99 seguido de pipe "|" apenas) ao final do arquivo,
     *  independente do numero de escolas, sinalizando que o mesmo foi encerrado."
     *
     * Por não haver uma estrutura lógica de busca de dados, nem sabermos de informações a serem buscadas, foi feito da
     * maneira mais simples
     */
    private function getRegistro99()
    {
        $registro = new Registro99();
        $this->dadosArquivo[] = $registro->getTipoRegistro() . "|";
    }
}
