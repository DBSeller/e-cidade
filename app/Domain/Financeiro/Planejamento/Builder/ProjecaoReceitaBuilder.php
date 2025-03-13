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

namespace App\Domain\Financeiro\Planejamento\Builder;

use App\Domain\Financeiro\Planejamento\Mappers\ProjecaoReceitaMapper;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use stdClass;

/**
 * Class ProjecaoReceitaBuilder
 * @package App\Domain\Financeiro\Planejamento\Builder
 */
class ProjecaoReceitaBuilder
{
    /**
     * @var ProjecaoReceitaMapper
     */
    private $mapper;

    /**
     * @var stdClass
     */
    private $stdClass;

    private $valorBase;
    /**
     * @var array
     */
    private $valores;

    public function __construct()
    {
        $this->mapper = new ProjecaoReceitaMapper();
    }

    /**
     * @param stdClass $dados
     * @return ProjecaoReceitaBuilder
     */
    public function addFromStdClass(stdClass $dados)
    {
        $this->stdClass = $dados;
        $this->mapper->ano = $dados->o70_anousu;
        $this->mapper->fonte = $dados->o57_fonte;
        $this->mapper->codigoFonte = $dados->o70_codfon;
        $this->mapper->descricao = $dados->o57_descr;
        $this->mapper->orgao = $dados->o70_orcorgao;
        $this->mapper->unidade = $dados->o70_orcunidade;
        $this->mapper->instituicao = $dados->o70_instit;
        $this->mapper->recurso = $dados->o70_codigo;
        $this->mapper->caracteristicaPeculiar = $dados->o70_concarpeculiar;
        $this->mapper->esferaOrcamentaria = $dados->o70_esferaorcamentaria;
        $this->mapper->estrutural = new EstruturalReceita($dados->o57_fonte);
        return $this;
    }

    /**
     * @param $valor
     * @return ProjecaoReceitaBuilder
     */
    public function addValorBase($valor)
    {
        $this->mapper->valorBase = $valor;
        return $this;
    }

    /**
     * @param array $valores
     * @return ProjecaoReceitaBuilder
     */
    public function addValoresProjetados(array $valores)
    {
        $this->mapper->valoresProjetados = $valores;
        return $this;
    }

    /**
     * @param boolean $tipoInclusao
     * @return ProjecaoReceitaBuilder
     */
    public function addIsManual($tipoInclusao)
    {
        $this->mapper->manual = $tipoInclusao;
        return $this;
    }

    /**
     * @param \App\Domain\Financeiro\Planejamento\Models\Planejamento $planejamento
     * @return $this
     */
    public function addPlanejamento(\App\Domain\Financeiro\Planejamento\Models\Planejamento $planejamento)
    {
        $this->mapper->planejamento = $planejamento;
        return $this;
    }

    /**
     * @return ProjecaoReceitaMapper
     */
    public function build()
    {
        return $this->mapper;
    }
}
