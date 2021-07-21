<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
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

    public function save(News $news, bool $flush = true): void
    {
        $this->getEntityManager()->persist($news);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(News $news, bool $flush = true): void
    {
        $this->getEntityManager()->remove($news);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchByPeriod(string $fromDate, string $toDate, string $orderField, string $order, int $limit, int $offset): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $qb = $conn->createQueryBuilder();

        $sql = $qb
             ->select('n.*')
             ->from('news', 'n')
             ->where('DATE(n.created_at) BETWEEN :fromDate and :toDate')
             ->addOrderBy(sprintf('n.%s', $orderField), $order)
             ->setFirstResult($offset)
             ->setMaxResults($limit)
             ->getSql();

        return $this->getResult($sql, [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function searchByIds(array $ids, string $orderField, string $order, int $limit, int $offset): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $qb = $conn->createQueryBuilder();

        $sql = $qb
             ->select('n.*')
             ->from('news', 'n')
             ->where('n.id IN (:ids)')
             ->addOrderBy(sprintf('n.%s', $orderField), $order)
             ->setFirstResult($offset)
             ->setMaxResults($limit)
             ->getSql();


        return $this->getResult($sql, [
            'ids' => $ids,
        ]);
    }

    public function searchByPeriodAndIds(array $ids, string $fromDate, string $toDate, string $orderField, string $order, int $limit, int $offset): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $qb = $conn->createQueryBuilder();

        $sql = $qb
             ->select('n.*')
             ->from('news', 'n')
             ->where('DATE(n.created_at) BETWEEN :fromDate and :toDate')
             ->andWhere('n.id IN (:ids)')
             ->addOrderBy(sprintf('n.%s', $orderField), $order)
             ->setFirstResult($offset)
             ->setMaxResults($limit)
             ->getSql();

        return $this->getResult($sql, [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'ids' => $ids,
        ]);
    }

    public function findCountByPeriod(string $fromDate, string $toDate): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT DATE(created_at) as date, COUNT(id) as `count` FROM news WHERE DATE(created_at) BETWEEN :fromDate and :toDate GROUP BY DATE(created_at)';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);

        return $stmt->fetchAll();
    }

    private function getResult(string $sql, array $params): array
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(News::class, 'n');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters($params);

        return $query->getResult();
    }
}
