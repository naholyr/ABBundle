<?php

namespace AB\ABBundle\Manager;

/**
 * AB tests manager: a "manager" will basically
 * handle the retrieving of test suites.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
use AB\ABBundle\TestSuite\ABTestSuiteInterface;

interface ABTestSuiteManagerInterface
{

    /**
     * Returns all available tests suites.
     *
     * @return array(ABTestSuiteInterface)
     */
    public function getActiveTestSuites();

    /**
     * Retrieves a test suite by its UID.
     *
     * @param string $uid
     *
     * @return ABTestSuiteInterface
     */
    public function getTestSuite($uid);

    /**
     * Saves and persist a test suite
     * 
     * @param ABTestSuiteInterface $test
     */
    public function persist(ABTestSuiteInterface $test_suite);
    
    /**
     * Removes a persisted test suite
     * 
     * @param ABTestSuiteInterface $test
     */
    public function remove(ABTestSuiteInterface $test_suite);
    
}
