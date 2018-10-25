# === Makefile Helper ===

# Styles
YELLOW=$(shell echo "\033[00;33m")
RED=$(shell echo "\033[00;31m")
RESTORE=$(shell echo "\033[0m")

# Variables
PHP_BIN := php
COMPOSER := composer
SRCS := src
CURRENT_DIR := $(shell pwd)
PLANTUMLJAR := $(CURRENT_DIR)/plantuml.jar
.DEFAULT_GOAL := list
EZ_DIR := $(CURRENT_DIR)/ezplatform

.PHONY: list
list:
	@echo "******************************"
	@echo "${YELLOW}Available targets${RESTORE}:"
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf " ${YELLOW}%-15s${RESTORE} > %s\n", $$1, $$2}'
	@echo "${RED}==============================${RESTORE}"

.PHONY: installez
installez: ## Install eZ as the local project
	@docker run -d -p 3316:3306 --name ezdbnovaformbuilder -e MYSQL_ROOT_PASSWORD=ezplatform mariadb:10.2
	@composer create-project ezsystems/ezplatform --prefer-dist --no-progress --no-interaction --no-scripts $(EZ_DIR)
	@curl -o tests/provisioning/wrap.php https://raw.githubusercontent.com/Plopix/symfony-bundle-app-wrapper/master/wrap-bundle.php
	@WRAP_APP_DIR=./ezplatform WRAP_BUNDLE_DIR=./ php tests/provisioning/wrap.php
	@rm tests/provisioning/wrap.php
	@echo "Please set up this way:"
	@echo "\tenv(DATABASE_HOST)     -> 127.0.0.01"
	@echo "\tenv(DATABASE_PORT)     -> 3316"
	@echo "\tenv(DATABASE_PASSWORD) -> ezplatform"
	cd $(EZ_DIR) && composer update --lock

.PHONY: serveez
serveez: stopez ## Clear the cache and start the web server
	@cd $(EZ_DIR) && rm -rf var/cache/*
	@docker start ezdbnovaformbuilder
	@cd $(EZ_DIR) && bin/console cache:clear
	@cd $(EZ_DIR) && bin/console server:start

.PHONY: stopez
stopez: ## Stop the web server if it is running
	@if [ -a $(EZ_DIR)/.web-server-pid ] ; \
	then \
		 cd $(EZ_DIR) && php bin/console server:stop;  \
	fi;
	@docker stop ezdbnovaformbuilder

.PHONY: codeclean
codeclean: ## Coding Standard checks
	$(PHP_BIN) ./vendor/bin/php-cs-fixer fix --config=.cs/.php_cs.php
	$(PHP_BIN) ./vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php bundle
	$(PHP_BIN) ./vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php ezbundle
	$(PHP_BIN) ./vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php tests
	$(PHP_BIN) ./vendor/bin/phpmd bundle text .cs/md_ruleset.xml
	$(PHP_BIN) ./vendor/bin/phpmd ezbundle text .cs/md_ruleset.xml
	$(PHP_BIN) ./vendor/bin/phpmd tests text .cs/md_ruleset.xml
	$(PHP_BIN) ./vendor/bin/phpcpd bundle
	$(PHP_BIN) ./vendor/bin/phpcpd ezbundle
	$(PHP_BIN) ./vendor/bin/phpcpd tests

.PHONY: tests
tests: ## Tests
	$(PHP_BIN) ./vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php bundle
	$(PHP_BIN) ./vendor/bin/phpcs --standard=.cs/cs_ruleset.xml --extensions=php ezbundle
	$(PHP_BIN) ./vendor/bin/phpcpd bundle
	$(PHP_BIN) ./vendor/bin/phpcpd ezbundle
