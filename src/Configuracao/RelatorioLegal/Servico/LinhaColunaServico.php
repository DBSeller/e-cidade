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

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColuna;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaValorRepositorio;
use Exception;
use stdClass;

/**
 * Class LinhaColunaServico
 * @package ECidade\Configuracao\RelatorioLegal\Servico
 */
class LinhaColunaServico
{
    /**
     * @var stdClass
     */
    private $parametros;

    /**
     * LinhaColunaServico constructor.
     * @param stdClass $parametros
     */
    public function __construct(stdClass $parametros)
    {
        $this->parametros = $parametros;
    }

    /**
     * @return LinhaColuna
     * @throws Exception
     */
    public function salvar()
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception("Código do relatório não informado.");
        }

        if (empty($this->parametros->linha)) {
            throw new Exception("Código da linha não informado.");
        }

        if (empty($this->parametros->coluna)) {
            throw new Exception("Código da coluna não informado.");
        }

        if (empty($this->parametros->ordem)) {
            throw new Exception("Ordem da coluna não informada.");
        }

        if (empty($this->parametros->periodo)) {
            throw new Exception("Código do período não informado.");
        }


        $linhaColuna = new LinhaColuna();
        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);
        $linha = LinhaRegistry::get($relatorio, $this->parametros->linha);
        $coluna = ColunaRegistry::get($this->parametros->coluna);

        if (!empty($this->parametros->sequencial)) {
            $linhaColuna->setSequencial($this->parametros->sequencial);
        }

        $linhaColuna->setRelatorio($relatorio);
        $linhaColuna->setLinha($linha);
        $linhaColuna->setColuna($coluna);
        $linhaColuna->setOrdem($this->parametros->ordem);
        $linhaColuna->setPeriodo($this->parametros->periodo);

        if (!empty($this->parametros->formula)) {
            $linhaColuna->setFormula($this->parametros->formula);
        }

        LinhaColunaRepositorio::save($linhaColuna);

        return $linhaColuna;
    }

    /**
     * @return LinhaColuna[]
     * @throws Exception
     */
    public function buscarPorRelatorioLinha()
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception("Código do relatório não informado.");
        }

        if (empty($this->parametros->linha)) {
            throw new Exception("Código da linha não informado.");
        }
        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);
        $linha = LinhaRegistry::get($relatorio, $this->parametros->linha);
        $linhaColunaRepositorio = new LinhaColunaRepositorio();

        return $linhaColunaRepositorio
            ->scopeRelatorio($relatorio)
            ->scopeLinha($linha)
            ->get();
    }

    /**
     * @throws Exception
     */
    public function excluirPorColuna()
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception("Código do relatório não informado.");
        }

        if (empty($this->parametros->linha)) {
            throw new Exception("Código da linha não informado.");
        }

        if (empty($this->parametros->coluna)) {
            throw new Exception("Código da coluna não informado.");
        }

        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);
        $linha = LinhaRegistry::get($relatorio, $this->parametros->linha);
        $coluna = ColunaRegistry::get($this->parametros->coluna);
        $linhaColunaRepositorio = new LinhaColunaRepositorio();

        $linhaColuna = $linhaColunaRepositorio->scopeRelatorio($relatorio)
            ->scopeLinha($linha)
            ->scopeColuna($coluna)->get();

        foreach ($linhaColuna as $linha) {
            $linhaColunaRepositorio
                ->resetScopes()
                ->delete($linha);
        }
    }

    /**
     * @param stdClass $parametros
     */
    public function setParametros(stdClass $parametros)
    {
        $this->parametros = $parametros;
    }
}
