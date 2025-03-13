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

namespace ECidade\RecursosHumanos\Pessoal\Model;

use Instituicao;
use Servidor;
use ServidorRepository;

/**
 * Class ServidorProcessosJudiciaisFolha
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class ServidorProcessosJudiciaisFolha
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var int
     */
    private $tipoProcesso;

    /**
     * @var string
     */
    private $numeroProcesso;

    /**
     * @var string
     */
    private $codigoIndicativoSuspensao;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var int
     */
    private $ano;

    /**
     * @var int
     */
    private $mes;

    /**
     * @var Servidor
     */
    private $servidor;

    /**
     * ServidorProcessosJudiciaisFolha constructor.
     * @param null|int $codigo
     */
    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $servidorProcessoJudicial = ServidorProcessosJudiciaisFolhaRepository::find($codigo);
            $this->sequencial = $servidorProcessoJudicial->getSequencial();
            $this->tipoProcesso = $servidorProcessoJudicial->getTipoContribuicao();
            $this->numeroProcesso = $servidorProcessoJudicial->getTipoInscricao();
            $this->codigoIndicativoSuspensao = $servidorProcessoJudicial->getNumeroInscricao();
            $this->instituicao = $servidorProcessoJudicial->getInstituicao();
            $this->ano = $servidorProcessoJudicial->getAno();
            $this->mes = $servidorProcessoJudicial->getMes();
            $this->servidor = $servidorProcessoJudicial->getServidor();
        }
    }

    /**
     * @param array $state
     * @return ServidorProcessosJudiciaisFolha
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function fromState(array $state)
    {

        $servidorProcessosJudiciais = new self();

        if (array_key_exists('rh226_sequencial', $state)) {
            $servidorProcessosJudiciais->setSequencial((int)$state['rh226_sequencial']);
        }

        if (array_key_exists('rh226_tipoprocesso', $state)) {
            $servidorProcessosJudiciais->setTipoProcesso((int)$state['rh226_tipoprocesso']);
        }

        if (array_key_exists('rh226_numero', $state)) {
            $servidorProcessosJudiciais->setNumeroProcesso((string)$state['rh226_numero']);
        }

        if (array_key_exists('rh226_indicativosuspensao', $state)) {
            $servidorProcessosJudiciais->setCodigoIndicativoSuspensao((string)$state['rh226_indicativosuspensao']);
        }

        if (array_key_exists('rh226_instituicao', $state)) {
            $servidorProcessosJudiciais->setInstituicao(new Instituicao($state['rh226_instituicao']));
        }

        if (array_key_exists('rh226_ano', $state)) {
            $servidorProcessosJudiciais->setAno((int)$state['rh226_ano']);
        }

        if (array_key_exists('rh226_mes', $state)) {
            $servidorProcessosJudiciais->setMes((int)$state['rh226_mes']);
        }

        if (array_key_exists('rh226_matricula', $state)) {
            $servidorProcessosJudiciais->setServidor(ServidorRepository::getInstanciaByCodigo($state['rh226_matricula']));
        }

        return $servidorProcessosJudiciais;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
          'sequencial'       => $this->getSequencial(),
          'tipoProcesso' => $this->getTipoProcesso(),
          'numeroProcesso'    => $this->getNumeroProcesso(),
          'codigoIndicativoSuspensao'  => $this->getCodigoIndicativoSuspensao(),
          'instituicao'      => $this->getInstituicao()->toArray(),
          'ano'              => $this->getAno(),
          'mes'              => $this->getMes(),
          'servidor'         => $this->getServidor()->toArray()
        );
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
     * @return int
     */
    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

    /**
     * @param int $tipoProcesso
     */
    public function setTipoProcesso($tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
    }

    /**
     * @return string
     */
    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

    /**
     * @param string $numeroProcesso
     */
    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

    /**
     * @return string
     */
    public function getCodigoIndicativoSuspensao()
    {
        return $this->codigoIndicativoSuspensao;
    }

    /**
     * @param string $codigoIndicativoSuspensao
     */
    public function setCodigoIndicativoSuspensao($codigoIndicativoSuspensao)
    {
        $this->codigoIndicativoSuspensao = $codigoIndicativoSuspensao;
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
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param int $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * @param Servidor $servidor
     */
    public function setServidor($servidor)
    {
        $this->servidor = $servidor;
    }
}
