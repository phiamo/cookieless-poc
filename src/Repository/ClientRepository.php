<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findClient(?string $guid, ?string $ssFingerprint, ?string $csFingerprint): ?Client
    {
        $qb = $this->createQueryBuilder('client');

        if ($csFingerprint) {
            $qb
                ->orWhere('client.csFingerprint = :csFingerprint')
                ->setParameter('csFingerprint', $csFingerprint)
            ;
        }

        if ($ssFingerprint) {
            $qb
                ->orWhere('client.ssFingerprint = :ssFingerprint')
                ->setParameter('ssFingerprint', $ssFingerprint)
            ;
        }

        if ($guid) {
            $qb
                ->orWhere('client.guid = :guid')
                ->setParameter('guid', $guid)
            ;
        }

        return $qb
            ->orderBy('client.updated')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
