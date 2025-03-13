<div class="container">
    <form name="form1" method="post">
        <table align="center" border="0" cellspacing="4" cellpadding="0">
            <tr>
                <td>
                    <label id="ancoraassentamento" class='bold m-2' for="assentamento">
                        Assentamento
                    </label>
                    <input type="text" id="assentamento2" class="field-size2 readonly" readonly>
                    <input type="text" id="descricaoassentamento2" class="field-size8 readonly" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    <label class='m-2 bold' for="periodo">
                        Ordem
                    </label>
                    <input type="text" id="ordem" lang="h12_descr" class="field-size1" oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);">

                    <label class='m-2 bold' for="periodo">
                        Periodo
                    </label>
                    <input type="text" id="periodo" lang="h12_descr" class="field-size1" oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);">

                    <label class='m-2  bold' for="periodo">
                        Unidade de tempo
                    </label>
                    <select id="unidadetempo">
                        <option value="year">Ano</option>
                        <option value="month">Mês</option>
                        <option value="day">Dia</option>
                    </select>
                    <label class='m-2  bold' for="percentual">
                        Percentual
                    </label>
                    <input type="text" id="percentual" lang="h12_descr" class="field-size1" oninput="js_ValidaCampos(this,4,'Numcgm','f','f',event);">
                </td>
            <tr>
                <td align="center" colspan="2">
                    <button type="reset" class="m-2 addintervalo">Limpar</button>
                    <button type="button" onClick="addIntervalopg2('create')" class="m-2 addintervalo">Adicionar</button>
                    <button style="display: none;" type="button" onClick="cancelarintervalo()" class="m-2 alterarintervalo">Cancelar</button>
                    <button style="display: none;" type="button" onClick="addIntervalopg2('alterar')" class="m-2 alterarintervalo">Atualizar</button>
                </td>
            </tr>
        </table>
    </form>

    <table id="table"></table>
</div>
<script>

</script>