<?php

namespace Jalle19\Haload\Http\FlashMessage;

/**
 * Class FlashMessageCollection
 * @package Jalle19\Haload\Http
 */
class FlashMessageCollection
{

	/**
	 * @var array
	 */
	private $messages = [];


	/**
	 * @param FlashMessage $message
	 */
	public function addMessage(FlashMessage $message)
	{
		$type = $message->getType();

		if (!array_key_exists($type, $this->messages)) {
			$this->messages[$type] = [];
		}

		$this->messages[$type][] = $message;
	}


	/**
	 * @return array
	 */
	public function getMessages()
	{
		return $this->messages;
	}


	/**
	 *
	 */
	public function reset()
	{
		$this->messages = [];
	}

}
