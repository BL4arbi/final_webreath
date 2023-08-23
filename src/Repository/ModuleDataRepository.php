<?php

namespace App\Repository;

use App\Entity\Module;
use App\Entity\ModuleData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<\my_project\src\Entity\ModuleData>
 *
 * @method ModuleData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleData[]    findAll()
 * @method ModuleData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleData::class);
    }

    public function save(ModuleData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatestByModule(Module $module): ?ModuleData
    {
        return $this->createQueryBuilder('md')
            ->where('md.module = :module')
            ->setParameter('module', $module)
            ->orderBy('md.time', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
