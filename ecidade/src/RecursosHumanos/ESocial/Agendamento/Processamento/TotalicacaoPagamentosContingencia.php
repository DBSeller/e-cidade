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
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository\ESocialEnvioRepository;
use Exception;
use Instituicao;

class TotalicacaoPagamentosContingencia implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * Rubrica constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws Exception
     * @throws \DBException
     */
    public function processar()
    {
        $bAlteracao = false;
        $oDadosESocial = new DadosESocial();

        $oDadosESocial->setInstituicao(new Instituicao(db_getsession('DB_instit')));
        $oDadosESocial->setReponsavelPeloPreenchimento($this->cgm);

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::TOTALIZACAO_PAGAMENTOS_CONTINGENCIA);        
        $oFormatter = FormatterFactory::get(Tipo::S1295);

        $aDadosPreenchimentoEmpregador = $oFormatter->formatar($oDadosPreenchimento);

        foreach ($aDadosPreenchimentoEmpregador as $iIndice => $oDados) {
            $oEvento = new Evento(TIPO::S1295, $this->cgm, $oDados->referencia, $oDados);
            $oEvento->iContador = $iIndice;

            if ($oEvento->adicionarFila()) {
                $repository = new ESocialEnvioRepository();
                $repository->scopeEmpregador($this->cgm);
                $repository->scopeEvento(current(Tipo::getLayout(Tipo::TOTALIZACAO_PAGAMENTOS_CONTINGENCIA)));
                $responsavel = "{$this->cgm}_{$oDados->perApur}";
                $repository->scopeResponsavelPreenchimento($responsavel);
                $reabertura_eventos = current($repository->get());
                if($reabertura_eventos) {
                    $repository->atualizarEvento($reabertura_eventos->getCodigo(), $reabertura_eventos->getSituacaosalva(), true);
                }
                $bAlteracao = true;
            }
        }

        return $bAlteracao;
    }
}