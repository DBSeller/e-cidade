<?php

use Classes\PostgresMigration;

class M15349AnexoIii extends PostgresMigration
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
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,24 ,'irrf sobre rendimento do trabalho' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'irrf sobre rendimento do trabalho' ,'f' ,'f' ,29 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,25 ,'contribuições para custeio dos sistemas previdenciário e ass' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'contribuições para custeio dos sistemas previdenciário e assistencial;' ,'f' ,'f' ,30 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,26 ,'compensação previdenciária ao rpps' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'compensação previdenciária ao rpps' ,'f' ,'f' ,31 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,27 ,'receitas do rpps - remunerações e outras receitas' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'receitas do rpps - remunerações e outras receitas' ,'f' ,'f' ,32 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,28 ,'receitas do fundo de assistência social dos servidores' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'receitas do fundo de assistência social dos servidores' ,'f' ,'f' ,33 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,29 ,'receitas do fundo de assistência à saúde do servidor' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'receitas do fundo de assistência à saúde do servidor' ,'f' ,'f' ,34 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,30 ,'outras contribuições sociais' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'outras contribuições sociais' ,'f' ,'f' ,35 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,31 ,'(+) perda para o fundeb' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'(+) perda para o fundeb' ,'f' ,'f' ,36 ,2 ,'' ,'f' ,1 );
insert into orcparamseq( o69_codparamrel ,o69_codseq ,o69_descr ,o69_grupo ,o69_grupoexclusao ,o69_nivel ,o69_libnivel ,o69_librec ,o69_libsubfunc ,o69_libfunc ,o69_verificaano ,o69_labelrel ,o69_manual ,o69_totalizador ,o69_ordem ,o69_nivellinha ,o69_observacao ,o69_desdobrarlinha ,o69_origem ) values ( 178 ,32 ,'Trans. Advindas de Emendas Parl. Ind.(§13, Art 166, CF)' ,1 ,0 ,0 ,'f' ,'f' ,'f' ,'f' ,'f' ,'Transferências Advindas de Emendas Parlamentares Individuais (§ 13 do artigo 166 da CF)' ,'f' ,'f' ,37 ,2 ,'' ,'f' ,1 );

SQL
        );
    }

    /**
     *
     */
    public function down()
    {
        $this->execute("delete from orcparamseq where o69_codparamrel = 178 and o69_codseq between 24 and 31");
    }
}
