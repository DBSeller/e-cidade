<?php

use Classes\PostgresMigration;

class M15986RelatorioHomologacaoProcesso extends PostgresMigration
{
    public function up()
    {
        $pdf =
        $this->execute(<<<SQL_UP
update db_paragrafopadrao
   set db61_texto = '\$oPDF->ln();
\$campos = "distinct liclicitem.*, pc01_descrmater, pc11_resum";
\$where = array(
    "l21_codliclicita = {\$l20_codigo}",
    "pc21_orcamforne <> 0",
    "not exists(select 1 from pcorcamdescla where pc32_orcamitem = pc23_orcamitem and pc32_orcamforne = pc23_orcamforne)"
);
\$sql = \$clliclicitem->sql_query_inf(null,\$campos, "l21_ordem",implode(" AND ", \$where));
\$result_dot = \$clliclicitem->sql_record(\$sql);

if (\$clliclicitem->numrows > 0) {
    for (\$w = 0; \$w < \$clliclicitem->numrows; \$w++) {
        db_fieldsmemory(\$result_dot, \$w);
        \$oPDF->setfont("arial", "b", 8);
        if (\$cor == 0) {
            \$cor = 1;
        } else {
            \$cor = 0;
        }
        \$oPDF->cell(10, \$alt, "", 0, 0, "C", \$cor);
        \$oPDF->multicell(0, \$alt, "Item {\$l21_ordem} *- {\$pc01_descrmater} - {\$pc11_resum}", 0, "L", \$cor);
        \$sql = \$clpcorcamitemlic->sql_query_file(null, " * ", null, " pc26_liclicitem = {\$l21_codigo}");
        \$result_itemlic = \$clpcorcamitemlic->sql_record(\$sql);

        if (\$clpcorcamitemlic->numrows > 0) {
            db_fieldsmemory(\$result_itemlic, 0);

            \$campos = " z01_numcgm, z01_nome";
            \$where = " pc24_orcamitem = {\$pc26_orcamitem} and pc24_pontuacao = 1";
            \$sql = \$clpcorcamjulg->sql_query(null, null, \$campos, null, \$where);
            \$result_julg = \$clpcorcamjulg->sql_record(\$sql);
            \$iLinhas = \$clpcorcamjulg->numrows;
            if (\$clpcorcamjulg->numrows > 0) {
                for (\$i = 0; \$i < \$iLinhas; \$i++) {
                    db_fieldsmemory(\$result_julg, \$i);
                    \$oPDF->cell(20, \$alt, "", 0, 0, "C", \$cor);
                    \$oPDF->multicell(0, \$alt, "\$z01_numcgm - \$z01_nome", 0, "L", \$cor);
                }
            }
        }
    }
}
\$oPDF->ln();'
  from db_docparagpadrao
       inner join db_documentopadrao on db60_coddoc = db62_coddoc
 where db62_codparag = db61_codparag
   and db60_tipodoc = 1703
   and db62_ordem = 3;

update db_paragrafo
   set db02_texto = '\$oPDF->ln();
\$campos = "distinct liclicitem.*, pc01_descrmater, pc11_resum";
\$where = array(
    "l21_codliclicita = {\$l20_codigo}",
    "pc21_orcamforne <> 0",
    "not exists(select 1 from pcorcamdescla where pc32_orcamitem = pc23_orcamitem and pc32_orcamforne = pc23_orcamforne)"
);
\$sql = \$clliclicitem->sql_query_inf(null,\$campos, "l21_ordem",implode(" AND ", \$where));
\$result_dot = \$clliclicitem->sql_record(\$sql);

if (\$clliclicitem->numrows > 0) {
    for (\$w = 0; \$w < \$clliclicitem->numrows; \$w++) {
        db_fieldsmemory(\$result_dot, \$w);
        \$oPDF->setfont("arial", "b", 8);
        if (\$cor == 0) {
            \$cor = 1;
        } else {
            \$cor = 0;
        }
        \$oPDF->cell(10, \$alt, "", 0, 0, "C", \$cor);
        \$oPDF->multicell(0, \$alt, "Item {\$l21_ordem} *- {\$pc01_descrmater} - {\$pc11_resum}", 0, "L", \$cor);
        \$sql = \$clpcorcamitemlic->sql_query_file(null, " * ", null, " pc26_liclicitem = {\$l21_codigo}");
        \$result_itemlic = \$clpcorcamitemlic->sql_record(\$sql);

        if (\$clpcorcamitemlic->numrows > 0) {
            db_fieldsmemory(\$result_itemlic, 0);

            \$campos = " z01_numcgm, z01_nome";
            \$where = " pc24_orcamitem = {\$pc26_orcamitem} and pc24_pontuacao = 1";
            \$sql = \$clpcorcamjulg->sql_query(null, null, \$campos, null, \$where);
            \$result_julg = \$clpcorcamjulg->sql_record(\$sql);
            \$iLinhas = \$clpcorcamjulg->numrows;
            if (\$clpcorcamjulg->numrows > 0) {
                for (\$i = 0; \$i < \$iLinhas; \$i++) {
                    db_fieldsmemory(\$result_julg, \$i);
                    \$oPDF->cell(20, \$alt, "", 0, 0, "C", \$cor);
                    \$oPDF->multicell(0, \$alt, "\$z01_numcgm - \$z01_nome", 0, "L", \$cor);
                }
            }
        }
    }
}
\$oPDF->ln();'
  from db_docparag
       inner join db_documento on db03_docum = db04_docum
 where db02_idparag = db04_idparag
   and db03_tipodoc = 1703
   and db04_ordem = 3;
SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN
update db_paragrafopadrao
   set db61_texto = '\$oPDF->ln(); \$result_dot=\$clliclicitem->sql_record(\$clliclicitem->sql_query_inf(null,"distinct liclicitem.*,pc01_descrmater,pc11_resum","l21_ordem","l21_codliclicita=\$l20_codigo")); if (\$clliclicitem->numrows>0){ for(\$w=0;\$w<\$clliclicitem->numrows;\$w++){ db_fieldsmemory(\$result_dot,\$w); \$oPDF->setfont("arial","b",8); if (\$cor == 0) { \$cor = 1; } else { \$cor = 0; } \$oPDF->cell(10,\$alt,"",0,0,"C",\$cor); \$oPDF->multicell(0,\$alt,"Item ".\$l21_ordem." *- " . \$pc01_descrmater . " - " . \$pc11_resum,0,"L",\$cor); \$result_itemlic=\$clpcorcamitemlic->sql_record(\$clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=\$l21_codigo")); \$a = \$clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=\$l21_codigo"); if (\$clpcorcamitemlic->numrows > 0) { db_fieldsmemory(\$result_itemlic,0); \$result_julg=\$clpcorcamjulg->sql_record(\$clpcorcamjulg->sql_query(null,null," z01_numcgm, z01_nome",null," pc24_orcamitem = \$pc26_orcamitem and pc24_pontuacao = 1")); \$iLinhas = \$clpcorcamjulg->numrows; if ( \$clpcorcamjulg->numrows > 0) { for (\$i = 0; \$i < \$iLinhas; \$i++ ) { db_fieldsmemory(\$result_julg, \$i); \$oPDF->cell(20,\$alt,"",0,0,"C",\$cor); \$oPDF->multicell(0,\$alt,"\$z01_numcgm - \$z01_nome",0,"L",\$cor); } } } } } \$oPDF->ln();'
  from db_docparagpadrao
       inner join db_documentopadrao on db60_coddoc = db62_coddoc
 where db62_codparag = db61_codparag
   and db60_tipodoc = 1703
   and db62_ordem = 3;

update db_paragrafo
   set db02_texto = '\$oPDF->ln(); \$result_dot=\$clliclicitem->sql_record(\$clliclicitem->sql_query_inf(null,"distinct liclicitem.*,pc01_descrmater,pc11_resum","l21_ordem","l21_codliclicita=\$l20_codigo")); if (\$clliclicitem->numrows>0){ for(\$w=0;\$w<\$clliclicitem->numrows;\$w++){ db_fieldsmemory(\$result_dot,\$w); \$oPDF->setfont("arial","b",8); if (\$cor == 0) { \$cor = 1; } else { \$cor = 0; } \$oPDF->cell(10,\$alt,"",0,0,"C",\$cor); \$oPDF->multicell(0,\$alt,"Item ".\$l21_ordem." *- " . \$pc01_descrmater . " - " . \$pc11_resum,0,"L",\$cor); \$result_itemlic=\$clpcorcamitemlic->sql_record(\$clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=\$l21_codigo")); \$a = \$clpcorcamitemlic->sql_query_file(null," * ",null," pc26_liclicitem=\$l21_codigo"); if (\$clpcorcamitemlic->numrows > 0) { db_fieldsmemory(\$result_itemlic,0); \$result_julg=\$clpcorcamjulg->sql_record(\$clpcorcamjulg->sql_query(null,null," z01_numcgm, z01_nome",null," pc24_orcamitem = \$pc26_orcamitem and pc24_pontuacao = 1")); \$iLinhas = \$clpcorcamjulg->numrows; if ( \$clpcorcamjulg->numrows > 0) { for (\$i = 0; \$i < \$iLinhas; \$i++ ) { db_fieldsmemory(\$result_julg, \$i); \$oPDF->cell(20,\$alt,"",0,0,"C",\$cor); \$oPDF->multicell(0,\$alt,"\$z01_numcgm - \$z01_nome",0,"L",\$cor); } } } } } \$oPDF->ln();'
  from db_docparag
       inner join db_documento on db03_docum = db04_docum
 where db02_idparag = db04_idparag
   and db03_tipodoc = 1703
   and db04_ordem = 3;
SQL_DOWN
);
    }
}
