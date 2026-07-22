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

namespace ECidade\Tributario\Juridico\Inicial;

use ECidade\Tributario\Arrecadacao\Custas\Interfaces;

use ECidade\Tributario\Divida\Certidao\Certidao;
use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilha;
use DateTime;
use ECidade\Tributario\Juridico\Inicial\Repository\InicialNumpreRepository;
use DBException;
use ECidade\Tributario\Caixa\Enum\ArretipoEnum;

/**
 * Class Inicial
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Inicial implements Interfaces\ParcelamentoHonorario
{
    /** @var integer inicial */
    private $codigo;

    /** @var integer Cgm do advogado */
    private $advogado;

    /** @var DateTime Data geraçao inicial */
    private $data;

    /** @var integer id_login */
    private $login;

    /** @var integer codlocal */
    private $codigoForo;

    /** @var integer codmov */
    private $codigoMovimento;

    /** @var integer instit */
    private $instituicao;

    /** @var integer situacao */
    private $situacao;

    /** @var Certidao[] */
    private $certidoes;

    /** @var InicialNome[] */
    private $inicialNomes;

    /** @var InicialNumpre[] */
    private $inicialNumpres;

    /** @var InicialPartilha[] */
    private $inicialPartilhas = array();

    /** @var integer parcelasHonorarios */
    private $parcelasHonorarios;

    /**
     * @param array $state
     * @return Inicial
     */
    public static function fromState($state)
    {
        $inicial = new self();

        if (array_key_exists('v50_inicial', $state)) {
            $inicial->setCodigo($state['v50_inicial']);
        }
        if (array_key_exists('v50_advog', $state)) {
            $inicial->setAdvogado($state['v50_advog']);
        }
        if (array_key_exists('v50_data', $state) && !empty($state['v50_data'])) {
            $inicial->setData(new DateTime($state['v50_data']));
        }
        if (array_key_exists('v50_id_login', $state)) {
            $inicial->setLogin($state['v50_id_login']);
        }
        if (array_key_exists('v70_codforo', $state)) {
            $inicial->setCodigoForo($state['v70_codforo']);
        }
        if (array_key_exists('v50_codmov', $state)) {
            $inicial->setCodigoMovimento($state['v50_codmov']);
        }
        if (array_key_exists('v50_instit', $state)) {
            $inicial->setInstituicao($state['v50_instit']);
        }
        if (array_key_exists('v50_situacao', $state)) {
            $inicial->setSituacao($state['v50_situacao']);
        }

        return $inicial;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Inicial
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getAdvogado()
    {
        return $this->advogado;
    }

    /**
     * @param int $advogado
     * @return Inicial
     */
    public function setAdvogado($advogado)
    {
        $this->advogado = $advogado;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     * @return Inicial
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param int $login
     * @return Inicial
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoForo()
    {
        return $this->codigoForo;
    }

    /**
     * @param int $codigoForo
     * @return Inicial
     */
    public function setCodigoForo($codigoForo)
    {
        $this->codigoForo = $codigoForo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoMovimento()
    {
        return $this->codigoMovimento;
    }

    /**
     * @param int $codigoMovimento
     * @return Inicial
     */
    public function setCodigoMovimento($codigoMovimento)
    {
        $this->codigoMovimento = $codigoMovimento;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $instituicao
     * @return Inicial
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param int $situacao
     * @return Inicial
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * @return Certidao[]
     */
    public function getCertidoes()
    {
        return $this->certidoes;
    }

    /**
     * @param Certidao[] $certidoes
     * @return Inicial
     */
    public function setCertidoes($certidoes)
    {
        $this->certidoes = $certidoes;
        return $this;
    }

    /**
     * @param Certidao $certidao
     * @return Inicial
     */
    public function addCertidao(Certidao $certidao)
    {
        $this->certidoes[] = $certidao;
        return $this;
    }

    /**
     * @return InicialNome[]
     */
    public function getInicialNomes()
    {
        return $this->inicialNomes;
    }

    /**
     * @param InicialNome[] $inicialNomes
     * @return Inicial
     */
    public function setInicialNomes($inicialNomes)
    {
        $this->inicialNomes = $inicialNomes;
        return $this;
    }

    /**
     * @param InicialNome $inicialNome
     * @return $this
     */
    public function addInicialNome($inicialNome)
    {
        $this->inicialNomes[] = $inicialNome;
        return $this;
    }

    /**
     * @return InicialNumpre[]
     */
    public function getInicialNumpres()
    {
        return $this->inicialNumpres;
    }

    /**
     * @param InicialNumpre[] $inicialNumpres
     * @return Inicial
     */
    public function setInicialNumpres($inicialNumpres)
    {
        $this->inicialNumpres = $inicialNumpres;
        return $this;
    }

    /**
     * @param InicialNumpre $inicialNumpre
     * @return $this
     */
    public function addInicialNumpre($inicialNumpre)
    {
        $this->inicialNumpres[] = $inicialNumpre;
        return $this;
    }

    /**
     * @return InicialPartilha[]
     */
    public function getInicialPartilhas()
    {
        return $this->inicialPartilhas;
    }

    /**
     * @param InicialPartilha[] $inicialPartilhas
     * @return Inicial
     */
    public function setInicialPartilhas($inicialPartilhas)
    {
        $this->inicialPartilhas = $inicialPartilhas;
        return $this;
    }

    /**
     * @param InicialPartilha $inicialPartilha
     * @return Inicial
     */
    public function addInicialPartilha($inicialPartilha)
    {
        $this->inicialPartilhas[] = $inicialPartilha;
        return $this;
    }

    /**
     * @return InicialPartilha|null
     */
    public function getFirstInicialPartilha()
    {
        if (!empty($this->inicialPartilhas)) {
            return reset($this->inicialPartilhas);
        }

        return null;
    }

    /**
     * Calcula o valor da inicial até a data informada
     * @param \DateTime $data data da atualização
     * @return int
     */
    public function getValorAtualizadoAte(\DateTime $data)
    {

        $sqlNumpres = "select v59_numpre from inicialnumpre where v59_inicial = {$this->getCodigo()}";
        $rsNumpres = db_query($sqlNumpres);
        $valorAtualizado = 0;
        \db_utils::makeCollectionFromRecord($rsNumpres, function ($dado) use (&$valorAtualizado, $data) {

            $rsDebitos = debitos_numpre($dado->v59_numpre, 0, 0, $data->getTimestamp(), $data->format('Y'));
            $totalRegistros = pg_num_rows($rsDebitos);

            for ($iIndDebito = 0; $iIndDebito < $totalRegistros; $iIndDebito++) {
                $valorAtualizado += \db_utils::fieldsMemory($rsDebitos, $iIndDebito)->total;
            }
        });
        return $valorAtualizado;
    }

    /**
     * @return Inicial
     */
    public function withNumpres()
    {
        if (empty($this->inicialNumpres)) {
            $numpreRepository = new InicialNumpreRepository();

            $numpres = $numpreRepository->scopeInicial($this->getCodigo())->get();
            $this->setInicialNumpres($numpres);
        }

        return $this;
    }


   /**
    * @param integer $inicial
    * @param mixed integer
    * @return integer[]
    */
    public static function getExercicios($inicial, $tipoInicial = ArretipoEnum::INICIALFORO)
    {
        switch ($tipoInicial) {
            case ArretipoEnum::INICIAL_FORO:
                return self::getExerciciosInicial($inicial);
            break;
        }
    }

    /**
     * @param integer $inicial
     * @return integer[]
     */
    public static function getExerciciosInicial($inicial)
    {
        $daoInicial = new \cl_inicial;
        $sql = $daoInicial->sql_query_exercicio_by_inicial($inicial);
        $rs = \db_query($sql);

        if (!$rs) {
            throw new DBException("Ocorreu erro ao buscar informações de exercício para a inicial código {$inicial}.");
        }

        if (pg_num_rows($rs) == 0) {
            return array(0);
        }

        $exercicios = \db_utils::makeCollectionFromRecord(
            $rs,
            function ($oItem) {
                return (int) $oItem->exercicio;
            }
        );
        return $exercicios;
    }

    /**
     * @return int
     */
    public function getParcelasHonorarios()
    {
        return $this->parcelasHonorarios;
    }

    /**
     * @param int $parcelasHonorarios
     */
    public function setParcelasHonorarios($parcelasHonorarios)
    {
        $this->parcelasHonorarios = $parcelasHonorarios;
    }
}
