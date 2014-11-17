

DBGrid.prototype.setPesquisa = function (iColunaPesquisa) {

  /**
   * Carrega os dados da TreeView
   */
//  var oEstiloDBTreeview      = document.createElement("link");
//      oEstiloDBTreeview.href = "estilos/dbtreeview.style.css";
//      oEstiloDBTreeview.rel  = "stylesheet";
//      oEstiloDBTreeview.type = "text/css";
//      document.getElementsByTagName("head")[0].appendChild(oEstiloDBTreeview);
//
  /**
   * Criando Variaveis de Referencia
   */
  var __parent                                = this;
  this.oPesquisa                              = new Object();
  var __this                                  = this.oPesquisa;
  //var aLinhaGrid                              = __parent.aRows;

  /**
   * Criando DIV Principal
   */
  __this.oDiv                                 = document.createElement("DIV");
  __this.oDiv.id                              = 'pesquisa' + __parent.sName;
  __this.oDiv.style.padding                   = "5px";
  __this.oDiv.style.verticalAlign             = "middle";

  /**
   * Criando Span com o Label
   */
  __this.oSpanLabel                           = document.createElement("SPAN");
  __this.oSpanLabel.innerHTML                 = "<b>Pesquisar: </b>";

  /**
   * Criando Span com Input de Pesquisa
   */
  __this.oSpanInput                           = document.createElement("SPAN");
  __this.oInputPesquisa                       = document.createElement("input");
  __this.oInputPesquisa.type                  = 'text';
  __this.oInputPesquisa.title                 = 'Informe a expressão de pesquisa';
  __this.oInputPesquisa.style.marginTop       = "3px";

  __this.oBotaoPesquisa1                      = document.createElement("input");
  __this.oBotaoPesquisa1.setAttribute('type', 'button');
  __this.oBotaoPesquisa1.style.background     = "url('imagens/icon_find_black.png') no-repeat scroll 0% 0% transparent";
  __this.oBotaoPesquisa1.style.backgroundPosition = "2px 2px";

  __this.oSpanInput.appendChild(__this.oInputPesquisa);
  __this.oSpanInput.appendChild(__this.oBotaoPesquisa1);


  /**
   * Adicionando Spans Criados a Div Principal do Componente
   */
  __this.oDiv.appendChild (__this.oSpanLabel);
  __this.oDiv.appendChild (__this.oSpanInput);

  /**
   * Adicionando Div principal ao Container da Grid
   */
  __parent.gridContainer.insertBefore(__this.oDiv, $('grid'+__parent.sName));

  /**
   * Eventos nos componentes do formulario de Pesquisa
   */
  __this.oInputPesquisa.observe("keypress", function (eEvento) {

    if (eEvento.which == 13) {
      __parent.oPesquisa.buscarDados();
    }
  });

  __this.oInputPesquisa.observe("change", function (event) {
    __parent.oPesquisa.buscarDados();
  });


/////////////////////////// Métodos \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
  /**
   * Efetua busca nos Dados da Grid
   */
  __this.buscarDados = function () {

    var aDadosGrid        = __parent.aRows;
    var sConteudoPesquisa = __this.oInputPesquisa.getValue().toLowerCase();
    var iCountRows        = 0;

    for (var iLinhaGrid = 0; iLinhaGrid < aDadosGrid.length; iLinhaGrid++) {

      var oCelula  = aDadosGrid[iLinhaGrid].aCells[iColunaPesquisa];
      var iPosicao = oCelula.getValue().toLowerCase().indexOf(sConteudoPesquisa);

      if ( iPosicao  == -1 ) {
        $(aDadosGrid[iLinhaGrid].sId).style.display = "none";
      } else {
        $(aDadosGrid[iLinhaGrid].sId).style.display = "";
        iCountRows++;

      }
    }

    __parent.setNumRows(iCountRows)

  }


}