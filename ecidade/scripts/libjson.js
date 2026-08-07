function makeObj(){
  /*
   *  Metodo addAttribute()
   */
  makeObj.prototype.addAttribute = function(nome,valor){


    if (typeof(valor) != 'object'){

      eval('this.'+nome+' = "'+valor+'";');

    }else{

      eval('this.'+nome+' = '+JSON.stringify(valor)+';');

      eval('alert(this.'+nome+');');

    }

  }

  makeObj.prototype.returnStrJson = function () {

    var str = JSON.stringify(this);
    return str;

  }

}
