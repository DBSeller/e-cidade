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

namespace ECidade\RecursosHumanos\ESocial\Service\ContribuicaoSindical;


use CgmRepository;
use ECidade\RecursosHumanos\ESocial\Mapeadores\ContribuicaoSindical\TipoContribuicao;
use ECidade\RecursosHumanos\ESocial\Model\ContribuicaoSindical\Contribuicao;
use ECidade\RecursosHumanos\ESocial\Model\ContribuicaoSindical\Periodo;
use ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical\ContribuicaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical\PeriodoRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\SindicatoRepository;
use Exception;
use stdClass;

class PeriodoService
{
    /**
     * @var
     */
    private $periodoRepository;
    private $contribuicaoRepository;

    /**
     * PeriodoService constructor.
     */
    public function __construct()
    {
        $this->periodoRepository = new PeriodoRepository();
        $this->contribuicaoRepository = new ContribuicaoRepository();
    }

    /**
     * @param stdClass $parametros
     * @return Periodo
     * @throws Exception
     */
    public function salvar(stdClass $parametros)
    {
        if (empty($parametros->empregador)) {
            throw new Exception('O campo "Empregador" é obrigatório.');
        }

        if (empty($parametros->indicativoPeriodo)) {
            throw new Exception('O campo "Indicativo de Período" é obrigatório.');
        }

        if (empty($parametros->periodo)) {
            throw new Exception('O campo "Período" é obrigatório.');
        }

        /**
         * @todo validar no backend se periodo esta compativel com indicativo de periodo
         */

        $periodo = new Periodo();
        $periodo->setEmpregador(CgmRepository::getByCodigo($parametros->empregador));
        $periodo->setIndicativoPeriodo($parametros->indicativoPeriodo);
        $periodo->setPeriodo($parametros->periodo);

        if (isset($parametros->sequencialPeriodo) && $parametros->sequencialPeriodo) {
            $periodo->setSequencial($parametros->sequencialPeriodo);
            $this->periodoRepository->scopeSequencial($parametros->sequencialPeriodo, '!=');
        }

        $existe = $this->periodoRepository->scopeEmpregador($parametros->empregador)
            ->scopeIndicativoPeriodo($parametros->indicativoPeriodo)
            ->scopePeriodo($parametros->periodo)
            ->get();

        if (count($existe) > 0) {
            throw new Exception("Já existe cadastrado o período {$parametros->periodo} para o empregador {$periodo->getEmpregador()->getNome()}.");
        }

        $this->periodoRepository->resetScopes();
        return $this->periodoRepository->save($periodo);
    }

    /**
     * @param $parametros
     * @return Contribuicao
     * @throws Exception
     */
    public function adicionarContribuicao($parametros)
    {
        if (empty($parametros->sequencialPeriodo)) {
            throw new Exception('O "Período" deve estar selecionado.');
        }
        if (empty($parametros->codigoSindicato)) {
            throw new Exception('O campo "Sindicato" é obrigatório.');
        }
        if (empty($parametros->tipoContribuicao)) {
            throw new Exception('O campo "Tipo de Contribuição" é obrigatório.');
        }

        if (empty($parametros->valor)) {
            throw new Exception('O campo "Valor" é obrigatório.');
        }

        $periodo = PeriodoRepository::find($parametros->sequencialPeriodo);
        $contribuicao = new Contribuicao();
        $contribuicao->setPeriodo($periodo);
        $contribuicao->setSindicato(SindicatoRepository::find($parametros->codigoSindicato));
        $contribuicao->setTipoContribuicao($parametros->tipoContribuicao);
        $contribuicao->setValor($parametros->valor);

        if (isset($parametros->sequencialContribuicao) && $parametros->sequencialContribuicao) {
            $contribuicao->setSequencial($parametros->sequencialContribuicao);
            $this->contribuicaoRepository->scopeSequencial($parametros->sequencialContribuicao, '!=');
        }
        $existe = $this->contribuicaoRepository->scopeSindicato($parametros->codigoSindicato)
            ->scopeTipoContribuicao($parametros->tipoContribuicao)
            ->scopePeriodo($parametros->sequencialPeriodo)
            ->get();

        if (count($existe) > 0) {
            $sindicato = $contribuicao->getSindicato()->getRazaoSocial();
            $tipo = TipoContribuicao::get($parametros->tipoContribuicao);
            throw new Exception("O sindicato informado {$sindicato} com o tipo de contribuição {$tipo} já esta cadastrado o período {$periodo->getPeriodo()}.");
        }

        $this->contribuicaoRepository->resetScopes();

        return $this->contribuicaoRepository->save($contribuicao);
    }
}
