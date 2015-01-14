<?php

namespace AnnotateTests\Themes;

use Tester;


class TestCase extends Tester\TestCase
{

	/**
	 * @var \Mockista\Registry
	 */
	protected $mockista;



	protected function setUp()
	{
		$this->mockista = new \Mockista\Registry;
	}



	protected function tearDown()
	{
		if ($this->mockista) {
			$this->mockista->assertExpectations();
		}
	}

}
