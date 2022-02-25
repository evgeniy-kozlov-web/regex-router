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

	public function validRulesProvider()
	{
		return array(
			[
				new Route('GET', '/\/about/', 'about', 'about_us'),
				'/about',
				'GET'
			],
			[
				new Route('GET', '/[\d*]/', 'decimals', 'decimals'),
				'123123123',
				'GET'
			],
			[
				new Route('GET', '/[\w+]/', 'letters', 'letters'),
				'test_test',
				'GET'
			],
			[
				new Route('GET', '/[a-z*]/i', 'register', 'register'),
				'testTest',
				'GET'
			],
		);
	}

	/**
	 * @dataProvider validRulesProvider
	 */
	public function testItReturnsRouteByRule(Route $route, string $path, string $method)
	{
		$this->assertTrue($route->equals($this->router->addRoute($route)->getRouteByRule($path, $method)['route']));
	}

	public function validRulesProviderWithMatches()
	{
		return array(
			[
				new Route('GET', '/\/about/', 'about', 'about_us'),
				'/about',
				'GET',
				[
					'/about'
				]
			],
			[
				new Route('GET', '/([\d*]+)/', 'decimals', 'decimals'),
				'123123123',
				'GET',
				[
					'123123123'
				]
			],
			[
				new Route('GET', '/([\w+]+)/', 'letters', 'letters'),
				'test_test',
				'GET',
				[
					'test_test'
				]
			],
			[
				new Route('GET', '/([a-z]+)/i', 'register', 'register'),
				'testTest',
				'GET',
				[
					'testTest'
				]
			],
		);
	}

	/**
	 * @dataProvider validRulesProviderWithMatches
	 */
	public function testItReturnsMatchesByRule(Route $route, string $path, string $method, array $matches)
	{
		$this->assertEquals(
			$matches,
			$this->router->addRoute($route)->getRouteByRule($path, $method)['matches']
		);
	}

	public function invalidRulesProvider()
	{
		return array(
			[
				new Route('GET', '/\/about/', 'about', 'about_us'),
				'/not_about',
				'GET'
			],
			[
				new Route('GET', '/[\d*]/', 'decimals', 'decimals'),
				'test',
				'GET'
			],
			[
				new Route('GET', '/[\w+]/', 'letters', 'letters'),
				'----',
				'GET'
			],
			[
				new Route('GET', '/[a-z*]/i', 'register', 'register'),
				'123',
				'GET'
			],
		);
	}

	/**
	 * @dataProvider invalidRulesProvider
	 */
	public function testItReturnsFalseIfRuleIsNotExists(Route $route, string $path, string $method)
	{
		$this->assertFalse($this->router->addRoute($route)->getRouteByRule($path, $method));
	}

	public function testItThrowsSlugIsExistsExceptionIfSlugIsRepeats()
	{
		$this->expectException(\app\exceptions\SlugIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'))->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'));
	}

	public function testItThrowsRuleIsExistsExceptionIfRuleIsRepeats()
	{
		$this->expectException(\app\exceptions\RuleIsAlreadyExistsException::class);

		$this->router->addRoute(new Route('GET', '/\/about/', 'test', 'about_us'))->addRoute(new Route('GET', '/\/about/', 'test', 'not_about_us'));
	}

	public function testItReturnsFalseIfSlugIsNotExists()
	{
		$this->assertFalse($this->router->getRoute('test'));
	}
}
