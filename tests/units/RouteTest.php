<?php

namespace tests\units;

use \app\routing\Route;

class RouteTest extends \PHPUnit\Framework\TestCase
{
	public function testItCanReturnsValidCredentials()
	{
		$route = new Route('GET', '/about', 'test', 'about_us');

		$this->assertEquals(
			'GET',
			$route->getMethod()
		);

		$this->assertEquals(
			'/about',
			$route->getPath()
		);

		$this->assertEquals(
			'test',
			$route->getTarget()
		);

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItSavesValidSlug()
	{
		$route = new Route('GET', '/about', 'test', ' _ _a$bo/*ut_u@s_ _ ');

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItConvertSlugToLowerCase()
	{
		$route = new Route('GET', '/about', 'test', 'ABOUT_US');

		$this->assertEquals(
			'about_us',
			$route->getSlug()
		);
	}

	public function testItConvertMethodToUpperCase()
	{
		$route = new Route('get', '/about', 'test', 'about_us');

		$this->assertEquals(
			'GET',
			$route->getMethod()
		);
	}

	public function testItThrowsSlugIsEmptyExceptionIfSlugIsEmpty()
	{
		$this->expectException(\app\exceptions\SlugIsEmptyException::class);

		$route = new Route('GET', '/about', 'test', '');
	}
}
