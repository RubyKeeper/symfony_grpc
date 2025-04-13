<?php

namespace App\Repository;

use App\Entity\BlockPhone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlockPhone>
 */
class BlockPhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockPhone::class);
    }

    public function insert(BlockPhone $blockPhone): BlockPhone
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($blockPhone);
        $entityManager->flush();
        return $blockPhone;
    }

    public function isBlocked(string $phone) {
        return $this->createQueryBuilder('b')
            ->andWhere('b.phone = :phone')
            ->andWhere('b.blockAt > :blocked')
            ->setParameter('phone', $phone)
            ->setParameter('blocked', (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
