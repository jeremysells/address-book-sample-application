<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Libraries\Theme;

use JeremySells\AddressBook\Libraries\Template\TemplateLibrary;

class ThemeLibrary
{
    /**
     * @var TemplateLibrary
     */
    private $templateLibrary;

    /**
     * Constructor.
     *
     * @param TemplateLibrary $templateLibrary
     */
    public function __construct(TemplateLibrary $templateLibrary)
    {
        $this->templateLibrary = $templateLibrary;
    }

    /**
     * Renders the controller method and returns a string of the output
     * @param string $title
     * @param string $method
     * @param array $params
     * @return String
     * @psalm-suppress MixedAssignment
     */
    public function renderControllerMethod(
        string $title,
        string $method,
        array $params = []
    ) : String {
        $methodRendered = $this->templateLibrary->renderMethod($method, $params);
        return $this->templateLibrary->renderFile('theme', ['title' => $title, 'mainHtml' => $methodRendered]);
    }
}
