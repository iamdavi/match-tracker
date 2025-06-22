<?php

namespace App\Repository;

use App\Entity\UserTeamRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTeamRole>
 *
 * @method UserTeamRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTeamRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTeamRole[]    findAll()
 * @method UserTeamRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTeamRoleRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, UserTeamRole::class);
	}

	public function save(UserTeamRole $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(UserTeamRole $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	//    /**
	//     * @return UserTeamRole[] Returns an array of UserTeamRole objects
	//     */
	//    public function findByExampleField($value): array
	//    {
	//        return $this->createQueryBuilder('u')
	//            ->andWhere('u.exampleField = :val')
	//            ->setParameter('val', $value)
	//            ->orderBy('u.id', 'ASC')
	//            ->setMaxResults(10)
	//            ->getQuery()
	//            ->getResult()
	//        ;
	//    }

	//    public function findOneBySomeField($value): ?UserTeamRole
	//    {
	//        return $this->createQueryBuilder('u')
	//            ->andWhere('u.exampleField = :val')
	//            ->setParameter('val', $value)
	//            ->getQuery()
	//            ->getOneOrNullResult()
	//        ;
	//    }
}
