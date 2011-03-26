<?php

namespace AB\ABBundle\Base;

use AB\ABBundle\Model\ErrorNoVersionAvailable;
use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Model\SessionInterface;
use Symfony\Component\HttpFoundation\Session;

/**
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
class HttpSession implements SessionInterface
{

    protected $storage;

    protected $key_prefix;

    public function __construct(Session $storage, $key_prefix = 'ABBundle/Session/')
    {
        $this->storage = $storage;
        $this->key_prefix = $key_prefix;
    }

    public function getVersion(TestSuiteInterface $test_suite)
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

    private function getKey(TestSuiteInterface $test_suite)
    {
        return $this->key_prefix . $test_suite->getUID();
    }

    private function randomize(TestSuiteInterface $test_suite)
    {
        $possibles = $test_suite->getAvailableVersions();

        if (count($possibles) == 0) {
            throw new ErrorNoVersionAvailable();
        }

        return $possibles[array_rand($possibles)];
    }

    public function setVersion(TestSuiteInterface $test_suite, $version)
    {
        $this->storage->set($this->getKey($test_suite), $version);
    }

}
