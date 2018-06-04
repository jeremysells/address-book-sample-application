<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Controllers;

use JeremySells\AddressBook\Entities\OrganisationEntity;
use JeremySells\AddressBook\Entities\PersonEntity;
use JeremySells\AddressBook\Libraries\Output\OutputFactory;
use JeremySells\AddressBook\Libraries\Output\OutputInterface;
use JeremySells\AddressBook\Libraries\Theme\ThemeLibrary;
use JeremySells\AddressBook\Repositories\OrganisationRepository;
use JeremySells\AddressBook\Repositories\PersonRepository;

class OrganisationController extends AbstractController
{
    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @var OrganisationRepository
     */
    private $organisationRepository;

    /**
     * OrganisationController constructor.
     *
     * @param ThemeLibrary           $themeLibrary
     * @param OrganisationRepository $organisationRepository
     * @param PersonRepository       $personRepository
     */
    public function __construct(
        ThemeLibrary $themeLibrary,
        OrganisationRepository $organisationRepository,
        PersonRepository $personRepository
    ) {
        parent::__construct($themeLibrary);
        $this->organisationRepository = $organisationRepository;
        $this->personRepository = $personRepository;
    }

    /**
     * @param string $organisationId
     * @throws \Throwable
     * @return OutputInterface
     */
    public function people(string $organisationId): OutputInterface
    {
        //Cast - Route/Url inputs integers
        $organisationIdInt = (int) $organisationId;
        unset($organisationId);

        //---LOAD-------------------------------------------------------------
        /** @var OrganisationEntity|null $organisationEntity */
        $organisationEntity = $this->organisationRepository->findOneBy(['id' => $organisationIdInt]);
        if ($organisationEntity === null) {
            throw new \Exception('Invalid or missing organisation contact');
        }

        //Get all the people not in this org
        $peopleNotInOrganisation = $this->personRepository->getPeopleExcept($organisationEntity->getPeopleIds());

        //---ADD--------------------------------------------------------------
        if (!empty($_POST['add_person_id'])) {
            $oldPeopleNotInOrganisation = $peopleNotInOrganisation;
            $peopleNotInOrganisation = [];
            /** @var PersonEntity $personEntity */
            foreach ($oldPeopleNotInOrganisation as $personEntity) {
                //If its a matching person, add to organisation
                if (((int)$personEntity->getId()) === ((int)$_POST['add_person_id'])) {
                    $organisationEntity->addPerson($personEntity);
                    $this->organisationRepository->persistAndFlush([$organisationEntity]);
                    continue;
                }
                //Else remember
                $peopleNotInOrganisation[] = $personEntity;
            }
        }

        //---REMOVE----------------------------------------------------------
        if (!empty($_POST['remove_person_id'])) {
            /** @var PersonEntity $personEntity */
            foreach ($organisationEntity->getPeople() as $personEntity) {
                if (((int)$personEntity->getId()) === ((int)$_POST['remove_person_id'])) {
                    //Remove person and save
                    $organisationEntity->removePerson($personEntity);
                    $this->organisationRepository->persistAndFlush([$organisationEntity]);

                    //Allow re-adding the person :)
                    $peopleNotInOrganisation[] = $personEntity;
                }
            }
        }

        //---DISPLAY----------------------------------------------------------
        $content = $this->themeLibrary->renderControllerMethod(
            'Manage Organisation People',
            __METHOD__,
            [
                "organisationEntity" => $organisationEntity,
                "peopleNotInOrganisation" => $peopleNotInOrganisation
            ]
        );
        return OutputFactory::newHttpOutput($content);
    }
}
