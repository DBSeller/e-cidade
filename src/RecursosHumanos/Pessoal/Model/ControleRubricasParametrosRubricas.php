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

namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametros;
use Exception;
use Instituicao;
use InstituicaoRepository;
use Rubrica;
use RubricaRepository;

class ControleRubricasParametrosRubricas
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var ControleRubricasParametros
     */
    private $controleHorasExtras;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var Rubrica
     */
    private $rubrica;

    /**
     * @var bool
     */
    private $permiteExclusao = true;

    public function __construct($sequencial = null)
    {
        if ($sequencial) {
            $dao = db_utils::getDao("db_controlehorasextrasrubricas");
            $sql = $dao->sql_query_file($sequencial);
            $rs = $dao->sql_record($sql);
            $this::fromState($rs);
        }
    }

    /**
     * @param array $state
     * @return ControleRubricasParametrosRubricas
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $controleHorasExtrasRubricas = new self();

        if (array_key_exists('rh233_sequencial', $state)) {
            $controleHorasExtrasRubricas->setSequencial((int)$state['rh233_sequencial']);
        }

        if (array_key_exists('rh233_controlehorasextras', $state)) {
            $controleHorasExtrasRubricas->setControleHorasExtras(
                new ControleRubricasParametros((int)$state['rh233_controlehorasextras'])
            );
        }

        if (array_key_exists('rh233_instituicao', $state)) {
            $controleHorasExtrasRubricas->setInstituicao(
                InstituicaoRepository::getInstituicaoByCodigo((int)$state['rh233_instituicao'])
            );
        }

        if (array_key_exists('rh233_rubrica', $state)) {
            $controleHorasExtrasRubricas->setRubrica(
                RubricaRepository::getInstanciaByCodigo($state['rh233_rubrica'])
            );
        }

        if (array_key_exists('rh233_permite_exclusao', $state)) {
            $controleHorasExtrasRubricas->setPermiteExclusao($state['rh233_permite_exclusao'] === 't');
        }

        return $controleHorasExtrasRubricas;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return ControleRubricasParametros
     */
    public function getControleHorasExtras()
    {
        return $this->controleHorasExtras;
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     */
    public function setControleHorasExtras($controleHorasExtras)
    {
        $this->controleHorasExtras = $controleHorasExtras;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return Rubrica
     */
    public function getRubrica()
    {
        return $this->rubrica;
    }

    /**
     * @param Rubrica $rubrica
     */
    public function setRubrica($rubrica)
    {
        $this->rubrica = $rubrica;
    }

    /**
     * @return bool
     */
    public function isPermiteExclusao()
    {
        return $this->permiteExclusao;
    }

    /**
     * @param bool $permiteExclusao
     */
    public function setPermiteExclusao($permiteExclusao)
    {
        $this->permiteExclusao = $permiteExclusao;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'sequencial' => $this->getSequencial(),
            'codigoInstituicao' => $this->getInstituicao()->getCodigo(),
            'codigoRubrica' => $this->getRubrica()->getCodigo(),
            'descricaoRubrica' => $this->getRubrica()->getDescricao(),
            'permiteExclusao' => $this->isPermiteExclusao()
        );

        return $retorno;
    }
}
