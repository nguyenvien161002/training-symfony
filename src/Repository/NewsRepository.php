<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('n')
            ->getQuery()
            ->getResult();
    }

    public function new($title, $description)
    {
        $news = new News();
        $current = new \DateTime();
        $now = DateTimeImmutable::createFromMutable($current);
        $news->setTitle($title);
        $news->setDescription($description);
        $news->setCreatedAt($now);
        $news->setUpdatedAt($now);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($news);
        $entityManager->flush();
        return $news;
    }

    public function update(News $news)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($news);
        $entityManager->flush();
        return $news;
    }

    public function getNewsDetail($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function delete(News $news)
    {
        $entityManager = $this->getEntityManager();
        try {
            $entityManager->remove($news);
            $entityManager->flush();
            return true; 
        } catch (\Exception $e) {
            return false; 
        }
    }


//    /**
//     * @return News[] Returns an array of News objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?News
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
