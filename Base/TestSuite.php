<?php

namespace AB\ABBundle\Base;

use AB\ABBundle\Model\TestSuiteInterface;

/**
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
abstract class TestSuite implements TestSuiteInterface
{

    /**
     * Unique UID, should not be an automatic ID to stay human-readable, as
     * it should be referenced in the code.
     *
     * @var string
     */
    protected $uid;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $versions = array();

    /**
     * @var array
     */
    protected $scores = array();

    /**
     * @var array
     */
    protected $replacements;

    /**
     * @var boolean
     */
    protected $active;

    public function __construct($uid, array $versions = array('A', 'B'), $description = "")
    {
        $this->uid = $uid;
        $this->description = $description;
        foreach ($versions as $version) {
            $this->addVersion($version);
        }
        $this->active = true;
    }

    public function getUID()
    {
        return $this->uid;
    }

    protected function checkVersion($version)
    {
        if (!in_array($version, $this->versions)) {
            throw new ABErrorUnavailableVersion();
        }
    }

    public function removeVersion($version)
    {
        $this->checkVersion($version);
    }

    public function addVersion($version)
    {
        $this->versions[] = $version;
        $this->scores[$version] = 0;
    }

    public function getAvailableVersions()
    {
        return $this->versions;
    }

    public function addReplacements($version, array $replacements)
    {
        $this->checkVersion($version);

        $this->replacements[$version] = array_merge(isset($this->replacements[$version]) ? $this->replacements[$version] : array(), $replacements);
    }

    public function setReplacements($version, array $replacements)
    {
        $this->checkVersion($version);

        $this->replacements[$version] = $replacements;
    }

    public function getReplacements($version)
    {
        $this->checkVersion($version);

        return isset($this->replacements[$version]) ? $this->replacements[$version] : null;
    }

    public function getResource($version, $resource)
    {
        $this->checkVersion($version);

        return isset($this->replacements[$version][$resource]) ? $this->replacements[$version][$resource] : null;
    }

    public function addScore($version, $points = +1)
    {
        $this->checkVersion($version);

        if (!isset($this->scores[$version])) {
            $this->scores[$version] = $points;
        } else {
            $this->scores[$version] += $points;
        }
    }

    public function getScores()
    {
        return $this->scores;
    }

    public function getScore($version)
    {
        $this->checkVersion($version);

        return isset($this->scores[$version]) ? $this->scores[$version] : 0;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setActive($boolean)
    {
        $this->active = $boolean;
    }

    public function isActive()
    {
        return $this->active;
    }

}
