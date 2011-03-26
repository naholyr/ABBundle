<?php

use AB\ABBundle\Tests\Mock\TestSuite;
use AB\ABBundle\Tests\Mock\Session;
use AB\ABBundle\Tests\Mock\Manager;

class MockTestCase extends PHPUnit_Framework_TestCase
{

    private $manager;
    private $session;
    private $test_suite;

    public function __construct()
    {
        $this->manager = new Manager();

        $this->session = new Session();

        $this->test_suite = new TestSuite('colors', array('red', 'blue'));
        $this->test_suite->addReplace('red', array('white' => 'red'));
        $this->test_suite->addReplace('blue', array('white' => 'blue'));

        $this->manager->persist($this->test_suite);
    }

    public function testManager()
    {
        $this->assertEquals($this->manager->getActiveTestSuites(), array($this->test_suite));
        $this->assertEquals($this->manager->getTestSuite('colors'), $this->test_suite);

        $this->manager->remove($this->test_suite);
        $this->assertEquals($this->manager->getActiveTestSuites(), array());

        $this->manager->persist($this->test_suite);
        $this->assertEquals($this->manager->getActiveTestSuites(), array($this->test_suite));
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
        $this->assertEquals($this->test_suite->getResource('red', 'black'), null);
        $this->assertEquals($this->test_suite->getResource('blue', 'black'), null);
    }

    public function testInvalidVersion()
    {
        $this->setExpectedException('AB\\ABBundle\\Model\\ErrorUnavailableVersion');
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
