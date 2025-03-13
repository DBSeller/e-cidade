DBViewFiltroRecursos = function ( novaJanela ) {


    oInstancia = this;

    if (novaJanela === undefined) {
        novaJanela = 'true';
    }

    this.getConteudoFiltro = function () {
        var sContent = "<div class='container' style='width:99%; height: 90%'> ";
        sContent += "  <iframe id='iframe_filtro_recursos' name='iframe_filtro_recursos' src='func_filtrorecursos.php?novaJanela="+novaJanela+"' style='border:0; width: 100%; height: 80%'> </iframe>";
        sContent += "  <input type='hidden' name='recursos' id='recursos' />";
        sContent += "</div>";
        return sContent;
    };

    this.construirJanela = function ()
    {


        var sContent  = this.getConteudoFiltro();

        if ( oInstancia.windowSelecaoRecurso ) {
            oInstancia.windowSelecaoRecurso.show(2, 2);
            return true;
        }

        oInstancia.windowSelecaoRecurso = new windowAux('wndRecursos', 'Lista de Recursos', (screen.availWidth - 100), (screen.availHeight - 100));
        oInstancia.windowSelecaoRecurso.setContent(sContent);
        oInstancia.windowSelecaoRecurso.allowCloseWithEsc(false);
        oInstancia.windowSelecaoRecurso.setShutDownFunction(function () {
            oInstancia.windowSelecaoRecurso.hide();
        });
        oInstancia.windowSelecaoRecurso.show(2, 2);

    }

    this.getListaRecursos = function(){
        return $F('recursos');
    }

    this.show = function (){
        oInstancia.windowSelecaoRecurso.show(2, 2);
    }

    this.hide = function () {
        oInstancia.windowSelecaoRecurso.hide();
    }


}
