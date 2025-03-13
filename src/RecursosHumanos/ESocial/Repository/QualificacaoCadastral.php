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

namespace ECidade\RecursosHumanos\ESocial\Repository;

use ECidade\RecursosHumanos\ESocial\Model\QualificacaoCadastral as QualificacaoCadastralModel;

/**
 * Class QualificacaoCadastral
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class QualificacaoCadastral
{
    public function __construct()
    {
    }

    /**
     * @param $selecao
     * @return QualificacaoCadastralModel[]
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function buscarServidoresPorSelecao($selecao)
    {
        if (empty($selecao)) {
            throw new \ParameterException('Seleção não informada.');
        }

        $servidores = \ServidorRepository::getServidoresBySelecao(
          \DBPessoal::getAnoFolha(),
          \DBPessoal::getMesFolha(),
          $selecao
        );

        if (empty($servidores)) {
            throw new \BusinessException('Nenhuma matrícula encontrada para a seleção informada.');
        }

        return $this->montaDadosServidores($servidores);
    }

    /**
     * @param $servidores
     * @return QualificacaoCadastralModel[]
     */
    private function montaDadosServidores($servidores)
    {
        $qualificacoesCadastrais = array();

        foreach ($servidores as $servidor) {
            if ($servidor->isRescindido()) {
                continue;
            }

            $cgm = $servidor->getCgm();

            if ($cgm instanceof \CgmJuridico) {
                continue;
            }

            if ($servidor->getCodigoMovimentacao() == null) {
              continue;
            }
            $qualificacaoCadastral = new QualificacaoCadastralModel();
            $qualificacaoCadastral->setCpf($cgm->getCpf());
            $qualificacaoCadastral->setNIS($cgm->getPIS());
            $qualificacaoCadastral->setNome($cgm->getNomeCompleto());
            $qualificacaoCadastral->setDataNascimento($servidor->getDataNascimento());
            $qualificacaoCadastral->setMatricula($servidor->getMatricula());

            $qualificacoesCadastrais[$cgm->getCodigo()] = $qualificacaoCadastral;
        }

        usort($qualificacoesCadastrais, function($qualificacoesCadastrais, $qualificacoesCadastraisComparacao){
            return strcmp($qualificacoesCadastrais->getNome(), $qualificacoesCadastraisComparacao->getNome());    
        });

        return $qualificacoesCadastrais;
    }

    /**
     * @param $matriculas
     * @return QualificacaoCadastralModel[]
     * @throws \BusinessException
     * @throws \ParameterException
     */
    public function buscarServidoresPorMatriculas($matriculas)
    {
        if (empty($matriculas)) {
            throw new \ParameterException('Matrículas não informadas.');
        }

        $servidores = array();

        foreach ($matriculas as $matricula) {
            $servidores[] = \ServidorRepository::getInstanciaByCodigo($matricula, \DBPessoal::getAnoFolha(),
              \DBPessoal::getMesFolha());
        }

        if (empty($servidores)) {
            throw new \BusinessException('Nenhuma matrícula encontrada para as matrículas informadas.');
        }

        return $this->montaDadosServidores($servidores);
    }
}
