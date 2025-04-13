<?php

namespace App\Repository;

use App\Entity\VerificationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

/**
 * @extends ServiceEntityRepository<VerificationCode>
 */
class VerificationCodeRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    /**
     * @throws Exception
     */
    public function save(VerificationCode $verificationCode)
    {
        $queryBuilder = $this->connection
            ->createQueryBuilder()->insert('verification_code')
            ->values([
                'phone'      => ':phone',
                'code'       => ':code',
                'token'      => ':token',
                'created_at' => ':createdAt',
                'count'      => ':count',
            ])
            ->setParameters([
                'phone'     => $verificationCode->getPhone(),
                'code'      => $verificationCode->getCode(),
                'token'     => $verificationCode->getToken(),
                'createdAt' => $verificationCode->getCreatedAt()->format(
                    'Y-m-d H:i:s',
                ),
                'count'     => $verificationCode->getCount(),
            ]);
        $queryBuilder->executeQuery();
    }

    public function delete($id)
    {
        $this->connection->delete('verification_code', ['id' => $id]);
    }

    public function findByPhone($phone)
    {
        $queryBuilder = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('verification_code')
            ->where('phone = :phone')
            ->andWhere('created_at > :createdAt')
            ->setParameter('phone', $phone)
            ->setParameter(
                'createdAt',
                (new \DateTimeImmutable('- 1 minute'))->format('Y-m-d H:i:s'),
            )
            ->setMaxResults(1);
        return $queryBuilder->executeQuery()->fetchAssociative();
    }

    public function findByTokenAndCode(string $token, string $code)
    {
        $queryBuilder = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('verification_code')
            ->where('token = :token')
            ->where('code = :code')
            ->andWhere('created_at > :createdAt')
            ->setParameter('token', $token)
            ->setParameter('code', $code)
            ->setParameter(
                'createdAt',
                (new \DateTimeImmutable('- 1 minute'))->format('Y-m-d H:i:s'),
            )
            ->setMaxResults(1);
        return $queryBuilder->executeQuery()->fetchAssociative();
    }

    /**
     * Инкрементно обновляет количество вызовов
     *
     * @param $id
     *
     * @return void
     * @throws Exception
     */
    public function updateCount(int $id)
    {
        $sql = 'update verification_code set count = count+1 where id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }

    /**
     * @throws Exception
     */
    public function getCount(string $phone): ?int {
        $queryBuilder = $this->connection
        ->createQueryBuilder()
            ->select('sum(count)')
            ->from('verification_code')
            ->where('phone = :phone')
            ->andWhere('created_at > :createdAt')
            ->setParameter('phone', $phone)
            ->setParameter('createdAt', (new \DateTimeImmutable('- 15 minute'))->format('Y-m-d H:i:s'),);
        return $queryBuilder->executeQuery()->fetchOne();
    }
}
