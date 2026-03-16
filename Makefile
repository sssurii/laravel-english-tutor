.PHONY: help setup up down build restart logs shell artisan composer npm native-install native-run native-build clean

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-20s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: ## Run initial setup (install Laravel and NativePHP)
	chmod +x setup.sh
	./setup.sh

up: ## Start all containers
	docker-compose up -d

down: ## Stop all containers
	docker-compose down

build: ## Build Docker images
	docker-compose build --no-cache

restart: ## Restart all containers
	docker-compose restart

logs: ## Show container logs
	docker-compose logs -f

shell: ## Access the app container shell
	docker-compose exec app bash

artisan: ## Run artisan command (use: make artisan cmd="migrate")
	docker-compose exec app php artisan $(cmd)

composer: ## Run composer command (use: make composer cmd="install")
	docker-compose exec app composer $(cmd)

npm: ## Run npm command (use: make npm cmd="install")
	docker-compose exec app npm $(cmd)

native-install: ## Install NativePHP
	docker-compose exec app php artisan native:install

native-run: ## Run NativePHP mobile app
	docker-compose exec app php artisan native:run

native-build: ## Build NativePHP mobile app
	docker-compose exec app php artisan native:build

migrate: ## Run database migrations
	docker-compose exec app php artisan migrate

fresh: ## Fresh migration with seeding
	docker-compose exec app php artisan migrate:fresh --seed

test: ## Run tests
	docker-compose exec app php artisan test

clean: ## Clean up everything (containers, volumes, src)
	docker-compose down -v
	rm -rf src/

permissions: ## Fix storage and cache permissions
	docker-compose exec app chmod -R 777 storage bootstrap/cache
