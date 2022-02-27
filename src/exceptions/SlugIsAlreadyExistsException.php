<?php

namespace app\exceptions;

class SlugIsAlreadyExistsException extends \Exception
{
	protected $code = 422;
	protected $message = 'Slug is already exists';
}
