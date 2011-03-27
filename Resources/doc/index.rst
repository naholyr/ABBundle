###########################################
A/B Testing Symfony2 Bundle - documentation
###########################################

A/B testing consists of providing different presentations to your users,
and choose the one that works best.

See `definition on Wikipedia <http://en.wikipedia.org/wiki/A/B_testing>`_.

This bundle is intended to provide you some shortcuts to ease automation of those
tests in your application.

************
Installation
************

Install the bundle as you're used to. For example if your project is versionned using Git: ::

   git add submodule "https://github.com/naholyr/ABBundle.git" "src/AB/ABBundle"

Add the bundle to your autoload: ::

   // app/autoload.php
   $loader->registerNamespace('AB', __DIR__.'/../src');

Enable the bundle in your application's kernel: ::

   // app/AppKernel.php
   // public function registerBundles() ...
   $bundles[] = new AB\ABBundle\ABBundle();

*************
Configuration
*************

There are two main components in this bundle:
#. The "manager" will handle the persistence layer.
#. The "session" will handle the affectation of test suites' version to a user.

Manager (persistence layer)
===========================

To store your test suites, you need to provide a persistence backend.

Two drivers are provided: ``odm`` (Doctrine MongoDB) or ``orm`` (Doctrine ORM).

Specify your driver in your configuration: ::

   # app/config/config.yml
   ab:
      db_driver: odm 

Customize model
---------------

The first thing you may want to configure is the ``TestSuite`` object itself.

To achieve this goal, write your own object model, that should at least implement 
``AB\ABBundle\Model\TestSuiteInterface``. You should override ``AB\ABBundle\Base\TestSuite``,
a basic, persistence agnostic, implementation. The default manager expects a constructor
compatible with this implementation: ::

   __construct($uid, array $versions = array('A', 'B'))

Then you must tell the manager to use this class, and the Doctrine manager to use
the corresponding repository: ::

   # app/config/config.yml
   ab:
      model_repository: "MyBundle:TestSuite"
      model_class: "Me\\MyBundle\\Entity\\TestSuite"

Customize manager
-----------------

To provide your own manager, just create yours implementing ``AB\ABBundle\Model\ManagerInterface``.

Your constructor must be compatible with ``AB\ABBundle\Base\DoctrineManager``'s one: ::

   __construct(ObjectManager $object_manager, $model_repository, $model_class)

Then declare its class name in your configuration: ::

   # app/config/config.yml
   ab:
      manager_class: "Me\\MyBundle\\MyOwnABManager"

Customize the whole persistence layer
-------------------------------------

If you don't use Doctrine, or wrote a manager which is incompatible with base implementation,
then you can use the "custom" DB driver, which means you will have to declare yourself the
``ab.manager`` service: ::

   # app/config/config.yml
   ab:
      db_driver: custom # means you will provide everything
      persistence_service: "the.persistence.service.id"
   services:
      ab.manager:
         class: "Me\\MyBundle\\MyOwnABManager"
         arguments:
            - "@ab.persistence"
            ... 

Session driver
==============

TODO

* Introduction
* Customizing the session driver
* Default HttpSession

*****
Usage
*****

Legacy documentation
====================

::

    ### Usage

    Standard use case: you want to test two different labels on a button, and check which one is best.

    1 - Create your test suite

        $ab = $this->get('ab');
        $m = $ab->getManager();
        $t = $m->newTestSuite('register_label'); // Default versions: A and B
        $t->addReplacements('A', array('Click here' => 'Click here to register for free')); // Version A
        $t->addReplacements('B', array('Click here' => 'Register now ! It\'s free !'));     // Version B
        $m->persist($t);

    2 - In your source page, get the label depending on version randomly stored in user's session

        $ab = $this->get('ab');
        $label = $ab->getResource('Click here', 'register_label');

    3 - In your target page, give points to the current version, which has brought you a user :)

        $ab = $this->get('ab');
        $ab->addScore(+1, 'register_label');

    4 - Check the scores, and make your choice wisely !

        $ab = $this->get('ab');
        $scores = $ab->getScores('register_label');
        $winner = $scores['A'] > $scores['B'] ? 'A' : 'B';
        $loser = $winner == 'A' ? 'B' : 'A';
        printf('%s won by %d points, against %d.', $winner, $scores[$winner], $scores[$loser]);

    #### Alternative usage

    Note that if you're going to call getResource(), addScore(), or getScores() more than once and
    don't want to repeat the UID each time, you can start by calling:

        $ab->setCurrentTestSuite('register_label');

    After this call, you can omit UID in those methods.
