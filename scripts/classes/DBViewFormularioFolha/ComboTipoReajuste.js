require_once('scripts/widgets/dbcomboBox.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');

/**
 * Cria um ComboBox de Tipo de Reajuste com 
 * as opções 'Real', 'Paridade'
 *
 * @return DBComboBox
 */
DBViewFormularioFolha.ComboTipoReajuste = function(){

  var oDBComboTipoReajuste = new DBComboBox('tipoReajuste', null, []);

  oDBComboTipoReajuste.addItem('f', 'Real');
  oDBComboTipoReajuste.addItem('t', 'Paridade');

  return oDBComboTipoReajuste;
}