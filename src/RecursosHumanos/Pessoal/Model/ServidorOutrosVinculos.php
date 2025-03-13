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

use Instituicao;
use Servidor;
use ServidorRepository;

/**
 * Class ServidorOutrosVinculos
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class ServidorOutrosVinculos
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $seqpes;
    /**
     * @var int
     */
    private $tipoContribuicao;
    /**
     * @var int
     */
    private $tipoInscricao;
    /**
     * @var int
     */
    private $numeroInscricao;
    /**
     * @var int
     */
    private $codigoCategoria;
    /**
     * @var float
     */
    private $valorRemuneracao;
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
     * ServidorOutrosVinculos constructor.
     * @param null $codigo
     * @throws Exception
     */
    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $servidorOutrosVinculos = ServidorOutrosVinculosRepository::find($codigo);
            $this->sequencial = $servidorOutrosVinculos->getSequencial();
            $this->tipoContribuicao = $servidorOutrosVinculos->getTipoContribuicao();
            $this->tipoInscricao = $servidorOutrosVinculos->getTipoInscricao();
            $this->numeroInscricao = $servidorOutrosVinculos->getNumeroInscricao();
            $this->codigoCategoria = $servidorOutrosVinculos->getCodigoCategoria();
            $this->valorRemuneracao = $servidorOutrosVinculos->getValorRemuneracao();
            $this->instituicao = $servidorOutrosVinculos->getInstituicao();
            $this->ano = $servidorOutrosVinculos->getAno();
            $this->mes = $servidorOutrosVinculos->getMes();
            $this->servidor = $servidorOutrosVinculos->getServidor();
        }
    }

    /**
     * @param array $state
     * @return ServidorOutrosVinculos
     * @throws Exception
     */
    public static function fromState(array $state)
    {

        $servidorOutrosVinculos = new self();

        if (array_key_exists('rh224_sequencial', $state)) {
            $servidorOutrosVinculos->setSequencial((int)$state['rh224_sequencial']);
        }

        if (array_key_exists('rh224_tipocontribuicao', $state)) {
            $servidorOutrosVinculos->setTipoContribuicao((int)$state['rh224_tipocontribuicao']);
        }

        if (array_key_exists('rh224_tipoinscricao', $state)) {
            $servidorOutrosVinculos->setTipoInscricao((int)$state['rh224_tipoinscricao']);
        }

        if (array_key_exists('rh224_numeroinscricao', $state)) {
            $servidorOutrosVinculos->setNumeroInscricao((string)$state['rh224_numeroinscricao']);
        }

        if (array_key_exists('rh224_codigocategoria', $state)) {
            $servidorOutrosVinculos->setCodigoCategoria((int)$state['rh224_codigocategoria']);
        }

        if (array_key_exists('rh224_valorremuneracao', $state)) {
            $servidorOutrosVinculos->setValorRemuneracao((float)$state['rh224_valorremuneracao']);
        }

        if (array_key_exists('rh224_instituicao', $state)) {
            $servidorOutrosVinculos->setInstituicao(new Instituicao($state['rh224_instituicao']));
        }

        if (array_key_exists('rh224_ano', $state)) {
            $servidorOutrosVinculos->setAno((int)$state['rh224_ano']);
        }

        if (array_key_exists('rh224_mes', $state)) {
            $servidorOutrosVinculos->setMes((int)$state['rh224_mes']);
        }

        if (array_key_exists('rh224_matricula', $state)) {
            $servidorOutrosVinculos->setServidor(ServidorRepository::getInstanciaByCodigo($state['rh224_matricula']));
        }

        return $servidorOutrosVinculos;
    }

    public function setSeqpes($seqpes)
    {
        $this->seqpes = $seqpes;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
          'sequencial'       => $this->getSequencial(),
          'tipoContribuicao' => $this->getTipoContribuicao(),
          'tipoInscricao'    => $this->getTipoInscricao(),
          'numeroInscricao'  => $this->getNumeroInscricao(),
          'codigoCategoria'  => $this->getCodigoCategoria(),
          'valorRemuneracao' => $this->getValorRemuneracao(),
          'instituicao'      => $this->getInstituicao()->toArray(),
          'ano'              => $this->getAno(),
          'mes'              => $this->getMes(),
          'servidor'         => $this->getServidor()->toArray()
        );
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function getTipoContribuicao()
    {
        return $this->tipoContribuicao;
    }

    public function setTipoContribuicao($tipoContribuicao)
    {
        $this->tipoContribuicao = $tipoContribuicao;
    }

    public function getTipoInscricao()
    {
        return $this->tipoInscricao;
    }

    public function setTipoInscricao($tipoInscricao)
    {
        $this->tipoInscricao = $tipoInscricao;
    }

    public function getNumeroInscricao()
    {
        return $this->numeroInscricao;
    }

    public function setNumeroInscricao($numeroInscricao)
    {
        $this->numeroInscricao = $numeroInscricao;
    }

    public function getCodigoCategoria()
    {
        return $this->codigoCategoria;
    }

    public function setCodigoCategoria($codigoCategoria)
    {
        $this->codigoCategoria = $codigoCategoria;
    }

    public function getValorRemuneracao()
    {
        return $this->valorRemuneracao;
    }

    public function setValorRemuneracao($valorRemuneracao)
    {
        $this->valorRemuneracao = $valorRemuneracao;
    }

    public function getInstituicao()
    {
        return $this->instituicao;
    }

    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function getMes()
    {
        return $this->mes;
    }

    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    public function getServidor()
    {
        return $this->servidor;
    }

    public function setServidor(Servidor $servidor)
    {
        $this->servidor = $servidor;
    }
}