<?php

namespace AB\ABBundle\Document;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Base\TestSuite as BaseTestSuite;

/**
 * @mongodb:Document(collection="ab_test_suites")
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
class TestSuite extends BaseTestSuite
{

    /**
     * @mongodb:Id
     */
    protected $id;

    /**
     * @mongodb:String
     * @mongodb:UniqueIndex(order="asc")
     */
    protected $uid;

    /**
     * @mongodb:String
     */
    protected $description;

    /**
     * @mongodb:Collection
     */
    protected $versions = array();

    /**
     * @mongodb:Hash
     */
    protected $scores = array();

    /**
     * @mongodb:Hash
     */
    protected $replacements;

    /**
     * @mongodb:Boolean
     */
    protected $active;

}
