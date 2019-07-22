#!/usr/bin/env bash

./getcomposer.sh

php composer.phar install

./vendor/bin/doctrine-migrations -n migrate
