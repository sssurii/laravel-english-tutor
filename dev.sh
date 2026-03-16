#!/bin/bash

# Helper script for common development tasks

case "$1" in
    "start")
        echo "Starting containers..."
        docker compose up -d
        echo "Containers started! Access at http://localhost:8000"
        ;;

    "stop")
        echo "Stopping containers..."
        docker compose down
        echo "Containers stopped!"
        ;;

    "logs")
        docker compose logs -f
        ;;

    "reverb")
        case "$2" in
            "start")
                docker compose up -d reverb
                echo "Reverb started on ws://localhost:8080"
                ;;
            "restart")
                docker compose restart reverb
                echo "Reverb container restarted"
                ;;
            *)
                echo "Usage: ./dev.sh reverb [start|restart]"
                ;;
        esac
        ;;

    "queue")
        case "$2" in
            "start")
                docker compose up -d queue
                echo "Queue worker started"
                ;;
            "restart")
                docker compose restart queue
                echo "Queue worker restarted"
                ;;
            *)
                echo "Usage: ./dev.sh queue [start|restart]"
                ;;
        esac
        ;;

    "shell")
        echo "Accessing container shell..."
        docker compose exec app bash
        ;;

    "artisan")
        shift
        docker compose exec app php artisan "$@"
        ;;

    "composer")
        shift
        docker compose exec app composer "$@"
        ;;

    "migrate")
        echo "Running migrations..."
        docker compose exec app php artisan migrate
        ;;

    "fresh")
        echo "Running fresh migrations..."
        docker compose exec app php artisan migrate:fresh --seed
        ;;

    "test")
        echo "Running tests..."
        docker compose exec app php artisan test
        ;;

    "native")
        case "$2" in
            "install")
                docker compose exec app php artisan native:install
                ;;
            "jump")
                if ! docker compose exec -T app test -x /var/www/node_modules/.bin/vite; then
                    echo "Frontend dependencies not found. Installing npm packages..."
                    docker compose exec app npm install
                fi
                docker compose exec app php artisan native:jump
                ;;
            "run")
                docker compose exec app php artisan native:run
                ;;
            "build")
                docker compose exec app php artisan native:build
                ;;
            *)
                echo "Usage: ./dev.sh native [install|jump|run|build]"
                ;;
        esac
        ;;

    "fix-permissions")
        echo "Fixing permissions..."
        docker compose exec app chmod -R 777 storage bootstrap/cache
        echo "Permissions fixed!"
        ;;

    "clear-cache")
        echo "Clearing all caches..."
        docker compose exec app php artisan config:clear
        docker compose exec app php artisan cache:clear
        docker compose exec app php artisan view:clear
        docker compose exec app php artisan route:clear
        echo "Cache cleared!"
        ;;

    *)
        echo "Usage: ./dev.sh [command]"
        echo ""
        echo "Available commands:"
        echo "  start              - Start all containers"
        echo "  stop               - Stop all containers"
        echo "  logs               - Show container logs"
        echo "  reverb [cmd]       - Manage Reverb websocket server"
        echo "  queue [cmd]        - Manage queue worker"
        echo "  shell              - Access container shell"
        echo "  artisan [cmd]      - Run artisan command"
        echo "  composer [cmd]     - Run composer command"
        echo "  migrate            - Run database migrations"
        echo "  fresh              - Fresh migrations with seeding"
        echo "  test               - Run tests"
        echo "  native install     - Install NativePHP"
        echo "  native jump        - Start NativePHP Jump dev server"
        echo "  native run         - Run NativePHP mobile app"
        echo "  native build       - Build NativePHP mobile app"
        echo "  reverb start       - Start Reverb websocket server"
        echo "  queue start        - Start queue worker"
        echo "  fix-permissions    - Fix storage permissions"
        echo "  clear-cache        - Clear all Laravel caches"
        echo ""
        echo "Examples:"
        echo "  ./dev.sh start"
        echo "  ./dev.sh artisan make:controller MyController"
        echo "  ./dev.sh composer require package/name"
        echo "  ./dev.sh native jump"
        echo "  ./dev.sh native run"
        ;;
esac
