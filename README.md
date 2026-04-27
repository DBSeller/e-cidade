# e-Cidade - Ambiente e Base de Dados

Bem-vindo ao **e-Cidade** rodando em containers Docker.  
Este projeto foi preparado para simplificar ao máximo a instalação e execução da aplicação. 🚀

### Instalação do Docker
 - [docker](https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/)
 - [docker-compose](https://docs.docker.com/compose/install/#install-compose)

&nbsp;

---

## 📥 1. Clonar o projeto

Clone este repositório na sua máquina:  

```bash
git clone http://softwarepublico.gov.br/gitlab/e-cidade/e-cidade.git
```
Entre na pasta do projeto
```bash
cd e-cidade
```

## 🐳 2. Subir os containers

Deverá ser executado o `docker-compose` da raiz do e-cidade.

Todos os containers possui um volume mapeado da raiz do e-cidade para a pasta `/var/www/html/ de cada container.

Construa e inicie os serviços com:

```bash
docker-compose up -d --build
```

## 🛠️ Passo 3 – Descompacte o banco de dados

Execute o comando para descompactar o banco de dados:

```bash
docker exec -it ecidade_php56 gunzip docker/database/ecidade_base.sql.gz
```

## 🛠️ Passo 4 – Executar o Script de Instalação

Execute o script de instalação dentro do container PHP:

```bash
docker exec -it ecidade_php56 bash docker/install.sh
```

## 🛠️ Passo 5 Ajustar permissões do e-Cidade

Deverá ser executado na raiz do e-cidade.
Para garantir a execução correta, aplique as permissões necessárias:

```bash
docker exec -it ecidade_php56 chmod -R 775 /var/www/html
```

## 🔑 Passo 6 – Acessar a Aplicação

Após a instalação, acesse o sistema nos endereços:

- [http://localhost:8080](http://localhost:8080)
- [http://127.0.0.1:8080](http://127.0.0.1:8080)

**Credenciais de Acesso:**

- **Login:** `dbseller`
- **Senha:** `dbseller`

&nbsp;

---

## 🧰 Solução de Problemas

- **Porta 8080 em uso?**  
  Altere no `docker-compose.yml` de `8080:80` para outra, como `8081:80`, e acesse via [http://localhost:8081](http://localhost:8081).

- **Script `install.sh` não encontrado?**  
  Verifique se você está no diretório certo e se o script está localizado em `docker/install.sh`.

- **Erro de permissão no script?**  
  Rode o comando abaixo para garantir permissão de execução:

  ```bash
  chmod +x docker/install.sh
  ```
- **Precisa refazer o e-Cidade e o banco de dados?**  
  Rode o comando abaixo para remover(-v: Volumes,--rmi all: Imagens) e após rode o passo 4 e 5:

  ```bash
  docker-compose down -v --rmi all
  ```
  
## Estrutura das imagens

```
                              +----------+
                              |  browser |          
                              +----------+
                                |       |
                                |       |
                            +----------------+  
                            | localhost:8080 |
                            +----------------+  
                                |       |               
                                |       |             
                            +----------------+            
                            | ecidade-apache |            
                            +----------------+            
                                |        |                
                                |        |         
                            ____|        |_____           
                          /                   \          
                          /                     \         
                  +------------------------------------+
                  |           POSTGRES 5432            |
                  +------------------------------------+
```
