<?php
namespace OpenActu\UrlBundle\Exceptions;

interface InvalidUrlExceptionInterface
{
	const INVALID_SCHEME_FORMAT_MESSAGE 	= 'the current scheme is invalid (given "%name%")';
	const INVALID_SCHEME_FORMAT_CODE	= 404;
}
