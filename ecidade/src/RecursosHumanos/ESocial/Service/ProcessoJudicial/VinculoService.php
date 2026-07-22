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

use Exception;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Vinculo;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\VinculoRepository;

class VinculoService
{
    /**
     * @var
     */
    private $vinculoRepository;

    /**
     * VinculoService constructor.
    */
    public function __construct()
    {
        $this->vinculoRepository = new VinculoRepository();
    }

    /**
     * @param Vinculo
     * @return Vinculo
     * @throws Exception
     */
    public function salvar(Vinculo $vinculo)
    {
        $matricula = $vinculo->getServidorProcesso()->getMatricula();
        $nome = $vinculo->getServidorProcesso()->getNomeServidor();
        if (empty($vinculo->getRegimeTrabalhista())) {
            throw new Exception("Tipo de regime trabalhista do servidor " .
                "<strong>{$matricula}-{$nome}</strong> não definido." .
                " Favor revisar.");
        }
        if (empty($vinculo->getRegimePrevidenciario())) {
            throw new Exception("Tipo de regime previdenciário do servidor " .
                "<strong>{$matricula}-{$nome}</strong> não definido." .
                " Favor revisar.");
        }
        if (empty($vinculo->getDataAdmissao())) {
            throw new Exception("Data de admissão do trabalhador do servidor " .
                "<strong>{$matricula}-{$nome}</strong> não definido." .
                " Favor revisar.");
        }

        if ($vinculo->getRegimeTrabalhista() == 1) {
            if (!in_array($vinculo->getTempoParcial(), ["0","1","2","3"])) {
                throw new Exception("Código relativo ao tipo de contrato em tempo parcial de " .
                    "<strong>{$matricula}-{$nome}</strong> não definido." .
                    " É obrigatório, pois, <i>'Tipo de regime trabalhista'</i> é de valor 1 (CLT)." .
                    " Favor revisar.");
            }
        }

        return $this->vinculoRepository->save($vinculo);
    }

    /**
     * @param Vinculo
     * @return Vinculo
     * @throws Exception
     */
    public function excluir(Vinculo $vinculo)
    {
        return $this->vinculoRepository->delete($vinculo);
    }
}
