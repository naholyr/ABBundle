<?php

namespace AB\ABBundle\Base;

use AB\ABBundle\Model\SessionInterface;
use Symfony\Component\HttpFoundation\Session;
use AB\ABBundle\TestSuite\ABTestSuiteInterface;

class HttpSession implements SessionInterface
{

    protected $storage;

    public function __construct(Session $storage)
    {
        $this->storage = $storage;
    }

    public function getVersion(ABTestSuiteInterface $test_suite)
    {
        $key = $this->getKey($test_suite);
        if ($this->storage->has($key)) {
            $value = $this->storage->get($key);
        } else {
            $value = $this->randomize($test_suite);
            $this->storage->set($key, $value);
        }

        return $value;
    }

    private function getKey(ABTestSuiteInterface $test_suite)
    {
        return sprintf('ABBundle/Session/%s', $test_suite->getUID());
    }

    private function randomize(ABTestSuiteInterface $test_suite)
    {
        $possibles = $test_suite->getAvailableVersions();

        if (count($possibles) == 0) {
            throw new ABErrorNoVersionAvailable();
        }

        return $possibles[array_rand($possibles)];
    }

    public function setVersion(ABTestSuiteInterface $test_suite, $version)
    {
        $this->storage->set($this->getKey($test_suite), $version);
    }

}
