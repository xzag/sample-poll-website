<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;

$dotenv = Dotenv::create(dirname(__DIR__));
$dotenv->load();

\Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models"));

$dbHost = getenv('POSTGRES_HOST');
$dbName = getenv('POSTGRES_DB');
$dbUser = getenv('POSTGRES_USER');
$dbPass = getenv('POSTGRES_PASSWORD');

$conn = array(
    'url' => "pgsql://{$dbUser}:{$dbPass}@{$dbHost}/{$dbName}",
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
