<?php

namespace Jalle19\Haload\Http\Controller;

use Psr\Http\Message\ResponseInterface;

/**
 * Class StaticAssetController
 * @package Jalle19\Haload\Http\Controller
 */
class StaticAssetController extends AbstractController
{

	/**
	 * @var array
	 */
	private static $cachedStyles = [];

	/**
	 * @var array
	 */
	private static $cachedScripts = [];


	/**
	 * @inheritDoc
	 */
	protected function init()
	{
		// Cache styles and scripts
		if (empty(self::$cachedStyles)) {
			self::$cachedStyles = $this->getConcatenatedStyles();
		}

		if (empty(self::$cachedScripts)) {
			self::$cachedScripts = $this->getConcatenatedScripts();
		}
	}


	/**
	 * @return ResponseInterface
	 */
	public function stylesAction()
	{
		return $this->createResponse(self::$cachedStyles, 200, [
			'Content-Type' => 'text/css',
		]);
	}


	/**
	 * @return ResponseInterface
	 */
	public function scriptsAction()
	{
		return $this->createResponse(self::$cachedScripts, 200, [
			'Content-Type' => 'application/javascript',
		]);
	}


	/**
	 * @return string
	 */
	private function getConcatenatedStyles()
	{
		$styles = [
			file_get_contents(__DIR__ . '/../../../bower_components/bootstrap/dist/css/bootstrap.css'),
			file_get_contents(__DIR__ . '/../../../templates/css/styles.css'),
		];

		return implode("\n", $styles);
	}


	/**
	 * @return string
	 */
	private function getConcatenatedScripts()
	{
		$scripts = [
			file_get_contents(__DIR__ . '/../../../bower_components/jquery/dist/jquery.js'),
			file_get_contents(__DIR__ . '/../../../bower_components/bootstrap/dist/js/bootstrap.js'),
		];

		return implode("\n", $scripts);
	}

}
