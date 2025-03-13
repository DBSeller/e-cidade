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

namespace ECidade\Saude\Laboratorio\Repository;

use ECidade\Saude\Laboratorio\Model\ColetaItem;
use PhpOffice\PhpWord\Tests\Element\ObjectTest;
use Exception;
use UsuarioSistema;

/**
 * Class ColetaItemRepository
 * @package ECidade\Saude\Laboratorio\Repository
 */
class ColetaItemRepository
{
    /**
     * @var Object
     */
    private $dao;

    /**
     * ItemRequisicaoRepository constructor.
     * @param $dao \cl_lab_coletaitem
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param ColetaItem $coletaItem
     * @return ColetaItem
     * @throws Exception
     */
    public function salvar(
        ColetaItem $coletaItem
    ) {
        $this->dao->la32_i_codigo = $coletaItem->getCodigo();
        $this->dao->la32_i_usuario = $coletaItem->getUsuario();
        $this->dao->la32_i_requiitem = $coletaItem->getItem();
        $this->dao->la32_d_data = $coletaItem->getData();
        $this->dao->la32_c_hora = $coletaItem->getHora();
        $this->dao->la32_i_avisapaciente = $coletaItem->getAvisaPaciente();
        $this->dao->la32_c_horaentrega = $coletaItem->getHoraEntrega();
        $this->dao->la32_d_entrega = $coletaItem->getDataEntrega();

        if (!$coletaItem->getCodigo()) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($coletaItem->getCodigo());
        }
        if ($this->dao->erro_status === '0') {
            throw new Exception($this->dao->erro_msg);
        }

        return $coletaItem;
    }

    public function buscar($where = "")
    {
        $sql = $this->dao->sql_query(null, "*", null, $where);
        $rs = db_query($sql);

        $dados = [];

        while ($linha = pg_fetch_array($rs)) {
            $dados[] = $linha;
        }

        return $dados;
    }
}
