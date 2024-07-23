gendiff -h:
	./bin/gendiff -h
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin