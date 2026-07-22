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

namespace ECidade\Educacao\Escola\Censo\Identificacao\Layout;

use ECidade\Educacao\Escola\Censo\Identificacao\Model\Pessoa;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper\Mapper;

/**
 * Class Layout
 * @package ECidade\Educacao\Escola\Censo\Identificacao\Layout
 */
class Layout
{
    /**
     * @var Pessoa[]
     */
    private $pessoas;

    private $dePara = [
        'Código do aluno na Entidade/Escola' => 'codigo_pessoa',
        'Número do CPF' => 'cpf',
        'Número da Matrícula (Registro Civil - Certidão de nascimento)' => 'certidao_nascimento',
        'Nome completo' => 'nome',
        'Data de nascimento' => 'data_nascimento',
        'Filiação 1 (Preferencialmente o nome da mãe)' => 'filiacao_1',
        'Filiação 2 (Preferencialmente o nome do pai)' => 'filiacao_2',
        'Município de nascimento' => 'municipio_nascimento',
        'Identificação única do aluno (Inep)' => 'inep',
    ];

    /**
     * @var string
     */
    private $filePath;
    /**
     * @var array
     */
    private $dadosArquivo = [];

    public function __construct()
    {
        $this->filePath = "tmp/layout_identificacao_" . time() . ".txt";
    }

    /**
     * @param Pessoa[] $pessoas
     */
    public function setPessoas(array $pessoas)
    {
        $this->pessoas = $pessoas;
    }

    /**
     * @return string
     */
    public function gerarArquivo()
    {
        $this->parsePessoas();
        $this->dumpToFile();
        return $this->filePath;
    }


    protected function parse(array $dadosRegistro)
    {
        $dados = array();
        foreach ($this->dePara as $item) {
            $dados[] = $dadosRegistro[$item];
        }

        return $dados;
    }

    private function parsePessoas()
    {
        foreach ($this->pessoas as $pessoa) {
            $this->dadosArquivo[] = $this->parse($pessoa->toArray());
        }
    }

    protected function dumpToFile()
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
}
