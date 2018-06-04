<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Repositories;

use JeremySells\AddressBook\Entities\AbstractContactEntity;

class ContactRepository extends AbstractRepository
{
    /**
     * Searches for contacts
     * @param string $query
     * @return AbstractContactEntity[]
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function searchResults(string $query)
    {
        $queryBuilder = $this->createQueryBuilder("a");
        $queryBuilder->andWhere("(a.name LIKE :name) OR (a.contactDetails LIKE :contactDetails)");
        $queryBuilder->setParameter(":name", "%{$query}%");
        $queryBuilder->setParameter(":contactDetails", "%{$query}%");
        return $queryBuilder->getQuery()->getResult();
    }
}
