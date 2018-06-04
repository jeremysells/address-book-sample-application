<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="JeremySells\AddressBook\Repositories\OrganisationRepository")
 * @ORM\Table(name="organisation")
 */
class OrganisationEntity extends AbstractContactEntity
{
    const TYPE_NAME = "Organisation";

    /**
     * @ORM\ManyToMany(targetEntity="PersonEntity", mappedBy="organisations")
     * @var Collection|PersonEntity[]
     */
    private $people;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->people = new ArrayCollection();
    }

    /**
     * @param PersonEntity $personEntity
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function addPerson(PersonEntity $personEntity) : void
    {
        if ($this->people->contains($personEntity)) {
            return;
        }
        $this->people->add($personEntity);
        $personEntity->addOrganisation($this);
    }

    /**
     * @param PersonEntity $personEntity
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function removePerson(PersonEntity $personEntity) : void
    {
        if (!$this->people->contains($personEntity)) {
            return;
        }
        $this->people->removeElement($personEntity);
        $personEntity->removeOrganisation($this);
    }

    /**
     * @return Collection|PersonEntity[]
     * @psalm-suppress MismatchingDocblockReturnType
     */
    public function getPeople() : Collection
    {
        return $this->people;
    }

    /**
     * @return int[]
     */
    public function getPeopleIds() : array
    {
        $ids = [];
        /** @var PersonEntity $personEntity */
        foreach ($this->people as $personEntity) {
            $ids[] = (int) $personEntity->getId();
        }
        return $ids;
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
