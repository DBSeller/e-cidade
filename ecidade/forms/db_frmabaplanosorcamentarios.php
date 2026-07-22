<?php
?>
<div class="subcontainer">
    <fieldset>
        <legend>Informe os dados do plano orçamentário</legend>
        <table class="form-container">
            <tr>
                <td><label for="po_descricao">Título:</label></td>
                <td>
                    <input type="text" id="po_descricao" name="po_descricao" class="field-size8"/>
                </td>
            </tr>
            <tr>
                <td><label for="po_valor">Valor:</label></td>
                <td>
                    <input type="text" id="po_valor" name="po_valor"/>
                </td>
            </tr>
        </table>
    </fieldset>

    <input type="button" value="Adicionar" id="btn-po-adicionar">
    <br/>
    <fieldset style="width: 700px;">
        <legend>Plano Orçamentário</legend>
        <div id="ctnGridPlanoOrcamentario"></div>
    </fieldset>
</div>


