<?php

namespace ECidade\RecursosHumanos\RH\Recadastramento;

use ECidade\RecursosHumanos\Pessoal\Model\ServidorMovimentacao;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;

class ServidorAposentado extends Servidor
{

    public function run()
    {
        $this->salvarDadosDependentes();
        $this->salvarDadosPessoais();
        $this->salvarDadosDocumentos();
        $this->salvarEndereco();
        $this->salvarEnderecoExterior();
        $this->salvarDadosEstrangeiro();
        $this->salvarDadosPCD();
        $this->salvarDadosContato();
        $this->salvarDadosPensionista();
    }

    private function salvarDadosPessoais()
    {

        $secao = $this->form->getSecao("dados_pessoais_aposentados");

        if (empty($secao)) {
            $secao = $this->form->getSecao("dados_pessoais");
        }

//        $campo = $secao->getCampo("nome_funcionario");
//        $this->cgm->setNome($campo->getResposta());
//        $this->cgm->setNomeCompleto($campo->getResposta());

        $campo = $secao->getCampo("nome_social_esocial");
        $this->cgm->setNomeSocial($campo->getResposta());

        $campo = $secao->getCampo("sexo_funcionario");
        $this->cgm->setSexo(
            $campo->getResposta()->codigo
        );
        $this->servidor->setSexo($campo->getResposta()->codigo);

        $campo = $secao->getCampo("data_nascimento_funcionario");
        $nascimento = empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta());
        $this->cgm->setDataNascimento($nascimento);
        $this->servidor->setDataNascimento($nascimento);

        $campo = $secao->getCampo("genero");
        $this->cgm->setGenero($campo->getResposta()->codigo);

        $campo = $secao->getCampo("estado_civil");
        $this->cgm->setEstadoCivil($this->estadoCivilParaEcidade($campo->getResposta()->codigo));
        $this->servidor->setEstadoCivil($this->estadoCivilParaEcidadeServidor($campo->getResposta()->codigo));

        $campo = $secao->getCampo("mae");
        $this->cgm->setNomeMae($campo->getResposta());

        $campo = $secao->getCampo("pai");
        $this->cgm->setNomePai($campo->getResposta());

        $dependentes = $this->servidor->getDependentes();

        $dependente = array_filter($dependentes, function ($dependente) {
            return $dependente->isConjuge();
        });

        if (empty($dependente)) {
            $conjuge = new \Dependente();
            $conjuge->setInstituicao($this->servidor->getCodigoInstituicao());
            $conjuge->setMatricula($this->servidor->getMatricula());
            $conjuge->setGrauParentesco("C");
            $conjuge->setSalarioFamilia("N");
            $conjuge->setTipo(0);
            $conjuge->setSexo(strtolower($this->servidor->getSexo()) == 'm' ? "F" : "M"); //ver com a lorena
            $conjuge->setFinsPrevidenciarios(false);
            $conjuge->setCondicaoEspecial("N");
            $conjuge->setTipoParentesco(1);
        } else {
            $conjuge = current($dependente);
        }

        $campo = $secao->getCampo("dados_conjuge");
        $conjuge->setNome($campo->getResposta());
        $campo = $secao->getCampo("cpf_conjuge");
        $conjuge->setCpf($this->apenasNumero($campo->getResposta()));
        $campo = $secao->getCampo("data_nasc_conjuge");
        if (!empty($this->apenasNumero($campo->getResposta()))) {
            $conjuge->setDataNascimento(new \DBDate($campo->getResposta()));
        }

        if (!empty($conjuge->getNome()) or !empty($conjuge->getCpf())) {
            $conjuge->save();
        }

        $campo = $secao->getCampo("municipio_nasc_esocial");
        $this->cgm->setNaturalidade($campo->getResposta()->descricao);
        $this->servidor->setNaturalidade($campo->getResposta()->descricao);

        $campo = $secao->getCampo("pais_nasc_esocial");
        $this->cgm->setPaisNascimento($campo->getResposta()->id);
        $this->cgm->setPaisNacionalidade($campo->getResposta()->id);

        $campo = $secao->getCampo("nacionalidade");
        $this->cgm->setNacionalidade($campo->getResposta()->codigo);


        $campo = $secao->getCampo("escolaridade");
        $this->servidor->setGrauInstrucao(
            $this->escolaridadeCodigoParaEcidade($campo->getResposta()->codigo)
        );

        if (in_array((int)$campo->getResposta()->codigo, array(8, 10, 11, 12))) {
            require_once(modification('model/processoOuvidoria.model.php'));
            $campo = $secao->getCampo("descricao_do_curso");
            $assentamento = new \AssentamentoFuncional();
            $assentamento->setMatricula($this->servidor->getMatricula());
            $assentamento->setHistorico(empty($campo->getResposta()) ? "NÃO INFORMADO" : $campo->getResposta());
            $processoOuvidoria = \ProcessoOuvidoria::findByAtendimento($this->atendimentoOvidoria->getId());
            $processo = $processoOuvidoria->getProcesso();
            $assentamento->setDataTermino(new \DBDate($processo->getDataProcesso()));
            $assentamento->setDataConcessao(new \DBDate($processo->getDataProcesso()));
            $assentamento->setDataLancamento(date("Y-m-d"));
            $tipoAssentamento = \TipoAssentamentoRepository::getInstance()->getInstanciaPorTipo("NS");
            $assentamento->setTipoAssentamento($tipoAssentamento->getSequencial());
            $assentamento->setHora(date("H:i"));
            \AssentamentoFuncionalRepository::persist($assentamento);
        }

        $campo = $secao->getCampo("raca");
        $this->servidor->setRacaCor(
            $this->racaParaEcidade($campo->getResposta()->codigo)
        );

        $this->cgm->save();
        $this->servidor->save();
    }

    private function salvarDadosDocumentos()
    {
        $secao = $this->form->getSecao("documentos_aposentados");

        $campo = $secao->getCampo("rg_aposentados");
        $this->cgm->setIdentidade($campo->getResposta());

        $campo = $secao->getCampo("orgao_emissor_rg_aposentados");
        $this->cgm->setIdentOrgao($campo->getResposta());

        $campo = $secao->getCampo("data_emissao_rg_aposentados");
        $this->cgm->setIdentDataExp(
            empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
        );

        $this->cgm->save();

        $documento = $this->servidor->documento();
        if (!$documento) {
            $documento = new \ServidorDocumento();
            $documento->setMatricula($this->servidor->getMatricula());
        }

        $campo = $secao->getCampo("titulo_eleitoral_aposentados");
        $documento->setTituloDeEleitor($campo->getResposta());
        $campo = $secao->getCampo("orgao_emissor_titulo_eleitor_aposentados");
        $documento->setSecaoTituloDeEleitor($campo->getResposta());
        $campo = $secao->getCampo("orgao_emissor_titulo_aposentados");
        $documento->setZonaTituloDeEleitor($campo->getResposta());
        $campo = $secao->getCampo("certificado_reservista_aposentados");
        $documento->setReservistaNumero($campo->getResposta());
        $campo = $secao->getCampo("pispasep_aposentados");
        $documento->setPis($campo->getResposta());

        $campo = $secao->getCampo("registro_orgao_de_classe_aposentados");
        $documento->setOrgaoClasse($campo->getResposta());
        $campo = $secao->getCampo("data_orgao_classe_aposentados");
        $documento->setOrgaoClasseData(
            empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
        );
        $campo = $secao->getCampo("orgao_emissor_orgao_classe_aposentados");
        $documento->setOrgaoClasseEmissor($campo->getResposta());
        $campo = $secao->getCampo("data_validade_orgao_classe_aposentados");
        if (!empty($campo)) {
            $documento->setOrgaoClasseValidade(
                empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
            );
        }

        $campo = $secao->getCampo("cnh_carteira_de_motorista_aposentados");
        $documento->setCnhNumero($campo->getResposta());
        $campo = $secao->getCampo("cnh_categoria_aposentados");
        $documento->setCnhCategoria($campo->getResposta());
        $campo = $secao->getCampo("uf_cnh_aposentados");
        $documento->setCnhUf($campo->getResposta()->codigo);
        $campo = $secao->getCampo("data_emissao_cnh_aposentados");
        $documento->setCnhEmissao(
            empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
        );
        $campo = $secao->getCampo("data_validade_cnh_aposentados");
        $documento->setCnhValidade(
            empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
        );

        $campo = $secao->getCampo("rne_aposentados");
        $documento->setRneRegistro($campo->getResposta());
        $campo = $secao->getCampo("orgao_emissor_rne_aposentados");
        $documento->setRneOrgaoEmissor($campo->getResposta());
        $campo = $secao->getCampo("data_emissao_rne_aposentados");
        $documento->setRneEmissao(
            empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
        );
        $campo = $secao->getCampo("data_validade_rne_aposentados");
        $documento->setRneValidade(
            empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
        );
        $campo = $secao->getCampo("data_entrada_rne_aposentados");
        if (!empty($campo)) {
            $documento->setRneEntrada(
                empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta())
            );
        }
        $documento->save();
    }

    private function salvarEndereco()
    {
        $this->limpaEndereco();
        
        $secao = $this->form->getSecao("rendereco");

        $campo = $secao->getCampo("cep_aposentados");
        $this->cgm->setCep($this->apenasNumero($campo->getResposta()));

        $campo = $secao->getCampo("uf_end_aposentados");
        $this->cgm->setUf($campo->getResposta()->descricao);

        $campo = $secao->getCampo("municipio_end_aposentados");
        $this->cgm->setMunicipio($campo->getResposta()->descricao);

        $campo = $secao->getCampo("bairro_end_aposentados");
        $this->cgm->setBairro(pg_escape_string($campo->getResposta()));

        $campo = $secao->getCampo("logradouro_end_aposentados");
        $this->cgm->setLogradouro(pg_escape_string($campo->getResposta()));

        $campo = $secao->getCampo("numero_endereco_aposentados");
        if (!empty($campo->getResposta()) and !is_numeric($campo->getResposta())) {
            throw new \Exception("Numero de endereço inválido");
        }
        $this->cgm->setNumero($campo->getResposta());

        $campo = $secao->getCampo("complemento_endereco_aposentados");
        $this->cgm->setComplemento(pg_escape_string($campo->getResposta()));

        $this->cgm->save();
    }

    private function salvarEnderecoExterior()
    {
        $secao = $this->form->getSecao("endereco_exterior");
        $campo = $secao->getCampo("pais_endereco_aposentados");
        $pais = $campo->getResposta();
        if (empty($pais)) {
            return ;
        }
        $pais = strtoupper(trim(\DBString::removerCaracteresEspeciaisAcentos($pais)));

        $cadenderpais = new  \cl_cadenderpais();
        $sql = $cadenderpais->sql_query_file(
            null,
            "*",
            null,
            "db70_descricao ILIKE '%{$pais}%' or db70_sigla ilike '%{$pais}%'"
        );

        $rs = db_query($sql);
        $paisObj = pg_fetch_object($rs);

        if (empty($paisObj) or $paisObj->db70_sequencial === 105 or strtolower($pais) == "brasil") {
            return;
        }

        $this->cgm->setPaisExterior($paisObj->db70_sequencial);

        $campo = $secao->getCampo("cep_aposentados_exterior");
        $this->cgm->setCodigoPostalExterior($this->apenasNumero($campo->getResposta()));

        $campo = $secao->getCampo("municipio_aposentados");
        $this->cgm->setCidadeExterior($campo->getResposta());

        $campo = $secao->getCampo("bairro_aposentados");
        $this->cgm->setBairroExterior(pg_escape_string($campo->getResposta()));

        $campo = $secao->getCampo("logradouro_aposentados");
        $this->cgm->setLogradouroExterior(pg_escape_string($campo->getResposta()));

        $campo = $secao->getCampo("numero_end_aposentados");
        if (!empty($campo->getResposta()) and !is_numeric($campo->getResposta())) {
            throw new \Exception("Número de endereço exterior inválido");
        }
        $this->cgm->setNumeroExterior($campo->getResposta());

        $campo = $secao->getCampo("complemento_end_aposentados");
        $this->cgm->setComplementoExterior(pg_escape_string($campo->getResposta()));

        $this->cgm->save();
    }

    private function salvarDadosEstrangeiro()
    {
        $secao = $this->form->getSecao("estrangeiro");

        $campoResidencia = $secao->getCampo("tempo_de_residencia_aposentados");
        $campoCondicao = $secao->getCampo("cond_ingre_traba_aposentados");

        try {
            $isImigrante = $this->servidor->isImigrante();
        } catch (\Exception $ex) {
            $isImigrante = false;
        }

        if ($isImigrante) {
            $imigrante = $this->servidor->getDadosImigrante();
        } else {
            $imigrante = new \Imigrante();
            $imigrante->setMatricula($this->servidor->getMatricula());
            $imigrante->setInstituicao($this->servidor->getCodigoInstituicao());
        }

        if (!empty($campoCondicao->getResposta())) {
            $imigrante->setCodigoCondicao($campoCondicao->getResposta()->codigo);
        }

        if (!empty($campoResidencia->getResposta())) {
            $imigrante->setCodigoResidencia($campoResidencia->getResposta()->codigo);
        }

        if (!empty($imigrante->getCodigoCondicao()) || !empty($imigrante->getCodigoResidencia())) {
            $imigrante->save();
        }
    }

    private function salvarDadosPCD()
    {

        $deficiente = $this->servidor->deficiente();
        if (!$deficiente) {
            $deficiente = new \ServidorDeficiente();
            $deficiente->setMatricula($this->servidor->getMatricula());
            $deficiente->setInstituicao($this->servidor->getCodigoInstituicao());
        }

        $secao = $this->form->getSecao("pcd_aposentados");

        $campo = $secao->getCampo("deficiencia_fisica_aposentados");
        $deficiente->setFisica($campo->getResposta()->codigo == 1 ? true : false);

        $campo = $secao->getCampo("deficiencia_visual_aposentados");
        $deficiente->setVisual($campo->getResposta()->codigo == 1 ? true : false);

        $campo = $secao->getCampo("deficiencia_auditiva_aposentados");
        $deficiente->setAuditiva($campo->getResposta()->codigo == 1 ? true : false);

        $campo = $secao->getCampo("deficiencia_intelectual_aposentados");
        $deficiente->setIntelectual($campo->getResposta()->codigo == 1 ? true : false);

        $campo = $secao->getCampo("deficiencia_mental_aposentado");
        $deficiente->setMental($campo->getResposta()->codigo == 1 ? true : false);

        $campoReabilitado = $secao->getCampo("reabilitado_aposentados");
        $campoReadaptado = $secao->getCampo("readaptado_aposentado");
        $reabilitado = false;
        if ($campoReabilitado->getResposta()->codigo == 1 || $campoReadaptado->getResposta()->codigo == 1) {
            $reabilitado = true;
        }
        $deficiente->setReabilitado($reabilitado);

        $campo = $secao->getCampo("pre_cota_aposentados");
        $deficiente->setCota($campo->getResposta()->codigo == 1 ? true : false);
        $deficiente->save();
    }

    /**
     * @throws \BusinessException
     * @throws \ParameterException
     * @throws \Exception
     */
    private function salvarDadosDependentes()
    {

        $secao = $this->form->getSecao("dependentes_aposentado");
        $dependentes = $secao->getResposta();
        $dependentesOld = $this->servidor->getDependentes();

        foreach ($dependentesOld as $dependenteOld) {
            $dependenteOld->delete();
        }

        foreach ($dependentes as $dependente) {
            if (empty($dependente->nome_aposentados)) {
                continue;
            }
            $dependenteModel = new \Dependente();
            $dependenteModel->setMatricula($this->servidor->getMatricula());
            $dependenteModel->setInstituicao($this->servidor->getCodigoInstituicao());
            $dependenteModel->setNome($dependente->nome_aposentados);
            $dependenteModel->setGrauParentesco(
                $this->parentescoParaEcidade(
                    $dependente->tipo_de_dependetes->codigo,
                    $dependente->sexo_aposentados->codigo
                )
            );

            $dependenteModel->setTipoParentesco(
                str_pad(
                    $dependente->tipo_de_dependentes_ativos->codigo,
                    2,
                    "0",
                    STR_PAD_LEFT
                )
            );

            $dependenteModel->setDataNascimento(
                empty($this->apenasNumero($dependente->data_aposentados))
                    ? null
                    : new \DBDate($dependente->data_aposentados)
            );
            $dependenteModel->setCpf($this->apenasNumero($dependente->cpf_aposentados));
            $dependenteModel->setSalarioFamilia(
                $this->salarioFamiliaParaEcidade(
                    $dependente->dependente_salario_familia_aposentados->codigo,
                    $this->parentescoParaEcidade(
                        $dependente->tipo_de_dependetes->codigo,
                        $dependente->sexo_aposentados->codigo
                    )
                )
            );
            $dependenteModel->setFinsPrevidenciarios(
                $dependente->dependente_fins_previdenciarios_aposentados->codigo == 1 ? true : false
            );
            $dependenteModel->setCondicaoEspecial(
                $dependente->dependente_incapacidade_aposentados->codigo == 1 ? "C" : "N"
            );
            /**
             * ver com a lorena sim ou não mas tem condições especificas no ecidade
             */
            $dependenteModel->setTipo(
                $this->irfParaEcidade(
                    $dependente->dependente_irrf_aposentados->codigo,
                    $dependente->tipo_de_dependetes->codigo
                )
            );
            $dependenteModel->setSexo($dependente->sexo_aposentados->codigo == 1 ? "M" : "F");
            $dependenteModel->save();
        }
    }

    private function salvarDadosContato()
    {

        $secao = $this->form->getSecao("contato");

        $campo = $secao->getCampo("celular");
        $this->cgm->setCelular($campo->getResposta());

        $campo = $secao->getCampo("email");
        $this->cgm->setEmail(trim(strtolower($campo->getResposta())));

        $campo = $secao->getCampo("telefone");
        $this->cgm->setTelefone($campo->getResposta());

        $this->cgm->save();
    }

    private function salvarDadosPensionista()
    {

        if ($this->checarSeTodosDadosDePensionistaSaoNulos()) {
            return;
        }
        $secao = $this->form->getSecao("aposentado_pensionista");
        $movimentacao = ServidorMovimentacao::find($this->servidor->getCodigoMovimentacao());

        $campo = $secao->getCampo("tipo_de_aposentadoria_propria");
        $movimentacao->setTipoAposentadoriaPensao(
            $this->tipoPrevidencia($campo->getResposta()->codigo)
        );

        if ($movimentacao->isPensionista()) {
            $campo = $secao->getCampo("cpf_instituidor_da_pensao");
            $instituidor = \Servidor::findCpf(
                $this->apenasNumero($campo->getResposta())
            );

            $pesorigem = new \cl_rhpesorigem();
            $sql = $pesorigem->sql_query(
                null,
                "rh21_regist",
                null,
                "rh21_regist = {$this->servidor->getMatricula()} and rh21_regpri = {$instituidor->getMatricula()}"
            );
            $temInstituidor = pg_fetch_object(db_query($sql));

            if (empty($temInstituidor)) {
                $pesorigem = new \cl_rhpesorigem();
                $pesorigem->rh21_regist = $this->servidor->getMatricula();
                $pesorigem->rh21_regpri = $instituidor->getMatricula();
                $pesorigem->incluir($this->servidor->getMatricula());
            }

            $campo = $secao->getCampo("pensao_adquirida_por_morte");
            if ($campo->getResposta() == 1) {
                $movimentacao->setTipoAposentadoriaPensao(1);
            }
        }

        $campo = $secao->getCampo("insencao_doenca_incapacidade");
        $movimentacao->setDeficienteFisico($campo->getResposta()->codigo == 1);

        $campo = $secao->getCampo("data_isencao_doenca_incapacitante");
        $dataLaudo = empty($this->apenasNumero($campo->getResposta())) ? null : new \DBDate($campo->getResposta());
        if (!empty($dataLaudo)) {
            $movimentacao->setDataLaudoMolestia(new \DateTime($dataLaudo->format("Y-m-d")));
        }

        ServidorMovimentacaoRepository::save($movimentacao);
    }


    private function checarSeTodosDadosDePensionistaSaoNulos()
    {

        $secao = $this->form->getSecao("aposentado_pensionista");
        if ($secao) {
            return true;
        }
        if (empty($secao->getCampo("cpf_instituidor_da_pensao")->getResposta())
            and empty($secao->getCampo("tipo_de_aposentadoria_propria")->getResposta())
            and empty($secao->getCampo("pensao_adquirida_por_morte")->getResposta())
            and empty($secao->getCampo("insencao_doenca_incapacidade")->getResposta())
            and empty($secao->getCampo("data_isencao_doenca_incapacitante")->getResposta())

        ) {
            return true;
        }

        return false;
    }
}
