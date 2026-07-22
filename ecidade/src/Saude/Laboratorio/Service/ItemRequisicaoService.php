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
 *
 */

namespace ECidade\Saude\Laboratorio\Service;

use DateTime;
use ECidade\Saude\Laboratorio\Repository\ItemRequisicaoRepository;
use ECidade\Saude\Laboratorio\Model\ItemRequisicao;
use Exception;
use Instituicao;
use DBDepartamento;
use JSON;
use InstituicaoRepository;
use Cgs;

/**
 * Class ItemRequisicaoService
 * @package ECidade\Saude\Laboratorio\Service
 */
class ItemRequisicaoService
{
    private $repositorio;
    /**
     * ItemRequisicaoService constructor.
     * @param ItemRequisicaoRepository $repositorio
     */
    public function __construct(ItemRequisicaoRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    /**
     * Salva os dados do item da requisicao
     */
    public function salvar($parametros)
    {

        $itemRequisicao = new ItemRequisicao();
        $itemRequisicao->setCodigo(isset($parametros->la21_i_codigo) ? $parametros->la21_i_codigo : '');
        $itemRequisicao->setRequisicao(isset($parametros->la21_i_requisicao) ? $parametros->la21_i_requisicao : '');
        $itemRequisicao->setDataEntrega(isset($parametros->la21_d_entrega) ? $parametros->la21_d_entrega : '');
        $itemRequisicao->setDataCadastro(isset($parametros->la21_d_data) ? $parametros->la21_d_data : '');
        $itemRequisicao->setHoraCadastro(isset($parametros->la21_c_hora) ? $parametros->la21_c_hora : '');
        $itemRequisicao->setCodigoSetor(isset($parametros->la21_i_setorexame) ? $parametros->la21_i_setorexame : '');
        $itemRequisicao->setHoraCadastro(isset($parametros->sd24_c_cadastro) ? $parametros->sd24_c_cadastro : '');
        $itemRequisicao->setEmergencia(isset($parametros->la21_i_emergencia) ? $parametros->la21_i_emergencia : '');
        $itemRequisicao->setSituacao(isset($parametros->la21_c_situacao) ? $parametros->la21_c_situacao : '');
        $itemRequisicao->setQuantidade(isset($parametros->la21_i_quantidade) ? $parametros->la21_i_quantidade : '');
        $itemRequisicao->setObservacao(isset($parametros->la21_observacao) ? $parametros->la21_observacao : '');
        $itemRequisicao->setMotivo(isset($parametros->la21_motivonovacoleta) ? $parametros->la21_motivonovacoleta : '');

        $itemRequisicao = $this->repositorio->salvar($itemRequisicao);

        return $itemRequisicao;
    }

    /**
     * salva os dados do item da requisicao e desfaz até onde foi regredido
     */
    public function regredirSituacao($parametros, $situacaoNova, $situacaoAntiga, $codigoRequisicao)
    {
        $itemRequisicao = new ItemRequisicao();
        $itemRequisicao->setCodigo(isset($parametros->la21_i_codigo) ? $parametros->la21_i_codigo : '');
        $itemRequisicao->setRequisicao(isset($parametros->la21_i_requisicao) ? $parametros->la21_i_requisicao : '');
        $itemRequisicao->setDataEntrega(isset($parametros->la21_d_entrega) ? $parametros->la21_d_entrega : '');
        $itemRequisicao->setDataCadastro(isset($parametros->la21_d_data) ? $parametros->la21_d_data : '');
        $itemRequisicao->setHoraCadastro(isset($parametros->la21_c_hora) ? $parametros->la21_c_hora : '');
        $itemRequisicao->setCodigoSetor(isset($parametros->la21_i_setorexame) ? $parametros->la21_i_setorexame : '');
        $itemRequisicao->setHoraCadastro(isset($parametros->sd24_c_cadastro) ? $parametros->sd24_c_cadastro : '');
        $itemRequisicao->setEmergencia(isset($parametros->la21_i_emergencia) ? $parametros->la21_i_emergencia : '');
        $itemRequisicao->setSituacao(isset($parametros->la21_c_situacao) ? $parametros->la21_c_situacao : '');
        $itemRequisicao->setQuantidade(isset($parametros->la21_i_quantidade) ? $parametros->la21_i_quantidade : '');
        $itemRequisicao->setObservacao(isset($parametros->la21_observacao) ? $parametros->la21_observacao : '');
        $itemRequisicao->setMotivo(isset($parametros->la21_motivonovacoleta) ? $parametros->la21_motivonovacoleta : '');
        
        if ($parametros->la21_i_codigo) {
            if ($situacaoNova < "70 - Entregue" && $situacaoAntiga == "70 - Entregue") {
                $lab_entrega = new \cl_lab_entrega();
                $lab_entrega->excluir(null, "la31_i_requiitem =".$parametros->la21_i_codigo);
            }
            
            if ($situacaoNova < "60 - Conferido" && $situacaoAntiga >= "60 - Conferido") {
                $lab_conferencia = new \cl_lab_conferencia();
                $lab_conferencia->excluir(null, "la47_i_requiitem =".$parametros->la21_i_codigo);
            }
            
            if ($situacaoNova < "50 - Lancado" && $situacaoAntiga >= "50 - Lancado") {
                $lab_resultado = new \cl_lab_resultado();
                $sql = $lab_resultado->sql_query_file(null, '*', null, "la52_i_requiitem =".$parametros->la21_i_codigo);
                $rs = db_query($sql);
                $codigoResultado = pg_fetch_object($rs)->la52_i_codigo;
                
                $lab_resultadoitem = new \cl_lab_resultadoitem();
                $sql = $lab_resultadoitem->sql_query_file(null, '*', null, "la39_i_resultado =".$codigoResultado);
                $rs = db_query($sql);
                $codigoResultadoItem = pg_fetch_object($rs)->la39_i_codigo;
                
                $lab_resultadonum = new \cl_lab_resultadonum();
                $lab_resultadonum->excluir(null, "la41_i_result =".$codigoResultadoItem);

                $lab_resultadoitem->excluir(null, "la39_i_resultado =".$codigoResultado);

                $lab_resultado->excluir(null, "la52_i_requiitem =".$parametros->la21_i_codigo);
            }
            
            if ($situacaoNova < "30 - Coletado" && $situacaoAntiga >= "30 - Coletado") {
                $lab_coletaitem = new \cl_lab_coletaitem();
                $lab_coletaitem->excluir(null, "la32_i_requiitem =".$parametros->la21_i_codigo);
            }

            if ($situacaoNova < "20 - Autorizado" && $situacaoAntiga >= "20 - Autorizado") {
                $lab_autoriza = new \cl_lab_autoriza();
                $lab_autoriza->excluir(null, "la48_i_requisicao =".$codigoRequisicao);
                
                $lab_requisicao = new \cl_lab_requisicao();
                $lab_requisicao->la22_i_codigo = $codigoRequisicao;
                $lab_requisicao->la22_i_autoriza = 1;
                $lab_requisicao->alterar($codigoRequisicao);
            }

            $itemRequisicao = $this->repositorio->salvar($itemRequisicao);
        } else {
            throw new Exception("Não foi possível regredir o exame da requisição.\nContate o suporte.");
        }

        return $itemRequisicao;
    }

    public function verificaSePossuiItensConferidos($requisicao, $situacao)
    {
        return count($this->repositorio->buscaItensConferidosPorRequisicao($requisicao, $situacao)) > 0;
    }

    public function excluirExamesPorRequisicao($exames)
    {
        $exames = explode(',', $exames);
        foreach ($exames as $exame) {
            $this->repositorio->excluir($exame);
        }
    }

    public function buscarSituacaoExamesPorRequisicao($requisicao)
    {
        return $this->repositorio->buscarExamesPorRequisicao($requisicao);
    }

    public function buscarProcedimentoExameItemRequisicao($parametros)
    {
        return $this->repositorio->buscarProcedimentoItem($parametros->requisicaoItem);
    }
}
