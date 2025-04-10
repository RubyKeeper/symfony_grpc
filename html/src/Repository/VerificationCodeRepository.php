<?php

namespace App\Repository;

use App\Entity\VerificationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function save($verificationCode)
    {
        $this->connection->insert('verification_codes', [
            'phone' => $verificationCode->getPhone(),
            'code' => $verificationCode->getCode(),
            'token' => $verificationCode->getToken(),
            'created_at' => $verificationCode->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $verificationCode->getUpdatedAt()->format('Y-m-d H:i:s'),
            'count' => $verificationCode->getCount(),
        ]);
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
        $sql = 'SELECT * FROM verification_codes WHERE phone = :phone';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['phone' => $phone]);

        return $stmt->fetchAll();
    }
}
