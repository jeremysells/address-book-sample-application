<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="JeremySells\AddressBook\Repositories\ContactRepository")
 * @ORM\Table(name="contact")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"person" = "PersonEntity", "organisation" = "OrganisationEntity"})
 */
abstract class AbstractContactEntity extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string", name="contact_details")
     * @var string
     */
    protected $contactDetails = '';

    /**
     * Map of Discriminators To Classes
     * @var string[]
     */
    public static $discrMap = [
        'person'        => PersonEntity::class,
        'organisation'  => OrganisationEntity::class
    ];

    /**
     * Map of Discriminators To Name
     * @var string[]
     */
    public static $discrMapToName = [
        'person'        => PersonEntity::TYPE_NAME,
        'organisation'  => OrganisationEntity::TYPE_NAME
    ];

    /**
     * @return int
     */
    public function getId() :? int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getContactDetails(): string
    {
        return $this->contactDetails;
    }

    /**
     * @param string $contactDetails
     */
    public function setContactDetails(string $contactDetails): void
    {
        $this->contactDetails = $contactDetails;
    }

    //---LOGIC---------------------------------------------------------------

    /**
     * Gets the type name (e.g. "Organisation", "Person" etc)
     * Note: Name (as in human readable)
     * @return string
     */
    abstract public function getTypeName() : string;

    /**
     * @return string
     */
    public function getDiscr()
    {
        $map = array_flip(self::$discrMap);
        return (string) $map[static::class];
    }
}
