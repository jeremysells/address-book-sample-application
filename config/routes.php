<?php
declare(strict_types=1);

use JeremySells\AddressBook\Controllers\ContactController;
use JeremySells\AddressBook\Controllers\DefaultController;
use JeremySells\AddressBook\Controllers\OrganisationController;

return [
//  [REVERSE ROUTE              |HTTP METHOD    |URL                            |CONTROLLER                     |FUNCTION]
    ['404',                     'GET',          '/404',                         DefaultController::class,       'get404'],
    ['homepage'          ,      'GET',          '/',                            DefaultController::class,       'homepage'],
    ['populate-test-data',      'GET',          '/populate-test-data',          DefaultController::class,       'populateTestData'],
    ['contact-index',           'GET',          '/contacts',                    ContactController::class,       'all'],
    ['search',                  'GET',          '/search',                      ContactController::class,       'search'],
    ['contact-edit',            'POST',         '/contact',                     ContactController::class,       'item'],
    ['contact-view',            'GET|POST',     '/contact/[i:id]',              ContactController::class,       'item'],
    ['organisation-people',     'GET|POST',     '/organisation/[i:id]/people',  OrganisationController::class,  'people']
];
