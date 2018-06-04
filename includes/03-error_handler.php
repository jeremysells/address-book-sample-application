<?php
declare(strict_types=1);

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\ErrorHandler;

//Setup logging but do not pollute the global scope
call_user_func(function () {
    $monologLogger = new MonologLogger('auto-error');
    $level = MonologLogger::toMonologLevel(getenv('LOG_LEVEL'));
    $monologLogger->pushHandler(new StreamHandler('php://stderr', $level));
    $errorHandler = new ErrorHandler($monologLogger);
    $errorHandler->registerExceptionHandler();
    $errorHandler->registerFatalHandler();
    getContainer()->set(MonologLogger::class, $monologLogger);
});
