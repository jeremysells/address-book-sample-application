<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Controllers;

use JeremySells\AddressBook\Libraries\Theme\ThemeLibrary;

abstract class AbstractController
{
    /**
     * @var ThemeLibrary
     */
    protected $themeLibrary;

    /**
     * Constructor
     *
     * @param ThemeLibrary $themeLibrary
     */
    public function __construct(
        ThemeLibrary $themeLibrary
    ) {
        $this->themeLibrary = $themeLibrary;
    }
}
