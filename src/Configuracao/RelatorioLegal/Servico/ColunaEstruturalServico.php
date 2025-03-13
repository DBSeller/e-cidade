<?php
/**
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

use ECidade\Configuracao\RelatorioLegal\Modelo\ColunaEstrutural;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaEstruturalRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaEstruturalRepositorio;
use Exception;
use stdClass;

/**
 * Class ColunaEstruturalServico
 * @package ECidade\Configuracao\RelatorioLegal\Servico
 */
class ColunaEstruturalServico
{
    /**
     * @var stdClass
     */
    private $parametros;

    /**
     * @var ColunaEstruturalRepositorio
     */
    private $repositorio;

    /**
     * LinhaServico constructor.
     * @param stdClass $parametros
     */
    public function __construct(stdClass $parametros)
    {
        $this->parametros = $parametros;
        $this->repositorio = new ColunaEstruturalRepositorio();
    }

    /**
     * @throws Exception
     */
    public function salvar()
    {
        if (empty($this->parametros->exclusao)) {
            throw new Exception("Exclusão não informada.");
        }

        if (empty($this->parametros->estrutural)) {
            throw new Exception("Estrutural não informado.");
        }

        if (empty($this->parametros->coluna)) {
            throw new Exception('Coluna não informada');
        }

        if (empty($this->parametros->ano)) {
            throw new Exception('Ano não informado.');
        }

        $colunaEstrutural = new ColunaEstrutural();
        $colunaEstrutural->setExclusao($this->parametros->exclusao === 'true');
        $colunaEstrutural->setEstrutural($this->parametros->estrutural);
        $colunaEstrutural->setColuna(ColunaRegistry::get($this->parametros->coluna));
        $colunaEstrutural->setAno($this->parametros->ano);

        if (!empty($this->parametros->sequencial)) {
            $colunaEstrutural->setSequencial($this->parametros->sequencial);
        }

        ColunaEstruturalRepositorio::save($colunaEstrutural);
    }

    /**
     * @throws Exception
     */
    public function excluir()
    {
        if (empty($this->parametros->sequencial)) {
            throw new Exception('É necessário informar o código sequencial.');
        }

        $colunaEstrutural = ColunaEstruturalRegistry::get($this->parametros->sequencial);
        $this->repositorio->delete($colunaEstrutural);
    }

    /**
     * @throws Exception
     */
    public function excluirPorColuna()
    {
        $colunaEstruturais = $this->buscarColunaEstruturalPorColuna();

        foreach ($colunaEstruturais as $colunaEstrutural) {
            $this->repositorio->resetScopes()->delete($colunaEstrutural);
        }
    }

    /**
     * @return ColunaEstrutural[]
     * @throws Exception
     */
    public function buscarColunaEstruturalPorColuna()
    {
        if (empty($this->parametros->coluna)) {
            throw new Exception("É necessário informar a coluna.");
        }

        return $this->repositorio->scopeColuna(
            ColunaRegistry::get($this->parametros->coluna)
        )->get();
    }
}
