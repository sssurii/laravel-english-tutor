# 🚀 English Tutor - NativePHP + Laravel App

**Dockerized English learning desktop application built with NativePHP and Laravel 12.**

✅ Setup Complete | Laravel 12 | NativePHP 1.3 | PHP 8.4 | MySQL 8.0 | Docker Compose v2

---

## 📖 Documentation Guide

- **[START-HERE.md](START-HERE.md)** 👈 **Read this first!** Complete getting started guide
- **[QUICKSTART.md](QUICKSTART.md)** - Fast-track setup instructions
- **[CHECKLIST.md](CHECKLIST.md)** - Setup progress checklist
- **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Common issues and solutions
- **[NATIVEPHP-CONFIG.md](NATIVEPHP-CONFIG.md)** - NativePHP configuration examples
- **[SETUP-GUIDE.txt](SETUP-GUIDE.txt)** - Visual reference guide

---

## ⚡ Quick Start

```bash
# 1. Run the automated setup
./setup.sh

# 2. Wait for installation (5-10 minutes)

# 3. Access your app
# Web: http://localhost:8000
```

That's it! Your NativePHP + Laravel app is ready to use.

---

## 📦 What's Included

- ✅ **PHP 8.2** with all necessary extensions
- ✅ **Laravel** (latest version)
- ✅ **NativePHP** (nativephp/laravel + nativephp/electron)
- ✅ **MySQL 8.0** database
- ✅ **Composer** for dependency management
- ✅ **Node.js & NPM** for frontend assets
- ✅ **Docker & Docker Compose** orchestration
- ✅ **Helper scripts** for development tasks

---

## 🏗️ Architecture

### Two Docker Containers:

1. **App Container** (`nativephp-app`)
   - PHP 8.2-CLI with extensions
   - Laravel application
   - NativePHP packages
   - Composer, Node.js, NPM

2. **Database Container** (`nativephp-db`)
   - MySQL 8.0
   - Persistent storage

### Project Structure:
```
english-tutor/
├── docker/                     Docker configs
│   └── php/php.ini            PHP settings
├── src/                       Laravel app (created after setup)
├── docker-compose.yml         Container orchestration
├── Dockerfile                 App container image
├── setup.sh                   🔧 Main setup script
├── dev.sh                     🔧 Development helper
├── Makefile                   Make commands
└── *.md                       Documentation
```

---

## 🎮 Daily Development Commands

### Using dev.sh (Recommended)
```bash
./dev.sh start                  # Start containers
./dev.sh stop                   # Stop containers
./dev.sh logs                   # View logs
./dev.sh shell                  # Access container
./dev.sh artisan migrate        # Run migrations
./dev.sh composer require pkg   # Install packages
./dev.sh native serve           # Run NativePHP
```

### Using Makefile
```bash
make up                         # Start containers
make down                       # Stop containers
make logs                       # View logs
make shell                      # Access container
make artisan cmd="migrate"      # Run artisan
make native-serve               # Run NativePHP
```

### Using Docker Compose
```bash
docker-compose up -d
docker-compose down
docker-compose exec app php artisan migrate
docker-compose exec app php artisan native:serve
```

---

## 🌐 Access Points

- **Web App:** http://localhost:8000
- **Database:** localhost:3306
  - Database: `laravel`
  - Username: `laravel`
  - Password: `password`
  - Root Password: `root`

---

## 🎯 Getting Started

### Prerequisites
- Docker installed
- Docker Compose installed
- 5-10 minutes for initial setup

### Setup Process

**Option 1: Automated (Recommended)**
```bash
./setup.sh
```

**Option 2: Manual**
Follow [QUICKSTART.md](QUICKSTART.md) for step-by-step instructions.

**Option 3: Using Make**
```bash
make setup
```

---

## 🛠️ Common Tasks

### Create Models
```bash
./dev.sh artisan make:model Lesson -m
./dev.sh artisan make:model Word -m
```

### Create Controllers
```bash
./dev.sh artisan make:controller LessonController --resource
```

### Run Migrations
```bash
./dev.sh migrate
```

### Install UI Framework
```bash
./dev.sh composer require laravel/breeze --dev
./dev.sh artisan breeze:install
```

### Clear Caches
```bash
./dev.sh clear-cache
```

### Fix Permissions
```bash
./dev.sh fix-permissions
```

---

## 🖥️ NativePHP Desktop App

### Development Mode
```bash
./dev.sh native serve
```

### Build Desktop App
```bash
./dev.sh native build
```

**Note:** For production desktop builds, you may need Electron installed on your host machine.

See [NATIVEPHP-CONFIG.md](NATIVEPHP-CONFIG.md) for configuration examples.

---

## 🆘 Troubleshooting

**Containers won't start?**
```bash
./dev.sh stop && ./dev.sh start
./dev.sh logs
```

**Permission errors?**
```bash
./dev.sh fix-permissions
```

**Database connection failed?**
- Check `src/.env` has `DB_HOST=db`
- Verify containers are running: `docker-compose ps`

**More help:** See [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

---

## 🔧 Helper Scripts

| Script | Purpose |
|--------|---------|
| `setup.sh` | Initial automated setup |
| `dev.sh` | Development commands |
| `manual-setup.sh` | Step-by-step manual setup |
| `Makefile` | Make-based commands |

---

## 📚 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [NativePHP Documentation](https://nativephp.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [Electron Documentation](https://www.electronjs.org/docs)

---

## 💡 Tips

- ✅ Keep containers running while developing
- ✅ All code changes in `src/` are live-reloaded
- ✅ Use `./dev.sh` for quick commands
- ✅ Use `make` for standardized tasks
- ✅ Check logs if something isn't working
- ✅ Don't commit `src/vendor` or `src/node_modules`

---

## 🔒 Security Note

⚠️ **Development credentials included.** Change for production!

Update `docker-compose.yml` and `src/.env` with secure credentials before deploying.

---

## 🎓 Building Your English Tutor App

Get started with your app:

```bash
# Create models
./dev.sh artisan make:model Lesson -m
./dev.sh artisan make:model Word -m
./dev.sh artisan make:model Quiz -m

# Create controllers
./dev.sh artisan make:controller LessonController --resource
./dev.sh artisan make:controller QuizController --resource

# Run migrations
./dev.sh migrate

# Test NativePHP
./dev.sh native serve
```

---

## 📝 Next Steps

1. ✅ Run `./setup.sh` to install everything
2. ✅ Access http://localhost:8000 to verify
3. ✅ Read [START-HERE.md](START-HERE.md) for detailed guide
4. ✅ Start building your English Tutor features!

---

## 🎉 Ready to Build!

Your complete NativePHP + Laravel development environment is ready.

**Run this to begin:**
```bash
./setup.sh
```

Then visit **http://localhost:8000**

Happy coding! 🚀

---

**Questions?** Check the documentation files or troubleshooting guide.

