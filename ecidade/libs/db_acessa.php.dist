<?php

$sql = "
    SELECT db30_codversao AS ultimaversao, db30_codrelease AS ultimarelease
    FROM db_versao
    ORDER BY db30_codver DESC
    LIMIT 1
";

$rs = db_query($sql);

if (!$rs) {
    throw new Exception(pg_last_error());
}

$db_fonte_codversao = (string)pg_fetch_result($rs, 0, 'ultimaversao');
$db_fonte_codrelease = (string)pg_fetch_result($rs, 0, 'ultimarelease');
