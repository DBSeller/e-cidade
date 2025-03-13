<form id="frmCondutores">
	<input type="hidden" name="q173_sequencial" id="q173_sequencial">
	<fieldset style="width: 740px">
		<legend>Condutor Auxiliar</legend>
		<table>
            <tr>
                <td>
                    <b>
                        <a id="ancoraCondutorCGM" href="#">
                            <label for="q173_cgm">Condutor:</label>
                        </a>
                    </b>
                </td>
                <td colspan="3">
                    <input id="q173_cgm" name="q173_cgm" type="text" data="z01_numcgm" class="field-size2"/>
                    <input id="descricaoCondutorCGM" name="descricaoCondutorCGM" type="text" data="z01_nome" class="field-size7 readonly" disabled="disabled"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="q173_datainicio"><b>Data de início:</b></label>
                </td>
                <td>
                    <input id="q173_datainicio" name="q173_datainicio"/>
                </td>
                <td>
                    <label for="q173_datafim"><b>Data de fim:</b></label>
                </td>
                <td>
                    <input id="q173_datafim" name="q173_datafim" class="campo_opcional" />
                </td>
            </tr>
		</table>
	</fieldset>
	<input type="button" name="adicionarCondutor" id="adicionarCondutor" value="Salvar">
	<input type="button" name="limparCondutor" id="limparCondutor" value="Limpar">
	<br><br>
	<fieldset>
		<legend>Condutores Auxiliares</legend>
		<div id="gridCondutoresAuxiliares"></div>
	</fieldset>
</form>

<script type="text/javascript">
    $('q173_datainicio').classList.add("field-size2");
    $('q173_datafim').classList.add("field-size2");

    const q173_datainicio = new DBInputDate($('q173_datainicio')),
    q173_datafim = new DBInputDate($('q173_datafim'));

    var lookupInscricaoCGM = new DBLookUp(
        $('ancoraCondutorCGM'),
        $('q173_cgm'),
        $('descricaoCondutorCGM'),
        {
          'sArquivo': 'func_nome.php',
          'sLabel': 'Pesquisar CGM',
          'aParametrosAdicionais': ['testanome=true', 'filtro=1']
        }
    );

    var collection = new Collection().setId('codigo');
    var grid = DatagridCollection.create(collection).configure({'order': false, 'height': 100});
    grid.addColumn('cgm', {label: 'CGM', width: '10%'});
    grid.addColumn('nome', {label: 'Nome', width: '55%'});
    grid.addColumn('datainicio', {label: 'Data de inicio', width: '12%'});
    grid.addColumn('datafim', {label: 'Data de fim', width: '12%'});
    
    grid.addAction('Alterar', null, function(event, item) {
        $('q173_sequencial').value = item.codigo;
        $('q173_cgm').value = item.cgm;
        $('descricaoCondutorCGM').value = item.nome;
        $('q173_datainicio').value = item.datainicio;
        $('q173_datafim').value = item.datafim;
        collection.remove(item.codigo);
        grid.reload();
    }, true, 'fa-pencil-alt', ['button-action']);

    grid.addAction('Excluir', null, function(event, item) {
        if (confirm(`Deseja excluir o condutor auxiliar ${item.nome}?`)) {
            
            const data = new FormData();

            data.append('q173_sequencial', item.codigo);

            HttpClient.post(`${apiUrl}tributario/issqn/veiculos/condutorauxiliar/delete`, {body: data}).then(response => {
                
                alert(response.message);

                if (response.error == true) {
                    return;
                }

                collection.remove(item.codigo);
                grid.reload();
            }).catch(error => {
                alert(error.message);
            });
        }
    }, true, 'fa-trash-alt', ['button-action']);

    if ($('db_opcao').value == 3 || $('db_opcao').value == 4) {
        grid.hideColumns([4]);
    }

    grid.show($('gridCondutoresAuxiliares'));

    function limparFormularioCondutor() {
        $('q173_sequencial').value = '';
        $('q173_cgm').value = '';
        $('descricaoCondutorCGM').value = '';
        $('q173_datainicio').value = '';
        $('q173_datafim').value = '';
    }

    $('limparCondutor').onclick = () => {
        if ($('q173_sequencial').value != '') {
            preencherGrid();
        }
        limparFormularioCondutor();
        grid.reload();
    }

    function adicionarCondutor() {

        const data = new FormData();
        data.append('q173_issveiculo', $('q172_sequencial').value);
        data.append('q173_sequencial', $('q173_sequencial').value);
        data.append('q173_cgm', $('q173_cgm').value);
        data.append('q173_datainicio', $('q173_datainicio').value.replace(/[/]/g, '-'));

        var datafim = $('q173_datafim').value.replace(/\s+/g, '');

        if (datafim == '//') {
            datafim = '';
        }
        data.append('q173_datafim',datafim.replace(/[/]/g, '-'));

        HttpClient.post(`${apiUrl}tributario/issqn/veiculos/condutorauxiliar`, {body: data}).then(response => {
            
            alert(response.message);

            if (response.error == true) {
                return;
            }

            $('q173_sequencial').value = response.data.q173_sequencial;

            preencherGrid();
            limparFormularioCondutor();
        }).catch(error => {
            alert(error.message);
        });
    }

    function preencherGrid() {
        
        var datafim = $('q173_datafim').value.replace(/\s+/g, '');

        if (datafim == '//') {
            datafim = '';
        }

        var condutor = {
            'codigo': $('q173_sequencial').value,
            'cgm': $('q173_cgm').value,
            'nome': $('descricaoCondutorCGM').value,
            'datainicio': $('q173_datainicio').value,
            'datafim': datafim}
        collection.add(condutor);
        grid.reload();
    }

    $('adicionarCondutor').onclick = () => {
        adicionarCondutor();
    };
</script>
