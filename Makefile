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

eslint:
	npx eslint assets/

stylelint:
	npx stylelint "assets/styles/**/*.scss"

twig:
	php bin/console lint:twig templates
	vendor/bin/twigcs templates

doctrine:
	php bin/console doctrine:schema:valid --skip-sync

analyse:
	make composer
	make eslint
	make stylelint
	make twig
	make phpmd
	make phpcpd
	make doctrine
	make phpstan
