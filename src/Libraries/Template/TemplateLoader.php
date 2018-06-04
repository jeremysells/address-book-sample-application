<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Libraries\Template;

class TemplateLoader
{
    /**
     * Renders a file and returns the result
     * @param string $file
     * @param array  $parameters
     * @return string
     * @codeCoverageIgnore
     * @psalm-suppress MixedArgument
     * @psalm-suppress UnresolvableInclude
     */
    public function renderTemplate(string $file, array $parameters = []) : string
    {
        $initLevel = ob_get_level();
        try {
            ob_start();
            extract($parameters);
            include $file;
            $result = ob_get_contents();
            ob_end_clean();
            return $result;
        } catch (\Throwable $exc) {
            //Have we opened an output buffer? close it
            if ($initLevel < ob_get_level()) {
                ob_end_clean();
            }
            throw $exc;
        }
    }
}
