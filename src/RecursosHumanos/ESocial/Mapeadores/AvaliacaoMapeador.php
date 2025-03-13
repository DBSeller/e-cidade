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

namespace ECidade\RecursosHumanos\ESocial\Mapeadores;

use Avaliacao;

class AvaliacaoMapeador
{
    /**
     * @var array
     */
    protected $dePara = array();

    /**
     * @var Avaliacao
     */
    protected $avaliacao;

    public function setAvaliacao(Avaliacao $avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    public function addDePara($identificadorPergunta, $identificadorOpcao, $valor)
    {
        $opcao = array(
            "identificador_campo" => $identificadorOpcao,
            "valor" => $valor
        );
        if (!empty($this->dePara[$identificadorPergunta])) {
            $this->dePara[$identificadorPergunta]["opcoes"][] = $opcao;
        } else {
            $this->dePara[$identificadorPergunta] = array(
                "opcoes" => array($opcao)
            );
        }
    }

    public function parseAvaliacao()
    {
        $dadosAvaliacao = (object)array(
            'codigo' => $this->avaliacao->getCodigo(),
            'grupos' => array()
        );

        foreach ($this->avaliacao->getGrupos() as $grupo) {
            $dadoGrupo = (object)array(
                'codigo' => $grupo->getCodigo(),
                'perguntas' => array()
            );

            foreach ($grupo->getPerguntas() as $pergunta) {
                $dadoPergunta = (object)array(
                    'codigo' => $pergunta->getCodigo(),
                    'respostas' => array()
                );
                $identificadorCampo = $pergunta->getIdentificadorCampo();

                if (array_key_exists($identificadorCampo, $this->dePara)) {
                    $registro = $this->dePara[$identificadorCampo];
                    foreach ($registro['opcoes'] as $opcao) {
                        foreach ($pergunta->getRespostas() as $respostaAvaliacao) {
                            if ($respostaAvaliacao->identificadorcampo == $opcao['identificador_campo']) {
                                $resposta = (object)array(
                                    "codigo" => $respostaAvaliacao->codigoresposta,
                                    "identificadorCampo" => $pergunta->getIdentificadorCampo(),
                                    "valor" => $opcao['valor'],
                                    "valorAuxiliar" => null,
                                );
                                $dadoPergunta->respostas[] = $resposta;
                            }
                        }
                    }
                }
                $dadoGrupo->perguntas[] = $dadoPergunta;
            }
            $dadosAvaliacao->grupos[] = $dadoGrupo;
        }

        return $dadosAvaliacao;
    }

    public function getPropriedadeDePara($identificadorPergunta, $identificadorResposta = null)
    {
        foreach ($this->dePara as $index => $dePara) {
            if ($index == $identificadorPergunta) {
                if (empty($identificadorResposta)) {
                    return $dePara;
                }

                foreach ($dePara["opcoes"] as $opcao) {
                    if ($opcao["identificador_campo"] == $identificadorResposta) {
                        return $opcao["valor"];
                    }
                }
            }
        }
        return null;
    }
}


