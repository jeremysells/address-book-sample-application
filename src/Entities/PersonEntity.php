<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="JeremySells\AddressBook\Repositories\PersonRepository")
 * @ORM\Table(name="person")
 */
class PersonEntity extends AbstractContactEntity
{
    const TYPE_NAME = "Person";

    /**
     * @ORM\ManyToMany(targetEntity="OrganisationEntity")
     * @ORM\JoinTable(
     *     name="person_in_organisation",
     *     joinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="organisation_id", referencedColumnName="id")}
     * )
     * @var \Doctrine\Common\Collections\Collection|OrganisationEntity[]
     */
    private $organisations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organisations = new ArrayCollection();
    }

    /**
     * @param OrganisationEntity $organisationEntity
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function addOrganisation(OrganisationEntity $organisationEntity) : void
    {
        if ($this->organisations->contains($organisationEntity)) {
            return;
        }

        $this->organisations->add($organisationEntity);
        $organisationEntity->addPerson($this);
    }

    /**
     * @param OrganisationEntity $organisationEntity
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function removeOrganisation(OrganisationEntity $organisationEntity) : void
    {
        if (!$this->organisations->contains($organisationEntity)) {
            return;
        }
        $this->organisations->removeElement($organisationEntity);
        $organisationEntity->removePerson($this);
    }

    //---LOGIC---------------------------------------------------------------

    /**
     * @inheritdoc
     */
    public function getTypeName() : string
    {
        return self::TYPE_NAME;
    }
}
