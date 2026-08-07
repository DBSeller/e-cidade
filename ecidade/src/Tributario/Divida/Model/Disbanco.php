<?php

namespace ECidade\Tributario\Divida\Model;

use DateTime;
use Instituicao;
use InstituicaoRepository;

class Disbanco
{
    /**
     * @var string
     */
    private $numeroCbo;
    /**
     * @var int
     */
    private $codigoBanco;
    /**
     * @var string
     */
    private $codigoAgencia;
    /**
     * @var int
     */
    private $codigoRet;
    /**
     * @var DateTime
     */
    private $dataArquivo;
    /**
     * @var DateTime
     */
    private $dataPago;
    /**
     * @var float
     */
    private $valorPago;
    /**
     * @var float
     */
    private $valorJuros;
    /**
     * @var float
     */
    private $valorMulta;
    /**
     * @var float
     */
    private $valorAcrescimo;
    /**
     * @var float
     */
    private $valorDesconto;
    /**
     * @var float
     */
    private $valorTotal;
    /**
     * @var string
     */
    private $cedente;
    /**
     * @var float
     */
    private $valorCalculado;
    /**
     * @var int
     */
    private $idRet;
    /**
     * @var bool
     */
    private $classifica;
    /**
     * @var int
     */
    private $numpre;
    /**
     * @var int
     */
    private $numpar;
    /**
     * @var string
     */
    private $convenio;
    /**
     * @var Instituicao
     */
    private $instituicao;
    /**
     * @var DateTime
     */
    private $dataCredito;

    /**
     * @return string
     */
    public function getNumeroCbo()
    {
        return $this->numeroCbo;
    }

    /**
     * @param string $numeroCbo
     */
    public function setNumeroCbo($numeroCbo)
    {
        $this->numeroCbo = $numeroCbo;
    }

    /**
     * @return int
     */
    public function getCodigoBanco()
    {
        return $this->codigoBanco;
    }

    /**
     * @param int $codigoBanco
     */
    public function setCodigoBanco($codigoBanco)
    {
        $this->codigoBanco = $codigoBanco;
    }

    /**
     * @return string
     */
    public function getCodigoAgencia()
    {
        return $this->codigoAgencia;
    }

    /**
     * @param string $codigoAgencia
     */
    public function setCodigoAgencia($codigoAgencia)
    {
        $this->codigoAgencia = $codigoAgencia;
    }

    /**
     * @return int
     */
    public function getCodigoRet()
    {
        return $this->codigoRet;
    }

    /**
     * @param int $codigoRet
     */
    public function setCodigoRet($codigoRet)
    {
        $this->codigoRet = $codigoRet;
    }

    /**
     * @return DateTime
     */
    public function getDataArquivo()
    {
        return $this->dataArquivo;
    }

    /**
     * @param DateTime $dataArquivo
     */
    public function setDataArquivo($dataArquivo)
    {
        $this->dataArquivo = $dataArquivo;
    }

    /**
     * @return DateTime
     */
    public function getDataPago()
    {
        return $this->dataPago;
    }

    /**
     * @param DateTime $dataPago
     */
    public function setDataPago($dataPago)
    {
        $this->dataPago = $dataPago;
    }

    /**
     * @return float
     */
    public function getValorPago()
    {
        return $this->valorPago;
    }

    /**
     * @param float $valorPago
     */
    public function setValorPago($valorPago)
    {
        $this->valorPago = $valorPago;
    }

    /**
     * @return float
     */
    public function getValorJuros()
    {
        return $this->valorJuros;
    }

    /**
     * @param float $valorJuros
     */
    public function setValorJuros($valorJuros)
    {
        $this->valorJuros = $valorJuros;
    }

    /**
     * @return float
     */
    public function getValorMulta()
    {
        return $this->valorMulta;
    }

    /**
     * @param float $valorMulta
     */
    public function setValorMulta($valorMulta)
    {
        $this->valorMulta = $valorMulta;
    }

    /**
     * @return float
     */
    public function getValorAcrescimo()
    {
        return $this->valorAcrescimo;
    }

    /**
     * @param float $valorAcrescimo
     */
    public function setValorAcrescimo($valorAcrescimo)
    {
        $this->valorAcrescimo = $valorAcrescimo;
    }

    /**
     * @return float
     */
    public function getValorDesconto()
    {
        return $this->valorDesconto;
    }

    /**
     * @param float $valorDesconto
     */
    public function setValorDesconto($valorDesconto)
    {
        $this->valorDesconto = $valorDesconto;
    }

    /**
     * @return float
     */
    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    /**
     * @param float $valorTotal
     */
    public function setValorTotal($valorTotal)
    {
        $this->valorTotal = $valorTotal;
    }

    /**
     * @return string
     */
    public function getCedente()
    {
        return $this->cedente;
    }

    /**
     * @param string $cedente
     */
    public function setCedente($cedente)
    {
        $this->cedente = $cedente;
    }

    /**
     * @return float
     */
    public function getValorCalculado()
    {
        return $this->valorCalculado;
    }

    /**
     * @param float $valorCalculado
     */
    public function setValorCalculado($valorCalculado)
    {
        $this->valorCalculado = $valorCalculado;
    }

    /**
     * @return int
     */
    public function getIdRet()
    {
        return $this->idRet;
    }

    /**
     * @param int $idRet
     */
    public function setIdRet($idRet)
    {
        $this->idRet = $idRet;
    }

    /**
     * @return bool
     */
    public function isClassifica()
    {
        return $this->classifica;
    }

    /**
     * @param bool $classifica
     */
    public function setClassifica($classifica)
    {
        $this->classifica = $classifica;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param int $numpre
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    /**
     * @return int
     */
    public function getNumpar()
    {
        return $this->numpar;
    }

    /**
     * @param int $numpar
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
    }

    /**
     * @return string
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param string $convenio
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
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
     * @return DateTime
     */
    public function getDataCredito()
    {
        return $this->dataCredito;
    }

    /**
     * @param DateTime $dataCredito
     */
    public function setDataCredito($dataCredito)
    {
        $this->dataCredito = $dataCredito;
    }

    /**
     * @param array $state
     * @return Disbanco
     * @throws \Exception
     */
    public static function fromState(array $state)
    {
        $disbanco = new self();

        if (array_key_exists('k00_numbco', $state)) {
            $disbanco->setNumeroCbo($state['k00_numbco']);
        }
        if (array_key_exists('k15_codbco', $state)) {
            $disbanco->setCodigoBanco($state['k15_codbco']);
        }
        if (array_key_exists('k15_codage', $state)) {
            $disbanco->setCodigoAgencia($state['k15_codage']);
        }
        if (array_key_exists('codret', $state)) {
            $disbanco->setCodigoRet($state['codret']);
        }
        if (array_key_exists('dtarq', $state)) {
            $dataArquivo = new DateTime($state['dtarq']);
            $disbanco->setDataArquivo($dataArquivo);
        }
        if (array_key_exists('dtpago', $state)) {
            $dataPago = new DateTime($state['dtpago']);
            $disbanco->setDataPago($dataPago);
        }
        if (array_key_exists('vlrpago', $state)) {
            $disbanco->setValorPago($state['vlrpago']);
        }
        if (array_key_exists('vlrjuros', $state)) {
            $disbanco->setValorJuros($state['vlrjuros']);
        }
        if (array_key_exists('vlrmulta', $state)) {
            $disbanco->setValorMulta($state['vlrmulta']);
        }
        if (array_key_exists('vlracres', $state)) {
            $disbanco->setValorAcrescimo($state['vlracres']);
        }
        if (array_key_exists('vlrdesco', $state)) {
            $disbanco->setValorDesconto($state['vlrdesco']);
        }
        if (array_key_exists('vlrtot', $state)) {
            $disbanco->setValorTotal($state['vlrtot']);
        }
        if (array_key_exists('cedente', $state)) {
            $disbanco->setCedente($state['cedente']);
        }
        if (array_key_exists('vlrcalc', $state)) {
            $disbanco->setValorCalculado($state['vlrcalc']);
        }
        if (array_key_exists('idret', $state)) {
            $disbanco->setIdRet($state['idret']);
        }
        if (array_key_exists('classi', $state)) {
            $disbanco->setClassifica($state['classi'] == 't');
        }
        if (array_key_exists('k00_numpre', $state)) {
            $disbanco->setNumpre($state['k00_numpre']);
        }
        if (array_key_exists('k00_numpar', $state)) {
            $disbanco->setNumpar($state['k00_numpar']);
        }
        if (array_key_exists('convenio', $state)) {
            $disbanco->setConvenio($state['convenio']);
        }
        if (array_key_exists('instit', $state)) {
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo($state['instit']);
            $disbanco->setInstituicao($instituicao);
        }
        if (array_key_exists('dtcredito', $state) && !empty($state['dtcredito'])) {
            $dataCredito = new DateTime($state['dtcredito']);
            $disbanco->setDataCredito($dataCredito);
        }

        return $disbanco;
    }
}
