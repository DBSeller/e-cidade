<?php

use Classes\PostgresMigration;

class M13323UsuarioSlip extends PostgresMigration
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

        $this->execute("insert into db_syscampo values(1010475,'k17_idusuario','int4','Código do Usuário','null', 'Código do Usuário',10,'t','f','f',1,'text','Código do Usuário');");
        $this->execute("insert into db_sysarqcamp values(196,1010475,16,0);");
        $this->execute("alter table slip add k17_idusuario integer");

        /**
         * as mudanças abaixo apenas devem ir para niteroi.
         */
        $rsInstituicao = $this->query("select db21_codcli from configuracoes.db_config where db21_codcli = 7107");
        if ($rsInstituicao->rowCount() == 0) {;
            return;
        }

        $codigoParagrafo = "// Assinatura padrao

\$this->objpdf->rect(\$xcol, \$xlin +160, \$xcol +198, 40, 10, ''DF'', ''1234'');
\$this->objpdf->Setfont(''Arial'', ''B'', 6);
\$this->objpdf->text(\$xcol +7, \$xlin +165, ''SUPERINTENDÊNCIA DE FINANÇAS'');
\$this->objpdf->text(\$xcol +2, \$xlin +180, ''_______________________________________'');
\$this->objpdf->text(\$xcol +18, \$xlin +183, ''Conferido'');
\$this->objpdf->text(\$xcol +73, \$xlin +165, ''PAGUE-SE'');
\$this->objpdf->text(\$xcol +76, \$xlin +168, ''Data'');
\$this->objpdf->text(\$xcol +63, \$xlin +173, ''________ /________ /__________'');
\$this->objpdf->text(\$xcol +52, \$xlin +180, ''________________________________________________'');
\$this->objpdf->setXY(\$xcol +52, \$xlin +181);
\$this->objpdf->cell(56.8, 3, \$ass_sec, 0, 0, \"C\", 0);
\$this->objpdf->setXY(\$xcol +52, \$xlin +184);
\$this->objpdf->setXY(\$xcol +52, \$xlin +193);
\$this->objpdf->setXY(\$xcol +52, \$xlin +196);
\$this->objpdf->line(\$xcol +110, \$xlin +200, \$xcol +110, \$xlin +160);
\$this->objpdf->text(\$xcol +148, \$xlin +165, ''TESOURARIA'');
\$this->objpdf->text(\$xcol +114, \$xlin +170, ''Banco'');
\$this->objpdf->text(\$xcol +123, \$xlin +173, ''__________________________________________________________________'');
\$this->objpdf->text(\$xcol +114, \$xlin +180, ''Cheque'');
\$this->objpdf->text(\$xcol +123, \$xlin +183, ''__________________________________________________________________'');
\$this->objpdf->text(\$xcol +114, \$xlin +193, ''Data'');
\$this->objpdf->text(\$xcol +120, \$xlin +195, ''________ /________ /__________'');
\$this->objpdf->text(\$xcol +154, \$xlin +192, ''_______________________________________'');
\$this->objpdf->setXY(\$xcol +154.3, \$xlin +193);
\$this->objpdf->cell(46,3,\$ass_tes,0,0,\"C\",0);
\$this->objpdf->setXY(\$xcol +154.3, \$xlin +199);
\$this->objpdf->cell(46,-3,\$ass_tesFunc,0,0,\"C\",0);
\$this->objpdf->text(\$xcol + 3, \$xlin +199, ''Emitido por: ''.\$nomeUsuario);
// Recibo

\$this->objpdf->rect(\$xcol, \$xlin +200, \$xcol +198, 55, 10, ''DF'', ''1234'');
\$this->objpdf->SetFont(''Arial'', '''', 7);
\$this->objpdf->text(\$xcol +90, \$xlin +205, ''R E C I B O'');
\$this->objpdf->text(\$xcol +45, \$xlin +215, ''RECEBI(EMOS) DA ''.\$this->nomeinst.'', A IMPORTÂNCIA ACIMA ESPECIFICADA.'');
\$this->objpdf->text(\$xcol +4, \$xlin +235, ''R\$_________________________'');
\$this->objpdf->text(\$xcol +110, \$xlin +235, ''R\$_________________________'');
\$this->objpdf->text(\$xcol +45, \$xlin +235, ''EM ________/________/________'', 0, 0, ''C'', 0);
\$this->objpdf->text(\$xcol +150, \$xlin +235, ''EM ________/________/________'', 0, 0, ''C'', 0);
\$this->objpdf->text(\$xcol +75, \$xlin +245, ''_________________________________________'', 0, 1, ''C'', 0);
\$this->objpdf->SetFont(''Arial'', '''', 6);
\$this->objpdf->text(\$xcol +98, \$xlin +250, ''CREDOR'', 0, 1, ''C'', 0);";
        $this->execute("update db_paragrafo set db02_texto = '{$codigoParagrafo}' where db02_idparag = 179;");

    }

    public function down()
    {
        $this->execute("delete from db_sysarqcamp where codcam = 1010475");
        $this->execute("delete from db_syscampo where codcam = 1010475;");
        $this->execute("alter table slip drop k17_idusuario;");
    }
}
