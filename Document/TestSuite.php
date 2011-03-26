<?php

namespace AB\ABBundle\Document;

use AB\ABBundle\Model\TestSuiteInterface;

/**
 * @mongodb:Document(collection="ab_test_suites")
 * @author Nicolas Chambrier <naholyr@gmail.com>
 *
 */
class TestSuite implements TestSuiteInterface
{

    /**
     * @mongodb:Id
     */
    private $id;

    /**
     * @mongodb:String
     * @mongodb:UniqueIndex(order="asc")
     */
    private $uid;

    /**
     * @mongodb:String
     */
    private $description;

    /**
     * @mongodb:Collection
     */
    private $versions = array();

    /**
     * @mongodb:Hash
     */
    private $scores = array();

    /**
     * @mongodb:Hash
     */
    private $replacements;

    /**
     * @mongodb:Boolean
     */
    private $active;

    public function __construct($uid, array $versions = array('A', 'B'), $description = "")
    {
        $this->uid = $uid;
        $this->description = $description;
        foreach ($versions as $version) {
            $this->addVersion($version);
        }
    }
    
    public function getUID()
    {
        return $this->uid;
    }

    private function checkVersion($version)
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

        $this->replacements[$version] = array_merge(@$this->replacements[$version] ?: array(), $replacements);
    }

    public function setReplacements($version, array $replacements)
    {
        $this->checkVersion($version);

        $this->replacements[$version] = $replacements;
    }

    public function getResource($version, $resource)
    {
        $this->checkVersion($version);

        return @$this->replacements[$version][$resource] ?: $resource;
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

}
