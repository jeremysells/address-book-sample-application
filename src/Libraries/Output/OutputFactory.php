<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Libraries\Output;

class OutputFactory
{
    /**
     * Builds a new html output
     * @param string $content
     * @param int    $httpStatus
     * @return OutputInterface
     */
    public static function newHttpOutput(
        string $content,
        int $httpStatus = 200
    ) : OutputInterface {
        $output = new HttpOutput();
        $output->setContent($content);
        $output->setHeader('Content-Type', 'text/html');
        $output->setHttpStatusCodeCode($httpStatus);
        return $output;
    }
}
