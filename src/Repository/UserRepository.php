<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Scienta\DoctrineJsonFunctions\Query\AST\Functions\Mysql;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    
    public function findWithoutLeague()
    {
        //$qb = $this->createQueryBuilder('u');
        return $this->getEntityManager()->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.roles NOT LIKE :roles
            AND u.id NOT IN (
                SELECT us.id 
                FROM App\Entity\User us 
                INNER JOIN App\Entity\League l WITH l.responsible = us
            )
            AND u.id NOT IN (
                SELECT uc.id
                FROM App\Entity\User uc
                INNER JOIN App\Entity\Club c WITH c.secretary = uc
            )'
        )
        ->setParameter("roles", '[\"%ROLE_ADMIN%\"]')
        ->getResult();
    }

    
    public function getUsersWithLeague()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.roles NOT LIKE :roles
            AND u.id IN (
                SELECT us.id 
                FROM App\Entity\User us 
                INNER JOIN App\Entity\League l WITH l.responsible = us
            )'
        )
        ->setParameter("roles", '[\"%ROLE_ADMIN%\"]')
        ->getResult();
    }
    
}
