<?php

namespace App\Repository;

use App\Entity\VerificationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<VerificationCode>
 */
class VerificationCodeRepository extends ServiceEntityRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id
    ): ?object
    {
        $sql = 'SELECT * FROM verification_codes WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery(['id' => $id]);

        return $stmt->fetchAssociative();
    }

    /**
     * @throws Exception
     */
    public function save($verificationCode)
    {
        $queryBuilder = $this->connection->createQueryBuilder()->insert('verification_code')
            ->values([
                'phone'=> ':phone',
                'code' => ':code',
                'token' => ':token',
                'created_at' => ':createdAt',
                'updated_at' => ':updatedAt',
                'count' => ':count',
            ])
            ->setParameters([
                'phone' => $verificationCode->getPhone(),
                'code' => $verificationCode->getCode(),
                'token' => $verificationCode->getToken(),
                'createdAt' => $verificationCode->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $verificationCode->getUpdatedAt()->format('Y-m-d H:i:s'),
                'count' => $verificationCode->getCount(),
            ]);
        $queryBuilder->executeQuery();
    }

    public function update($verificationCode)
    {
        $this->connection->update('verification_codes', [
            'phone' => $verificationCode->getPhone(),
            'code' => $verificationCode->getCode(),
            'token' => $verificationCode->getToken(),
            'updated_at' => $verificationCode->getUpdatedAt()->format('Y-m-d H:i:s'),
            'count' => $verificationCode->getCount(),
        ], ['id' => $verificationCode->getId()]);
    }

    public function delete($id)
    {
        $this->connection->delete('verification_codes', ['id' => $id]);
    }

    public function findByPhone($phone)
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('verification_code')
            ->where('phone = :phone')
            ->andWhere('updated_at > :updatedAt')
            ->setParameter('phone', $phone)
            ->setParameter('updatedAt', (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s'))
        ->setMaxResults(1);
        return $queryBuilder->executeQuery()->fetchAssociative();
    }

    /**
     * Инкрементно обновляет количество вызовов
     * @param $id
     * @return void
     * @throws Exception
     */
    public function updateCount($id) {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->update('verification_code')
            ->set('count', 'count+1')
            ->where('id = :id')
            ->setParameter('id', $id);
        $queryBuilder->executeQuery();
    }
}
