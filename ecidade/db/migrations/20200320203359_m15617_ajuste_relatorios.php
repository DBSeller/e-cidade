<?php

use Classes\PostgresMigration;

class M15617AjusteRelatorios extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->execute(<<<SQL
update configuracoes.db_relatorio set db63_xmlestruturarel = '<?xml version="1.0" encoding="ISO-8859-1"?>
 <Relatorio>
  <Versao>1.0</Versao>
  <Propriedades versao="1.0" nome="Exportação das Naturezas da Receita" layout="dbseller" formato="A4" orientacao="portrait" margemsup="0" margeminf="0" margemesq="20" margemdir="20" tiposaida="csv"/>
  <Cabecalho></Cabecalho>
  <Rodape></Rodape>
  <Campos>
   <Campo id="1" nome="codigo_conta" alias="Código da Conta" largura="20" alinhamento="c" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
   <Campo id="2" nome="descricao_conta" alias="Descrição da Conta" largura="20" alinhamento="c" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  </Campos>
  <Consultas>
   <Consulta tipo="Principal">
    <Select>
     <Campo id="1"/>
     <Campo id="2"/>
    </Select>
    <From>select distinct on(substr(o57_fonte,2,8)) substr(o57_fonte,2,8) as codigo_conta,o57_descr as descricao_conta from orcfontes where o57_anousu = fc_getsession(''DB_anousu'')::int</From>
    <Where/>
    <Group></Group>
    <Order>
     <Ordem id="1" nome="codigo_conta" ascdesc="asc" alias="codigo_conta"/>
    </Order>
   </Consulta>
  </Consultas>
 </Relatorio>' where db63_nomerelatorio = 'Exportação das Naturezas da Receita';


update configuracoes.db_relatorio set db63_xmlestruturarel = ' <?xml version="1.0" encoding="ISO-8859-1"?>
 <Relatorio>
  <Versao>1.0</Versao>
  <Propriedades versao="1.0" nome="Exportação das Naturezas de Despesa" layout="dbseller" formato="A4" orientacao="portrait" margemsup="0" margeminf="0" margemesq="20" margemdir="20" tiposaida="csv"/>
  <Cabecalho></Cabecalho>
  <Rodape></Rodape>
  <Campos>
   <Campo id="1" nome="codigo_conta" alias="Código da Conta" largura="20" alinhamento="c" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
   <Campo id="2" nome="descricao_conta" alias="Descrição da Conta" largura="20" alinhamento="c" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  </Campos>
  <Consultas>
   <Consulta tipo="Principal">
    <Select>
     <Campo id="1"/>
     <Campo id="2"/>
    </Select>
    <From>select distinct on(substr(o56_elemento,2,8)) substr(o56_elemento,2,8) as codigo_conta,o56_descr as descricao_conta from orcelemento where o56_anousu = fc_getsession(''DB_anousu'')::int</From>
    <Where/>
    <Group></Group>
    <Order>
     <Ordem id="1" nome="codigo_conta" ascdesc="asc" alias="codigo_conta"/>
    </Order>
   </Consulta>
  </Consultas>
 </Relatorio>
' where db63_nomerelatorio = 'Exportação das Naturezas de Despesa';
SQL
);
    }
}
