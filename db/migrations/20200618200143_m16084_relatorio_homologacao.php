<?php

use Classes\PostgresMigration;

class M16084RelatorioHomologacao extends PostgresMigration
{
    private $codigo = "\$oPDF->ln();
\$campos = \"distinct liclicitem.*, pc01_descrmater, pc11_resum\";
\$where = array(
\"l21_codliclicita = {\$l20_codigo}\",
\"pc21_orcamforne <> 0\"
);
\$sql = \$clliclicitem->sql_query_inf(null, \$campos, \"l21_ordem\",implode( \" AND \" , \$where));
\$result_dot = \$clliclicitem->sql_record(\$sql);

if (\$clliclicitem->numrows > 0) {
    for (\$w = 0; \$w < \$clliclicitem->numrows; \$w++) {
        db_fieldsmemory(\$result_dot, \$w);
        \$oPDF->setfont(\"arial\", \"b\", 8);
        if (\$cor == 0) {
            \$cor = 1;
        } else {
            \$cor = 0;
        }
        \$oPDF->cell(10, \$alt, \"\", 0, 0, \"C\", \$cor);
        \$oPDF->multicell(0, \$alt, \"Item {\$l21_ordem} *- {\$pc01_descrmater} - {\$pc11_resum}\", 0, \"L\", \$cor);
        \$sql = \$clpcorcamitemlic->sql_query_file(null, \" * \", null, \" pc26_liclicitem = {\$l21_codigo}\");
        \$result_itemlic = \$clpcorcamitemlic->sql_record(\$sql);
    
        if (\$clpcorcamitemlic->numrows > 0) {
            db_fieldsmemory(\$result_itemlic, 0);
    
            \$campos = \" z01_numcgm, z01_nome\";
            \$where = \" pc24_orcamitem = {\$pc26_orcamitem} and pc24_pontuacao = 1\";
            \$sql = \$clpcorcamjulg->sql_query(null, null, \$campos, null, \$where);
            \$result_julg = \$clpcorcamjulg->sql_record(\$sql);
            \$iLinhas = \$clpcorcamjulg->numrows;
            if (\$clpcorcamjulg->numrows > 0) {
                for (\$i = 0; \$i < \$iLinhas; \$i++) {
                    db_fieldsmemory(\$result_julg, \$i);
                    \$oPDF->cell(20, \$alt, \"\", 0, 0, \"C\", \$cor);
                    \$oPDF->multicell(0, \$alt, \"\$z01_numcgm - \$z01_nome\", 0, \"L\", \$cor);
                }
            }
        }
    }
}
\$oPDF->ln();";

    public function up()
    {
        $this->upDocumentoParagrafo();
        $this->upDocumentoPadrao();
    }

    private function upDocumentoParagrafo()
    {
        $sql = "update db_paragrafo ";
        $sql .= "   set db02_texto = '{$this->codigo}' ";
        $sql .= "  from db_docparag ";
        $sql .= "       inner join db_documento on db03_docum = db04_docum ";
        $sql .= " where db02_idparag = db04_idparag ";
        $sql .= "   and db03_tipodoc = 1703 ";
        $sql .= "   and db04_ordem = 3;";

        $this->execute($sql);
    }

    private function upDocumentoPadrao()
    {
        $sql = "update db_paragrafopadrao ";
        $sql .= "   set db61_texto = '{$this->codigo}' ";
        $sql .= "  from db_docparagpadrao ";
        $sql .= "       inner join db_documentopadrao on db60_coddoc = db62_coddoc ";
        $sql .= " where db62_codparag = db61_codparag ";
        $sql .= "   and db60_tipodoc = 1703 ";
        $sql .= "   and db62_ordem = 3;";

        $this->execute($sql);
    }

    public function down()
    {
    }
}

