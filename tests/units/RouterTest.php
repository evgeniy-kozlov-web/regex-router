<?php

namespace tests\units;

use \app\routing\{Router, Route};

class RouterTest extends \PHPUnit\Framework\TestCase
{
	private Router $router;

	public function setUp(): void
	{
		$this->router = new Router();
	}

	public function testItIsDefaultByEmpty()
	{
		$this->assertEmpty($this->router->getRoutes());
	}

	public function testItCanAddRoutes()
	{
		$this->router->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'))->addRoute(new Route('GET', '/\/contacts/', 'contacts', 'contacts'));

		$this->assertEquals(
			2,
			count($this->router->getRoutes())
		);
	}

	public function testItCanGetRouteBySlug()
	{
		$route = new Route('GET', '/\/about/', 'test', 'about_us');
		$this->router->addRoute($route);

		$this->assertTrue($route->equals($this->router->getRoute('about_us')));
	}

	public function testItReturnsValidPaths()
	{
		$this->assertEquals(
			[
				'/\/about/'
			],
			$this->router->addRoute(new Route('GET', '/\/about/', 'about', 'about_us'))->getPaths()
		);
	}

	public function validPathsProvider()
	{
		return array(
			[
				new Route('GET', '/\/about/', 'about', 'about_us'),
				'/about'
			],
			[
				new Route('GET', '/[\d*]/', 'decimals', 'decimals'),
				'123123123'
			],
			[
				new Route('GET', '/[\w+]/', 'letters', 'letters'),
				'test_test'
			],
			[
				new Route('GET', '/[a-z*]/i', 'register', 'register'),
				'testTest'
			],
		);
	}

	/**
	 * @dataProvider validPathsProvider
	 */
	public function testItReturnsRouteByPath(Route $route, string $path)
	{
		$this->assertTrue($route->equals($this->router->addRoute($route)->getRouteByPath($path)['route']));
	}

	public function validPathsProviderWithMatches()
	{
		return array(
			[
				new Route('GET', '/\/about/', 'about', 'about_us'),
				'/about',
				[
					'/about'
				]
			],
			[
				new Route('GET', '/([\d*]+)/', 'decimals', 'decimals'),
				'123123123',
				[
					'123123123'
				]
			],
			[
				new Route('GET', '/([\w+]+)/', 'letters', 'letters'),
				'test_test',
				[
					'test_test'
				]
			],
			[
				new Route('GET', '/([a-z]+)/i', 'register', 'register'),
				'testTest',
				[
					'testTest'
				]
			],
		);
	}

	/**
	 * @dataProvider validPathsProviderWithMatches
	 */
	public function testItReturnsMatchesByPath(Route $route, string $path, array $matches)
	{
		$this->assertEquals(
			$matches,
			$this->router->addRoute($route)->getRouteByPath($path)['matches']
		);
	}

	public function invalidPathsProvider()
	{
		return array(
			[
				new Route('GET', '/\/about/', 'about', 'about_us'),
				'/not_about'
			],
			[
				new Route('GET', '/[\d*]/', 'decimals', 'decimals'),
				'test'
			],
			[
				new Route('GET', '/[\w+]/', 'letters', 'letters'),
				'----'
			],
			[
				new Route('GET', '/[a-z*]/i', 'register', 'register'),
				'123'
			],
		);
	}

	/**
	 * @dataProvider invalidPathsProvider
	 */
	public function testItThrowsPathIsNotExistsExceptionIfPathIsNotExists(Route $route, string $path)
	{
		$this->expectException(\app\exceptions\PathIsNotExistsException::class);

		$this->router->addRoute($route)->getRouteByPath($path);
	}

	public function testItThrowsSlugIsExistsExceptionIfSlugIsRepeats()
	{
		$this->expectException(\app\exceptions\SlugIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'))->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'));
	}

	public function testItThrowsPathIsExistsExceptionIfPathIsRepeats()
	{
		$this->expectException(\app\exceptions\PathIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'))->addRoute(new Route('GET', '/\/about/', 'test', 'not_about_us'));
	}

	public function testItThrowsSlugIsNotExistsExceptionIfSlugIsNotExists()
	{
		$this->expectException(\app\exceptions\SlugIsNotExistsException::class);

		$this->router->getRoute('test');
	}
}
