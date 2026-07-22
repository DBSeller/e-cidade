/** 
 * @fileoverview Define classe para adicionar navegacao pro teclado em tabelas
 *
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version  $Revision: 1.3 $
 */




/**
 * classe que disponibiliza um table navigation
 * @class tablenavigations 
 * @constructor
 * @param {object} objeto htmlTABLE 
 */

tablenavigation =  function (oTable) {
   
    this.table          = oTable;
    this.startCapture   = true;
    this.bindFor        = 'lovrot'; 
    this.sizeScroll     = 0;
    this.lastCell       = 0;
    this.itensForScroll = 6;//parseInt(this.table.style.height)/21
    this.hasScroll      = false;
    var obj = this;
    this.colFunction    = 1;
    if (this.table.style.overflowY == 'scroll') {
      this.hasScroll   = true;
    }
    this.sFunctionEvent = "onclick";
    /**
     * Adiciona os eventos de captura de teclado ao document.
     * @param {Object} obj objeto navigation; 
     */
    this.addHandler   = function (event) {
    
      if (!obj.startCapture) {
        return false;
      }
      //document.addEventListener('keydown',function (event) {
        
      var aElements = getElementsByClass('rowSelected', obj.table);
      if (aElements.length == 0) {
         
         obj.olderStyle =  obj.table.rows[0].className;
         obj.table.rows[0].className = 'rowSelected';
         obj.table.rows[0].focus();
         event.preventDefault();
         event.stopPropagation();
         
      }
      object = event.target
      switch (event.which) {  
  
         
          /**
           * @TODO Verificar apenas rows com display="" ou visibility = "visible";  
           */
        case 40: //Down
         
          //if ((object.nodeName == "INPUT" && object.type=='text') || object.nodeName == "SELECT" ){return true};
          if ((!obj.isTableCHild(object)) || object.nodeName == "SELECT" ){return true};
          if (obj.lastCell != 0  ){
            obj.lastCell.style.backgroundColor = "";
          }
          obj.lastCell = 0;
                       
          if (aElements.length == 1) {
              
            aElements[0].className=obj.olderStyle;
            if (obj.table.rows[aElements[0].sectionRowIndex+1]) {
              
              var iNextRow   = obj.validateNextRow(obj.table, obj.table.rows[aElements[0].sectionRowIndex+1]);
              obj.olderStyle =  obj.table.rows[iNextRow].className;
              obj.table.rows[iNextRow].className = 'rowSelected';
              obj.table.rows[iNextRow].focus();
              if (obj.hasScroll) {
                 
                if (iNextRow == 0) {
                  obj.table.rows[iNextRow].scrollIntoView(true);
                } 
                obj.sizeScroll += obj.table.rows[0].scrollHeight;
                if ((aElements[0].sectionRowIndex-1) % obj.itensForScroll == 5 || aElements[0].sectionRowIndex == 0) {
                 //if (obj.sizeScroll > parseInt(obj.table.style.height)) {
                  
                  obj.table.rows[aElements[0].sectionRowIndex].scrollIntoView(true);
                  obj.sizeScroll = 0;
                  if (obj.itensForScroll == 0) {
                    obj.itensForScroll = aElements[0].sectionRowIndex+1;
                  }
                }
              }
            }  
          } else {
            
            if  (obj.table.rows[obj.table.rows.length-1]) {
              this.olderStyle =  obj.table.rows[obj.table.rows.length-1].className;
            } else {
               this.olderStyle =  obj.table.rows[0].className;
            } 
            obj.table.rows[0].className = 'rowSelected';
            obj.table.rows[0].focus();
            if (obj.hasScroll) {
           
               obj.sizeScroll = obj.table.rows[0].scrollHeight;
               obj.table.rows[0].scrollIntoView(true);
               
            }
          }
           
          event.preventDefault();
          event.stopPropagation();
          break;
           
        case 38:
          
          //if ((object.nodeName == "INPUT" && object.type=='text') || object.nodeName == "SELECT" ){return true};
          if ((!obj.isTableCHild(object)) || object.nodeName == "SELECT" ){return true};
          
            if (obj.lastCell !=  0  ){
              obj.lastCell.style.backgroundColor = "";
            }
            
            obj.lastCell       = 0;
            if (aElements.length == 1) {
           
              aElements[0].className=obj.olderStyle;
              var iIndex = aElements[0].sectionRowIndex;
              if ( iIndex -1 >= 0 ) {
              
                var iNextRow   = obj.validateNextRow(obj.table, obj.table.rows[aElements[0].sectionRowIndex-1],"up");
                obj.olderStyle = obj.table.rows[iNextRow].className;
                obj.table.rows[iNextRow].className = 'rowSelected';
                obj.table.rows[iNextRow].focus();

                if (obj.hasScroll) {
                 
                  obj.sizeScroll -= obj.table.rows[iNextRow].scrollHeight;
                  if ((iNextRow) % obj.itensForScroll == 5 ) {
                    
                    var iIndex = aElements[0].sectionRowIndex - obj.itensForScroll;
                    if (aElements[0].sectionRowIndex - obj.itensForScroll < 0) {
                      iIndex = 0;
                    }
                    
                    obj.table.rows[iIndex].scrollIntoView(true);
                  }
                }
              } else {
               
                 var iLastIndex = 2;
                 if (obj.bindFor == 'lovrot') {
                   iLastIndex = 1;
                } 
                var iNextRow   =  obj.validateNextRow(obj.table, obj.table.rows[obj.table.rows.length-iLastIndex], "up")
                obj.olderStyle = obj.table.rows[iNextRow].className;
                obj.table.rows[iNextRow].className = 'rowSelected';
                obj.table.rows[iNextRow].focus();
                if (obj.hasScroll) {
                 
                  obj.sizeScroll = obj.table.rows.length-iLastIndex * obj.table.rows[0].scrollHeight;
                  obj.table.rows[iNextRow].scrollIntoView(true);
                }
              }
            }
            event.preventDefault();
            event.stopPropagation();
            break; 

          case 13:
          
            if (aElements.length == 1) {
                 
              if (event.target.nodeName == "SELECT" || event.target.nodeName == "INPUT") {
                 
                 //alert('fff');  
                 //event.target.blur();
                 //event.preventDefault();
                 return true;
                  
              }   
              var teste = function (){};
              if (obj.bindFor == 'lovrot') {
                if (eval("aElements[0].cells[obj.getColFunction()].childNodes[0]."+obj.sFunctionEvent)){
                  teste =  eval("aElements[0].cells[obj.getColFunction()].childNodes[0]."+obj.sFunctionEvent);
                }
              } else {
                 
                 teste = eval("aElements[0].cells[obj.getColFunction()]."+obj.sFunctionEvent);
              }
              teste();
            }
            event.preventDefault();
            event.stopPropagation();
            break;
        
          case 32: //ESPACO
            
            with (aElements[0].cells[0].childNodes[0]) {
              if (nodeName == "INPUT") {
  
                if (type=="checkbox") {
                    
                  click();
                  obj.olderStyle = obj.table.rows[aElements[0].sectionRowIndex].className;
                  obj.table.rows[aElements[0].sectionRowIndex].className = 'rowSelected';
                  blur();
                }
                /**
                 * Percorremos a linha e focamos oo primeiro input
                 */
                for (var i = 1; i <= aElements[0].cells.length; i++) {
              
                  if (aElements[0].cells[i] && checked) {
                 
                    with (aElements[0].cells[i]) {
                   
                      if (aElements[0].cells[i].childNodes[0].nodeType != 3 
                          && aElements[0].cells[i].childNodes[0].nodeName != "A") {
                        aElements[0].cells[i].childNodes[0].focus();
                        break;
                        
                      }
                    }    
                  }
                }
              }
            }
            event.preventDefault();
            event.stopPropagation();
            break;
       }
    }
   /**
    * Adiciona eventos de hover ao event onmouseOver, onmouseOut
    */ 
   this.doHover = function() {
       //alert(this.table); 
      for (var i = 0;i < this.table.rows.length; i++) {
      
         with (this.table.rows[i]) {
	         
	         if (className != 'ffHack' && style.display != 'none'  
	           && className != 'disabled') {
            
	            onmouseover = function () {
	            
	              var aElements = getElementsByClass('rowSelected', this.table);
	              if (aElements.length == 1) {
	               aElements[0].className = this.oldStyle;
	              }
	              this.oldStyle = this.className;  
	              this.className='rowSelected'
	             };
	             onmouseout = function() {this.className=this.oldStyle;};
	         };
         }
       }
    }
    
    /**
     * Inicia a captura dos eventos de teclado para a tabela
     */
    this.start = function () {
    
      this.startCapture = true;
      if (this.table.rows.length > 1) {
        this.table.rows[0].className='rowSelected';
      }
      document.observe("keydown", obj.addHandler);
      //this.addHandler(this);
      //this.doHover();
      return true;
      
    }
    
    /**
     * para com a captura dos eventos;
     */
    this.stop = function () {
    
      this.startCapture  = false;
      var aElements = getElementsByClass('rowSelected', obj.table);
      if (aElements.length == 1) {
      
        aElements[0].className = obj.oldStyle;
        obj.oldStyle = '';
      }
      document.stopObserving("keydown", obj.addHandler);
      return true;
      
    }
    /** 
     * Define qual coluna possui a chamada da funcao executada no keypress 
     */    
    this.setColFunction = function(iWhatCol) {
      this.colFunction = iWhatCol;
    }
    
    /** 
     * retorna a coluna do keypress 
     */
    this.getColFunction = function () {
      return this.colFunction;
    }   
    
  this.isTableCHild = function (oSender) {

    var el =  oSender;
    var isChild = false;
    while (el.offsetParent && el.id.toUpperCase() != 'wndAuxiliar') {
      
      if (el.className == "DBGrid") { 
         isChild = true;
      }
      el = el.offsetParent;
    }
    if (!isChild && (oSender.nodeName != 'INPUT' && oSender.type != "text")) {
      isChild = true;
    }
    return isChild;
  } 
  
  this.validateNextRow = function(oTable, oRowActive, sDirection) {
      
     var iIndexRow = 0;
     var lVerificar = true;
     if (sDirection == null) {
       sDirection = 'down';
     }
     var iTotalLinhas = oTable.rows.length;
     var iTested      = 0;
     while (lVerificar) {
       
       if (iTested == iTotalLinhas) {
         break;
       }
       iIndexRow = oRowActive.sectionRowIndex;
       if (oRowActive.className != 'ffHack' && oRowActive.style.display != 'none'  
           && oRowActive.className != 'disabled') {
       
         lVerificar = false;
         iIndexRow = oRowActive.sectionRowIndex;
         
       } else {
         
         
         if (sDirection == 'down') {
          
          if (oTable.rows[oRowActive.sectionRowIndex+1]) {
            oRowActive= oTable.rows[oRowActive.sectionRowIndex+1];
         
           } else {
             oRowActive= oTable.rows[0];
           }
         } else {
           
           var iIndex = oRowActive.sectionRowIndex-1;
           var iLastIndex = 2;
           if (obj.bindFor == 'lovrot') {
             iLastIndex = 1;
           }
           if (iIndex < 0) {iIndex = oTable.rows.length-iLastIndex};
           if (oTable.rows[iIndex]) {
              
             oRowActive= oTable.rows[iIndex];
             //lVerificar = false;
           } else {
             oRowActive = oTable.rows[oTable.rows.length-iLastIndex];
           }
         }
       }
       iTested++;
     }
     return iIndexRow;
  } 
  
  this.setEventFunction = function(sEvent)  {
     this.sFunctionEvent = sEvent;
  }
}
