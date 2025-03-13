<?php /*
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

namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\RecursosHumanos\Pessoal\Repository\TipoAgrupamentoRubricaRepository;

/**
 * Class TipoAgrupamentoRubrica
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class TipoAgrupamentoRubrica
{
    /**
     * @var int
     */
    private $sequencial;
    
    /**
     * @var descricao
     */
    private $descricao;
  
    /**
     * TipoAgrupamentoRubrica constructor.
     * @param null $codigo
     * @throws \Exception
     */
    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $TipoAgrupamentoRubrica = TipoAgrupamentoRubricaRepository::find($codigo);
            $this->sequencial = $TipoAgrupamentoRubrica->getSequencial();
            $this->descricao  = $TipoAgrupamentoRubrica->getDescricao();
        }
    }

    /**
     * @param array $state
     * @return TipoAgrupamentoRubrica
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $tipoagrupamento = new self();
        if (array_key_exists('rh238_sequencial', $state)) {
            $tipoagrupamento->setSequencial((int)$state['rh238_sequencial']);
        }

        if (array_key_exists('rh238_descricao', $state)) {
            $tipoagrupamento->setDescricao($state['rh238_descricao']);
        }

        return $tipoagrupamento;
    }
    
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
}
