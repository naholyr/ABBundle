<?php

namespace AB\ABBundle\Manager;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;

class ABTestSuiteManagerODM implements ABTestSuiteManagerInterface
{

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->dm->getRepository('ABBundle:ABTestSuiteDoctrineODMImpl');
    }

    public function getActiveTestSuites()
    {
        return $this->getRepository()->findBy(array('active' => true));
    }

    public function getTestSuite($uid)
    {
        return $this->getRepository()->findOneBy(array('uid' => $uid));
    }

    public function persist(ABTestSuiteInterface $test_suite, $flush = false)
    {
        $this->dm->persist($test_suite);
        if ($flush) {
            $this->dm->flush();
        }
    }

    public function remove(ABTestSuiteInterface $test_suite, $flush = false)
    {
        $this->dm->remove($test_suite);
        if ($flush) {
            $this->dm->flush();
        }
    }

}