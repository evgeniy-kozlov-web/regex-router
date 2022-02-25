<?php

namespace app\routing;

class Route
{
	private string $method;
	private string $path;
	private string $target;
	private string $slug;

	public function __construct(
		string $method,
		string $path,
		string $target,
		string $slug,
	) {
		if (empty($slug)) throw new \app\exceptions\SlugIsEmptyException();

		$this->method = strtoupper($method);
		$this->path = $path;
		$this->target = $target;
		$this->slug = $this->createSlug($slug);
	}

	public function equals(Route $route): bool
	{
		return $this->method === $route->method && $this->path === $route->path && $this->target === $route->target && $this->slug === $route->slug;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getTarget(): string
	{
		return $this->target;
	}

	private function createSlug(string $slug): string
	{
		$slug = preg_replace('/[^\w+]/', '', $slug);
		$slug = trim($slug, '_');
		$slug = strtolower($slug);

		return $slug;
	}
}
