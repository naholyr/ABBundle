<?php

use AB\ABBundle\Tests\Mock\MockTestSuite;
require_once __DIR__ . '/../bootstrap.php';

use AB\ABBundle\Tests\Mock\MockSession;
use AB\ABBundle\Tests\Mock\MockManager;

class MockTestCase extends PHPUnit_Framework_TestCase
{

    private $manager;
    private $session;
    private $test_suite;

    public function __construct()
    {
        $this->manager = new MockManager();

        $this->session = new MockSession();

        $this->test_suite = new MockTestSuite('colors', array('red', 'blue'));
        $this->test_suite->addReplace('red', array('white' => 'red'));
        $this->test_suite->addReplace('blue', array('white' => 'blue'));

        $this->manager->addTestSuite($this->test_suite);
    }

    public function testManager()
    {
        $this->assertEquals($this->manager->getActiveTestSuites(), array($this->test_suite));
        $this->assertEquals($this->manager->getTestSuite('colors'), $this->test_suite);
    }

    public function testSession()
    {
        $version1 = $this->session->getVersion($this->test_suite);
        $this->assertTrue(in_array($version1, $this->test_suite->getAvailableVersions()));
        $version2 = $this->session->getVersion($this->test_suite);
        $this->assertEquals($version1, $version2);
    }

    public function testResources()
    {
        $this->assertEquals($this->test_suite->getResource('red', 'white'), 'red');
        $this->assertEquals($this->test_suite->getResource('blue', 'white'), 'blue');
        $this->assertEquals($this->test_suite->getResource('red', 'black'), 'black');
        $this->assertEquals($this->test_suite->getResource('blue', 'black'), 'black');
    }

    public function testInvalidVersion()
    {
        $this->setExpectedException('AB\\ABBundle\\TestSuite\\ABErrorUnavailableVersion');
        $this->test_suite->getResource('orange', 'white');
    }

    public function testScore()
    {
        $this->test_suite->addScore('red');
        $this->test_suite->addScore('blue', +2);
        $scores = $this->test_suite->getScores();
        $this->assertEquals($scores['red'], 1);
        $this->assertEquals($scores['blue'], 2);
    }

}

