<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Libraries\Template;

class TemplateLibrary
{
    /**
     * @var string
     */
    private $viewsFolder;

    /**
     * Vars in all views
     * @var array
     */
    private $inAllViews = [];

    /**
     * @var string[]
     */
    private $removePrefixs = [
        'JeremySells\\AddressBook\\'
    ];

    /**
     * @var TemplateLoader
     */
    private $templateLoader;

    /**
     * Constructor.
     *
     * @param string $viewsFolder
     * @param mixed[] $inAllViews
     * @param TemplateLoader $templateLoader
     */
    public function __construct(string $viewsFolder, array $inAllViews, TemplateLoader $templateLoader)
    {
        $this->viewsFolder = $viewsFolder;
        $this->inAllViews = $inAllViews;
        $this->templateLoader = $templateLoader;
    }

    /**
     * Gets a template methods view
     * @param string $method
     * @param array $parameters
     * @return string
     */
    public function renderMethod(string $method, array $parameters = []) : String
    {
        //Remove the prefix
        $methodNoPrefix = $method;
        foreach ($this->removePrefixs as $removePrefix) {
            if (strpos($methodNoPrefix, $removePrefix) === 0) {
                $methodNoPrefix = substr($methodNoPrefix, strlen($removePrefix));
            }
        }

        //Convert the namespace to a file
        $file = '/src/'.str_replace(['\\', '::'], '/', $methodNoPrefix) ;
        return $this->renderFile($file, $parameters);
    }

    /**
     * Renders a file
     * @param string $file
     * @param array  $parameters
     * @return string
     */
    public function renderFile(string $file, array $parameters) : string
    {
        $fileInFolder = "{$this->viewsFolder}/{$file}.tpl.php";
        $templateParameters = array_merge($this->inAllViews, $parameters);
        return $this->templateLoader->renderTemplate($fileInFolder, $templateParameters);
    }
}
