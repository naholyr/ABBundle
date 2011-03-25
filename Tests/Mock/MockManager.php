<?php

namespace AB\ABBundle\Tests\Mock;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;

use AB\ABBundle\Manager\ABTestSuiteManagerInterface;

class MockManager implements ABTestSuiteManagerInterface
{

    private $test_suites = array();

    public function getActiveTestSuites()
    {
        return $this->test_suites;
    }

    public function getTestSuite($uid)
    {
        $found = array_filter($this->test_suites, function(ABTestSuiteInterface $test_suite) use ($uid) {
            return $test_suite->getUID() == $uid;
        });

        return count($found) > 0 ? $found[0] : null;
    }

    public function persist(ABTestSuiteInterface $test_suite)
    {
        $this->test_suites[] = $test_suite;
    }

    public function remove(ABTestSuiteInterface $test_suite)
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