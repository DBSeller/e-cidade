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
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Divida;

/**
 * Entidade que modela uma procedencia do banco de dados.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Procedencia
{
    /**
     * @var integer codigo
     */
    private $codigo;

    /**
     * @var string descr
     */
    private $descricao;

    /**
     * @var string dcomp
     */
    private $descricaoCompleta;

    /**
     * @var integer procedtipo
     */
    private $tipo;

    /**
     * @return Procedencia
     */
    public static function fromState($state)
    {
        $procedencia = new self;

        if (array_key_exists('v03_codigo', $state)) {
            $procedencia->setCodigo($state['v03_codigo']);
        }
        if (array_key_exists('v03_descr', $state)) {
            $procedencia->setDescricao($state['v03_descr']);
        }
        if (array_key_exists('v03_dcomp', $state)) {
            $procedencia->setDescricaoCompleta($state['v03_dcomp']);
        }
        if (array_key_exists('v03_procedtipo', $state)) {
            $procedencia->setTipo($state['v03_procedtipo']);
        }

        // TODO: Processar procedências vindas de 'diversos.procdiver'

        return $procedencia;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param  int $codigo
     * @return Procedencia
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param  string $descricao
     * @return Procedencia
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricaoCompleta()
    {
        return $this->descricaoCompleta;
    }

    /**
     * @param  string $descricaoCompleta
     * @return Procedencia
     */
    public function setDescricaoCompleta($descricaoCompleta)
    {
        $this->descricaoCompleta = $descricaoCompleta;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param  int $tipo
     * @return Procedencia
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }
}
