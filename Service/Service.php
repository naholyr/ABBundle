<?php

namespace AB\ABBundle\Service;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Document\TestSuite;
use AB\ABBundle\Model\SessionInterface;
use AB\ABBundle\Model\ManagerInterface;

class Service implements ServiceInterface
{

    private $manager;

    private $session;

    private $active_test_suites;

    private $current_test_suite;

    public function __construct(ManagerInterface $manager, SessionInterface $session)
    {
        $this->manager = $manager;
        $this->session = $session;
        $this->reloadActiveTestSuites();
    }

    public function reloadActiveTestSuites()
    {
        $this->active_test_suites = $this->manager->getActiveTestSuites();
        $this->current_test_suite = null;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function getSession()
    {
        return $this->session;
    }

    protected function getTestSuite($uid)
    {
        foreach ($this->active_test_suites as $test_suite) {
            if ($uid == $test_suite->getUID()) {
                return $test_suite;
            }
        }

        throw new ErrorTestSuiteNotFound();
    }

    public function setCurrentTestSuite($uid = false)
    {
        if (!$uid) {
            $this->current_test_suite = null;
        } elseif ($uid instanceof TestSuiteInterface) {
            $this->current_test_suite = $uid;
        } elseif (is_scalar($uid)) {
            $this->current_test_suite = $this->getTestSuite($uid);
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function getResource($resource, $uid = null)
    {
        if (is_null($uid) && is_null($this->current_test_suite)) {
            throw new ErrorNoCurrentTestSuite();
        }
        $test_suite = is_null($uid) ? $this->current_test_suite : $this->getTestSuite($uid);

        $version = $this->session->getVersion($test_suite);
        $replace = $test_suite->getResource($version, $resource);

        if (!is_null($replace)) {
            return $replace;
        } else {
            return $resource;
        }
    }

    public function addScore($points = +1, $uid = null)
    {
        if (is_null($uid) && is_null($this->current_test_suite)) {
            throw new ErrorNoCurrentTestSuite();
        }
        $test_suite = is_null($uid) ? $this->current_test_suite : $this->getTestSuite($uid);

        $version = $this->session->getVersion($test_suite);
        $test_suite->addScore($version, $points);
        $this->manager->persist($test_suite);
    }

}
