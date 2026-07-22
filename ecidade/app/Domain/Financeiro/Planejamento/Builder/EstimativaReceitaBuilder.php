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

use App\Domain\Financeiro\Planejamento\Mappers\EstimativaReceitaMapper;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use stdClass;

/**
 * Class EstimativaReceitaBuilder
 * @package App\Domain\Financeiro\Planejamento\Builder
 */
class EstimativaReceitaBuilder
{
    protected $mapper;

    public function __construct()
    {
        $this->mapper = new EstimativaReceitaMapper();
    }

    /**
     * @param stdClass $dados
     * @param EstruturalReceita $estrutural
     * @param boolean $temDesdobramento
     * @return object
     */
    public function buildAnalitico($dados, EstruturalReceita $estrutural, $temDesdobramento = false)
    {
        $this->defaultData($estrutural, $dados, $temDesdobramento);
        $valores = json_decode($dados->valores);
        $this->mapper->setValores($valores);

        $inflatores = json_decode($dados->inflatores);

        /**
         * caso não tenha inflator confirurado configura com 0%
         */
        if (empty($inflatores)) {
            foreach ($valores as $vlr) {
                $inflatores[] = (object)[
                    "ano" => $vlr->ano,
                    "percentual" => 0,
                    "deflator" => false,
                ];
            }
        }
        $this->mapper->setInflatores($inflatores);

        return $this->mapper->toArray();
    }

    /**
     * @param EstruturalReceita $estrutural
     * @param $valoresSintetico
     * @return object
     */
    public function buildSintetico(EstruturalReceita $estrutural, $valoresSintetico)
    {
        $this->mapper->setNivel($estrutural->getNivel());
        $this->mapper->setFonte($estrutural->getEstrutural());
        $this->mapper->setFonteMascara($estrutural->getEstruturalComMascara());
        $this->mapper->setValores($valoresSintetico);
        $this->mapper->setValorBase(0);
        return $this->mapper->toArray();
    }

    /**
     * @param EstruturalReceita $estrutural
     * @param stdClass $dados
     * @param $temDesdobramento
     */
    protected function defaultData(EstruturalReceita $estrutural, stdClass $dados, $temDesdobramento)
    {
        $this->mapper->setNivel($estrutural->getNivel());
        $this->mapper->setId($dados->id);
        $this->mapper->setPlanejamentoId($dados->planejamento_id);
        $this->mapper->setAnoorcamento($dados->anoorcamento);
        $this->mapper->setOrcfontesId($dados->orcfontes_id);
        $this->mapper->setRecursoId($dados->recurso_id);
        $this->mapper->setInstituicaoId($dados->instituicao_id);
        $this->mapper->setConcarpeculiarId($dados->concarpeculiar_id);
        $this->mapper->setOrcorgaoId($dados->orcorgao_id);
        $this->mapper->setOrcunidadeId($dados->orcunidade_id);
        $this->mapper->setCodigoEsferaorcamentaria($dados->esferaorcamentaria);
        $this->mapper->setValorBase((float)$dados->valorbase);
        $this->mapper->setInclusaomanual($dados->inclusaomanual);
        $this->mapper->setFonte($dados->fonte);
        $this->mapper->setFonteMascara($estrutural->getEstruturalComMascara());
        $this->mapper->setDescricaoFonte($dados->descricao_fonte);
        $this->mapper->setDescricaoOrgao($dados->descricao_orgao);
        $this->mapper->setDescricaoUnidade($dados->descricao_unidade);
        $this->mapper->setCaracteristicaPeculiar($dados->caracteristica_peculiar);
        $this->mapper->setNomeInstituicao($dados->nome_instituicao);
        $this->mapper->setRecurso($dados->recurso);
        $this->mapper->setFonteRecurso($dados->fonte_recurso);
        $this->mapper->setCodigoComplemento($dados->codigo_complemento);
        $this->mapper->setComplemento($dados->complemento);
        $this->mapper->temDesdobramento($temDesdobramento);
        $this->mapper->setDescricaoIdentificadorResultado($dados->identificador_resultado);
        $this->mapper->setDescricaoEsferaOrcamentaria($dados->esfera_orcamentaria);
    }
}
