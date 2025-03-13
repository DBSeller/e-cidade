<?php

namespace App\Domain\Saude\Ambulatorial\Builders;

use App\Domain\Saude\Ambulatorial\Requests\FindOrCreateCgsRequest;
use ECidade\Enum\Saude\Ambulatorial\RacaCorEnum;

class CgsApiBuilder
{
    /**
     * @param FindOrCreateCgsRequest $request
     * @return object
     * @throws \Exception
     */
    public function build(FindOrCreateCgsRequest $request)
    {
        return (object)[
            'dados_pessoais' => $this->buildDadosPessoais($request),
            'contato' => $this->buildContato($request),
            'biometria' => $this->buildBiometria(),
            'outrosDados' => $this->buildOutrosDados($request)
        ];
    }

    /**
     * @param FindOrCreateCgsRequest $request
     * @return object
     * @throws \Exception
     */
    private function buildDadosPessoais(FindOrCreateCgsRequest $request)
    {
        $encode = 'ISO-8859-1';
        return (object)[
            'nome' => !empty($request->nome) ? mb_strtoupper($request->nome, $encode) : 'NÃO INFORMADO',
            'nomeSocial' => mb_strtoupper($request->nome_social, $encode),
            'cpf' => $request->cpf,
            'cns' => $request->cns,
            'dataNascimento' => $request->data_nascimento,
            'nomeMae' => !empty($request->nome_mae) ? mb_strtoupper($request->nome_mae, $encode) : 'SEM INFORMAÇÃO',
            'nomePai' => !empty($request->nome_pai) ? mb_strtoupper($request->nome_pai, $encode) : 'SEM INFORMAÇÃO',
            'sexo' => !empty($request->sexo) ? $request->sexo : 'N',
            'racaCor' => !empty($request->raca) ? (new RacaCorEnum($request->raca))->name() : '',
            'codigo_etnia' => '',
            'nacionalidade' => '2', // salva como estrangeiro pois não é informado o municipio de nascimento
            'paisOrigem' => '10', // Brasil
            'municipioNascimento' => '',
            'ufNascimento' => '',
            'codigoIbge' => '',
            'tipoSangue' => '',
            'fatorRH' => '',
            'cgsMunicipio' => true,
            'cadastroInativo' => false
        ];
    }

    /**
     * @param FindOrCreateCgsRequest $request
     * @return object
     * @throws \Exception
     */
    private function buildContato(FindOrCreateCgsRequest $request)
    {
        return (object)[
            'email' => '',
            'telefone_fixo' => '',
            'telefone_celular' => '',
            'fax' => '',
            'endereco_principal' => $this->newEnderecoFromIbge($request->municipio)
        ];
    }

    /**
     * @param string $ibge
     * @return string|null
     * @throws \Exception
     */
    private function newEnderecoFromIbge($ibge)
    {
        if ($ibge == '') {
            return null;
        }

        $where = "db125_codigosistema = '{$ibge}' AND db125_db_sistemaexterno = 4";
        $dao = new \cl_cadendermunicipiosistema();
        $sql = $dao->sql_query('', 'db71_cadenderpais, db71_sequencial, db72_sequencial', '', $where);
        $rs = $dao->sql_record($sql);
        if ($dao->numrows == 0) {
            throw new \Exception('Erro ao buscar código de IBGE informado.', 400);
        }

        $dados = \db_utils::fieldsMemory($rs, 0);

        $model = new \endereco();
        $model->setCodigoPais($dados->db71_cadenderpais);
        $model->setCodigoEstado($dados->db71_sequencial);
        $model->setCodigoMunicipio($dados->db72_sequencial);
        $model->setDescricaoBairro('NÃO INFORMADO');
        $model->setDescricaoRua('NÃO INFORMADO');
        $model->setCadEnderRuaTipo(1);
        $model->salvaEndereco();

        return $model->getCodigoEndereco();
    }

    /**
     * @return object
     */
    private function buildBiometria()
    {
        return (object)['foto_nova_caminho' => ''];
    }

    /**
     * @param FindOrCreateCgsRequest $request
     * @return object
     */
    private function buildOutrosDados(FindOrCreateCgsRequest $request)
    {
        $observacoes = '';
        if ($request->has('altura_relativa') && $request->altura_relativa != '') {
            $alturaRelativa = utf8_decode($request->altura_relativa);
            $observacoes .= "ALTURA RELATIVA: {$alturaRelativa}\n";
        }
        if ($request->has('idade_relativa') && $request->idade_relativa != '') {
            $idadeRelativa = utf8_decode($request->idade_relativa);
            $observacoes .= "IDADE RELATIVA: {$idadeRelativa}\n";
        }
        if ($request->has('peso_relativo') && $request->peso_relativo != '') {
            $pesoRelativo = utf8_decode($request->peso_relativo);
            $observacoes .= "PESO RELATIVA: {$pesoRelativo}\n";
        }
        if ($request->has('observacoes') && $request->observacoes != '') {
            $observacoes .= utf8_decode($request->observacoes);
        }

        return (object)[
            'cgm' => '',
            'cge' => '',
            'cidadao' => '',
            'ocupacao' => '',
            'bolsafamilia' => '',
            'responsavel' => '',
            'observacoes' => $observacoes,
            'familia' => '',
            'escolaridade' => '',
            'estado_civil' => ''
        ];
    }
}
