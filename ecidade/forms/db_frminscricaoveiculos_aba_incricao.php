<form id="frmInscricao">
	<input type="hidden" name="db_opcao" id="db_opcao" value="<?=$db_opcao?>">
	<input type="hidden" name="q172_sequencial" id="q172_sequencial">
    <fieldset>
        <legend>Inscrição de Veículos</legend>
        <div id="ctnCondutor">
            <fieldset style="width: 740px">
                <legend>Dados do Condutor</legend>
                <table>
                    <tr>
                        <td class="field-size3">
                            <label for="q172_issbase"><b>Inscrição Municipal:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_issbase" id="q172_issbase" class="field-size2 readonly" disabled>
                        </td>
                        <td class="field-size3">
                            <label for="q172_datacadastro"><b>Data de cadastro:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_datacadastro" id="q172_datacadastro" class="field-size2 readonly" disabled>
                        </td>
                    </tr>
                    <tr id="linhaTipoAlvara" style="display: none;">
                        <td>
                            <label for="q98_sequencial"><b>Tipo de Alvará:</b></label>
                        </td>
                        <td>
                            <input id="q98_sequencial" name="q98_sequencial" type="text" class="field-size2"/>
                            <input id="q98_descricao" name="q98_descricao" type="text" class="field-size7 readonly" disabled="disabled"/>
                        </td>
                        <td>
                            <label for="q120_issalvara"><b>Alvará:</b></label>
                        </td>
                        <td>
                            <input id="q120_issalvara" name="q120_issalvara" type="text" class="field-size2 readonly" disabled="disabled"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>
                                <a id="ancoraInscricaoCGM" href="#">
                                    <label for="q02_numcgm">CGM:</label>
                                </a>
                            </b>
                        </td>
                        <td colspan="3">
                            <input id="q02_numcgm" name="q02_numcgm" type="text" data="z01_numcgm" class="field-size2"/>
                            <input id="descricaoInscricaoCGM" name="descricaoInscricaoCGM" type="text" data="z01_nome" class="field-size7 readonly" disabled="disabled"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="codigoMunicipio"><b>Município:</b></label>
                        </td>
                        <td>
                            <input type="hidden" name="codigoMunicipio" id="codigoMunicipio" class="field-size">
                            <input type="text" name="descricaoMunicipio" id="descricaoMunicipio" class="field-size9 readonly" disabled>
                        </td>
                        <td>
                            <label for="cep"><b>CEP:</b></label>
                        </td>
                        <td>
                            <input type="text" name="cep" id="cep" class="field-size2 readonly" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="endereco"><b>Endereço:</b></label>
                        </td>
                        <td>
                            <input type="hidden" name="endereco" id="endereco" class="field-size">
                            <input type="text" name="descricaoEndereco" id="descricaoEndereco" class="field-size9 readonly" disabled>
                        </td>
                        <td>
                            <label for="cnpjcpf"><b>CNPJ/CPF:</b></label>
                        </td>
                        <td>
                            <input type="text" name="cnpjcpf" id="cnpjcpf" class="field-size2 readonly" disabled>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>

        <div id="ctnEnderecoMunicipio">
            <fieldset>
                <legend>Endereço no município</legend>
                <table>
                    <tr>
                        <td>
                            <b>
                                <a id="ancoraLogradouro" href="#">
                                    <label for="j14_codigo">Cód. Logradouro:</label>
                                </a>
                            </b>
                        </td>
                        <td>
                            <input id="j14_codigo" name="j14_codigo" type="text" class="field-size2"/>
                            <input id="j14_nome" name="j14_nome" type="text" class="field-size7 readonly" disabled="disabled"/>
                        </td>
                        <td class="field-size3">
                            <label for="cepIssRuas"><b>CEP:</b></label>
                        </td>
                        <td>
                            <input type="text" name="cepIssRuas" id="cepIssRuas" class="field-size2" maxlength="8">
                        </td>
                    </tr>
                    <tr>
                        <td class="field-size3">
                            <b>
                                <a id="ancoraBairro" href="#">
                                    <label for="j13_codi">Cód. do Bairro:</label>
                                </a>
                            </b>
                        </td>
                        <td>
                            <input id="j13_codi" name="j13_codi" type="text" class="field-size2"/>
                            <input id="j13_descr" name="j13_descr" type="text" class="field-size7 readonly" disabled="disabled"/>
                        </td>
                        <td>
                            <label for="q02_numero"><b>Número:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q02_numero" id="q02_numero" class="field-size2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="q02_compl"><b>Complemento:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q02_compl" id="q02_compl" class="field-size9 campo_opcional" maxlength="40">
                        </td>
                        <td>
                            <label for="q02_cxpost"><b>Caixa Postal:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q02_cxpost" id="q02_cxpost" class="field-size2 campo_opcional"  maxlength="20">
                        </td>
                    </tr>
                     <tr>
                       <td><b>Controle Local Atividade:</b></td>
                       <td colspan="2">
                          <div id="descricaoLocalAlvara">
                          </div>
                       </td>
                     </tr>
                </table>
            </fieldset>
        </div>

        <div id="ctnVeiculo">
            <fieldset>
                <legend>Dados do Veículo</legend>
                <table>
                    <tr>
                        <td class="field-size3">
                            <label for="q172_tipo"><b>Tipo:</b></label>
                        </td>
                        <td>
                            <?php
                                db_select('q172_tipo', $tiposVeiculos, true, 1);
                            ?>
                        </td>
                        <td class="field-size3">
                            <label for="q172_placa"><b>Placa:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_placa" id="q172_placa" class="field-size2 campo_opcional" maxlength="20">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="q172_marca"><b>Marca:</b></label>
                        </td>
                        <td>
                            <?php
                                db_select('q172_marca', $marcas, true, 1);
                            ?>
                        </td>
                        <td>
                            <label for="q172_potencia"><b>Potência:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_potencia" id="q172_potencia" class="field-size2 campo_opcional" maxlength="20">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>
                                <a id="ancoraModelo" href="#">
                                    <label for="q172_modelo">Modelo:</label>
                                </a>
                            </b>
                        </td>
                        <td>
                            <input id="q172_modelo" name="q172_modelo" type="text" data="ve22_codigo" class="field-size2 campo_opcional"/>
                            <input id="descricaoModelo" name="descricaoModelo" type="text" data="ve22_descr" class="field-size7 readonly" disabled="disabled"/>
                        </td>
                        <td>
                            <label for="q172_capacidade"><b>Capacidade:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_capacidade" id="q172_capacidade" class="field-size2 campo_opcional" maxlength="20">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="q172_cor"><b>Cor:</b></label>
                        </td>
                        <td>
                            <?php
                                db_select('q172_cor', $cores, true, 1);
                            ?>
                        </td>
                        <td>
                            <label for="q172_anofabricacao"><b>Ano de Fabricação:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_anofabricacao" id="q172_anofabricacao" class="field-size2 campo_opcional" maxlength="4">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="q172_procedencia"><b>Procedências:</b></label>
                        </td>
                        <td>
                            <?php
                                db_select('q172_procedencia', $procedencias, true, 1);
                            ?>
                        </td>
                        <td>
                            <label for="q172_anomodelo"><b>Ano do Modelo:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_anomodelo" id="q172_anomodelo" class="field-size2 campo_opcional" maxlength="4">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="q172_categoria"><b>Categorias:</b></label>
                        </td>
                        <td>
                            <?php
                                db_select('q172_categoria', $categorias, true, 1);
                            ?>
                        </td>
                        <td>
                            <label for="q172_renavam"><b>Renavam:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_renavam" id="q172_renavam" class="field-size2 campo_opcional" maxlength="11">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="q172_chassi"><b>Número do chassi:</b></label>
                        </td>
                        <td>
                            <input type="text" name="q172_chassi" id="q172_chassi" class="field-size9 campo_opcional" maxlength="20">
                        </td>
                        <td>
                            <label for="q172_chassi"><b>AAM:</b></label>
                        </td>
                        <td >
                            <input type="text" name="q172_aam" id="q172_aam" class="field-size2 campo_opcional" maxlength="20">
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </fieldset>
    <input type="button" name="salvarInscricao" id="salvarInscricao" value="Salvar">
    <input type="button" name="excluirInscricao" id="excluirInscricao" value="Excluir" style="display: none">
    <input type="button" name="imprimirBIC" id="imprimirBIC" value="Imprimir BIC" style="display: none">
    <input type="button" name="imprimirAlvara" id="imprimirAlvara" value="Imprimir Alvará" style="display: none">
    <input type="button" name="pesquisarInscricao" id="pesquisarInscricao" value="Pesquisar" disabled>
</form>

<script type="text/javascript">
    $('q172_tipo').classList.add("field-size9");
    $('q172_marca').classList.add("field-size9");
    $('q172_cor').classList.add("field-size9");
    $('q172_procedencia').classList.add("field-size9");
    $('q172_categoria').classList.add("field-size9");

    var lookupInscricaoCGM = new DBLookUp(
        $('ancoraInscricaoCGM'),
        $('q02_numcgm'),
        $('descricaoInscricaoCGM'),
        {
          'sArquivo': 'func_nome.php',
          'sLabel': 'Pesquisar CGM',
          'aCamposAdicionais': ['z01_cgccpf', 'z01_munic','z01_cep','z01_ender'],
          'aParametrosAdicionais': ['testanome=true']
        }
    );

    lookupInscricaoCGM.setCallBack('onClick', (arguments) => {
        $('cnpjcpf').value =  arguments[2];
        $('descricaoMunicipio').value = arguments[3];
        $('cep').value = arguments[4];
        $('descricaoEndereco').value =  arguments[5];

        validarCGMdoMunicipio();
    });

    lookupInscricaoCGM.setCallBack('onChange', (error, arguments) => {

        $('cnpjcpf').value =  '';
        $('descricaoMunicipio').value = '';
        $('cep').value = '';
        $('descricaoEndereco').value =  '';
        $('j14_codigo').value = '';
        $('j14_nome').value = '';
        $('q02_numero').value = '';
        $('q02_compl').value = '';
        $('cepIssRuas').value = '';
        $('q02_cxpost').value = '';
        $('j13_codi').value = '';
        $('j13_descr').value = '';

        if (error == true) {
            return;
        }

        $('cnpjcpf').value =  arguments[2];
        $('descricaoMunicipio').value = arguments[3];
        $('cep').value = arguments[4];
        $('descricaoEndereco').value =  arguments[5];

        validarCGMdoMunicipio();

    });

    var lookupModelo = new DBLookUp(
        $('ancoraModelo'),
        $('q172_modelo'),
        $('descricaoModelo'),
        {
          'sArquivo': 'func_veiccadmodelo.php',
          'sLabel': 'Pesquisar Modelo'
        }
    );

    var lookupBairro = new DBLookUp(
        $('ancoraBairro'),
        $('j13_codi'),
        $('j13_descr'),
        {
          'sArquivo': 'func_bairro.php',
          'sLabel': 'Pesquisar Bairro'
        }
    );

    var lookupLogradouro = new DBLookUp(
        $('ancoraLogradouro'),
        $('j14_codigo'),
        $('j14_nome'),
        {
          'sArquivo': 'func_ruas.php',
          'sLabel': 'Pesquisar Logradouro'
        }
    );

	$('salvarInscricao').onclick = () => {

        if ($('q02_numcgm').value == '') {
            alert('CGM não informado.')
            return;
        }

        if ($('j13_codi').value == '') {
            alert('Bairro não informado.')
            return;
        }

        if ($('j14_codigo').value == '') {
            alert('Logradouro não informado.')
            return;
        }

        if ($('cepIssRuas').value == '') {
            alert('CEP não informado.')
            return;
        }

        if ($('q02_numero').value == '') {
            alert('Número não informado.')
            return;
        }

		const dataInscricao = new FormData();
        dataInscricao.append('executa', 'salvarInscricao');
        dataInscricao.append('q02_inscr', $('q172_issbase').value);
        dataInscricao.append('q02_numcgm', $('q02_numcgm').value);
        dataInscricao.append('q13_bairro', $('j13_codi').value);
        dataInscricao.append('j14_codigo', $('j14_codigo').value);
        dataInscricao.append('q02_numero', $('q02_numero').value);
        dataInscricao.append('q02_compl', $('q02_compl').value);
        dataInscricao.append('q02_cxpost', $('q02_cxpost').value);
        dataInscricao.append('z01_cep', $('cepIssRuas').value);
        dataInscricao.append('selectformalocalvara', $('selectformalocalvara').value);

		HttpClient.post(urlRpc, {body: dataInscricao}).then(responseInscricao => {

			if (responseInscricao.erro) {
				alert(responseInscricao.mensagem);
				return;
			}

			$('q172_issbase').value = responseInscricao.q02_inscr;

	        const data = new FormData();
	        data.append('q172_issbase', $('q172_issbase').value);
	        data.append('q172_datacadastro', '');
	        data.append('q172_tipo', $('q172_tipo').value);
	        data.append('q172_marca', $('q172_marca').value);
	        data.append('q172_modelo', $('q172_modelo').value);
	        data.append('q172_cor', $('q172_cor').value);
	        data.append('q172_procedencia', $('q172_procedencia').value);
	        data.append('q172_categoria', $('q172_categoria').value);
	        data.append('q172_chassi', $('q172_chassi').value);
	        data.append('q172_renavam', $('q172_renavam').value);
	        data.append('q172_placa', $('q172_placa').value);
	        data.append('q172_potencia', $('q172_potencia').value);
	        data.append('q172_capacidade', $('q172_capacidade').value);
	        data.append('q172_anofabricacao', $('q172_anofabricacao').value);
	        data.append('q172_anomodelo', $('q172_anomodelo').value);
            data.append('q172_sequencial', $('q172_sequencial').value);
	        data.append('q172_aam', $('q172_aam').value);

	        HttpClient.post(`${apiUrl}tributario/issqn/veiculos/veiculo`, {body: data}).then(response => {

	            if (response.error == true && $('q172_sequencial').value == '') {

                    excluirInscricao();
	            	alert(response.message);
	                return;
	            }

	            $('q172_sequencial').value = response.data.q172_sequencial;
                $('q07_inscr').value = $('q172_issbase').value;
                $('z01_nome').value = $('descricaoInscricaoCGM').value;

	            if (response.data.q172_datacadastro) {
	            	$('q172_datacadastro').value = new Date(response.data.q172_datacadastro + ' 12:00').getDateBR();
	            } else {
	            	$('q172_datacadastro').value = new Date().getDateBR();
	            }

	            alert(response.message);
				abaCondutoresAuxiliares.desbloquear();
				abaAtividades.desbloquear();
				$('db_opcao').value = 2;
                $('pesquisarInscricao').disabled = false;
	            $('abaCondutoresAuxiliares').dispatchEvent(new Event('click'));
	        }).catch(error => {
	            alert(error.message);
	        });
		});
	}


    function excluirInscricao()
    {
        const data = new FormData();
        data.append('executa', 'excluirInscricao');
        data.append('q02_inscr', $('q172_issbase').value);
        data.append('q172_sequencial', $('q172_sequencial').value);
        HttpClient.post(urlRpc, {body: data}).then(response => {

            alert(response.mensagem);
            if (response.erro) {
                return;
            }
            $('q172_issbase').value = '';
        });
    }

    $('pesquisarInscricao').onclick = () => {
        js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_inscricaoveiculo',
            'func_inscricaoveiculo.php?funcao_js=parent.retorno_pesquisar|q172_sequencial&calculo=1',
            'Pesquisa',
            true
        );
    };

    function retorno_pesquisar(id) {
        db_iframe_inscricaoveiculo.hide();

        HttpClient.get(`${apiUrl}tributario/issqn/veiculos/veiculo/getVeiculo?q172_sequencial=${id}`).then(response => {
            $('q172_issbase').value = response.data.q172_issbase;
            $('q172_tipo').value = response.data.q172_tipo;
            $('q172_marca').value = response.data.q172_marca;
            $('q172_modelo').value = response.data.q172_modelo;
            $('descricaoModelo').value = response.data.ve22_descr;
            $('q172_cor').value = response.data.q172_cor;
            $('q172_procedencia').value = response.data.q172_procedencia;
            $('q172_categoria').value = response.data.q172_categoria;
            $('q172_chassi').value = response.data.q172_chassi;
            $('q172_renavam').value = response.data.q172_renavam;
            $('q172_placa').value = response.data.q172_placa;
            $('q172_potencia').value = response.data.q172_potencia;
            $('q172_capacidade').value = response.data.q172_capacidade;
            $('q172_anofabricacao').value = response.data.q172_anofabricacao;
            $('q172_anomodelo').value = response.data.q172_anomodelo;
            $('q172_sequencial').value = response.data.q172_sequencial;
            $('q172_aam').value = response.data.q172_aam;
            $('q02_numcgm').value = response.data.z01_numcgm;
            $('descricaoInscricaoCGM').value = response.data.z01_nome;
            $('descricaoMunicipio').value = response.data.z01_munic;
            $('cep').value = response.data.z01_cep;
            $('descricaoEndereco').value = response.data.z01_ender;
            $('cnpjcpf').value = response.data.z01_cgccpf;
            $('q172_datacadastro').value = new Date(`${response.data.q172_datacadastro} 12:00`).getDateBR();
            $('q07_inscr').value = response.data.q172_issbase;
            $('z01_nome').value = response.data.z01_nome;
            $('j13_codi').value = response.data.j13_codi;
            $('j13_descr').value = response.data.j13_descr;
            $('j14_codigo').value = response.data.j14_codigo;
            $('j14_nome').value = response.data.j14_nome;
            $('q02_compl').value = response.data.q02_compl;
            $('q02_cxpost').value = response.data.q02_cxpost;

            if (response.data.issruas_cep != 'null') {
                $('cepIssRuas').value = response.data.issruas_cep;
            }

            if (response.data.q02_numero != '0') {
                $('q02_numero').value = response.data.q02_numero;
            }

            $('selectformalocalvara').value = response.data.q02_formalocalvara;

            grid.clear();
            response.data.condutores.each((condutor) => {

                var dataFim = '';
                if (condutor.q173_datafim != null) {
                    dataFim = new Date(`${condutor.q173_datafim} 12:00`).getDateBR();
                }

                var condutor = {
                    'codigo': condutor.q173_sequencial,
                    'cgm': condutor.q173_cgm,
                    'nome': condutor.z01_nome,
                    'datainicio': new Date(`${condutor.q173_datainicio} 12:00`).getDateBR(),
                    'datafim': dataFim
                };
                collection.add(condutor);
            });
            grid.reload();

            carregarAtividades();

            if ($('db_opcao').value == 4) {
                buscarTipoAlvara();
            }

        });
    }

    $('excluirInscricao').onclick = () => {

        if ($('q172_sequencial').value == '') {
            alert("Código da inscrição do veículo não informado.");
            return false;
        }

        if (!confirm("Deseja excluir esta inscrição?")) {
            return;
        }

        excluirInscricao();
        $('q172_sequencial').value = '';
        $('frmInscricao').reset();
    }

    $('imprimirAlvara').onclick = () => {
        const codigo = $('q172_sequencial').value;

        if(!codigo || codigo == ''){
            alert('Código precisa estar preenchido!');
            return false;
        }

        window.open(`iss3_emissaoalvaraveiculos001.php?codigoVeiculo=${codigo}`,'','location=0,HEIGHT=600,WIDTH=600');
    };

    $('imprimirBIC').onclick = () => {
        const codigo = $('q172_sequencial').value;

        if(!codigo || codigo == ''){
            alert('Código precisa estar preenchido!');
            return false;
        }

        window.open(`iss3_emissaobicveiculos001.php?codigoVeiculo=${codigo}`,'','location=0,HEIGHT=600,WIDTH=600');
    };

    js_descricaoLocalAlvara();

    function js_descricaoLocalAlvara()
    {
        var oParam = new Object();
        oParam.executa = "lista";

        new AjaxRequest("iss1_issbase015.RPC.php", oParam, js_getDescricaoLocalAlvara).execute();
    }

    function js_getDescricaoLocalAlvara(oRetorno)
    {
        if (oRetorno.mensagem != "") {
            alert(oRetorno.mensagem);
        }

        if (oRetorno.erro) {
            return;
        }

        const lista = document.getElementById("descricaoLocalAlvara");
        lista.innerHTML = "";
        const select = document.createElement("select");

        const descricao = 'Selecionar';
        const option = document.createElement("option");
        select.setAttribute("name","selectformalocalvara");
        select.setAttribute("id","selectformalocalvara");
        select.classList.add("field-size9");
        option.value = 0;
        option.appendChild(document.createTextNode(descricao));
        select.appendChild(option);
        lista.appendChild(select);

        for (var index = 0; index < oRetorno.lista.length; index++) {
            const descricao = oRetorno.lista[index].q167_descricao;
            const option = document.createElement("option");

            select.setAttribute("name","selectformalocalvara");
            select.setAttribute("id","selectformalocalvara");
            option.value = oRetorno.lista[index].q167_sequencial;
            option.appendChild(document.createTextNode(descricao));
            select.appendChild(option);
            lista.appendChild(select);
        }

        if ($('db_opcao').value == 3 || $('db_opcao').value == 4) {
            select.disabled = true;
            select.classList.add('readonly');
            select.style.color = '#a39d9d';
        }
    }

    function validarCGMdoMunicipio() {

        $('j14_codigo').value = '';
        $('j14_nome').value = '';
        $('q02_numero').value = '';
        $('q02_compl').value = '';
        $('cepIssRuas').value = '';
        $('q02_cxpost').value = '';
        $('j13_codi').value = '';
        $('j13_descr').value = '';

        if ($('q02_numcgm').value == '') {
            return;
        }

        const data = new FormData();
        data.append('executa', 'validarCGMdoMunicipio');
        data.append('q02_numcgm', $('q02_numcgm').value);
        HttpClient.post(urlRpc, {body: data}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            $('j14_codigo').value = response.j14_codigo;
            $('j14_nome').value = response.j14_nome;
            $('q02_numero').value = response.z01_numero;
            $('q02_compl').value = response.z01_compl;
            $('cepIssRuas').value = response.z01_cep;
            $('q02_cxpost').value = response.z01_cxpostal;
            $('j13_codi').value = response.j13_codi;
            $('j13_descr').value = response.j13_descr;
        });
    }

    function buscarTipoAlvara() {
        $('imprimirAlvara').style.display = 'none';
        $('imprimirAlvara').disabled = true;


        const data = new FormData();
        data.append('executa', 'buscarTipoAlvara');
        data.append('q02_inscr', $('q172_issbase').value);
        HttpClient.post(urlRpc, {body: data}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            if (response.q98_sequencial != '') {

                $('q98_sequencial').value = response.q98_sequencial;
                $('q98_descricao').value = response.q98_descricao;
                $('q120_issalvara').value = response.q120_issalvara;
                $('imprimirAlvara').style.display = '';
                $('imprimirAlvara').disabled = false;
            }
        });
    }
</script>
