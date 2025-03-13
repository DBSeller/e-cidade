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


namespace ECidade\Configuracao\Formulario\Mapper;

use ECidade\Configuracao\Formulario\Model\FieldLayoutTxt;
use ECidade\Configuracao\Formulario\Model\Pergunta;
use ECidade\Configuracao\Formulario\Model\Opcao;

/**
 * Class FormsFields
 * Busca os campos de para do layoutcampo x avaliacaopergunta e as opções de resposta (avaliacaoperguntaopcao)
 * @package ECidade\Configuracao\Formulario\Mapper
 */
class FormsFields
{
    public function getFromForms($idForm)
    {
        $sql = "
            select layoutcampo as id_campo, 
                   pergunta as id_pergunta,
                   db103_avaliacaotiporesposta as tipo_pergunta,
                   db103_descricao as pergunta,
                   db103_perguntaidentificadora as pergunta_identificadora, 
                   db104_identificadorcampo as identificador_opcao,
                   db104_valorresposta as valor_resposta,
                   db104_sequencial as id_opcao
              from configuracoes.deparalayoutcampoavaliacaopergunta 
              join configuracoes.deparalayoutavaliacao 
                on deparalayoutavaliacao.id = deparalayoutcampoavaliacaopergunta.deparalayoutavaliacao
              join avaliacaopergunta 
                on avaliacaopergunta.db103_sequencial = deparalayoutcampoavaliacaopergunta .pergunta
              join avaliacaoperguntaopcao 
                on avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
              where avaliacao = {$idForm} 
        ";

        $rs = db_query($sql);

        /**
         * @todo fazer as validações dos erros
         */
        if (!$rs) {
        }

        $maper = array();
        \db_utils::makeCollectionFromRecord($rs, function ($dado) use (&$maper) {
            if (!array_key_exists($dado->id_campo, $maper)) {
                $maper[$dado->id_campo] = $this->formatQuestion($dado);
            }

            $maper[$dado->id_campo]->addOption($this->formatOption($dado));
        });

        return $maper;
    }

    private function formatQuestion($dado)
    {

        $pergunta = new Pergunta();
        $pergunta->setCodigo($dado->id_pergunta);
        $pergunta->setTipoResposta($dado->tipo_pergunta);
        $pergunta->setDescricao($dado->pergunta);
        $pergunta->setPerguntaIdentificadora($dado->pergunta_identificadora == 't');

        return new FieldLayoutTxt($dado->id_campo, $pergunta);
    }

    private function formatOption($dado)
    {
        $opcao = new Opcao();
        $opcao->setCodigo($dado->id_opcao);
        $opcao->setIdentificadorCampo($dado->identificador_opcao);
        $opcao->setValorOpcao($dado->valor_resposta);
        return $opcao;
    }
}
