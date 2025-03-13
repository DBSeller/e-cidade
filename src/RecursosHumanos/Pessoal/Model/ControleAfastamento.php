<?php


namespace ECidade\RecursosHumanos\Pessoal\Model;

use BusinessException;
use Instituicao;
use InstituicaoRepository;
use Rubrica;
use RubricaRepository;

class ControleAfastamento
{
    /**
     * @var int $sequencial
     */
    private $sequencial;
    /**
     * @var int $afastamento
     */
    private $afastamento;
    /**
     * @var Rubrica $rubrica
     */
    private $rubrica;
    /**
     * @var int $tabelaPrevidencia
     */
    private $tabelaPrevidencia;
    /**
     * @var Instituicao $instituicao
     */
    private $instituicao;
    /**
     * @var int $ano
     */
    private $ano;
    /**
     * @var int $mes
     */
    private $mes;

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
    public function getAfastamento()
    {
        return $this->afastamento;
    }

    /**
     * @param int $afastamento
     */
    public function setAfastamento($afastamento)
    {
        $this->afastamento = $afastamento;
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
     * @return int
     */
    public function getTabelaPrevidencia()
    {
        return $this->tabelaPrevidencia;
    }

    /**
     * @param int $tabelaPrevidencia
     */
    public function setTabelaPrevidencia($tabelaPrevidencia)
    {
        $this->tabelaPrevidencia = $tabelaPrevidencia;
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
     * @param array $state
     * @return ControleAfastamento
     * @throws BusinessException
     */
    public static function fromState(array $state)
    {
        $controleAfastamento = new self();

        if (array_key_exists('rh231_sequencial', $state)) {
            $controleAfastamento->setSequencial((int)$state['rh231_sequencial']);
        }

        if (array_key_exists('rh231_afastamento', $state)) {
            $controleAfastamento->setAfastamento((int)$state['rh231_afastamento']);
        }

        if (array_key_exists('rh231_rubrica', $state)) {
            $controleAfastamento->setRubrica(RubricaRepository::getInstanciaByCodigo(trim($state['rh231_rubrica'])));
        }

        if (array_key_exists('rh231_tabelaprevidencia', $state)) {
            $controleAfastamento->setTabelaPrevidencia((int)($state['rh231_tabelaprevidencia']));
        }

        if (array_key_exists('rh231_instituicao', $state)) {
            $controleAfastamento->setInstituicao(
                InstituicaoRepository::getInstituicaoByCodigo($state['rh231_instituicao'])
            );
        }

        if (array_key_exists('rh231_ano', $state)) {
            $controleAfastamento->setAno((int)$state['rh231_ano']);
        }

        if (array_key_exists('rh231_mes', $state)) {
            $controleAfastamento->setMes((int)$state['rh231_mes']);
        }

        return $controleAfastamento;
    }

    public function toArray()
    {
        $rubrica = $this->getRubrica();
        $instituicao = $this->getInstituicao();

        if ($rubrica !== null) {
            $rubrica = $rubrica->toArray();
        }
        if ($instituicao !== null) {
            $instituicao = $instituicao->toArray();
        }

        return $retorno = array(
            'sequencial' => $this->getSequencial(),
            'afastamento' => $this->getAfastamento(),
            'rubrica' => $rubrica,
            'tabelaPrevidencia' => $this->getTabelaPrevidencia(),
            'instituicao' => $instituicao,
            'ano' => $this->getAno(),
            'mes' => $this->getMes()
        );
    }
}
