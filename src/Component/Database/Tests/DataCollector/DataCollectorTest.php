<?php

namespace Pagekit\Component\Database\Tests\DataCollector;

use Pagekit\Component\Database\Connection;
use Pagekit\Component\Database\DataCollector\DatabaseDataCollector;
use Pagekit\Component\Database\Logging\DebugStack;
use Pagekit\Tests\DbTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataCollectorTest extends DbTestCase
{
    /**
     * @var DebugStack
     */
    protected $logger;

    /**
     * @var DatabaseDataCollector
     */
    protected $collector;

    /**
     * @var Connection
     */
    protected $connection;

    public function setUp()
	{
        parent::setUp();
		$this->logger = new DebugStack;
		$this->collector = new DatabaseDataCollector($this->connection, $this->logger);
	}

	public function testCollect()
	{
		$this->logger->startQuery('SELECT something FROM table');
		$this->logger->stopQuery();
		$this->collector->collect(new Request, new Response);

		$this->assertEquals(1, $this->collector->getQueryCount());
		$this->assertEquals('SELECT something FROM table', $this->collector->getQueries()[1]['sql']);
		$this->assertEquals($this->logger->queries[1]['executionMS'], $this->collector->getTime());
	}
}