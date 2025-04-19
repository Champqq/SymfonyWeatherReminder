.PHONY: check fix analyse

check:
	php vendor/bin/phpstan analyse src
	php vendor/bin/phpcs src

fix:
	php vendor/bin/php-cs-fixer fix src
	php vendor/bin/phpcbf src

analyse:
	php vendor/bin/psalm
