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

use ECidade\Saude\Laboratorio\Model\ItemRequisicao;
use PhpOffice\PhpWord\Tests\Element\ObjectTest;
use Exception;
use UsuarioSistema;

/**
 * Class ItemRequisicaoRepositoryRepository
 * @package ECidade\Saude\Laboratorio\Repository
 */
class ItemRequisicaoRepository
{
    /**
     * @var Object
     */
    private $dao;

    /**
     * ItemRequisicaoRepository constructor.
     * @param $dao \cl_lab_requiitem
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param ItemRequisicao $itemRequisicao
     * @return ItemRequisicao
     * @throws Exception
     */
    public function salvar(
        ItemRequisicao $itemRequisicao
    ) {
        $this->dao->la21_i_codigo = $itemRequisicao->getCodigo();
        $this->dao->la21_i_requisicao = $itemRequisicao->getRequisicao();
        $this->dao->la21_d_entrega = $itemRequisicao->getDataEntrega();
        $this->dao->la21_d_data = $itemRequisicao->getDataCadastro();
        $this->dao->la21_c_hora = $itemRequisicao->getHoraCadastro();
        $this->dao->la21_i_setorexame = $itemRequisicao->getCodigoSetor();
        $this->dao->la21_i_emergencia = $itemRequisicao->getEmergencia();
        $this->dao->la21_c_situacao = $itemRequisicao->getSituacao();
        $this->dao->la21_i_quantidade = $itemRequisicao->getQuantidade();
        $this->dao->la21_observacao = $itemRequisicao->getObservacao();
        $this->dao->la21_motivonovacoleta = $itemRequisicao->getMotivo();

        if (!$itemRequisicao->getCodigo()) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($itemRequisicao->getCodigo());
        }
        if ($this->dao->erro_status === '0') {
            throw new Exception($this->dao->erro_msg);
        }

        return $itemRequisicao;
    }

    public function buscaItensConferidosPorRequisicao($requisicao, $situacao)
    {
        $codigo = substr($situacao, 0, 2);
        $rs = $this->dao->sql_record("
            select * 
            from (select * from lab_requiitem where SUBSTRING ( la21_c_situacao , 0 , 2) <> 'f') as x 
            where cast(SUBSTRING (la21_c_situacao , 0 , 3) as int) >= $codigo 
                and la21_i_requisicao = $requisicao
        ");

        return pg_fetch_all($rs);
    }

    public function buscarExamesPorRequisicao($requisicao)
    {
        $sql = $this->dao->sql_query_exame_requisicao(
            $requisicao,
            "la21_i_codigo codigoItemRequisicao, la08_i_codigo || ' - ' || la08_c_descr as exame, 
        la21_c_situacao as situacao, la21_c_situacao as situacaoAntiga"
        );

        $rs = db_query($sql);

        $dados = [];

        while ($linha = pg_fetch_array($rs)) {
            $dados[] = $linha;
        }

        return $dados;
    }

    public function buscarProcedimentoItem($item)
    {
        $sql = $this->dao->sql_query_controle($item, 'la53_i_procedimento');

        $rs = db_query($sql);

        $dados = [];

        while ($linha = pg_fetch_array($rs)) {
            $dados[] = $linha;
        }

        return $dados;
    }

    public function excluir($exame)
    {
        $this->dao->la21_i_codigo = $exame;

        $this->dao->excluir($exame);

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o exame da requisição.\nContate o suporte.");
        }
    }
}
