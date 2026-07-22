<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Sessao\SessaoRequest;
use ECidade\RecursosHumanos\Pessoal\Repository\PontoSalarioRepository;
use ECidade\RecursosHumanos\Pessoal\Model\PontoSalario;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sessao
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 * @property int rh247_sequencial
 * @property int rh247_comissao
 * @property string rh247_data
 * @property boolean rh247_processada
 * @property int rh247_tiposessao
 * @property SessaoServidor[] $servidores
 */
class Sessao extends Model
{
    /**
     * @var string
     */
    protected $table = 'pessoal.jetomsessao';

    /**
     * @var string
     */
    protected $primaryKey = 'rh247_sequencial';

    /**
     * @var array
     */
    protected $with = ['tipo', 'comissao'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh247_sequencial;
    }

    /**
     * @param int $rh247_sequencial
     */
    public function setSequencial($rh247_sequencial)
    {
        $this->rh247_sequencial = $rh247_sequencial;
    }

    /**
     * @return int
     */
    public function getComissao()
    {
        return $this->rh247_comissao;
    }

    /**
     * @param int $rh247_comissao
     */
    public function setComissao($rh247_comissao)
    {
        $this->rh247_comissao = $rh247_comissao;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->rh247_data;
    }

    /**
     * @param string $rh247_data
     */
    public function setData($rh247_data)
    {
        $this->rh247_data = $rh247_data;
    }

    /**
     * @return bool
     */
    public function isProcessada()
    {
        return $this->rh247_processada;
    }

    /**
     * @param bool $rh247_processada
     */
    public function setProcessada($rh247_processada)
    {
        $this->rh247_processada = $rh247_processada;
    }

    /**
     * @return int
     */
    public function getTiposessao()
    {
        return $this->rh247_tiposessao;
    }

    /**
     * @param int $rh247_tiposessao
     */
    public function setTiposessao($rh247_tiposessao)
    {
        $this->rh247_tiposessao = $rh247_tiposessao;
    }

        /**
     * @return int
     */
    public function getMes()
    {
        return $this->rh247_mes;
    }

    /**
     * @param int $rh247_mes
     */
    public function setMes($rh247_mes)
    {
        $this->rh247_mes = $rh247_mes;
    }

            /**
     * @return int
     */
    public function getAno()
    {
        return $this->rh247_ano;
    }

    /**
     * @param int $rh247_mes
     */
    public function setAno($rh247_ano)
    {
        $this->rh247_ano = $rh247_ano;
    }

    public function servidores()
    {
        return $this->hasMany(
            'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\SessaoServidor',
            'rh248_sessao',
            'rh247_sequencial'
        );
    }

    public function comissao()
    {
        return $this
            ->belongsTo(
                'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Comissao',
                'rh247_comissao',
                'rh242_sequencial'
            )->orderBy('rh242_descricao');
    }

    public function tipo()
    {
        return $this
            ->hasOne(
                'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\TipoSessao',
                'rh240_sequencial',
                'rh247_tiposessao'
            );
    }

    /**
     * @param SessaoRequest $request
     * @return Sessao
     * @throws Exception
     */
    public static function saveFromRequest(SessaoRequest $request)
    {
        $sessao = new self();
        $verificaQuantidade = true;

        if ($request->get('rh247_sequencial')) {
            $sessao = $sessao->find($request->get('rh247_sequencial'));

            if (empty($sessao)) {
                throw new Exception('Nenhuma sessão foi encontrada com o código informado.');
            }

            if ($sessao->isProcessada()) {
                throw new Exception('Não é possível modificar uma Sessão já processada.');
            }

            /**
             * Caso for alteração e não modificar o tipo de sessão, não é necessário validar a quantidade.
             */
            if ($sessao->getTiposessao() == $request->get('rh247_tiposessao')) {
                $verificaQuantidade = false;
            }
        }

        if ($verificaQuantidade) {
            $tipoSessao = ComissaoTipoSessao::where([
                'rh249_comissao' => $request->get('rh247_comissao'),
                'rh249_tiposessao' => $request->get('rh247_tiposessao')
            ])->first();

            if (empty($tipoSessao)) {
                $mensagemErro = 'Não foi encontrada uma configuração para esse Tipo de Sessão na Comissão selecionada.';
                throw new Exception($mensagemErro);
            }

            $quantidadeSessoesConfiguradas = (int) $tipoSessao->getAttribute('rh249_quantidade');

            $quantidadeSessoesLancadas = Sessao::where([
                'rh247_comissao' => $request->get('rh247_comissao'),
                'rh247_tiposessao' => $request->get('rh247_tiposessao')
            ])->count();

            if ($quantidadeSessoesLancadas >= $quantidadeSessoesConfiguradas) {
                $descricao = 'sessões';
                if ($quantidadeSessoesConfiguradas == 1) {
                    $descricao = 'sessão';
                }

                $mensagemErro = "Não é possível lançar mais de {$quantidadeSessoesConfiguradas} {$descricao} do tipo ";
                $mensagemErro .= "<b>{$tipoSessao->tipo->getDescricao()}</b> na Comissão selecionada.";
                throw new Exception($mensagemErro);
            }
        }
        $sessao->setComissao($request->get('rh247_comissao'));
        $sessao->setData($request->get('rh247_data'));
        $sessao->setProcessada($request->get('rh247_processada') ? true : false);
        $sessao->setTiposessao($request->get('rh247_tiposessao'));
        $sessao->setMes($request->mes);
        $sessao->setAno($request->ano);
        $sessao->save();

        $sessao->servidores()->delete();

        if ($request->get('servidores')) {
            $servidores = json_decode($request->get('servidores'));

            foreach ($servidores as $servidor) {
                $sessaoServidor = new SessaoServidor();
                $sessaoServidor->setServidor($servidor);
                $sessao->servidores()->save($sessaoServidor);
            }
        }

        return $sessao;
    }

    /**
     * @throws Exception
     */
    public function processar()
    {
        try {
            if ($this->isProcessada()) {
                throw new Exception('Não é possível reprocessar uma Sessão.');
            }

            foreach ($this->servidores as $servidor) {
                $configuracaoServidor = ComissaoConfiguracao::buscaConfiguracaoFiltrosProcessamento(
                    $this->getComissao(),
                    $servidor->dadosServidor->getAttribute('rh245_funcao'),
                    $this->getTiposessao()
                );

                if (empty($configuracaoServidor)) {
                    throw new Exception('Nenhuma configuração encontrada para a sessão selecionada.');
                }

                $rubrica = $configuracaoServidor->getAttribute('rh243_rubrica');
                $valor = $configuracaoServidor->getAttribute('rh243_valor');
                $quantidade = 1;

                $pontoSalario = PontoSalarioRepository::find(
                    $servidor->dadosServidor->getAttribute('rh245_matricula'),
                    \DBPessoal::getAnoFolha(),
                    \DBPessoal::getMesFolha(),
                    $rubrica
                );
                $pontoSalarioRepository = new PontoSalarioRepository();

                if (empty($pontoSalario)) {
                    $pontoSalario = new PontoSalario();
                } else {
                    $valor += $pontoSalario->getValor();
                    $quantidade += $pontoSalario->getQuantidade();
                    $pontoSalarioRepository->delete($pontoSalario);
                }


                $servidorDados = \ServidorRepository::getInstanciaByCodigo(
                    $servidor->dadosServidor->getAttribute('rh245_matricula')
                );

                $pontoSalario->setAno(\DBPessoal::getAnoFolha());
                $pontoSalario->setMes(\DBPessoal::getMesFolha());
                $pontoSalario->setMatricula($servidorDados->getMatricula());
                $pontoSalario->setRubrica($rubrica);
                $pontoSalario->setValor($valor);
                $pontoSalario->setQuantidade($quantidade);
                $pontoSalario->setLotacao($servidorDados->getCodigoLotacao());
                $pontoSalario->setInstituicao($servidorDados->getCodigoInstituicao());
                $pontoSalarioRepository->save($pontoSalario);
            }
            $this->setProcessada(true);
            $this->save();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Verifica se o usuário tem login ativo no sistema
     * @return Array de matriculas ativas
     * @throws Exception
     */
    public static function usuarioLoginsAtivos($db_session)
    {

        $columns = [
            'nome', 'login', 'usuarioativo', 'db_usuarios.id_usuario',
            'cgmlogin', 'z01_numcgm', 'z01_nome',
            'rh01_regist', 'rh01_instit'
        ];

        $loginExists = \DB::table('db_usuarios')
            ->select($columns)
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'db_usuarios.id_usuario')
            ->join('cgm', 'z01_numcgm', 'db_usuacgm.cgmlogin')
        ->join('rhpessoal', function ($join) use ($db_session) {

                $join->on('rhpessoal.rh01_numcgm', 'z01_numcgm')
            ->where('rhpessoal.rh01_instit', $db_session['instit']); // busca pela instituicao
        })
        ->where('db_usuarios.usuarioativo', 1) // busca por usuario ativo
        ->where('db_usuarios.id_usuario', $db_session['id_usuario']) // ID usuario a ser consultado
        ;

        $dataMatriculas = $loginExists->get()->map(function ($obj, $key) {
            return $obj->rh01_regist;
        })->toArray();

        // valida se o usuario tem login
        if (!$dataMatriculas) {
            throw new Exception("Servidor sem login");
        }

        return $dataMatriculas;
    }

    /**
     * @param $comissaoServidor
     * @param null $dataInicial
     * @param null $dataFinal
     * @param boolean $filtroProcessados retorna o total de Processados e não Processados
     * @return object Collection
     */
    public static function getSessoesDoServidorPorCompetenciaComissaoFuncao(
        $comissaoServidor,
        $ano = null,
        $mes = null,
        $filtroProcessados = false
    ) {
        $colunas = ["rh247_tiposessao", "rh247_processada", \DB::raw("COUNT(rh247_sequencial) as quantidade")];
        $agrupamento = ["rh247_tiposessao", "rh247_processada"];
        $competencia = CompetenciaHelper::get();

        if ($ano == null) {
            $competencia->getAno();
        }
        if ($mes == null) {
            $competencia->getMes();
        }
        if ($filtroProcessados) {
            array_shift($colunas);
            array_shift($agrupamento);
        }

        $retorno = \DB::table('pessoal.jetomsessao')
            ->select($colunas)
            ->join("pessoal.jetomsessaoservidor", "rh248_sessao", "rh247_sequencial")
            ->where("rh248_servidor", $comissaoServidor)
            ->where('rh247_ano', '=', $ano)
            ->where('rh247_mes', '=', $mes)
            ->groupby($agrupamento);
        return $retorno->get();
    }

    public static function verificaProcessoExistente($tipo, $servidor, $lancamentosServidor, $competencia)
    {
        $sessoesDoServidor = self::getSessoesDoServidorPorPeriodoComissaoFuncao(
            $servidor->rh245_sequencial,
            $competencia->dataInicial,
            $competencia->dataFinal
        );

        $sessoesPorTipo = $sessoesDoServidor->whereIn('rh247_tiposessao', [$tipo]);
        $sessaoProcessada = $sessoesPorTipo->whereIn('rh247_processada', [true]);

        $descricaoTipo = [
            1 => 'normal',
            2 => 'extraordinaria',
            3 => 'urgente'
        ];

        if ($sessaoProcessada->isNotEmpty()) {
            if ($lancamentosServidor[$descricaoTipo[$tipo]] < $sessaoProcessada->first()->quantidade) {
                $infoProcesso = $lancamentosServidor[$descricaoTipo[$tipo]];
                $infoProcesso.= "/".$sessaoProcessada->first()->quantidade;
                throw new Exception(
                    $infoProcesso." Não é possivel retroceder a quantidade de sessões já processadas do tipo: ".
                    $descricaoTipo[$tipo]. " Para o(a) servidor(a): ".$servidor->z01_nome
                );
            }
        }
    }
}
