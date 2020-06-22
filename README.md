API test mipos apertura y cierre de caja.

- Descargar el repo o clonarlo con git clone https://github.com/gusjara/mipos-test.git.
- Correr en la terminal composer install.
- Luego copiar y/o renombrarlo al archivo .env.example a -> .env
- Correr el comando php artisan key:generate.
- Crear una base de datos y agregarla en el archivo .env
- Correr php artisan migrate:fresh

- Si se requiere instalar passport usar php artisan passport:client --personal y asignar el nombre del cliente para los tokens.

Nota: La los 4 endpoints de la api están funcionando sin login.