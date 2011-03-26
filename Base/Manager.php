<?php

namespace AB\ABBundle\Base;

use AB\ABBundle\Model\ManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class DoctrineManager implements ManagerInterface
{

    /**
     * @var ObjectManager
     */
    protected $object_manager;

    /**
     * @var ObjectRepository
     */
    protected $object_repository;

    public function __construct(ObjectManager $object_manager, $repository_location)
    {
        $this->object_manager = $object_manager;
        $this->repository = $object_manager->getRepository($repository_location);
    }

    public function getActiveTestSuites()
    {
        return $this->object_repository->findBy(array('active' => true));
    }

    public function getTestSuite($uid)
    {
        return $this->object_repository->findOneBy(array('uid' => $uid));
    }

    public function persist(ABTestSuiteInterface $test_suite, $flush = false)
    {
        $this->object_manager->persist($test_suite);
        if ($flush) {
            $this->object_manager->flush();
        }
    }

    public function remove(ABTestSuiteInterface $test_suite, $flush = false)
    {
        $this->object_manager->remove($test_suite);
        if ($flush) {
            $this->object_manager->flush();
        }
    }

}