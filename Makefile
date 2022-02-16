# See:
# http://www.gnu.org/software/make/manual/make.html
# http://linuxlib.ru/prog/make_379_manual.html

### Ansible operations
build:
	echo "Start build"
	docker-compose up -d --build
	
rebuild:
	echo "Start rebuild"
	docker-compose down && docker-compose up -d --build
