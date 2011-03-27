<?php

namespace AB\ABBundle\Tests\Mock;

use Symfony\Component\Locale\Exception\NotImplementedException;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Model\ErrorUnavailableVersion;

/**
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
class TestSuite implements TestSuiteInterface
{

    private $uid = null;

    private $scores = array();

    private $replace = array();

    public function __construct($uid, array $versions)
    {
        $this->uid = $uid;
        foreach ($versions as $version) {
            $this->replace[$version] = array();
            $this->scores[$version] = 0;
        }
    }

    public function addReplacements($version, array $replace)
    {
        if (!in_array($version, $this->getAvailableVersions())) {
            throw new ErrorUnavailableVersion();
        }

        $this->replace[$version] = array_merge($this->replace[$version], $replace);
    }

    public function getUID()
    {
        return $this->uid;
    }

    public function getAvailableVersions()
    {
        return array_keys($this->scores);
    }

    public function getResource($version, $resource)
    {
        if (!in_array($version, $this->getAvailableVersions())) {
            throw new ErrorUnavailableVersion();
        }

        return @$this->replace[$version][$resource];
    }

    public function addScore($version, $points = +1)
    {
        if (!in_array($version, $this->getAvailableVersions())) {
            throw new ErrorUnavailableVersion();
        }

        $this->scores[$version] += $points;
    }

    public function getScores()
    {
        return $this->scores;
    }

    public function isActive()
    {
        return true;
    }

    public function setActive($boolean)
    {
        throw new NotImplementedException();
    }

}
