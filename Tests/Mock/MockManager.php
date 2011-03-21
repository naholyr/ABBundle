<?php

namespace AB\ABBundle\Tests\Mock;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;

use AB\ABBundle\Manager\ABTestSuiteManagerInterface;

class MockManager implements ABTestSuiteManagerInterface
{

    private $test_suites = array();

    public function addTestSuite(ABTestSuiteInterface $test_suite)
    {
        $this->test_suites[] = $test_suite;
    }

    public function getActiveTestSuites()
    {
        return $this->test_suites;
    }

    public function getTestSuite($uid)
    {
        $found = array_filter($this->test_suites,
            function(ABTestSuiteInterface $test_suite) use ($uid)
            {
                return $test_suite->getUID() == $uid;
            }
        );

        return count($found) > 0 ? $found[0] : null;
    }

}