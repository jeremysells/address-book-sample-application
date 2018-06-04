<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Repositories;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use JeremySells\AddressBook\Entities\AbstractEntity;

abstract class AbstractRepository extends DoctrineEntityRepository
{
    /**
     * @param AbstractEntity[] $entities
     */
    public function persist(array $entities) : void
    {
        foreach ($entities as $entity) {
            $this->_em->persist($entity);
        }
    }

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     */
    public function flush() : void
    {
        $this->_em->flush();
    }

    /**
     * Writes changes to the database
     * @param AbstractEntity[] $entities
     */
    public function persistAndFlush(array $entities) : void
    {
        $this->persist($entities);
        $this->flush();
    }

    /**
     * Removes an array of entities
     * @param AbstractEntity[] $entities
     */
    public function remove(array $entities) : void
    {
        foreach ($entities as $entity) {
            $this->_em->remove($entity);
        }
    }

    /**
     * @param AbstractEntity[] $entities
     */
    public function removeAndFlush(array $entities) : void
    {
        $this->remove($entities);
        $this->flush();
    }
}
