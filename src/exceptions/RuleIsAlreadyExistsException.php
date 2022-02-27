<?php

namespace app\exceptions;

class RuleIsAlreadyExistsException extends \Exception
{
	protected $code = 422;
	protected $message = 'Rule is already exists';
}
