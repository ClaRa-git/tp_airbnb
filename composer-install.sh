#!/bin/bash
docker exec php_poo composer install
docker exec php_poo composer dump-autoload