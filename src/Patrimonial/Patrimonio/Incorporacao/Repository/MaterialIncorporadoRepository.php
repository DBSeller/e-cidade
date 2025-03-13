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

namespace ECidade\Patrimonial\Patrimonio\Incorporacao\Repository;

use cl_bemincorporado;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Model\MaterialIncorporadoModel;
use Exception;

class MaterialIncorporadoRepository
{

    /**
     * @var MaterialIncorporadoModel[]
     */
    private $materiaisIncorporado = array();

    public function addMaterialIncorporavel(MaterialIncorporadoModel $materialIncorporado)
    {
        $this->materiaisIncorporado[] = $materialIncorporado;
    }

    /**
     * @return MaterialIncorporadoModel[]
     */
    public function get()
    {
        return $this->materiaisIncorporado;
    }

    /**
     *
     * @throws Exception
     */
    public function persist()
    {
        foreach ($this->materiaisIncorporado as $bemIncorporado) {
            $dao = new cl_bemincorporado();
            $dao->t13_sequencial = $bemIncorporado->getCodigo();
            $dao->t13_bens = $bemIncorporado->getBem()->getCodigoBem();
            $dao->t13_bempendenteincorporacao = $bemIncorporado->getMaterialPendenteIncorporado()->getCodigo();
            $dao->t13_data = $bemIncorporado->getData()->getDate();
            $dao->t13_reavaliacao = $bemIncorporado->isReavaliar() ? 'true' : 'false';
            $dao->t13_quantidade = $bemIncorporado->getQuantidade();
            $dao->t13_ativo = $bemIncorporado->isAtivo() ? 'true' : 'false';

            if (empty($dao->t13_sequencial)) {
                $dao->incluir(null);
            } else {
                $dao->alterar($dao->t13_sequencial);
            }

            if ($dao->erro_status == 0) {
                throw new Exception("Erro ao salvar incorporação do bem. " . pg_last_error());
            }

            $bemIncorporado->setCodigo($dao->t13_sequencial);
        }
    }


}