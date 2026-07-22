#!/usr/bin/env bash

sudo umount /dados/www/e-cidade.portovelho.ro.gov.br/integracao_externa/luckman/entrada
sudo umount /dados/www/e-cidade.portovelho.ro.gov.br/integracao_externa/luckman/saida

mkdir -p /dados/www/e-cidade.portovelho.ro.gov.br/integracao_externa/luckman
mkdir -p /dados/www/e-cidade.portovelho.ro.gov.br/integracao_externa/luckman/saida
mkdir -p /dados/www/e-cidade.portovelho.ro.gov.br/integracao_externa/luckman/entrada

mount -t cifs -o username=dbseller,password=dbseller,uid=1001,gid=33,file_mode=0664,dir_mode=0775 '\\10.139.1.21\XMLRequisicao' /dados/www/e-cidade.portovelho.ro.gov.br/ecidade-luckman/integracao_externa/luckman/saida
mount -t cifs -o username=dbseller,password=dbseller,uid=1001,gid=33,file_mode=0664,dir_mode=0775 '\\10.139.1.21\XMLResultado' /dados/www/e-cidade.portovelho.ro.gov.br/ecidade-luckman/integracao_externa/luckman/entrada
