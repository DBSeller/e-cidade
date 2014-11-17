/**
 * Função que calcula data adicionando dias, meses e anos
 * @param dData
 * @param iDiaSomar
 * @param iMesSomar
 * @param iAnoSomar
 * @returns {Date}
 */
function somaDataDiaMesAno(dData, iDiaSomar, iMesSomar, iAnoSomar) {

  iTimestamp = dData.getTime();
  iTimestamp = parseInt(iTimestamp) + (parseInt(iAnoSomar) * 31557600000); //31536000
  iTimestamp = parseInt(iTimestamp) + (parseInt(iMesSomar) * 2629800000);  //2628000
  iTimestamp = parseInt(iTimestamp) + (parseInt(iDiaSomar) * 86400000);
  return new Date(iTimestamp);
}

/**
 * Função que transforma a data no formoato do banco de dados e nativo do js
 * @param sDateIn
 * @returns {String}
 */
function getDateInDatabaseFormat(sDateIn) {

  if (sDateIn != undefined && sDateIn != "") {
    return sDateIn.split('/')[2] + "-" + sDateIn.split('/')[1] + "-" + sDateIn.split('/')[0];
  } else if (this.iYear > 0) {
    return this.iYear + "-" + this.iMonth + "-" + this.iDay;
  }
}

const DATA_PTBR = "d/m/Y";
const DATA_EN   = "Y-m-d";

Date.prototype.getFormatedDate = function( sFormato ){

  if ( sFormato === null ) {
    sFormato = DATA_EN;
  }

  var sDia = js_strLeftPad( this.getDate(), 2, "0");
  var sMes = js_strLeftPad( this.getMonth() + 1, 2, "0");
  var sAno = this.getFullYear();

  if ( sFormato == DATA_EN ) {
    return sAno + "-" + sMes + "-" + sDia;
  };
  return sDia + "/" + sMes + "/" + sAno;
};


/*
 * Verifica se sData se encontra entre sDataInicio e @sDataFim
 *
 * @param  {string} sData       Formato de entrada YYYY-MM-DD
 * @param  {string} sDataInicio Formato de entrada YYYY-MM-DD
 * @param  {string} sDataFim    Formato de entrada YYYY-MM-DD
 * @return {Boolean}            true se verdadeiro
 */
function js_validaIntervaloData(sData, sDataInicio, sDataFim) {

 var data1 = sDataInicio.substr(0,4)+''+sDataInicio.substr(5,2)+''+sDataInicio.substr(8,2);
 var data2 = sDataFim.substr(0,4)+''+sDataFim.substr(5,2)+''+sDataFim.substr(8,2);
 var sData = sData.substr(0,4)+''+sData.substr(5,2)+''+sData.substr(8,2);

 if ( parseInt(sData) >= parseInt(data1) && parseInt(sData) <= parseInt(data2) ) {
  return true;
 }
 return false;
}

Date.convertFrom = function(sData, sFormato) {

  if (!sData) {
    throw new Error("Você precisa informar um data.")
  }

  var iDia, iMes, iAno, dRetorno = new Date();

  switch (sFormato) {
    case DATA_PTBR:
      var aData = sData.split('/');

      iDia = aData[0];
      iMes = aData[1];
      iAno = aData[2];
    break;

    case DATA_EN:
    default:
      var aData = sData.split('-');

      iDia = aData[2];
      iMes = aData[1];
      iAno = aData[0];

  }

  dRetorno.setYear(iAno);
  dRetorno.setMonth(iMes-1);
  dRetorno.setDate(iDia);

  return dRetorno;
}