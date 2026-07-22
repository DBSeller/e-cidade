<div class="container">
    <table align="center" border="0" cellspacing="4" cellpadding="0">
        <tr>
            <td>
                <label id="ancoraassentamento3" class='bold m-2' for="assentamento3">
                    Assentamento
                </label>
                <input type="text" id="assentamento3" class="field-size2 readonly" readonly>
                <input type="text" id="descricaoassentamento3" class="field-size8 readonly" readonly>
            </td>
        </tr>
        <tr>
            <td>
                <form name="form1" method="post">
                    <table>
                        <thead>
                            <tr>
                                <td>Assentamento</td>
                                <td>Condição</td>
                                <td>Ação</td>
                                <td>Formula</td>
                                <td>Multiplicação</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select style="width:100px ;" id="allIdAssentamentos">
                                    </select>
                                    <select style="width:200px ;" id="allassentamaentos">
                                    </select>
                                </td>
                                <td>
                                    <select style="width:100px ;" id="condicao">
                                        <option value='inicio'>Inicio</option>
                                        <option value='antesdoinicio'>Antes do Inicio</option>
                                        <option value='meio'>Meio</option>
                                        <option value='final'>Final</option>
                                        <option value='interrompe'>Interrompe</option>
                                    </select>
                                </td>
                                <td>
                                    <select style="width:100px ;" id="resultado">
                                        <option value='+dias'>Protela</option>
                                        <option value='-dias'>Antecipa</option>
                                    </select>
                                </td>
                                <td>
                                    <select style="width:100px ;" id="operador">
                                        <option value='+'>+dias</option>
                                        <option value='-'>-dias</option>
                                        <option value='*'>*dias</option>
                                        <option value='m+'>+Meses</option>
                                        <option value='m-'>-Meses</option>
                                        <option value='m*'>*Meses</option>
                                    </select>
                                </td>
                                <td>
                                    <input style="width:100px ;" type="text" id="multiplicacao" lang="h12_descr" class="field-size1" oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </td>
        <tr>
            <td align="center" colspan="2">
                <button type="reset" class="m-2 addform">Limpar</button>
                <button type="button" onClick="addformpg3('create')" class="m-2 addform">Adicionar</button>
                <button style="display: none;" type="button" onClick="cancelarform()" class="m-2 alterarform">Cancelar</button>
                <button style="display: none;" type="button" onClick="addformpg3('alterar')" class="m-2 alterarform">Atualizar</button>
            </td>
        </tr>
    </table>
    <table id="table1"></table>
</div>