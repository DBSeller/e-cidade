<div class="container">
    <table align="center" border="0" cellspacing="4" cellpadding="0">
        <tr>
            <td>
                <label id="ancoraassentamentoAba4" class='bold m-2' for="assentamento3">
                    Assentamento
                </label>
                <input type="text" id="assentamentoAba4" class="field-size2 readonly" readonly>
                <input type="text" id="descricaoassentamentoAba4" class="field-size8 readonly" readonly>
            </td>
        </tr>
        <tr>
            <td>
                <form name="form1" method="post">
                    <table>
                        <thead>
                            <tr>
                                <td></td>
                                <td>Acão</td>
                                <td>Tipo</td>
                                <td>Fórmula</td>
                                <td>Condição</td>
                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label id="ancoraassentamento2Aba4" class='bold m-2' onclick="js_pesquisa_assentamentoAba4(true);" for="assentamento">
                                        <a href="#">Assentamento : </a>
                                    </label>
                                    <input type="text" onchange="js_pesquisa_assentamentoAba4(false);" onkeyup="js_ValidaMaiusculo(this,'f',event);" oninput="js_ValidaCampos(this,1,'Numcgm','f','f',event);" onkeydown="return js_controla_tecla_enter(this,event);" id="assentamento2Aba4" lang="h12_codigo" class="field-size2">
                                    <input type="text" id="descricaoassentamento2Aba4" lang="h12_descr" class="field-size8 readonly" readonly>
                                </td>
                                <td>
                                    <select style="width:100px ;" id="acaoAba4">
                                        <option value='1'>Concede</option>
                                        <option value='2'>Não Concede</option>
                                        <option value='3'>Validar</option>
                                    </select>
                                </td>
                                <td>
                                    <select style="width:100px ;" id="tipoAba4">
                                        <option value='1'>Acumula</option>
                                        <option value='2'>Não Acumula</option>
                                        <option value='3'>Protela</option>
                                    </select>
                                </td>
                                <td>
                                    <select style="width:100px ;" id="formulaAba4">
                                        <option value='+dias'>+Dias</option>
                                        <option value='+Meses'>+Meses</option>
                                    </select>
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" id="condicaoAba4">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </td>
        <tr>
            <td align="center" colspan="2">
                <button type="reset" class="m-2 addformAba4">Limpar</button>
                <button type="button" onClick="addformAba4('create')" class="m-2 addformAba4">Adicionar</button>
                <button style="display: none;" type="button" onClick="cancelarformAba4()" class="m-2 alterarformAba4">Cancelar</button>
                <button style="display: none;" type="button" onClick="addformAba4('alterar')" class="m-2 alterarformAba4">Atualizar</button>
            </td>
        </tr>
    </table>

    <table id="tableAba4"></table>
</div>