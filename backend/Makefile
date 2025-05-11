fresh:
	make up
	USER_ID=$$(id -u) docker compose exec app php artisan migrate:fresh --seed
up:
	USER_ID=$$(id -u) docker compose up -d
down:
	USER_ID=$$(id -u) docker compose down
restart:
	USER_ID=$$(id -u) docker compose restart
logs:
	USER_ID=$$(id -u) docker compose logs -f app
supervisor:
	USER_ID=$$(id -u) docker compose exec app supervisorctl
bash:
	USER_ID=$$(id -u) docker compose exec app bash
pint:
	USER_ID=$$(id -u) docker compose exec app ./vendor/bin/pint;
test:
	USER_ID=$$(id -u) docker compose exec app php artisan test --stop-on-failure
test-c:
	USER_ID=$$(id -u) docker compose exec app php artisan test --coverage --stop-on-failure
benchmark:
	USER_ID=$$(id -u) sh wrk-laravel-all-routes.sh
