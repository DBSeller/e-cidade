<?php
/**
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
 *  02111-1307, USA.save
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
namespace ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial;

use DBDate;
use JSON;

class TributoContribuicao
{
    /**
     * @var int
     *
     */
    private $sequencial;

    /**
     * @var int
     *
     * Referencia a tabela RHPROCESSOTRIBUTOBASE
     */
    private $sequencialTributoBase;

    /**
     * @var int
     *
     */
    private $codigoReceita;

    /**
     * @var numeric
     *
     */
    private $valorContribuicao;


    /**
     * @param array $state
     * @return TributoContribuicao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $tributoContribuicao = new self();

        if (array_key_exists('rh298_sequencial', $state)) {
            $tributoContribuicao->setSequencial((int)$state['rh298_sequencial']);
        }

        if (array_key_exists('rh298_sequencialtributobase', $state)) {
            $tributoContribuicao->setSequencialTributoBase((int)$state['rh298_sequencialtributobase']);
        }

        if (array_key_exists('rh298_tpcr', $state)) {
            $tributoContribuicao->setCodigoReceita($state['rh298_tpcr']);
        }

        if (array_key_exists('rh298_vrcr', $state)) {
            $tributoContribuicao->setValorContribuicao($state['rh298_vrcr']);
        }


        return $tributoContribuicao;
    }

    public function serialize()
    {
        $serialize = clone $this;
        return JSON::create()->stringify(get_object_vars($serialize));
    }

    /**
     * Get the value of sequencial
     *
     * @return  int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * Set the value of sequencial
     *
     * @param  int  $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * Get the value of sequencialTributoBase
     *
     * @return  int
     */
    public function getSequencialTributoBase()
    {
        return $this->sequencialTributoBase;
    }

    /**
     * Set the value of sequencialTributoBase
     *
     * @param  int  $sequencialTributoBase
     */
    public function setSequencialTributoBase($sequencialTributoBase)
    {
        $this->sequencialTributoBase = $sequencialTributoBase;
    }

    /**
     * Get the value of codigoReceita
     *
     * @return  int
     */
    public function getCodigoReceita()
    {
        return $this->codigoReceita;
    }

    /**
     * Set the value of codigoReceita
     *
     * @param  int  $codigoReceita
     *
     * @return  self
     */
    public function setCodigoReceita($codigoReceita)
    {
        $this->codigoReceita = $codigoReceita;
    }

    /**
     * Get the value of valorContribuicao
     *
     * @return  numeric
     */
    public function getValorContribuicao()
    {
        return $this->valorContribuicao;
    }

    /**
     * Set the value of valorContribuicao
     *
     * @param  numeric  $valorContribuicao
     */
    public function setValorContribuicao($valorContribuicao)
    {
        $this->valorContribuicao = $valorContribuicao;
    }
}
