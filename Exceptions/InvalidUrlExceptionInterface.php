<?php
namespace OpenActu\UrlBundle\Exceptions;

interface InvalidUrlExceptionInterface
{
	const INVALID_SCHEME_FORMAT_MESSAGE 	= 'the current scheme is invalid (given "%name%"). Check your configuration to accept this scheme';
	const INVALID_SCHEME_FORMAT_CODE	= 404;
        const NO_SCHEME_FOUND_MESSAGE		= 'no scheme given';
	const NO_SCHEME_FOUND_CODE		= 405;
	const INVALID_HOST_MESSAGE		= 'the current host is invalid (given "%name%")';
	const INVALID_HOST_CODE			= 406;
	const NO_HOST_FOUND_MESSAGE		= 'no host given';
	const NO_HOST_FOUND_CODE		= 407;
	const INVALID_PORT_FORMAT_MESSAGE	= 'the current port is invalid (given "%name%")';
	const INVALID_PORT_FORMAT_CODE		= 408;
        const INVALID_PORT_MODE_DEFINED_MESSAGE = 'invalid port mode defined (given "%name%")';
	const INVALID_PORT_MODE_DEFINED_CODE	= 409;
        const INVALID_PATH_MESSAGE		= 'the current path is invalid (given "%name%")';
	const INVALID_PATH_CODE			= 410;
        const INVALID_VALIDATION_CODE		= 411;
        const INVALID_SANITIZE_MESSAGE		= 'the current url can\'t be sanitized (given "%name%")';
	const INVALID_SANITIZE_CODE		= 412;
}
