<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // Créer la méthode getComments()
    public function getComments(Article $article)
    {
        //dd($article);

        //Copier+coller public function findByExampleField($value) : array (ligne 41 ⬇)
        // Créer le query builder avec ('a') correspondant à l'article
        return $this->createQueryBuilder('a')

            //Rendre les commentaires
            ->addSelect('comments')
            
            // Jointures entre les articles et les commentaires et appeler 'comments'
            ->leftJoin('a.comments', 'comments')
            // Récupérer la valeur de l'id des commentaires
            ->andWhere('comments.article = :val')
            ->setParameter('val', $article->getId())

            // Renvoyer le SQL
            ->getQuery()
            // Exécute et récupère le résultat
            ->getResult();
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
