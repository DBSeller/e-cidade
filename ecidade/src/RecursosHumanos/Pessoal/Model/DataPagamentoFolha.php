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

use ECidade\RecursosHumanos\Pessoal\Repository\DataPagamentoFolhaRepository;

use Instituicao;
use DBDate;

/**
 * Class DataPagamentoFolha
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class DataPagamentoFolha
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
     * @var int
     */
    private $ano;
    /**
     * @var int
     */
    private $mes;
    /**
     * @var DBDate
     */
    private $dataPagamento;

    /**
     * DataPagamentoFolha constructor.
     * @param null $codigo
     * @throws \Exception
     */
    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $dataPagamentoFolha = DataPagamentoFolhaRepository::find($codigo);
            $this->sequencial = $dataPagamentoFolha->getSequencial();
            $this->instituicao = $dataPagamentoFolha->getInstituicao();
            $this->ano = $dataPagamentoFolha->getAno();
            $this->mes = $dataPagamentoFolha->getMes();
            $this->dataPagamento = $dataPagamentoFolha->getDataPagamento();
        }
    }

    /**
     * @param array $state
     * @return DataPagamentoFolha
     * @throws \DBException
     * @throws \ParameterException
     */
    public static function fromState(array $state)
    {
        $dataPagamentoFolha = new self();

        if (array_key_exists('rh225_sequencial', $state)) {
            $dataPagamentoFolha->setSequencial((int)$state['rh225_sequencial']);
        }

        if (array_key_exists('rh225_instituicao', $state)) {
            $dataPagamentoFolha->setInstituicao(new Instituicao($state['rh225_instituicao']));
        }

        if (array_key_exists('rh225_ano', $state)) {
            $dataPagamentoFolha->setAno((int)$state['rh225_ano']);
        }

        if (array_key_exists('rh225_mes', $state)) {
            $dataPagamentoFolha->setMes((int)$state['rh225_mes']);
        }

        if (array_key_exists('rh225_datapagamento', $state)) {
            $dataPagamentoFolha->setDataPagamento(new DBDate($state['rh225_datapagamento']));
        }

        return $dataPagamentoFolha;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
          'sequencial'       => $this->getSequencial(),
          'instituicao'      => $this->getInstituicao()->toArray(),
          'ano'              => $this->getAno(),
          'mes'              => $this->getMes(),
          'dataPagamento'    => $this->getDataPagamento()->getDate(DBDate::DATA_PTBR)
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

    /**
     * @return DBDate
     */
    public function getDataPagamento() {
        return $this->dataPagamento;
    }

    /**
     * @param DBDate $dataPagamento
     */
    public function setDataPagamento(DBDate $dataPagamento) {
        $this->dataPagamento = $dataPagamento;
    }
}
