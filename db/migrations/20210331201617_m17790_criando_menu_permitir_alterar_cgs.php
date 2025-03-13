<?php

use Classes\PostgresMigration;

class M17790CriandoMenuPermitirAlterarCgs extends PostgresMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        //Esta migration tem a funcionalidade de dar permissão no novo menu criado para todos que tem permissão no menu de manutenção de CGS.

        $itensManutencaoCgs = "10239, 228438";
        $usuarios = $this->query("SELECT DISTINCT id_usuario, permissaoativa, anousu, id_instit FROM db_permissao WHERE id_item IN ($itensManutencaoCgs) AND anousu = extract(YEAR FROM current_date);")->fetchAll();
        $moduloAmbulatorial = 1000004;
        $menuProcedimentosId = 1818;
        $itemCriadoId = 228479;

        $this->execute(
            <<<SQL
                INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( $itemCriadoId ,'Permitir alterar CGS' ,'Permitir alterar CGS' ,'' ,'1' ,'1' ,'Este menu tem a funcionalidade de permitir ao usuário a alteração e exclusão do cgs.' ,'true' );
                INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( $menuProcedimentosId ,$itemCriadoId ,137 ,$moduloAmbulatorial );
SQL
        );
        
        
        foreach ($usuarios as $usuario){
            $usuarioId = $usuario['id_usuario'];
            $permissaoAtiva = $usuario['permissaoativa'];
            $anousu = $usuario['anousu'];
            $instituicaoId = $usuario['id_instit'];

            $permisaoModulo = $this->query("SELECT * FROM db_permissao WHERE id_usuario = $usuarioId AND id_item = $moduloAmbulatorial AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;")->fetch(PDO::FETCH_ASSOC);
            
            if(!$permisaoModulo){
                $this->execute(
                    <<<SQL
                        INSERT INTO db_permissao( id_usuario, id_item, permissaoativa, anousu, id_instit, id_modulo) 
                        VALUES ( $usuarioId, $moduloAmbulatorial, $permissaoAtiva, $anousu, $instituicaoId, $moduloAmbulatorial);
    
SQL
                );
            }

            $permisaoMenuProcedimento = $this->query("SELECT * FROM db_permissao WHERE id_usuario = $usuarioId AND id_item = $menuProcedimentosId AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;")->fetch(PDO::FETCH_ASSOC);

            if(!$permisaoMenuProcedimento){
                $this->execute(
                    <<<SQL
                        INSERT INTO db_permissao( id_usuario, id_item, permissaoativa, anousu, id_instit, id_modulo) 
                        VALUES ( $usuarioId, $menuProcedimentosId, $permissaoAtiva, $anousu, $instituicaoId, $moduloAmbulatorial);
    
SQL
                );
            }

            $permisaoMenuCriado = $this->query("SELECT * FROM db_permissao WHERE id_usuario = $usuarioId AND id_item = $itemCriadoId AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;")->fetch(PDO::FETCH_ASSOC);

            if(!$permisaoMenuCriado){
                $this->execute(
                    <<<SQL
                        INSERT INTO db_permissao( id_usuario, id_item, permissaoativa, anousu, id_instit, id_modulo) 
                        VALUES ( $usuarioId, $itemCriadoId, $permissaoAtiva, $anousu, $instituicaoId, $moduloAmbulatorial);
SQL
                );

            }
        }
    }

    public function down()
    {
        $itemCriadoId = 228479;
        $itemProcedimentosId = 1818;
        $moduloId = 1000004;

        $usuarios = $this->query("SELECT DISTINCT * FROM db_permissao WHERE id_item IN ($itemCriadoId)")->fetchAll(PDO::FETCH_ASSOC);

        $this->execute(
            <<<SQL
                DELETE FROM db_permissao WHERE id_item = $itemCriadoId;
SQL
        );


        foreach ($usuarios as $usuario){
            $usuarioId = $usuario['id_usuario'];
            $instituicaoId = $usuario['id_instit'];

            $permissoesModulo = $this->query("SELECT * FROM db_permissao WHERE id_modulo = $moduloId AND id_usuario = $usuarioId AND id_item != $itemProcedimentosId AND id_item != $moduloId AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;")->fetchAll();
            
            if(!$permissoesModulo){
                $this->execute(
                    <<<SQL
                        DELETE FROM db_permissao WHERE id_usuario = $usuarioId AND id_modulo = $moduloId AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;
SQL
                );
            }else{
                $menus = $this->query("SELECT id_item FROM db_permissao WHERE id_modulo = $moduloId AND id_usuario = $usuarioId AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;")->fetchAll(PDO::FETCH_COLUMN, 0);

                $menus = implode(',', $menus);

                $menus = $this->query("SELECT * FROM db_menu WHERE id_item_filho IN ( $menus ) and id_item = $itemProcedimentosId")->fetchAll(PDO::FETCH_ASSOC);
                
                if(!$menus){
                    $this->execute(
                        <<<SQL
                            DELETE FROM db_permissao WHERE id_usuario = $usuarioId AND id_modulo = $moduloId AND id_item = $itemProcedimentosId AND anousu = extract(YEAR FROM current_date) AND id_instit = $instituicaoId;
SQL
                    );
                }
            }


        }
            
        $this->execute(
            <<<SQL
                DELETE FROM db_menu WHERE id_item_filho = $itemCriadoId AND modulo = $moduloId;
                DELETE FROM db_itensmenu WHERE id_item = $itemCriadoId;
SQL
        );

    }
}
