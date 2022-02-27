<?php

namespace app\exceptions;

class SlugIsEmptyException extends \Exception
{
	protected $code = 422;
	protected $message = 'Slug is empty';
}
