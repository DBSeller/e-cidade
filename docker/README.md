# Container para e-cidade

Deverá ser executado o `docker-compose` da raiz do e-cidade.

Todos os containers possui um volume mapeado da raiz do e-cidade para a pasta `/var/www/html/ de cada container.

## Executando os containers

```$ docker-compose up -d```

## Parando os containers

```$ docker-compose down```

## Atualizando as imagens

```$ docker-compose pull```

## Build

```$ docker-compose up -d --build```

## Estrutura das imagens

```
            +----------+
            |  browser |          
            +----------+
             /        \
            /          \
+----------------+  +----------------+  
| localhost:8053 |  | localhost:8056 |  
+----------------+  +----------------+  
            \           /               
             \         /                
          +----------------+            
          | ecidade-apache |            
          +----------------+            
              |        |                
            :8053    :8056              
          ____|        |_____           
         /                   \          
        /                     \         
+----------------+   +----------------+
| ecidade-fpm53  |   | ecidade-fpm56  |
+----------------+   +----------------+
```

## Instalação

 - [docker](https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/)
 - [docker-compose](https://docs.docker.com/compose/install/#install-compose)
