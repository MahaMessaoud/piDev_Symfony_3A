<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public  function sms(){
// Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC8ccdc9247ba4ead9aabef04a1ccc183b';
        $auth_token = '4983c7243a884c7021934aa976559e84';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
// A Twilio number you own with SMS capabilities
        $twilio_number = "+12766000930";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
        // the number you'd like to send the message to
            '+21655510950',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+12766000930',
                // the body of the text message you'd like to send
                'body' => 'Votre réservation est traité avec succés et votre plat sera prêt dans une heure .Merci !'
            ]
        );
    }
//Stat
    public function countByDate(){
        $query = $this->createQueryBuilder('r');
        $query
            ->select('SUBSTRING(r.date , 1 , 10) as dateReservation , COUNT(r) as count')
            ->groupBy('dateReservation')
        ;
        return $query->getQuery()->getResult();
    }
}




