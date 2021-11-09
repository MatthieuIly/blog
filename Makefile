install:
	composer install

update:
	composer update

composer:
	composer valid


phpstan:
	php -d memory_limit=-1 vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

phpinsights:
	vendor/bin/phpinsights --no-interaction

phpcpd:
	vendor/bin/phpcpd src/

phpmd:
	vendor/bin/phpmd src/ text .phpmd.xml

analyse:
	make composer
	make phpcpd
	make phpmd
	make phpinsights
	make phpstan
