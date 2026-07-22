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

namespace ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Estatutario;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\EstatutarioRepository;
use Exception;
use stdClass;
use DBDate;

class EstatutarioService
{
    /**
     * @var
     */
    private $estatutarioRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->estatutarioRepository = new EstatutarioRepository();
    }

    /**
     * @param Estatutario
     * @return Estatutario
     * @throws Exception
     */
    public function salvar(Estatutario $estatutario)
    {
        $validar = false;
        $complemento = "Grupo de informações da sucessão de vínculo trabalhista/estatutário.";
        if (!empty($estatutario->getTipoInscricao()) ||
            !empty($estatutario->getInscricao() ||
            !empty($estatutario->getDataTransferencia()))) {
                $validar = true;
        }

        if ($validar && empty($estatutario->getTipoInscricao())) {
            throw new Exception('É necessário informar a "Tipo de inscrição" ' . $complemento . '. Favor revisar.');
        }

        if ($validar && empty($estatutario->getInscricao())) {
            throw new Exception('É necessário informar a "Número de inscrição do empregador" ' .
                $complemento . '. Favor revisar.');
        }

        if ($validar && empty($estatutario->getDataTransferencia())) {
            throw new Exception('É necessário informar a "Data da transferência do empregado" ' .
                $complemento . '. Favor revisar.');
        }

        return $this->estatutarioRepository->save($estatutario);
    }

        /**
     * @param Estatutario
     * @return Estatutario
     * @throws Exception
     */
    public function excluir(Estatutario $estatutario)
    {
        return $this->estatutarioRepository->delete($estatutario);
    }
}
