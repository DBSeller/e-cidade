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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use cl_avaliacaogruporespostaprocesso;
use db_utils;
use DBException;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use stdClass;

/**
 * Class Processos
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class Processos extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * Processos constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws DBException
     * @throws \Exception
     */
    public function processar()
    {
        $aProcessos = $this->buscaProcessos();
        $bAlteracao = false;

        foreach ($aProcessos as $oProcesso) {
            $oDadosESocial = new DadosESocial();
            $oDadosESocial->setReponsavelPeloPreenchimento($oProcesso);
            $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::PROCESSOS);

            $oFormatter = FormatterFactory::get(Tipo::S1070);
            $aDadosProcesso = $oFormatter->formatar($oDadosPreenchimento);
            $oEvento = new Evento(Tipo::S1070, $this->cgm, $oProcesso, $aDadosProcesso[0]);

            if ($oEvento->adicionarFila()) {
                $bAlteracao = true;
            }
        }
        return $bAlteracao;
    }

    /**
     * @return array
     * @throws DBException
     * @throws \Exception
     */
    private function buscaProcessos()
    {
        $iInstituicao = db_getsession('DB_instit');

        $oConfiguracao = new Configuracao();
        $iFormulario = $oConfiguracao->getFormulario(Tipo::PROCESSOS);

        $oAvaliacaoGrupoRespostaProcesso = new cl_avaliacaogruporespostaprocesso;

        $aCampos = array('DISTINCT eso05_avaliacaogruporesposta AS db_preenchimento');
        $aWhere = array("db102_avaliacao = {$iFormulario}");
        $aOrder = array('eso05_avaliacaogruporesposta');

        $sSql = $oAvaliacaoGrupoRespostaProcesso->buscaPreenchimento($aCampos, $aWhere, $aOrder, $iInstituicao);

        $rProcessos = db_query($sSql);

        if (!$rProcessos) {
            throw new DBException('Não foi possível buscar os processos.');
        }

        if (pg_num_rows($rProcessos) == 0) {
            throw new DBException('Nenhum Processo cadastrado!');
        }

        $aProcessos = db_utils::makeCollectionFromRecord($rProcessos, function (stdClass $oDados) {
            return $oDados->db_preenchimento;
        });

        return $aProcessos;
    }
}
