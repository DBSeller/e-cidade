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

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository;

/**
 * Class EFDReaberturaEventos
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class EFDReaberturaEventos extends ProcessamentoAbstract implements ProcessamentoInterface
{

    /**
     * @var string
     */
    private $cgm;

    /**
     * @var null
     */
    private $instituicao;

    /**
     * @var null
     */
    private $ano;

    /**
     * @var null
     */
    private $mes;

    /**
     * EFDReaberturaEventos constructor.
     * @param $cgm
     * @param null $instituicao
     * @param null $ano
     * @param null $mes
     */
    public function __construct($cgm, $instituicao = null, $ano = null, $mes = null)
    {
        $this->cgm = $cgm;
        $this->instituicao = $instituicao;
        $this->ano = $ano;
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function processar()
    {
        $cgmInstance = \CgmFactory::getInstanceByCgm($this->cgm);
        $cgm = $this->cgm;
        $inscricao = $cgmInstance->getCnpj();


        $alteracaoDados = false;

        $dados = new \stdClass();
        $dados->referencia = "{$this->ano}-{$this->mes}";
        $dados->perapur = $dados->referencia;
        $dados->inscricao_contribuinte = $inscricao;

        $eventoFila = new Evento(Tipo::R2098, $cgm, $dados->referencia, $dados);

        if ($eventoFila->adicionarFila()) {
            $repository = new Repository\ESocialEnvioRepository();
            $repository->scopeEmpregador($cgm);
            $repository->scopeEvento(current(Tipo::getLayout(Tipo::EFD_FECHAMENTO_PERIODICOS)));
            $r2099 = Tipo::getTitulos(Tipo::EFD_FECHAMENTO_PERIODICOS);
            $repository->scopeResponsavelPreenchimento($dados->referencia);
            $fechamento_periodico = current($repository->get());
            if(!$fechamento_periodico) {
                $competencia = str_replace("-", "/", $dados->referencia);
                throw new \Exception("Não é possível reabrir a competência {$competencia}, pois o evento {$r2099} não foi enviado.");
            } else {
                $repository->atualizarEvento($fechamento_periodico->getCodigo(), $fechamento_periodico->getSituacaosalva(), true);
                $alteracaoDados = true;
            }
        }
//        kill("");

        return $alteracaoDados;
    }
}