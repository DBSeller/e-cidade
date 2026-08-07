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

namespace ECidade\RecursosHumanos\ESocial\Service;

use AvaliacaoESocial;
use AvaliacaoRepository;
use db_utils;
use ECidade\Configuracao\Formulario\Repository\Formulario;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;
use ECidade\RecursosHumanos\ESocial\Mapeadores\AfastamentoTemporarioMapeador;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;
use ParameterException;

class AfastamentoTemporarioService
{
    private $servidorId;
    private $assentamentoId;

    /**
     * @var \Avaliacao
     */
    private $avaliacao;

    /**
     * AfastamentoTemporarioService constructor.
     * @param $servidorId
     * @param $assentamentoId
     */
    public function __construct($servidorId, $assentamentoId)
    {
        $this->servidorId = $servidorId;
        $this->assentamentoId = $assentamentoId;
    }

    /**
     * Realiza a busca dos dados do assentamento e retorna um objeto estruturado para salvar a avaliação
     * @return object
     * @throws Exception
     */
    private function parse()
    {
        $afastamentoMapeador = new AfastamentoTemporarioMapeador($this->getAvaliacao(), $this->assentamentoId);
        return $afastamentoMapeador->parseAvaliacao();
    }

    /**
     * @return \Avaliacao
     * @throws Exception
     */
    private function getAvaliacao()
    {
        if (empty($this->avaliacao)) {
            $configuracao = new Configuracao();
            $formularioId = $configuracao->getFormulario(Tipo::AFASTAMENTO_TEMPORARIO);
            $this->avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo($formularioId);
        }
        return $this->avaliacao;
    }

    /**
     * Salva o vínculo do servidor e o assentamento de afastamento caso não exista
     * @return integer
     * @throws Exception
     */
    private function getVinculo()
    {
        $dao = new \cl_afastamentoservidoresocial();
        $sql = $dao->sql_query_file(
            null,
            "eso12_sequencial",
            null,
            "eso12_assenta = $this->assentamentoId and eso12_rhpessoal = $this->servidorId"
        );

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar vínculo do afastamento e o servidor.");
        }

        $dao->eso12_sequencial = null;
        if (pg_num_rows($rs) > 0) {
            return db_utils::fieldsMemory($rs, 0)->eso12_sequencial;
        }

        return $this->saveVinculo();
    }

    private function saveVinculo()
    {
        $dao = new \cl_afastamentoservidoresocial();
        $dao->eso12_sequencial = null;
        $dao->eso12_assenta = $this->assentamentoId;
        $dao->eso12_rhpessoal = $this->servidorId;

        if (empty($dao->eso12_sequencial)) {
            $dao->incluir();
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar vículo do afastamento com o servidor.");
        }

        return $dao->eso12_sequencial;
    }

    /**
     * Busca o preenchimento para o vinculo, se não encontrar um preenchimento é criado um preenchimento novo
     * @param $vinculo
     * @throws Exception
     */
    private function getPreenchimendo($vinculo)
    {
        $dao = new \cl_avaliacaogruporespostaafastamentoesocial();
        $sql = $dao->sql_query_file(null, "eso13_avaliacaogruporesposta", null, " eso13_afastamentoservidoresocial = $vinculo");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar validar se o preenchimento existe.");
        }

        $codigoAvaliacaoGrupoResposta = null;
        if (pg_num_rows($rs) > 0) {
            $codigoAvaliacaoGrupoResposta = \db_utils::fieldsMemory($rs, 0)->eso13_avaliacaogruporesposta;
        }

        $this->getAvaliacao()->setAvaliacaoGrupo($codigoAvaliacaoGrupoResposta);
    }

    /**
     * @throws ParameterException
     * @throws Exception
     */
    public function preencherFormulario()
    {
        $vinculo = $this->getVinculo();

        $this->getPreenchimendo($vinculo);

        $parametros = array(
            'iCodigoPreenchimento' => $this->getAvaliacao()->getAvaliacaoGrupo(),
            'vinculo' => $vinculo,
            'matricula' => $this->servidorId
        );

        $oAvaliacaoESocial = new AvaliacaoESocial();
        $oAvaliacaoESocial->setAvaliacao($this->getAvaliacao());
        $oAvaliacaoESocial->setPerguntasRespostas($this->parse());
        $oAvaliacaoESocial->salvar(null, 'afastamentotemporario', $parametros);
    }


    /**
     * @throws ParameterException
     * @throws \BusinessException
     * @throws \DBException
     * @throws Exception
     */
    public function excluirFormulario()
    {
        $vinculo = $this->getVinculo();
        $this->getPreenchimendo($vinculo);
        $this->excluirVinculoFormulario($vinculo);

        $formulario  = Formulario::getById($this->getAvaliacao()->getCodigo());

        $resposta = Resposta::getBydId($formulario, $this->getAvaliacao()->getAvaliacaoGrupo());
        if (empty($resposta)) {
            throw new Exception('Resposta não encontrada no sistema. Verifique.');
        }
        Resposta::remover($resposta);


    }

    private function excluirVinculoFormulario($vinculo)
    {
        $daoVinculo = new \cl_afastamentoservidoresocial();
        $daoPreenchimento = new \cl_avaliacaogruporespostaafastamentoesocial();
        $daoPreenchimento->excluir(null, "eso13_afastamentoservidoresocial = {$vinculo}");
        $daoVinculo->excluir($vinculo);
    }
}
