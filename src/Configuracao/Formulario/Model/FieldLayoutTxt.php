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
 * Class FieldLayoutTxt
 * Mapeia os campos do layout txt vinculados com as perguntas de um formulário
 *
 * @package ECidade\Configuracao\Formulario\Model
 */
class FieldLayoutTxt
{

    private $idCampo;

    private $question;

    public function __construct($idCampo, Pergunta $question)
    {
        $this->idCampo = $idCampo;
        $this->question = $question;
    }

    public function addOption(Opcao $option)
    {
        $this->question->addOpcao($option);
    }

    /**
     * @return int
     */
    public function getIdCampo()
    {
        return $this->idCampo;
    }

    /**
     * @return Pergunta
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
