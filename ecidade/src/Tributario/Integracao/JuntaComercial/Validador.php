<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 23/10/17
 * Time: 15:35
 */

namespace ECidade\Tributario\Integracao\JuntaComercial;

use ECidade\Tributario\Integracao\JuntaComercial\Model\QSA\Contador;

/**
 * Classe responsável pelas informações vindas do REGIN
 * Class Validador
 * @package ECidade\Tributario\Integracao\JuntaComercial
 */
class Validador
{
    /**
     * @var Regin
     */
    private $dicionario;

    /**
     * Validador constructor.
     * @param Regin $regin
     */
    public function __construct(Regin $regin)
    {
        $this->dicionario = $regin;
    }

    /**
     * Funções geral que executa todas as validações específicas
     */
    public function validar()
    {
        $this->validarEmpresa($this->dicionario->getDadosGrupo(Regin::EMPRESA));
        $this->validarEmpresaEndereco($this->dicionario->getDadosGrupo(Regin::ENDERECO_INSCRICAO));
        $this->validarEnderecoCgm($this->dicionario->getDadosGrupo(Regin::ENDERECO_CGM));
        $this->validarSocio($this->dicionario->getDadosGrupo(Regin::SOCIO));
        $this->validarSocioEndereco($this->dicionario->getDadosGrupo(Regin::ENDERECO_SOCIO));
        $this->validarAtividade($this->dicionario->getDadosGrupo(Regin::ATIVIDADE));
        $this->validarEvento($this->dicionario->getDadosGrupo(Regin::EVENTO));
        $this->validarProtocolo($this->dicionario->getDadosGrupo(Regin::PROTOCOLO));
    }

    /**
     * Validamos os dados da empresa
     * @param \stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarEmpresa(\stdClass $dadosGrupo)
    {
        $campo = null;

        if (empty($dadosGrupo->uf) || strlen($dadosGrupo->uf) != 2 || is_numeric($dadosGrupo->uf) ) {
            $campo = 'uf';
        }

        if (empty($dadosGrupo->razao_social)) {
            $campo = 'razao_social';
        }

        if (!empty($dadosGrupo->data_junta) && (!is_numeric($dadosGrupo->data_junta) || strlen($dadosGrupo->data_junta) != 8 )) {
            $campo = 'data_junta';
        }

        if (!empty($dadosGrupo->data_cadastro) && (!is_numeric($dadosGrupo->data_cadastro) || strlen($dadosGrupo->data_cadastro) != 8 )) {
            $campo = 'data_cadastro';
        }

        if (empty($dadosGrupo->porte) || !is_numeric($dadosGrupo->porte)) {
            $campo = 'porte';
        }

        if (!is_numeric($dadosGrupo->cpfcnpj) || (strlen($dadosGrupo->cpfcnpj) != 14 && strlen($dadosGrupo->cpfcnpj) != 11)  ) {
            $campo = 'cpfcnpj';
        }

        if (empty($dadosGrupo->protocolo)) {
            $campo = 'protocolo';
        }

        if (!is_null($campo)) {

            $campo = $this->dicionario->campos[Regin::EMPRESA][$campo];
            throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
        }
    }

    /**
     * Validamos o endereço da empresa referente ao cadastro de alvará
     * @param \stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarEmpresaEndereco(\stdClass $dadosGrupo)
    {
        $campo = null;

        if (!empty($dadosGrupo->codigo_municipio) && !is_numeric($dadosGrupo->codigo_municipio) ) {
            $campo = 'codigo_municipio';
        }

        if (!empty($dadosGrupo->uf) && (strlen($dadosGrupo->uf) != 2 || is_numeric($dadosGrupo->uf) )) {
            $campo = 'uf';
        }

        if (empty($dadosGrupo->protocolo)) {
            $campo = 'protocolo';
        }

        if (!is_null($campo)) {

            $campo = $this->dicionario->campos[Regin::ENDERECO_INSCRICAO][$campo];
            throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
        }
    }

    /**
     * Validamos o endereço do CGM da empresa
     * @param \stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarEnderecoCgm(\stdClass $dadosGrupo)
    {
        $campo = null;

        if (!empty($dadosGrupo->codigo_municipio) && !is_numeric($dadosGrupo->codigo_municipio) ) {
            $campo = 'codigo_municipio';
        }

        if (!empty($dadosGrupo->uf) && ( strlen($dadosGrupo->uf) != 2 || is_numeric($dadosGrupo->uf) ) ) {
            $campo = 'uf';
        }

        if (empty($dadosGrupo->protocolo)) {
            $campo = 'protocolo';
        }

        if (!is_null($campo)) {

            $campo = $this->dicionario->campos[Regin::ENDERECO_CGM][$campo];
            throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
        }
    }

    /**
     * Validamos os dados do QSA da empresa
     * @param array|\stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarSocio($dadosGrupo)
    {
        foreach ($dadosGrupo as $dados) {

            $campo = null;

            if (empty($dados->tipo_relacionamento) || !is_numeric($dados->tipo_relacionamento)) {
                $campo = 'tipo_relacionamento';
            }

            $dados->cpfcnpj = trim($dados->cpfcnpj);

            if ( empty($dados->cpfcnpj) || !is_numeric($dados->cpfcnpj) ||
                 (strlen($dados->cpfcnpj) != 14 && strlen($dados->cpfcnpj) != 11 ) ) {
                $campo = 'cpfcnpj';
            }

            if (empty($dados->protocolo)) {
                $campo = 'protocolo';
            }

            if (!is_null($campo)) {

                $campo = $this->dicionario->campos[Regin::SOCIO][$campo];
                throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
            }
        }
    }

    /**
     * Validamos o endereço e demais informações do QSA da empresa
     * @param array|\stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarSocioEndereco( $dadosGrupo)
    {
        $campo = null;

        foreach ($dadosGrupo as $dados) {

            $dados->cpfcnpj = trim($dados->cpfcnpj);

            if (empty($dados->cpfcnpj) || !is_numeric($dados->cpfcnpj) ||
              (strlen($dados->cpfcnpj) != 14 && strlen($dados->cpfcnpj) != 11 )) {
                $campo = 'cpfcnpj';
            }

            if (empty($dados->razao_social)) {
                $campo = 'razao_social';
            }

            if (empty($dados->protocolo)) {
                $campo = 'protocolo';
            }

            if (!is_null($campo)) {

                $campo = $this->dicionario->campos[Regin::ENDERECO_SOCIO][$campo];
                throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
            }
        }
    }

    /**
     * Validamos os dados das atividades
     * @param array|\stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarAtividade( $dadosGrupo)
    {
        $campo = null;
        foreach ($dadosGrupo as $dados) {

            if (empty($dados->data_inicio) || !is_numeric($dados->data_inicio) || strlen($dados->data_inicio) != 8) {
                $campo = 'data_inicio';
            }

            if (empty($dados->tipo_atividade) || !is_numeric($dados->tipo_atividade)) {
                $campo = 'tipo_atividade';
            }

            if (empty($dados->codigo) || !is_numeric($dados->codigo)) {
                $campo = 'codigo';
            }

            if (empty($dados->protocolo)) {
                $campo = 'protocolo';
            }

            if (!is_null($campo)) {

                $campo = $this->dicionario->campos[Regin::ATIVIDADE][$campo];
                throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
            }
        }
    }

    /**
     * Validamos os dados dos eventos
     * @param array|\stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarEvento($dadosGrupo)
    {
        $campo = null;
        foreach ($dadosGrupo as $dados) {

            if (empty($dados->protocolo)) {
                $campo = 'protocolo';
            }

            if (empty($dados->codigo_evento) || !is_numeric($dados->codigo_evento)) {
                $campo = 'codigo_evento';
            }

            if (empty($dados->nome_evento)) {
                $campo = 'nome_evento';
            }

            if (!is_null($campo)) {

                $campo = $this->dicionario->campos[Regin::EVENTO][$campo];
                throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
            }
        }
    }

    /**
     * Validamos os dados do protocolo
     * @param \stdClass $dadosGrupo
     * @throws \BusinessException
     */
    private function validarProtocolo(\stdClass $dadosGrupo)
    {
        $campo = null;

        if (empty($dadosGrupo->tipo_acao) || !is_numeric($dadosGrupo->tipo_acao)) {
            $campo = 'tipo_acao';
        }

        if (!is_null($campo)) {

            $campo = $this->dicionario->campos[Regin::PROTOCOLO][$campo];
            throw new \BusinessException(utf8_encode("O campo $campo esta invalido."));
        }
    }
}