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

use Servidor;
use CgmFisico;
use ServidorRepository;
use CgmRepository;

/**
 * Class PensaoAlimenticia
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class PensaoAlimenticia
{
    /**
     * @var int
     */
    private $sequencial;
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
     * @var CgmFisico
     */
    private $cgmPensionista;
    /**
     * @var float
     */
    private $valorRescisao;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var float
     */
    private $valorDecimo;

    /**
     * PensaoAlimenticia constructor.
     * @param null $codigo
     * @throws Exception
     */
    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $pensaoAlimenticia = PensaoAlimenticiaRepository::find($codigo);
            $this->sequencial = $pensaoAlimenticia->getSequencial();
            $this->ano = $pensaoAlimenticia->getAno();
            $this->mes = $pensaoAlimenticia->getMes();
            $this->servidor = $pensaoAlimenticia->getServidor();
            $this->cgmPensionista = $pensaoAlimenticia->getCgmPensionista();
            $this->valorRescisao = $pensaoAlimenticia->getValorRescisao();
            $this->valor = $pensaoAlimenticia->getValor();
            $this->valorDecimo = $pensaoAlimenticia->getValorDecimo();
        }
    }

    /**
     * @param array $state
     * @return PensaoAlimenticia
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function fromState(array $state)
    {
        $pensaoAlimenticia = new self();

        if (array_key_exists('r52_sequencial', $state)) {
            $pensaoAlimenticia->setSequencial((int)$state['r52_sequencial']);
        }

        if (array_key_exists('r52_anousu', $state)) {
            $pensaoAlimenticia->setAno((int)$state['r52_anousu']);
        }

        if (array_key_exists('r52_mesusu', $state)) {
            $pensaoAlimenticia->setMes((int)$state['r52_mesusu']);
        }

        if (array_key_exists('r52_regist', $state)) {
            $pensaoAlimenticia->setServidor(ServidorRepository::getInstanciaByCodigo($state['r52_regist']));
        }

        if (array_key_exists('r52_numcgm', $state)) {
            $pensaoAlimenticia->setCgmPensionista(CgmRepository::getByCodigo($state['r52_numcgm']));
        }

        if (array_key_exists('r52_valres', $state)) {
            $pensaoAlimenticia->setValorRescisao((float)$state['r52_valres']);
        }

        if (array_key_exists('r52_valor', $state)) {
            $pensaoAlimenticia->setValor((float)$state['r52_valor']);
        }

        if (array_key_exists('r52_val13', $state)) {
            $pensaoAlimenticia->setValorDecimo((float)$state['r52_val13']);
        }

        return $pensaoAlimenticia;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'ano' => $this->getAno(),
            'mes' => $this->getMes(),
            'servidor' => $this->getServidor()->toArray(),
            'cgmPensionista' => $this->getCgmPensionista()->toArray(),
            'valorRescisao' => $this->getValorRescisao(),
            'valor' => $this->getValor(),
            'valorDecimo' => $this->getValorDecimo()
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

    /**
     * @return CgmFisico
     */
    public function getCgmPensionista()
    {
        return $this->cgmPensionista;
    }

    /**
     * @param CgmFisico $cgmPensionista
     */
    public function setCgmPensionista($cgmPensionista)
    {
        $this->cgmPensionista = $cgmPensionista;
    }

    /**
     * @return float
     */
    public function getValorRescisao()
    {
        return $this->valorRescisao;
    }

    /**
     * @param float $valor
     */
    public function setValorRescisao($valorRescisao)
    {
        $this->valorRescisao = $valorRescisao;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return float
     */
    public function getValorDecimo()
    {
        return $this->valorDecimo;
    }

    /**
     * @param float $valorDecimo
     */
    public function setValorDecimo($valorDecimo)
    {
        $this->valorDecimo = $valorDecimo;
    }
}
