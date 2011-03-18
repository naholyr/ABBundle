<?php

namespace AB\ABBundle\Tests\Mock;

use AB\ABBundle\Session\ABErrorNoVersionAvailable;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;

use AB\ABBundle\Session\ABSessionInterface;

class MockSession implements ABSessionInterface
{

    private $version = array();

    public function getVersion(ABTestSuiteInterface $test_suite)
    {
        $key = $test_suite->getUID();
        if (!isset($this->version[$key])) {
            $possibles = $test_suite->getAvailableVersions();
            if (count($possibles) == 0) {
                throw new ABErrorNoVersionAvailable();
            } else {
                $this->version[$key] = $possibles[array_rand($possibles)];
            }
        }

        return $this->version[$key];
    }

    public function setVersion(ABTestSuiteInterface $test_suite, $version)
    {
        $this->version[$test_suite->getUID()] = $version;
    }

}
