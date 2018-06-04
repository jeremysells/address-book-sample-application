<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Controllers;

use JeremySells\AddressBook\Entities\AbstractContactEntity;
use JeremySells\AddressBook\Entities\OrganisationEntity;
use JeremySells\AddressBook\Entities\PersonEntity;
use JeremySells\AddressBook\Libraries\Output\OutputFactory;
use JeremySells\AddressBook\Libraries\Output\OutputInterface;
use JeremySells\AddressBook\Libraries\Output\RedirectOutput;
use JeremySells\AddressBook\Libraries\Theme\ThemeLibrary;
use JeremySells\AddressBook\Repositories\ContactRepository;

class ContactController extends AbstractController
{
    /**
     * @var ContactRepository
     */
    protected $contactRepository;

    /**
     * Constructor
     *
     * @param ThemeLibrary          $themeLibrary
     * @param ContactRepository     $contactRepository
     */
    public function __construct(
        ThemeLibrary $themeLibrary,
        ContactRepository $contactRepository
    ) {
        parent::__construct($themeLibrary);
        $this->contactRepository = $contactRepository;
    }

    /**
     * Gets all the contacts
     *
     * @return OutputInterface
     * @throws \Throwable
     */
    public function all(): OutputInterface
    {
        $contact = $this->contactRepository->findAll();

        return $this->index(
            $contact,
            'All Contacts'
        );
    }

    /**
     * Searches for an contact
     *
     * @return OutputInterface
     * @throws \Throwable
     */
    public function search(): OutputInterface
    {
        $query = isset($_GET['q']) ? (string) $_GET['q'] : '';

        $contacts = $this->contactRepository->searchResults($query);
        $contactsFound = count($contacts);
        $contactsTotal = $this->contactRepository->count([]);

        return $this->index(
            $contacts,
            'Search Contacts',
            "Found ($contactsFound) out of ($contactsTotal) Contacts"
        );
    }

    /**
     * Contact list (search and index)
     *
     * @param string $title
     * @param array  $contacts
     * @param string $titleSub
     * @return OutputInterface
     * @throws \Exception
     */
    private function index(
        array $contacts,
        string $title,
        string $titleSub = ''
    ) : OutputInterface {
        $content = $this->themeLibrary->renderControllerMethod(
            $title,
            __METHOD__,
            [
                "contacts" => $contacts,
                "title" => $title,
                "titleSub" => $titleSub
            ]
        );
        return OutputFactory::newHttpOutput($content);
    }

    /**
     * Views/Edits an item
     * @param int|void $id
     * @return OutputInterface
     * @throws \Exception
     */
    public function item($id = null)
    {
        //---LOAD-------------------------------------------------------------
        $contactEntity = null;
        if (!empty($id)) {
            /** @var OrganisationEntity|PersonEntity $contactEntity */
            $contactEntity = $this->contactRepository->findOneBy(['id' => (int) $id]);
        } elseif (!empty($_POST['id'])) {
            /** @var OrganisationEntity|PersonEntity $contactEntity */
            $contactEntity = $this->contactRepository->findOneBy(['id' => (int) $_POST['id']]);
        } elseif (!empty($_POST['discr'])) {
            $class = AbstractContactEntity::$discrMap[(string) $_POST['discr']];
            /** @var OrganisationEntity|PersonEntity $contactEntity */
            $contactEntity = new $class();
        }

        //---VALIDATION-------------------------------------------------------
        if ($contactEntity === null) {
            throw new \Exception("Error unknown contact. TODO better user error here");
        }

        //---DELETE-----------------------------------------------------------
        if (!empty($_POST['delete'])) {
            $this->contactRepository->removeAndFlush([$contactEntity]);
            return new RedirectOutput('/contacts'); //Go Home. TODO user feedback
        }

        //---SAVE-------------------------------------------------------------
        if (!empty($_POST['save'])) {
            //ToDo: Validation on the length of these vars
            $contactEntity->setName((string) $_POST['name']);
            $contactEntity->setContactDetails((string) $_POST['contactDetails']);

            $this->contactRepository->persistAndFlush([$contactEntity]);
        }

        //---DISPLAY----------------------------------------------------------
        /** @psalm-suppress MixedMethodCall */
        $classTypeName = $contactEntity->getTypeName(); //Note: Static call to the super class
        /** @psalm-suppress MixedMethodCall */
        $actionName = ((int) $contactEntity->getId()) > 0 ? "Edit" : "Add";
        $content = $this->themeLibrary->renderControllerMethod(
            "{$actionName} {$classTypeName}",
            __METHOD__,
            [
                'contactEntity' => $contactEntity,
                'actionName' => $actionName
            ]
        );
        return OutputFactory::newHttpOutput($content);
    }
}
