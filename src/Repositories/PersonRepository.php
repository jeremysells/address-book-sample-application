<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Repositories;

use JeremySells\AddressBook\Entities\PersonEntity;

class PersonRepository extends AbstractRepository
{
    /**
     * @param int[] $peopleIds
     * @return PersonEntity[]
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function getPeopleExcept(array $peopleIds)
    {
        if (empty($peopleIds)) {
            return $this->findAll();
        }
        $queryBuilder = $this->createQueryBuilder("p");
        $queryBuilder->where($queryBuilder->expr()->notIn('p.id', $peopleIds));
        return $queryBuilder->getQuery()->getResult();
    }
}
