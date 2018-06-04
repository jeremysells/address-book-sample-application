<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use JeremySells\AddressBook\Entities\AbstractContactEntity;
use JeremySells\AddressBook\Entities\OrganisationEntity;
use JeremySells\AddressBook\Entities\PersonEntity;
use JeremySells\AddressBook\Libraries\Template\TemplateLibrary;
use JeremySells\AddressBook\Repositories\ContactRepository;
use JeremySells\AddressBook\Repositories\OrganisationRepository;
use JeremySells\AddressBook\Repositories\PersonRepository;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\Configuration as DoctrineConfiguration;
use Doctrine\Common\Cache\PredisCache as DoctrinePredisCache;
use Predis\Client as PredisClient;

//http://php-di.org/doc/php-definitions.html

//---CLASS DEFINITIONS--------------------------------------------------------

$definitions = [
    LoggerInterface::class          => DI\get(MonologLogger::class),
    TemplateLibrary::class          => DI\autowire()->constructor(
        DI\get('configPathFolderViews'),
        array(
            'configHttpNodeModules' => DI\get('configHttpNodeModules'),
        )
    ),
    PredisClient::class => function () {
        return new PredisClient(
            array(
                'host' => getenv('REDIS_HOSTNAME'),
                'port' => getenv('REDIS_PORT'),
                'timeout' => (float) getenv('REDIS_TIMEOUT'),
            )
        );
    },
    PDO::class => function () {
        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=utf8;port=%s",
            getenv('DATABASE_HOSTNAME'),
            getenv('DATABASE_SCHEMA'),
            getenv('DATABASE_PORT')
        );
        return new PDO(
            $dsn,
            getenv('DATABASE_USERNAME'),
            getenv('DATABASE_PASSWORD'),
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    },
    DoctrineEntityManager::class    => function () {
        $container = getContainer();
        $vendorAnnotations = '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php';
        AnnotationRegistry::registerFile(rtrim(getenv('PATH_APPLICATION_CODE'), '/') . $vendorAnnotations);

        $driver = AnnotationDriver::create([
            getenv('PATH_APPLICATION_CODE') . '/src/Entities'
        ]);
        $config = new DoctrineConfiguration();
        $config->setMetadataDriverImpl($driver);
        $config->setProxyDir(getenv('PATH_APPLICATION_CODE_DYNAMIC_CACHE') . '/doctrine');
        $config->setProxyNamespace('DoctrineProxies');
        $config->setAutoGenerateProxyClasses(true);

        if (!empty(getenv('REDIS_HOSTNAME'))) {
            $cacheDriver = new DoctrinePredisCache($container->get(PredisClient::class));
            $config->setQueryCacheImpl($cacheDriver);
        }

        $entityManager = DoctrineEntityManager::create(
            array('pdo' => $container->get(PDO::class)),
            $config
        );
        if (getenv('DATABASE_DEBUG_QUERIES')) {
            $entityManager->getConfiguration()->setSQLLogger(new EchoSQLLogger());
        }
        return $entityManager;
    }
];

//---CONFIG-------------------------------------------------------------------
$definitions = array_merge($definitions, [
    'configPathApplicationCode'     => DI\env('PATH_APPLICATION_CODE'),
    'configPathFolderViews'         => DI\env('PATH_APPLICATION_CODE_VIEWS'),
    'configPathFolderDynamic'       => DI\env('PATH_APPLICATION_CODE_DYNAMIC'),
    'configPathFolderDynamicUser'   => DI\env('PATH_APPLICATION_CODE_DYNAMIC_USER'),
    'configPathFolderDynamicCache'  => DI\env('PATH_APPLICATION_CODE_DYNAMIC_CACHE'),
    'configHttpNodeModules'         => DI\env('HTTP_NODE_MODULES')
]);

//---REPOSITORY---------------------------------------------------------------
$repositoryMapping = [
    ContactRepository::class        => AbstractContactEntity::class,
    PersonRepository::class         => PersonEntity::class,
    OrganisationRepository::class   => OrganisationEntity::class
];
foreach ($repositoryMapping as $repositoryClass => $entityClass) {
    $definitions[$repositoryClass] = function () use ($entityClass) {
        return getContainer()->get(DoctrineEntityManager::class)->getRepository($entityClass);
    };
}

return $definitions;
