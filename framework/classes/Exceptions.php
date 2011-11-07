<?php

/*Base exception class for Http Exceptions*/
class HttpError extends Exception
{
    public function __construct($code, $message = null)
    {
        parent::__construct($message, $code);
    }
}

/*400 error*/
class BadRequest extends HttpError
{
    public function __construct($message = null)
    {
        parent::__construct(400, $message);
    }
}

/*401 error*/
class Unauthorized extends HttpError
{
    public function __construct($message = null)
    {
        parent::__construct(401, $message);
    }
}

/*403 error*/
class Forbidden extends HttpError
{
    public function __construct($message = null)
    {
        parent::__construct(403, $message);
    }
}

/*404 error*/
class NotFound extends HttpError
{
    public function __construct($message = null)
    {
        parent::__construct(404, $message);
    }
}

/*405 error*/
class MethodNotAllowed extends HttpError
{
    public function __construct($message = null)
    {
        parent::__construct(405, $message);
    }
}

/*500 error*/
class InternalError extends HttpError
{
    public function __construct($message = null)
    {
        parent::__construct(500, $message);
    }
}
