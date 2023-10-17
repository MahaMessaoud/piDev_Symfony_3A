<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;

/**
 * @extends ServiceEntityRepository<Fournisseur>
 *
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    public function save(Fournisseur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fournisseur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public  function sms($tel){
        // Your Account SID and Auth Token from twilio.com/console
                $sid = 'AC0078640e2043a045bec1c7a1f4c526ed';
                $auth_token = '5b7b293f01490fce7e8e1fa5f6865c11';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
                $twilio_number = "+12765308290";
        
                $client = new Client($sid, $auth_token);
                $client->messages->create(
                // the number you'd like to send the message to
                    '+216'.$tel,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => '+12765308290',
                        // the body of the text message you'd like to send
                        'body' => 'Votre charge a été bien reçue.Merci!'
                    ]
                );
            }
//    /**
//     * @return Fournisseur[] Returns an array of Fournisseur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fournisseur
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
