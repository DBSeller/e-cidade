<?php

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use Exception;

/**
 * Class Linha
 * @package ECidade\Configuracao\RelatorioLegal\Modelo
 */
class Linha
{
    /**
     * @var Relatorio
     */
    private $relatorio;
    /**
     * @var int
     */
    private $linha;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var int
     */
    private $grupo;
    /**
     * @var int
     */
    private $grupoExclusao;
    /**
     * @var int
     */
    private $nivel;
    /**
     * @var bool
     */
    private $liberaNivel;
    /**
     * @var bool
     */
    private $liberaRecurso;
    /**
     * @var bool
     */
    private $liberaSubFuncao;
    /**
     * @var bool
     */
    private $liberaFuncao;
    /**
     * @var bool
     */
    private $verificaAno;
    /**
     * @var string
     */
    private $label;
    /**
     * @var bool
     */
    private $manual;
    /**
     * @var bool
     */
    private $totalizadora;
    /**
     * @var int
     */
    private $ordem;
    /**
     * @var int
     */
    private $nivelLinha;
    /**
     * @var string
     */
    private $observacao;
    /**
     * @var bool
     */
    private $desdobra;
    /**
     * @var int
     */
    private $origem;

    /**
     * @var LinhaColuna[]
     */
    private $linhaColunas = [];

    /**
     * @var ConfiguracaoPadrao[]
     */
    private $filtroPadrao = [];

    /**
     * @var LinhaInformacaoComplementar[]
     */
    private $linhaInformacoesComplementares = [];

    /**
     * @var Configuracao
     */
    private $configuracaoUsuario;

    /**
     * @param array $state
     * @return Linha
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o69_codparamrel', $state)) {
            $self->setRelatorio(RelatorioRegistry::get($state['o69_codparamrel']));
        }

        if (array_key_exists('o69_codseq', $state)) {
            $self->setLinha($state['o69_codseq']);
        }

        if (array_key_exists('o69_descr', $state)) {
            $self->setDescricao($state['o69_descr']);
        }

        if (array_key_exists('o69_grupo', $state)) {
            $self->setGrupo($state['o69_grupo']);
        }

        if (array_key_exists('o69_grupoexclusao', $state)) {
            $self->setGrupoExclusao($state['o69_grupoexclusao']);
        }

        if (array_key_exists('o69_nivel', $state)) {
            $self->setNivel($state['o69_nivel']);
        }

        if (array_key_exists('o69_libnivel', $state)) {
            $self->setLiberaNivel($state['o69_libnivel'] === 't');
        }

        if (array_key_exists('o69_librec', $state)) {
            $self->setLiberaRecurso($state['o69_librec'] === 't');
        }

        if (array_key_exists('o69_libsubfunc', $state)) {
            $self->setLiberaSubFuncao($state['o69_libsubfunc'] === 't');
        }

        if (array_key_exists('o69_libfunc', $state)) {
            $self->setLiberaFuncao($state['o69_libfunc'] === 't');
        }

        if (array_key_exists('o69_verificaano', $state)) {
            $self->setVerificaAno($state['o69_verificaano'] === 't');
        }

        if (array_key_exists('o69_labelrel', $state)) {
            $self->setLabel($state['o69_labelrel']);
        }

        if (array_key_exists('o69_manual', $state)) {
            $self->setManual($state['o69_manual'] === 't');
        }

        if (array_key_exists('o69_totalizador', $state)) {
            $self->setTotalizadora($state['o69_totalizador'] === 't');
        }

        if (array_key_exists('o69_ordem', $state)) {
            $self->setOrdem($state['o69_ordem']);
        }

        if (array_key_exists('o69_nivellinha', $state)) {
            $self->setNivelLinha($state['o69_nivellinha']);
        }

        if (array_key_exists('o69_observacao', $state)) {
            $self->setObservacao($state['o69_observacao']);
        }

        if (array_key_exists('o69_desdobrarlinha', $state)) {
            $self->setDesdobra($state['o69_desdobrarlinha'] === 't');
        }

        if (array_key_exists('o69_origem', $state)) {
            $self->setOrigem($state['o69_origem']);
        }

        LinhaRegistry::set($self);

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $dados = array(
            'relatorio' => $this->getRelatorio()->getSequencial(),
            'linha' => $this->getLinha(),
            'descricao' => $this->getDescricao(),
            'grupo' => $this->getGrupo(),
            'grupoExclusao' => $this->getGrupoExclusao(),
            'nivel' => $this->getNivel(),
            'liberaNivel' => $this->isLiberaNivel(),
            'liberaRecurso' => $this->isLiberaRecurso(),
            'liberaSubFuncao' => $this->isLiberaSubFuncao(),
            'liberaFuncao' => $this->isLiberaFuncao(),
            'verificaAno' => $this->isVerificaAno(),
            'label' => $this->getLabel(),
            'manual' => $this->isManual(),
            'totalizadora' => $this->isTotalizadora(),
            'ordem' => $this->getOrdem(),
            'nivelLinha' => $this->getNivelLinha(),
            'observacao' => $this->getObservacao(),
            'desdobra' => $this->isDesdobra(),
            'origem' => $this->getOrigem(),
            'colunas' => array(),
            'filtroPadrao' => array(),
            'informacaoComplementar' => array(),
        );

        if ($this->linhaColunas) {
            foreach ($this->linhaColunas as $coluna) {
                $dados['colunas'][] = $coluna->toArray();
            }
        }
        if ($this->filtroPadrao) {
            foreach ($this->filtroPadrao as $filtroPadrao) {
                $dados['filtroPadrao'][] = $filtroPadrao->toArray();
            }
        }

        if ($this->linhaInformacoesComplementares) {
            foreach ($this->linhaInformacoesComplementares as $informacaoComplementar) {
                $dados['informacaoComplementar'][] = $informacaoComplementar->toArray();
            }
        }

        return $dados;
    }

    /**
     * @return Relatorio
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * @param Relatorio $relatorio
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
    }

    /**
     * @return int
     */
    public function getLinha()
    {
        return (int)$this->linha;
    }

    /**
     * @param int $linha
     */
    public function setLinha($linha)
    {
        $this->linha = (int)$linha;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return (string)$this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = (string)$descricao;
    }

    /**
     * @return int
     */
    public function getGrupo()
    {
        return (int)$this->grupo;
    }

    /**
     * @param int $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = (int)$grupo;
    }

    /**
     * @return int
     */
    public function getGrupoExclusao()
    {
        return (int)$this->grupoExclusao;
    }

    /**
     * @param int $grupoExclusao
     */
    public function setGrupoExclusao($grupoExclusao)
    {
        $this->grupoExclusao = (int)$grupoExclusao;
    }

    /**
     * @return int
     */
    public function getNivel()
    {
        return (int)$this->nivel;
    }

    /**
     * @param int $nivel
     */
    public function setNivel($nivel)
    {
        $this->nivel = (int)$nivel;
    }

    /**
     * @return bool
     */
    public function isLiberaNivel()
    {
        return (bool)$this->liberaNivel;
    }

    /**
     * @param bool $liberaNivel
     */
    public function setLiberaNivel($liberaNivel)
    {
        $this->liberaNivel = (bool)$liberaNivel;
    }

    /**
     * @return bool
     */
    public function isLiberaRecurso()
    {
        return (bool)$this->liberaRecurso;
    }

    /**
     * @param bool $liberaRecurso
     */
    public function setLiberaRecurso($liberaRecurso)
    {
        $this->liberaRecurso = (bool)$liberaRecurso;
    }

    /**
     * @return bool
     */
    public function isLiberaSubFuncao()
    {
        return (bool)$this->liberaSubFuncao;
    }

    /**
     * @param bool $liberaSubFuncao
     */
    public function setLiberaSubFuncao($liberaSubFuncao)
    {
        $this->liberaSubFuncao = (bool)$liberaSubFuncao;
    }

    /**
     * @return bool
     */
    public function isLiberaFuncao()
    {
        return (bool)$this->liberaFuncao;
    }

    /**
     * @param bool $liberaFuncao
     */
    public function setLiberaFuncao($liberaFuncao)
    {
        $this->liberaFuncao = (bool)$liberaFuncao;
    }

    /**
     * @return bool
     */
    public function isVerificaAno()
    {
        return (bool)$this->verificaAno;
    }

    /**
     * @param bool $verificaAno
     */
    public function setVerificaAno($verificaAno)
    {
        $this->verificaAno = (bool)$verificaAno;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return (string)$this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = (string)$label;
    }

    /**
     * @return bool
     */
    public function isManual()
    {
        return (bool)$this->manual;
    }

    /**
     * @param bool $manual
     */
    public function setManual($manual)
    {
        $this->manual = (bool)$manual;
    }

    /**
     * @return bool
     */
    public function isTotalizadora()
    {
        return (bool)$this->totalizadora;
    }

    /**
     * @param bool $totalizadora
     */
    public function setTotalizadora($totalizadora)
    {
        $this->totalizadora = (bool)$totalizadora;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return (int)$this->ordem;
    }

    /**
     * @param int $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = (int)$ordem;
    }

    /**
     * @return int
     */
    public function getNivelLinha()
    {
        return (int)$this->nivelLinha;
    }

    /**
     * @param int $nivelLinha
     */
    public function setNivelLinha($nivelLinha)
    {
        $this->nivelLinha = (int)$nivelLinha;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return (string)$this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = (string)$observacao;
    }

    /**
     * @return bool
     */
    public function isDesdobra()
    {
        return (bool)$this->desdobra;
    }

    /**
     * @param bool $desdobra
     */
    public function setDesdobra($desdobra)
    {
        $this->desdobra = (bool)$desdobra;
    }

    /**
     * @return int
     */
    public function getOrigem()
    {
        return (int)$this->origem;
    }

    /**
     * @param int $origem
     */
    public function setOrigem($origem)
    {
        $this->origem = (int)$origem;
    }

    /**
     * @return LinhaColuna[]
     */
    public function getLinhaColunas()
    {
        return $this->linhaColunas;
    }

    /**
     * @param LinhaColuna[] $linhaColunas
     * @return Linha
     */
    public function setLinhaColunas(array $linhaColunas)
    {
        $this->linhaColunas = $linhaColunas;
        return $this;
    }

    /**
     * @return ConfiguracaoPadrao[]
     */
    public function getFiltroPadrao()
    {
        return $this->filtroPadrao;
    }

    /**
     * @param ConfiguracaoPadrao[] $filtroPadrao
     * @return Linha
     */
    public function setFiltroPadrao($filtroPadrao)
    {
        $this->filtroPadrao = $filtroPadrao;
        return $this;
    }

    /**
     * @return LinhaInformacaoComplementar[]
     */
    public function getLinhaInformacoesComplementares()
    {
        return $this->linhaInformacoesComplementares;
    }

    /**
     * @param LinhaInformacaoComplementar[] $linhaInformacoesComplementares
     * @return Linha
     */
    public function setLinhaInformacoesComplementares(array $linhaInformacoesComplementares)
    {
        $this->linhaInformacoesComplementares = $linhaInformacoesComplementares;
        return $this;
    }

    /**
     * @param LinhaColuna $linhaColuna
     * @return $this
     */
    public function addLinhaColuna(LinhaColuna $linhaColuna)
    {
        $this->linhaColunas[$linhaColuna->getSequencial()] = $linhaColuna;
        return $this;
    }

    /**
     * @param LinhaInformacaoComplementar $linhaInformacaoComplementar
     * @return $this
     */
    public function addInformacaoComplementar(LinhaInformacaoComplementar $linhaInformacaoComplementar)
    {
        $sequencial = $linhaInformacaoComplementar->getSequencial();
        $this->linhaInformacoesComplementares[$sequencial] = $linhaInformacaoComplementar;
        return $this;
    }

    /**
     * @param ConfiguracaoPadrao $linhaFiltroPadrao
     * @return $this
     */
    public function addFiltroPadrao(ConfiguracaoPadrao $linhaFiltroPadrao)
    {
        $this->filtroPadrao[$linhaFiltroPadrao->getSequencial()] = $linhaFiltroPadrao;
        return $this;
    }

    public function setConfiguracaoUsuario(ConfiguracaoUsuario $configuracao)
    {
        $this->configuracaoUsuario = $configuracao;
    }

    /**
     * Retorna a confifuração da linha
     * @return Configuracao
     */
    public function getConfiguracao()
    {
        if (is_null($this->configuracaoUsuario) && !empty($this->filtroPadrao)) {
            return $this->filtroPadrao[0];
        }
        return $this->configuracaoUsuario;
    }
}
