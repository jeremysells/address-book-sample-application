<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Controllers;

use JeremySells\AddressBook\Libraries\Theme\ThemeLibrary;
use JeremySells\AddressBook\Entities\OrganisationEntity;
use JeremySells\AddressBook\Entities\PersonEntity;
use JeremySells\AddressBook\Libraries\Output\OutputFactory;
use JeremySells\AddressBook\Libraries\Output\OutputInterface;
use JeremySells\AddressBook\Repositories\ContactRepository;

class DefaultController extends AbstractController
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * Constructor
     *
     * @param ThemeLibrary $themeLibrary
     * @param ContactRepository $contactRepository
     */
    public function __construct(
        ThemeLibrary $themeLibrary,
        ContactRepository $contactRepository
    ) {
        parent::__construct($themeLibrary);
        $this->contactRepository = $contactRepository;
    }

    /**
     * Homepage
     *
     * @return OutputInterface
     * @throws \Exception
     */
    public function homepage(): OutputInterface
    {
        $content = $this->themeLibrary->renderControllerMethod(
            'Welcome',
            __METHOD__
        );
        return OutputFactory::newHttpOutput($content);
    }

    /**
     * 404 Page
     *
     * @return OutputInterface
     * @throws \Throwable
     */
    public function get404(): OutputInterface
    {
        $content = $this->themeLibrary->renderControllerMethod(
            '404 Page Not Found',
            __METHOD__
        );
        return OutputFactory::newHttpOutput($content, 404);
    }

    /**
     * Imports some test contacts
     * Note: This is not written for efficiency!
     * Note: Also it overwrites any data in the system
     *
     * @return OutputInterface
     * @throws \Throwable
     */
    public function populateTestData(): OutputInterface
    {
        //Remove all existing
        /** @psalm-suppress MixedTypeCoercion */
        $this->contactRepository->removeAndFlush($this->contactRepository->findAll());

        //---ADD ORGANISATIONS------------------------------------------------
        //http://www.fantasynamegenerators.com/company-names.php
        $organisations = [
            1 => ['Pumpkin Microsystems', "Phone: 0555 123456\nEmail: somebody@example.com"],
            2 => ['Stormex', 'Phone: 0555 112233'],
            3 => ['Web Technologies', 'Email: test@example.com'],
            4 => ['Web Enterprise', ''],
            5 => ['Web Dev Systems', "Phone: 0555 555555\nEmail: example@example.com"]
        ];
        $organisationEntities = [];
        foreach ($organisations as $organisation) {
            list ($name, $contactDetails) = $organisation;
            $organisationEntity = new OrganisationEntity();
            $organisationEntity->setName($name);
            $organisationEntity->setContactDetails($contactDetails);
            $organisationEntities[] = $organisationEntity;
        }
        $this->contactRepository->persistAndFlush($organisationEntities);

        //---ADD PEOPLE-------------------------------------------------------
        //https://www.name-generator.org.uk/quick/
        $people = [
            1 => ['Jerome Alvarez', "Phone: 0555 0987\nEmail: jerome-alvarez@example.com"],
            2 => ['Alexis Fountain', 'Phone: 0555 9876'],
            3 => ['Ridwan May', 'Email: ridwan@example.com'],
            4 => ['Andrew Buchanan', ''],
            5 => ['Anastasia Hubbard', "Phone: 0555 653123\nEmail: anastasia-hubbard@example.com"]
        ];
        $peopleEntities = [];
        foreach ($people as $person) {
            list ($name, $contactDetails) = $person;
            $personEntity = new PersonEntity();
            $personEntity->setName($name);
            $personEntity->setContactDetails($contactDetails);
            $peopleEntities[] = $personEntity;
        }
        $this->contactRepository->persistAndFlush($peopleEntities);

        //---LINK PEOPLE AND ORGANISATIONS------------------------------------
        $links = [
            //Person <(to)> Organisation
            1 => [1, 2],
            2 => [1, 3, 4],
            3 => [2],
            4 => [2]
        ];
        foreach ($links as $person => $organisations) {
            $personEntity = $peopleEntities[$person];
            foreach ($organisations as $organisation) {
                $organisationEntity = $organisationEntities[$organisation];
                $personEntity->addOrganisation($organisationEntity);
            }
        }
        $this->contactRepository->flush();

        //---DISPLAY----------------------------------------------------------
        $content = $this->themeLibrary->renderControllerMethod(
            'Import Test Contacts',
            __METHOD__
        );
        return OutputFactory::newHttpOutput($content);
    }
}
