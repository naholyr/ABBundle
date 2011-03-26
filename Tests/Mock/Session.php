<?php

namespace AB\ABBundle\Tests\Mock;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Model\ErrorNoVersionAvailable;
use AB\ABBundle\Model\SessionInterface;

/**
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
class Session implements SessionInterface
{

    private $version = array();

    public function getVersion(TestSuiteInterface $test_suite)
    {
        $key = $test_suite->getUID();
        if (!isset($this->version[$key])) {
            $possibles = $test_suite->getAvailableVersions();
            if (count($possibles) == 0) {
                throw new ErrorNoVersionAvailable();
            } else {
                $this->version[$key] = $possibles[array_rand($possibles)];
            }
        }

        return $this->version[$key];
    }

    public function setVersion(TestSuiteInterface $test_suite, $version)
    {
        $this->version[$test_suite->getUID()] = $version;
    }

}
