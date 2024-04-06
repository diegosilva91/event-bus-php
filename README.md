
## Install

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer install
```

### Unit Test

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer run-unit-tests
```

### Fix style

```bash
docker run --rm --interactive --tty --volume $PWD:/app composer:2 composer fix-style
```
