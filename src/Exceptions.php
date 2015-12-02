<?php

/**
 * @author: Albaraa Mishlawi
 * @package: albaraam/php-gcm
 */

namespace albaraam\gcm;

class Exception extends \Exception {}

class LogicException extends Exception {}

class RuntimeException extends Exception {}

class IlegalApiKeyException extends LogicException {}

class TooManyRecipientsException extends LogicException {}

class TooBigPayloadException extends LogicException {}

class WrongGcmIdException extends LogicException {}

class HttpException extends RuntimeException {}

class AuthenticationException extends RuntimeException {}