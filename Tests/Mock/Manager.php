<?php

namespace AB\ABBundle\Tests\Mock;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Model\ManagerInterface;

class Manager implements ManagerInterface
{

    private $test_suites = array();

    public function getActiveTestSuites()
    {
        return $this->test_suites;
    }

    public function getTestSuite($uid)
    {
        $found = array_filter($this->test_suites, function(TestSuiteInterface $test_suite) use ($uid) {
            return $test_suite->getUID() == $uid;
        });

        return count($found) > 0 ? $found[0] : null;
    }

    public function persist(TestSuiteInterface $test_suite)
    {
        $this->test_suites[] = $test_suite;
    }

    public function remove(TestSuiteInterface $test_suite)
    {
        $found = null;
        foreach ($this->test_suites as $i => $persisted_test_suite) {
            if ($persisted_test_suite->getUID() == $test_suite->getUID()) {
                $found = $i;
                break;
            }
        }
        if (!is_null($found)) {
            unset($this->test_suites[$found]);
            $this->test_suites = array_values($this->test_suites); // FIX keys
        }
    }

}