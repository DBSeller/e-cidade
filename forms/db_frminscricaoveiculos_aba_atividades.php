<?php
    //Form de inclusao
    include modification("forms/db_frminscricaoveiculos_aba_atividades_fmrtabativ.php");
?>

<fieldset>
    <legend>Atividades Cadastradas</legend>
    <div id="gridAtividades"></div>
</fieldset>

<script type="text/javascript">

    //Bloco de funções referentes ao grid de atividades
    var collectionAtividades = new Collection().setId('codigo');
    var gridAtividades = DatagridCollection.create(collectionAtividades).configure({'order': false, 'height': 100});

    gridAtividades.addColumn('atividadeinterna', {label: 'Interna', width: '5%'});
    gridAtividades.addColumn('principal', {label: 'Principal', width: '5%'});
    gridAtividades.addColumn('codigoatividade', {label: 'Código Atividade', width: '5%'});
    gridAtividades.addColumn('descricao', {label: 'Descrição', width: '20%'});
    gridAtividades.addColumn('datainicio', {label: 'Início', width: '10%'});
    gridAtividades.addColumn('horainicio', {label: 'Hora inicial', width: '5%'});
    gridAtividades.addColumn('horafim', {label: 'Hora final', width: '5%'});
    gridAtividades.addColumn('datafim', {label: 'Fim', width: '10%'});
    gridAtividades.addColumn('databaixa', {label: 'Baixa', width: '10%'});
    gridAtividades.addColumn('permanente', {label: 'Permanente', width: '5%'});
    gridAtividades.addColumn('quantidade', {label: 'Quantidade', width: '5%'});

    gridAtividades.addAction('Alterar', null, function(event, item) {

        $('q07_seq').value = item.codigo;
        $('q07_ativ').value = item.codigoatividade;
        $('q03_descr').value = item.descricao;
        $('princ').value = (item.principal === 'Sim') ? 't' : 'f';
        $('q07_quant').value = item.quantidade;
        $('q07_perman').value = (item.permanente === 'Sim') ? 't' : 'f';
        $('q07_datain').value = item.datainicio;
        $('q07_datafi').value = item.datafim;
        $('q07_horaini').value = item.horainicio;
        $('q07_horafim').value = item.horafim;
        $('q07_val_ativ_int').value = item.atividadeinterna;

        collectioncollectionAtividades.remove(item.codigo);
        gridAtividades.reload();

    }, true, 'fa-pencil-alt', ['button-action']);

    gridAtividades.addAction('Excluir', null, function(event, item) {
        if (confirm(`Deseja excluir atividade ${item.descricao}?`)) {
            const data = new FormData();
            data.append('executa', 'excluirAtividade');
            data.append('q07_seq', item.codigo);
            data.append('q07_inscr', $('q07_inscr').value);
            data.append('q07_ativ', item.codigoatividade);

            HttpClient.post(urlRpc, {body: data}).then(response => {
                if (response.erro) {
                    alert(response.mensagem);
                    return;
                } else {
                    collectionAtividades.remove(item.codigo);
                    gridAtividades.reload();
                }
            });
        }
    }, true, 'fa-trash-alt', ['button-action']);

    if ($('db_opcao').value == 3 || $('db_opcao').value == 4) {
        gridAtividades.hideColumns([11]);
    }

    gridAtividades.show($('gridAtividades'));

    //Busca as atividades da inscricao e preenche o grid
    function carregarAtividades() {
        const formdata = new FormData();

        formdata.append('executa', 'carregarAtividades');
        formdata.append('q02_inscr', $('q172_issbase').value);

        HttpClient.post(urlRpc, {body: formdata}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            } else {
                for(var objAtividade of response.data){
                    const
                        dataInicio = objAtividade.q07_datain,
                        dataFim = objAtividade.q07_datafi,
                        dataBaixa = objAtividade.q07_databx;

                    collectionAtividades.add({
                        'codigo': objAtividade.q07_seq,
                        'atividadeinterna': objAtividade.q07_val_ativ_int,
                        'principal': (objAtividade.q88_inscr !== "") ? 'Sim' : 'Não',
                        'codigoatividade': objAtividade.q03_ativ,
                        'descricao': objAtividade.q03_descr,
                        'datainicio': (dataInicio !== "") ? new Date(`${dataInicio} 12:00`).getDateBR() : '',
                        'horainicio': objAtividade.q07_horaini,
                        'horafim': objAtividade.q07_horafim,
                        'datafim': (dataFim !== "") ? new Date(`${dataFim} 12:00`).getDateBR() : '',
                        'databaixa': (dataBaixa !== "") ? new Date(`${dataBaixa} 12:00`).getDateBR() : '',
                        'permanente': (objAtividade.q07_perman === 'f') ? 'Não' : 'Sim',
                        'quantidade': objAtividade.q07_quant,
                    });
                }

                gridAtividades.reload();
            }
        });
    }

    function salvarAtividade(){
        const data = new FormData();
        data.append('executa', 'salvarAtividade');
        data.append('q07_seq', $('q07_seq').value);
        data.append('q07_ativ', $('q07_ativ').value);
        data.append('q07_inscr', $('q07_inscr').value);
        data.append('princ', $('princ').value);
        data.append('q07_quant', $('q07_quant').value);
        data.append('q07_perman', $('q07_perman').value);
        data.append('q07_datain', $('q07_datain').value);
        data.append('q07_datafi', $('q07_datafi').value);
        data.append('q07_horaini', $('q07_horaini').value);
        data.append('q07_horafim', $('q07_horafim').value);
        data.append('q07_val_ativ_int', $('q07_val_ativ_int').value);

        HttpClient.post(urlRpc, {body: data}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            } else {
                const
                    objAtividade = response.data[0],
                    dataInicio = objAtividade.q07_datain,
                    dataFim = objAtividade.q07_datafi,
                    dataBaixa = objAtividade.q07_databx;

                $('q07_seq').value = objAtividade.q07_seq;

                collectionAtividades.add({
                    'codigo': objAtividade.q07_seq,
                    'atividadeinterna': objAtividade.q07_val_ativ_int,
                    'principal': (objAtividade.q88_inscr !== "") ? 'Sim' : 'Não',
                    'codigoatividade': objAtividade.q03_ativ,
                    'descricao': objAtividade.q03_descr,
                    'datainicio': (dataInicio !== "") ? new Date(`${dataInicio} 12:00`).getDateBR() : '',
                    'horainicio': objAtividade.q07_horaini,
                    'horafim': objAtividade.q07_horafim,
                    'datafim': (dataFim !== "") ? new Date(`${dataFim} 12:00`).getDateBR() : '',
                    'databaixa': (dataBaixa !== "") ? new Date(`${dataBaixa} 12:00`).getDateBR() : '',
                    'permanente': (objAtividade.q07_perman === 'f') ? 'Não' : 'Sim',
                    'quantidade': objAtividade.q07_quant,
                });

                gridAtividades.reload();
                limparFormularioAtividades();
            }
        });
    }

    function limparFormularioAtividades()
    {
        $('q07_seq').value = '';
        $('form_atividade').reset();
        $('q07_inscr').value = $('q172_issbase').value;
        $('z01_nome').value = $('descricaoInscricaoCGM').value;
    }

</script>