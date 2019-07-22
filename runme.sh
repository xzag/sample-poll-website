#!/usr/bin/env bash

if [[ ! -f ".env" ]]; then
    echo "Created .env file"
    cp .env.dist .env
fi

set -o allexport;
source .env;
set +o allexport

echo "Building containers"
docker-compose -f "docker-compose.${ENV}.yml" up -d

echo "Executing migrations"
docker-compose -f "docker-compose.${ENV}.yml" exec poll-website-php bash -c "./docker/wait-for-it.sh poll-website-db:5432 -- ./script-up.sh"

