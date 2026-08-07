<?php

use Classes\PostgresMigration;

class M12227IncluirRecursoPadraoNaoInformado extends PostgresMigration
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


        /**
         * verificar se nao existe um recurso com a estrutura o codigo 0 .
         * caso nao exista, devemos incluir um registro na tabela estrutura valor
         */

        $rsRecurso= $this->query("select * from orctiporec where o15_codigo = 0");
        if ($rsRecurso->rowCount() > 0) {
            return;
        }

        /**
         * veriicar qual o codigo da estrutura do recurso
         */
        $dadosParametros = $this->query("select o50_estruturarecurso from orcparametro where o50_anousu = ".date("Y"));
        if ($dadosParametros->rowCount() == 0) {
            return;
        }
        $codigoEstrutura = $dadosParametros->fetchColumn(0);
        $rsEstruturaValor = $this->query("
        
        insert into db_estruturavalor
         (db121_sequencial,        
          db121_db_estrutura,      
          db121_estrutural,        
          db121_descricao,         
          db121_estruturavalorpai, 
          db121_nivel,             
          db121_tipoconta         
        )
         values (
         nextval('db_estruturavalor_db121_sequencial_seq'),
         {$codigoEstrutura},
         '000', 
         'Não informado', 
         null, 
         1,
         1
        ) returning db121_sequencial;
        ");
         $codigoEstruturaValor = $rsEstruturaValor->fetchColumn(0);

         $insert = "insert into orctiporec (
                 o15_codigo,               
                 o15_descr,                
                 o15_codtri,               
                 o15_finali,               
                 o15_tipo,                 
                 o15_datalimite,           
                 o15_db_estruturavalor,    
                 o15_codigosiconfi,        
                 o15_loaidentificadoruso,  
                 o15_loatipo,
                 o15_loagrupo,             
                 o15_loaespecificacao     
           ) values (
             0, 
             'NAO INFORMADO',
             '000', 
             'NÃO INFORMADO', 
             1, 
             NULL, 
             {$codigoEstruturaValor}, 
             null, 
             
             0,
             0,
             0, 
             '00')";

         $this->execute($insert);


    }
}
