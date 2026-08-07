<?php

use Classes\PostgresMigration;

class M18538AjusteCampoCodigoAprovacao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            ALTER TABLE caixa.operacoesrealizadastef ALTER COLUMN k198_codigoaprovacao TYPE TEXT;
            update db_syscampo set nomecam = 'k198_codigoaprovacao', conteudo = 'text', descricao = 'Código da aprovação retornado pelo CTFClient', valorinicial = '0', rotulo = 'Código Aprovação', nulo = 'f', tamanho = 50, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código Aprovação' where codcam = 1013231;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            ALTER TABLE caixa.operacoesrealizadastef ALTER COLUMN k198_codigoaprovacao TYPE INTEGER USING (k198_codigoaprovacao::integer);
            update db_syscampo set nomecam = 'k198_codigoaprovacao', conteudo = 'text', descricao = 'Código da aprovação retornado pelo CTFClient', valorinicial = '0', rotulo = 'Código Aprovação', nulo = 'f', tamanho = 50, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'int4', rotulorel = 'Código Aprovação' where codcam = 1013231;
SQL
        );
    }
}
