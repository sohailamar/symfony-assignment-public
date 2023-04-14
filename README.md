Follow steps
## docker compose up -d

Go into docker instance
## docker compose exec php /bin/bash
RUN following commands inside docker
### composer install
### php bin/console doctrine:migrations:migrate

### php bin/console fruits:fetch

Visit following url to get/view fruits json
http://localhost:8080/fruits