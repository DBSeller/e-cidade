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

use cl_controlehorasextras;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRubricasRepository;
use Exception;
use Instituicao;
use InstituicaoRepository;
use Selecao;

class ControleRubricasParametros
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var Selecao
     */
    private $selecao;

    /**
     * @var int
     */
    private $ano;

    /**
     * @var int
     */
    private $mes;

    /**
     * @var ControleRubricasParametrosRubricas[]
     */
    private $controleHorasExtrasRubricas;

    /**
     * @param string $sequencial
     * @throws Exception
     */
    public function __construct($sequencial = null)
    {
        if ($sequencial) {
            $dao = new cl_controlehorasextras();
            $repository = new ControleRubricasParametrosRepository($dao);
            $dados = $repository->find($sequencial);
            $this->sequencial= $dados->getSequencial();
            $this->instituicao= $dados->getInstituicao();
            $this->ano = $dados->getAno();
            $this->mes = $dados->getMes();
        }
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @return Selecao
     */
    public function getSelecao()
    {
        return $this->selecao;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param Selecao $selecao
     */
    public function setSelecao($selecao)
    {
        $this->selecao = $selecao;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @param int $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * @param ControleRubricasParametrosRubricas $controleHorasExtrasRubricas
     */
    public function addControleHorasExtrasRubricas(ControleRubricasParametrosRubricas $controleHorasExtrasRubricas)
    {
        $this->controleHorasExtrasRubricas[] = $controleHorasExtrasRubricas;
    }

    /**
     * @return ControleRubricasParametrosRubricas[]
     */
    public function getControleHorasExtrasRubricas()
    {
        return $this->controleHorasExtrasRubricas;
    }

    public static function fromState(array $state)
    {
        $controleHorasExtras = new self();

        if (array_key_exists('rh232_sequencial', $state)) {
            $controleHorasExtras->setSequencial((int)$state['rh232_sequencial']);
        }

        if (array_key_exists('rh232_instituicao', $state)) {
            $controleHorasExtras->setInstituicao(
                InstituicaoRepository::getInstituicaoByCodigo((int)$state['rh232_instituicao'])
            );
        }

        if (array_key_exists('rh232_selecao', $state)) {
            $controleHorasExtras->setSelecao(new Selecao((int)$state['rh232_selecao']));
        }

        if (array_key_exists('rh232_ano', $state)) {
            $controleHorasExtras->setAno((int)$state['rh232_ano']);
        }

        if (array_key_exists('rh232_mes', $state)) {
            $controleHorasExtras->setMes((int)$state['rh232_mes']);
        }

        return $controleHorasExtras;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'sequencial' => $this->getSequencial(),
            'codigoInstituicao' => $this->getInstituicao()->getCodigo(),
            'codigoSelecao' => $this->getSelecao()->getCodigo(),
            'descricaoSelecao' => $this->getSelecao()->getDescricao(),
            'ano' => $this->getAno(),
            'mes' => $this->getMes(),
            'controleHorasExtrasRubricas' => array()
        );

        if ($this->getControleHorasExtrasRubricas()) {
            foreach ($this->getControleHorasExtrasRubricas() as $controleHorasExtrasRubricas) {
                $retorno['controleHorasExtrasRubricas'][] = $controleHorasExtrasRubricas->toArray();
            }
        }

        return $retorno;
    }

    /**
     * @throws Exception
     */
    public function withRubricas()
    {
        if ($this->sequencial) {
            $repository = new ControleRubricasParametrosRubricasRepository();
            $controleHorasExtrasRubricas = $repository->buscarPorControleHorasExtras($this);

            foreach ($controleHorasExtrasRubricas as $controleHorasExtrasRubrica) {
                $this->addControleHorasExtrasRubricas($controleHorasExtrasRubrica);
            }
        }

        return $this;
    }
}
