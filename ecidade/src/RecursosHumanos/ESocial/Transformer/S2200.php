<?php

namespace ECidade\RecursosHumanos\ESocial\Transformer;

use CgmRepository;
use DBPessoal;
use ECidade\RecursosHumanos\ESocial\Factory\CamposS2200;
use ECidade\RecursosHumanos\ESocial\Repository\AdmissaoPreliminarRepository;
use ECidade\RecursosHumanos\ESocial\Repository\AdmissaoTrabalhadorRepository;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorMovimentacao;
use ECidade\RecursosHumanos\Pessoal\Model\Sindicato;
use ECidade\RecursosHumanos\Pessoal\Repository\DependenteRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use Exception;
use ServidorRepository;

/**
 * Class S2200
 * @package ECidade\RecursosHumanos\ESocial\Transformer
 */
class S2200 extends Sugestao
{
    /**
     * @var int
     */
    private $matricula;

    /**
     * Como podem haver vários dependentes, use a string %dep% para servir
     * como um alias para o número do dependente
     *
     * @example 'dependente_%dep%' será convertido para 'dependente_1', 'dependente_2'... na hora de buscar os valores
     *
     * @var array
     */
    private $deParaDependentes = array(
        'nome' => 'nmDep_%dep%',
        'nascimento' => 'dtNascto_%dep%',
        'cpf' => 'cpfDep_%dep%',
        'tipo' => array(
            'tpDep_%dep%' => array(
                '1' => 'dependente_%dep%_tpDep_01',
                '2' => 'dependente_%dep%_tpDep_03',
                '3' => 'dependente_%dep%_tpDep_04',
                '4' => 'dependente_%dep%_tpDep_06',
                '6' => 'dependente_%dep%_tpDep_06',
                '5' => 'dependente_%dep%_tpDep_06',
                '6' => 'dependente_%dep%_tpDep_09',
                '7' => 'dependente_%dep%_tpDep_10',
                '8' => 'dependente_%dep%_tpDep_11'
            )
        ),
        'finsPrevidenciarios' => array(
            'depFinsPrev_%dep%' => array(
                true => 'depFinsPrev_sim_%dep%',
                false => 'depFinsPrev_nao_%dep%'
            )
        )
    );

    /**
     * @var array
     */
    private $deParaCeletista = array(
        'tpRegJor' => array(
            1 => 'tpRegJor_1',
            2 => 'tpRegJor_2',
            3 => 'tpRegJor_3',
            4 => 'tpRegJor_4'
        )
    );

    /**
     * S2200 constructor.
     * @param int $matricula
     */
    public function __construct($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function posProcessamento()
    {
        $this->deParaDependentes();
        $this->deParaAdmissaoPreliminar();
        $this->deParaCeletista();
    }

    /**
     * @throws Exception
     */
    private function deParaDependentes()
    {
        $servidor = ServidorRepository::getInstanciaByCodigo($this->matricula);

        $dependenteRepository = new DependenteRepository();
        $dependentes = $dependenteRepository
            ->scopeMatricula($this->matricula)
            ->orderBy(array('rh31_nome'))
            ->setUseJoin(true)
            ->get(array('rh31_nome', 'rh31_dtnasc', 'rh31_irf', 'dp01_cpf', 'rh31_fins_previdenciarios'));

        $numeroDependente = 1;


        foreach ($dependentes as $dependente) {
            foreach ($dependente->toArray() as $atributo => $valor) {
                if (in_array($atributo, $this->deParaESocial)) {
                    $valor = $this->buscarValorCorrespondenteESocial($atributo, $valor);
                }

                if (!array_key_exists($atributo, $this->deParaDependentes)) {
                    continue;
                }

                /**
                 * Somente trazer depFinsPrev se o trabalhador estiver vinculado ao RPPS
                 */
                if ($atributo === 'finsPrevidenciarios') {
                    if (!$servidor->isRpps()) {
                        continue;
                    }
                }

                if ($atributo === 'tipo') {
                    $campo = $this->parseStringDependente('depIRRF_%dep%', $numeroDependente);
                    $this->dados[$campo]['option'] = $this->parseStringDependente(
                        'dependente_%dep%_depIRRF_S',
                        $numeroDependente
                    );
                }

                $identificador = $this->deParaDependentes[$atributo];

                if (is_array($identificador)) {
                    $identificadorPergunta = key($identificador);

                    if (!empty($identificador[$identificadorPergunta][$valor])) {
                        $identificadorResposta = $identificador[$identificadorPergunta][$valor];

                        $identificadorResposta = $this->parseStringDependente(
                            $identificadorResposta,
                            $numeroDependente
                        );
                        $identificadorPergunta = $this->parseStringDependente(
                            $identificadorPergunta,
                            $numeroDependente
                        );

                        $this->dados[$identificadorPergunta]['option'] = $identificadorResposta;
                    } else {
                        /**
                         * Se não for do irrf, preencha o irrf com não
                         */
                        if ($atributo === 'tipo') {
                            $campo = $this->parseStringDependente('depIRRF_%dep%', $numeroDependente);
                            $this->dados[$campo]['option'] = $this->parseStringDependente(
                                'dependente_%dep%_depIRRF_N',
                                $numeroDependente
                            );
                        }
                    }
                } else {
                    $identificador = $this->parseStringDependente($identificador, $numeroDependente);
                    $this->dados[$identificador] = $valor;
                }
            }

            $numeroDependente++;
        }
    }

    /**
     * @param $nomeCampo
     * @param $valor
     * @return mixed
     * @throws Exception
     */
    protected function buscarValorCorrespondenteESocial($nomeCampo, $valor)
    {
        $campo = CamposS2200::getCampo($nomeCampo);

        return $campo->getValue($valor);
    }

    /**
     * @throws Exception
     */
    private function deParaAdmissaoPreliminar()
    {
        $cgm = CgmRepository::getByMatricula($this->matricula);

        $admissao = AdmissaoPreliminarRepository::getInstance()
            ->scopeCpf($cgm->getCpf())
            ->get();

        if (!empty($admissao)) {
            $this->dados['cadIni'] = array(
                'option' => 'cadIni_S',
            );
        }
    }

    /**
     * @throws Exception
     */
    private function deParaCeletista()
    {
        $servidorMovimentacaoRepository = new ServidorMovimentacaoRepository();
        $movimentacao = $servidorMovimentacaoRepository
            ->scopeAno(DBPessoal::getAnoFolha())
            ->scopeMes(DBPessoal::getMesFolha())
            ->scopeMatricula($this->matricula)
            ->first(array('rh02_regimejornadatrabalho'));

        $servidor = ServidorRepository::getInstanciaByCodigo($this->matricula);

        if ($movimentacao instanceof ServidorMovimentacao && $movimentacao->getRegimeJornadaTrabalho()) {
            $regimeJornadaTrabalho = $movimentacao->getRegimeJornadaTrabalho();
            $this->dados['tpRegJor']['option'] = $this->deParaCeletista['tpRegJor'][$regimeJornadaTrabalho];
        }

        if ($servidor->getSindicato() instanceof Sindicato) {
            $this->dados['dtBase'] = $servidor->getSindicato()->getMesDataBase() ?: '';
            $this->dados['cnpjSindCategProf'] = $servidor->getSindicato()->getCnpj() ?: '';
        }
    }

    /**
     * @return resource|null
     */
    protected function buscarDados()
    {
        return null;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function possuiPreenchimento()
    {
        $admissaoTrabalhadorRepository = AdmissaoTrabalhadorRepository::getInstance();

        $preenchimentos = $admissaoTrabalhadorRepository
            ->scopeMatricula($this->matricula)
            ->get(array('eso02_sequencial'));

        return !empty($preenchimentos);
    }

    /**
     * Substitui as 'variáveis' nas strings de $this->deParaDependentes
     *
     * @param string $string
     * @param int $numeroDependente
     * @return string
     */
    private function parseStringDependente($string, $numeroDependente)
    {
        $string = str_replace('%dep%', "{$numeroDependente}", $string);

        return $string;
    }
}
