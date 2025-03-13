<?php

use Classes\PostgresMigration;

class M13329TipoSaldoMsc extends PostgresMigration
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

        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");

        $this->execute("
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010429 ,'c125_tiposaldo' ,'int4' ,'TIpo do Saldo' ,'3' ,'TIpo do Saldo' ,10,'true' ,'false' ,'false' ,1 ,'text' ,'TIpo do Saldo' );
        delete from db_syscampodef where codcam = 1010429;
        insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 1010429 ,'1' ,'MSC' );
        insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 1010429 ,'2' ,'Ecidade' );
        insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 1010429 ,'3' ,'Conta corrente' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010339 ,1010429 ,10 ,0 );");
        $this->execute("alter table conplanoatributosaldo add c125_tiposaldo integer");
        $this->execute("update conplanoatributosaldo set c125_tiposaldo = 1 where c125_conplanosistema = 1");
        $this->execute("insert into  conplanoatributosaldo select nextval('conplanoatributosaldo_c125_sequencial_seq'), c125_anousu, c125_mesusu, c125_hashcontaatributos, c125_valor, c125_natureza, c125_tipo, c125_conplanosistema, c125_instit, 2 from conplanoatributosaldo where c125_tiposaldo = 1 and  c125_conplanosistema = 1");
        $this->execute("update conplanoatributosaldo set c125_tiposaldo = 3 where c125_conplanosistema <> 1");

        $this->execute(<<<SQL

update conplanoinfocomplementar 
                       set c121_sql = 'select
  case when fc_getsession(''DB_anousu'')::int <= 2018 then
   (SELECT distinct (
                        CASE 
                             WHEN c74_codlan IS NOT NULL AND c53_tipo in(100, 101) THEN lpad(orcreceita.o70_codigo::varchar, 4, ''0'')
                             WHEN c75_codlan IS NOT NULL AND c71_coddoc not in (6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) AND c53_tipo in(30, 31) THEN lpad(c61_codigo::varchar, 4, ''0'')
                             WHEN c71_coddoc in ( 6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) THEN lpad(dotemp.o58_codigo::varchar, 4, ''0'')
                             WHEN c75_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN lpad(dotemp.o58_codigo::varchar, 4, ''0'')         
                             WHEN c73_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN lpad(dotlan.o58_codigo::varchar, 4, ''0'')         
                             WHEN c74_codrec IS NOT NULL and dotrec.o58_codigo is not null THEN lpad(dotrec.o58_codigo::varchar, 4, ''0'')   
                             WHEN c74_codrec IS NOT NULL THEN lpad(o70_codigo::varchar, 4, ''0'')                                            
                             WHEN recursopagdebito.c61_reduz IS NOT NULL THEN lpad(c61_codigo::varchar, 4, ''0'')                            
                             ELSE (SELECT lpad(c61_codigo::varchar, 4, ''0'')                                                                
                                   FROM conplanoreduz                                                                                      
                                   WHERE c61_reduz = conta_reduzida                                                                     
                                   AND c61_anousu = anousu)                                                                                
                        END
                    ) AS infocomplementar_valor
               FROM conlancam                                                                                                             
                    INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                                                                   
                    INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc                                                                   
                    LEFT JOIN conlancamemp ON c75_codlan = c70_codlan                                                                   
                    LEFT JOIN empempenho empemp1 ON c75_numemp = empemp1.e60_numemp                                                    
                    LEFT JOIN orcdotacao dotemp ON empemp1.e60_coddot = dotemp.o58_coddot                                                
                                               AND empemp1.e60_anousu = dotemp.o58_anousu                                               
                    LEFT JOIN conlancamdot ON c73_codlan = c70_codlan                                                                    
                    LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot                                                        
                                                AND c73_anousu = dotlan.o58_anousu                                                       
                    LEFT JOIN conlancamrec ON c74_codlan = c70_codlan                                                                    
                    LEFT JOIN orcreceita ON c74_codrec = o70_codrec                                                                      
                                        AND c74_anousu = o70_anousu                                                                     
                    LEFT JOIN conlancampag ON c82_codlan = c70_codlan                                                                    
                    LEFT JOIN conplanoreduz AS recursopagdebito ON c82_reduz = recursopagdebito.c61_reduz                                
                                                               AND c82_anousu = recursopagdebito.c61_anousu                             
                    LEFT JOIN conlancamcorrente conlancorr1 ON conlancorr1.c86_conlancam =  c70_codlan                                   
                    LEFT JOIN corgrupocorrente corgrpcor1 ON corgrpcor1.k105_data = conlancorr1.c86_data                                 
                                                         AND corgrpcor1.k105_autent = conlancorr1.c86_autent                           
                                                         AND corgrpcor1.k105_id = conlancorr1.c86_id                                   
                                                         AND corgrpcor1.k105_corgrupotipo = 3                                          
                    LEFT JOIN corgrupocorrente corgrpcor2 ON corgrpcor2.k105_corgrupo = corgrpcor1.k105_corgrupo                         
                                                         AND corgrpcor2.k105_corgrupotipo = 1                                           
                    LEFT JOIN coremp ON k12_id = corgrpcor2.k105_id                                                                 
                                    AND k12_data = corgrpcor2.k105_data                                                               
                                    AND k12_autent = corgrpcor2.k105_autent                                                             
                    LEFT JOIN empempenho empemp2 ON  k12_empen = empemp2.e60_numemp                                                      
                    LEFT JOIN orcdotacao dotrec ON empemp2.e60_coddot = dotrec.o58_coddot                                               
                                               AND empemp2.e60_anousu = dotrec.o58_anousu                                              
                                                                                                                                         
              WHERE c70_codlan = codigo_lancamento)

when fc_getsession(''DB_anousu'')::int > 2018 then
 (select c130_orctiporec::varchar from conlancamrecurso where c130_conlancam = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza limit 1)
end  as infocomplementar_valor
'
                     where c121_sequencial = 3
SQL
        );
    }

    /**
     *
     */
    public function down()
    {
        $this->execute("delete from db_syscampodef where codcam = 1010429");
        $this->execute("delete from db_sysarqcamp where codcam = 1010429");
        $this->execute("delete from db_syscampo where codcam = 1010429");
        $this->execute("alter table conplanoatributosaldo drop c125_tiposaldo;");
    }
}
