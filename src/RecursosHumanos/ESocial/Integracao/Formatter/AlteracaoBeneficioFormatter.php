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

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use AfastamentoRepository;
use CgmJuridico;
use DBDate;
use DBException;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorMovimentacao;
use ECidade\RecursosHumanos\ESocial\Repository\ServidorAlteracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class AlteracaoBeneficioFormatter extends CadastroBeneficioFormatter
{
    /**
     * @var \Servidor
     */
    private $servidorAtual;


    /**
     * @var \DBDate || null
     */
    private $dataAlteracao = null;


    /**
     * @var \ServidorMovimentacao
     */
    private $movimentacaoAtual;
    /**
     * @var CgmJuridico
     */
    private $empregador;

    /**
     * @var \DBDate
     */
    private $dataObrigatoriedade;

    /**
     * @param array $dados
     * @return array|\Assentamento[]
     * @throws \DBException
     */
    public function formatar($dados)
    {
        $dadosServidor = parent::formatar($dados);
        $retorno = [];
        foreach ($dadosServidor as &$dado) {
            $this->servidorAtual = null;
            $this->dataAlteracao = null;
            foreach ($dados->beneficios as $servidor) {
                if ($servidor->getMatricula() == $dado->beneficiario->matricula) {
                    $this->servidorAtual = $servidor;
                    break;
                }
            }
            $dado = $this->ajustaDados($dado);
            if ($dado) {
                $retorno[] = $dado;
            }
        }
        return $retorno;
    }

    public function ajustaDados($dado)
    {
        $servidorAlteracao = ServidorAlteracao::findMatriculaByLayout(
            $this->servidorAtual->getMatricula(),
            Tipo::S2416,
            false,
            true
        );
        if (!$servidorAlteracao) {
            return false;
        }
        $this->dataAlteracao = $servidorAlteracao->getDataS2416();
        $this->identificacaoBeneficio($dado);
        $this->dadosBeneficio($dado);
        $this->identificacaoPensaoMorte($dado);
        $this->identificacaoSuspensao($dado);
        $this->removeDados($dado);
        $servidorAlteracao->setProcessamentoS2416(true);
        $servidorAlteracao->save();
        return $dado;
    }

    public function identificacaoBeneficio(&$dado)
    {
        $dado->ideBeneficio = clone $dado->beneficiario;
        $dado->ideBeneficio->nrBeneficio = $dado->ideBeneficio->matricula;
        unset($dado->ideBeneficio->matricula);
    }

    public function dadosBeneficio(&$dado)
    {
        $dado->infoBenAlteracao = new \stdClass();
        $dado->infoBenAlteracao->dtAltBeneficio = $this->dataAlteracao->getDate();
        $dado->infoBenAlteracao->dadosBeneficio = clone $dado->infoBenInicio->dadosBeneficio;
    }


    public function identificacaoPensaoMorte(&$dado)
    {
        if (isset($dado->infoBenAlteracao->dadosBeneficio->infoPenMorte)
            && !empty($dado->infoBenAlteracao->dadosBeneficio->infoPenMorte)) {
            if (isset($dado->infoBenAlteracao->dadosBeneficio->infoPenMorte->instPenMorte)
                && !empty($dado->infoBenAlteracao->dadosBeneficio->infoPenMorte->instPenMorte)
            ) {
                unset($dado->infoBenAlteracao->dadosBeneficio->infoPenMorte->instPenMorte);
            }
        }
    }

    /**
     * Verificamos se o beneficio esta suspenso
     */
    public function identificacaoSuspensao(&$dado)
    {
        $possuiSuspensao = false;
        if (!empty($this->servidorAtual)) {
            $ano = $this->servidorAtual->movimentacao->getAno();
            $mes = $this->servidorAtual->movimentacao->getMes();
            $dataInicial = "{$ano}-{$mes}-01";
            $dataFinal = "{$ano}-{$mes}-" . DBDate::getQuantidadeDiasMes($mes, $ano);
            /**
             * Verificamos se o servidor possui afastamentos na competencia do processamento
             */
            $afastamentos = AfastamentoRepository::getTodosAfastamentosNoPeriodo(
                $this->servidorAtual->getMatricula(),
                $dataInicial,
                $dataFinal
            );

            /**
             * Caso possua afastamentos
             * devemos verificar se possui o assentamento referente ao afastamento
             * Regra informada em reuniao no dia 10/08/2022 pelo Sandro
             * Para essa verificacao, existe a tabela afastaassenta
             */
            if (!empty($afastamentos)) {
                foreach ($afastamentos as $afastamento) {
                    if ($possuiSuspensao || !empty($afastamento->r45_codret)) {
                        continue;
                    }
                    $sql = "select * from recursoshumanos.afastaassenta where h81_afasta = {$afastamento->r45_codigo}";
                    $rs = db_query($sql);
                    if (!$rs) {
                        $msg = "Erro ao buscar informações sobre o assentamento do afastamento código
                            {$afastamento->r45_codigo} da matrícula {$this->servidorAtual->getMatricula()}.";
                        throw new DBException($msg);
                    }
                    /**
                     * Caso encontre
                     */
                    if (pg_numrows($rs) > 0) {
                        $possuiSuspensao = true;
                    }
                }
            }
        }

        if ($possuiSuspensao) {
            /**
             * O Sandro informou que quando encontrar, será somente o código 01
             * Motivo da suspensão do benefício.
             * Valores válidos:
             * 01 - Suspensão por não recadastramento
             * 99 - Outros motivos de suspensão
             */
            $dado->suspensao = new \stdClass();
            $dado->suspensao->mtvSuspensao = "01";
            $dado->infoBenAlteracao->dadosBeneficio->indSuspensao = "S";
        } else {
            $dado->infoBenAlteracao->dadosBeneficio->indSuspensao = "N";
        }
    }


    public function removeDados(&$dado)
    {
        unset($dado->beneficiario);
        unset($dado->infoBenInicio);
    }
}
