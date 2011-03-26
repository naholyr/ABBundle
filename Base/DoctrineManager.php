<?php

namespace AB\ABBundle\Base;

use AB\ABBundle\Model\TestSuiteInterface;
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

    protected $test_suite_class_name;

    public function __construct(ObjectManager $object_manager, $repository_location, $test_suite_class_name)
    {
        $this->object_manager = $object_manager;
        $this->object_repository = $object_manager->getRepository($repository_location);
        $this->test_suite_class_name = $test_suite_class_name;
    }

    public function getActiveTestSuites()
    {
        return $this->object_repository->findBy(array('active' => true));
    }

    public function getTestSuite($uid)
    {
        return $this->object_repository->findOneBy(array('uid' => $uid));
    }

    public function persist(TestSuiteInterface $test_suite, $flush = true)
    {
        $this->object_manager->persist($test_suite);
        if ($flush) {
            $this->object_manager->flush();
        }
    }

    public function remove(TestSuiteInterface $test_suite, $flush = true)
    {
        $this->object_manager->remove($test_suite);
        if ($flush) {
            $this->object_manager->flush();
        }
    }

    public function newTestSuite($uid, array $versions = array('A', 'B'))
    {
        $class = $this->test_suite_class_name;

        return new $class($uid, $versions);
    }

}
