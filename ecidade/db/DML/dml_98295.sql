-- CADASTRO > PROCEDIMENTOS > PARCELA ÚNICA
-- CADASTRO > PROCEDIMENTOS > PARCELA ÚNICA > INCLUSÃO GERAL DE PARCELA ÚNICA
-- CADASTRO > PROCEDIMENTOS > PARCELA ÚNICA > PRORROGA PARCELA ÚNICA
delete from db_itensfilho where id_item = 1577;
delete from db_itensmenu where id_item in(1150593, 1679, 1577);

-- CADASTRO > PROCEDIMENTOS > GERAR TXTS MULTI FINALITÁRIO
delete from db_itensfilho where id_item = 5037;
delete from db_itensmenu where id_item = 5037;

-- CADASTRO > PROCEDIMENTOS > CARTÃO NUMÉRICO
delete from db_itensmenu where id_item = 4058;

-- DIVIDA ATIVA > PROCEDIMENTOS > TEXTOS DAS CERTIDÕES > ...
delete from db_itensfilho where id_item in(1630, 1631, 1632, 1627, 1628, 1629, 1633, 1634, 1635, 1637, 1641, 1642, 1643, 1638, 1639, 1640, 1644, 1645, 1646, 1647, 1651, 1652, 1653, 1648, 1649, 1650, 1654, 1655, 1656, 4133,4134,4135);
delete from db_itensmenu where id_item in(1636, 1626, 1630, 1631, 1632, 1627, 1628, 1629, 1633, 1634, 1635, 1637, 1641, 1642, 1643, 1638, 1639, 1640, 1644, 1645, 1646, 1647, 1651, 1652, 1653, 1648, 1649, 1650, 1654, 1655, 1656, 4132, 4133,4134,4135);

-- DIVIDA ATIVA > PROCEDIMENTOS > TEXTOS DO TERMO DE PARCELAMENTO > ...
delete from db_itensfilho where id_item = 5093;
delete from db_itensmenu where id_item in(1619, 5093, 1620, 1621, 1622, 1623, 1624) ;

-- DIVIDA ATIVA > PROCEDIMENTOS > TEXTOS DA PETIÇÃO INICIAL > ...
delete from db_itensfilho where id_item in(1603, 1612, 5073);
delete from db_itensmenu where id_item in(1598, 1599, 1600, 1601, 1602, 1611, 5073, 1603, 1612, 1604, 1605, 1606, 1607, 1608, 1609, 1610);

-- DIVIDA ATIVA > PROCEDIMENTOS > ASSINATURAS > ...
delete from db_itensfilho where id_item = 1670;
delete from db_itensmenu where id_item in(1618, 1615, 1616, 1617, 1613, 1614, 1625, 1657, 1670);

-- JURÍDICO > PROCEDIMENTOS > GERA PETIÇÃO > PARCELAMENTO
-- JURÍDICO > PROCEDIMENTOS > GERA PETIÇÃO > INICIAL QUITADA
delete from db_itensfilho where id_item in(2106, 4532);
delete from db_itensmenu where id_item in(2106, 4532);

-- NOTIFICAÇÃO > PROCEDIMENTOS > CONFIGURAÇÃO DE DOCUMENTO POR TIPO
delete from db_itensfilho where id_item = 6869;
delete from db_itensmenu where id_item = 6869;