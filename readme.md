# Simple Laravel Chat

## Inicializando o projeto

Para inicializar este projeto, deve-se seguir os seguintes passos:

```shell
git clone https://github.com/nogenem/chat-laravel.git
cd chat-laravel/laradock
cp env-example .env
sudo docker-compose up -d workspace nginx mysql redis phpmyadmin laravel-echo-server
sudo docker-compose exec workspace bash
composer install
cp .env.example .env
php artisan key:generate
yarn
exit
sudo docker-compose exec mysql bash
mysql -u root -p
root
CREATE DATABASE chat_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit
exit
sudo docker-compose exec workspace bash
php artisan migrate
php artisan db:seed    # opcional, irá gerar 10 usuários e 50 mensagens aleatórias entre eles
exit
cd ..
sudo chown -R $USER .
sudo chmod -R 777 storage bootstrap/cache
```
E então pode-se abrir o link: [localhost](http://localhost)

## Comandos/Urls importantes

- Inicialização dos containers:
```shell
sudo docker-compose up -d workspace nginx mysql redis phpmyadmin laravel-echo-server
```

- 'Desligamento' dos containers:
```shell
sudo docker-compose down
```

- 'Desligamento' dos containers:
```shell
sudo docker-compose down
```

- Acessar o 'bash' do container 'workspace':
```shell
sudo docker-compose exec workspace bash
```

- Modificar o 'owner' dos arquivos do projeto após a geração de novos arquivos dentro do container 'workspace':
	- Isso é necessário pois os arquivos são gerados com sudo
```shell
sudo chown -R $USER .
```

- Acessar phpmyadmin:
    - [localhost:8080](http://localhost:8080)

