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

namespace ECidade\Configuracao\Formulario\Model;

/**
 * Class Preenchimento
 * Deve representar as resposta de um preenchimento
 * @package ECidade\Configuracao\Formulario\Model
 */
class Preenchimento
{
    private $id;

    /**
     * @var Pergunta[]
     */
    private $perguntas = array();

    private $identificadores = array();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Pergunta[]
     */
    public function getPerguntas()
    {
        return $this->perguntas;
    }

    /**
     * @param Pergunta $pergunta
     */
    public function addPergunta(Pergunta $pergunta)
    {
        $this->perguntas[] = $pergunta;
    }

    public function setIdentificadores($identificadores)
    {
        $this->identificadores = $identificadores;
    }

    public function getIdentificadores()
    {
        return $this->identificadores;
    }
}
