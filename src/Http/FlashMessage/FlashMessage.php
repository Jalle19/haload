<?php

namespace Jalle19\Haload\Http\FlashMessage;

/**
 * Class FlashMessage
 * @package Jalle19\Haload\Http\FlashMessage
 */
class FlashMessage
{

	const TYPE_SUCCESS = 'success';
	const TYPE_INFO    = 'info';
	const TYPE_WARNING = 'warning';
	const TYPE_ERROR   = 'error';

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $content;


	/**
	 * FlashMessage constructor.
	 *
	 * @param string $type
	 * @param string $content
	 */
	public function __construct($type, $content)
	{
		$this->type    = $type;
		$this->content = $content;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}
	

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

}
