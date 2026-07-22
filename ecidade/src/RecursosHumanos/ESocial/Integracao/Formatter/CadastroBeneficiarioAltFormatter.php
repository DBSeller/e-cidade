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

use ECidade\RecursosHumanos\ESocial\Entity\Servidor;
use ECidade\RecursosHumanos\ESocial\Repository\ServidorAlteracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class CadastroBeneficiarioAltFormatter extends CadastroBeneficiarioFormatter
{
    /**
     * @var \Servidor
     */
    private $servidorAtual;

    /**
     * @var \Date
     */
    private $dataAlteracao = null;

    /**
     * @param array $dados
     * @return array|\Assentamento[]
     * @throws \DBException
     */
    public function formatar($dados)
    {
        $return = [];
        $dadosServidor = parent::formatar($dados);

        foreach ($dadosServidor as $servidor) {
            $this->dataAlteracao = $this->getDataAlteracao($this->getServidorAtual()->getMatricula());
            if ($this->dataAlteracao) {
                $return[] = $this->processar($servidor);
                $this->dataAlteracao->setProcessamentoS2405(true);
                $this->dataAlteracao->save();
            }
        }
        return $return;
    }
    
    private function processar($servidor)
    {
        $this->servidorAtual = $servidor;
        $dadoServidor = new \stdClass();

        $dadoServidor->inscricao_empregador = $this->getEmpregador()->getCnpj();
        $dadoServidor->referencia = $servidor->referencia;

        $this->formatarDados($dadoServidor);

        return $dadoServidor;
    }

    private function getDataAlteracao($matricula)
    {
        return ServidorAlteracao::findMatriculaByLayout(
            $matricula,
            Tipo::S2405,
            false,
            true
        );
    }

    private function formatarDados(&$dadoServidor)
    {
        $this->montarGrupos($dadoServidor);
    }
    
    private function montarGrupos(&$dadoServidor)
    {
        /*
         * Grupo 13 - ideBenef
         */
        $dadoServidor->ideBenef = new \stdClass();
        $dadoServidor->ideBenef->cpfBenef = $this->servidorAtual->beneficiario['cpfBenef'];

        /**
         * Grupo 15 - alteracao
         */
        $dadoServidor->alteracao = new \stdClass();
        $dadoServidor->alteracao->dtAlteracao = $this->dataAlteracao->getDataS2405()->getDate();
        $dadoServidor->alteracao->dadosBenef = new \stdClass();
        $dadoServidor->alteracao->dadosBenef->nmBenefic = $this->servidorAtual->beneficiario['nmBenefic'];
        $dadoServidor->alteracao->dadosBenef->sexo = $this->servidorAtual->beneficiario['sexo'];
        $dadoServidor->alteracao->dadosBenef->racaCor = $this->servidorAtual->beneficiario['racaCor'];
        if (!empty($this->servidorAtual->beneficiario['estCiv'])) {
            $dadoServidor->alteracao->dadosBenef->estCiv = $this->servidorAtual->beneficiario['estCiv'];
        }
        $dadoServidor->alteracao->dadosBenef->incFisMen = $this->servidorAtual->beneficiario['incFisMen'];

        /**
         * Grupo 23 - endereco
         */
        $dadoServidor->alteracao->dadosBenef->endereco = new \stdClass();
        if (!empty($this->servidorAtual->beneficiario['endereco']['brasil'])) {
            $dadoServidor->alteracao->dadosBenef->endereco->brasil =
                $this->servidorAtual->beneficiario['endereco']['brasil'];
        } elseif (!empty($this->servidorAtual->beneficiario['endereco']['exterior'])) {
            $dadoServidor->alteracao->dadosBenef->endereco->exterior =
                $this->servidorAtual->beneficiario['endereco']['exterior'];
        }

        /**
         * Grupo 41 - dependente
         */
        if (!empty($this->servidorAtual->beneficiario['dependente'])) {
            $dadoServidor->alteracao->dadosBenef->dependente = $this->servidorAtual->beneficiario['dependente'];
        }
    }
}
