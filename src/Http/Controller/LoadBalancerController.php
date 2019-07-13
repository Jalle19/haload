<?php

namespace Jalle19\Haload\Http\Controller;

use Jalle19\Haload\Configuration\Haproxy\LoadBalancer;
use Jalle19\Haload\Http\FlashMessage\FlashMessage;
use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class LoadBalancerController
 * @package Jalle19\Haload\Http\Controller
 */
class LoadBalancerController extends AbstractController
{

	/**
	 * @return ResponseInterface
	 * @throws HttpMethodNotAllowedException
	 */
	public function createAction()
	{
		switch ($this->request->getMethod()) {
			case 'GET':
				// Serve the form
				$body = $this->templateEngine->render('loadbalancer::create', [
					'title' => 'Create load balancer',
				]);

				return $this->createResponse($body);
			case 'POST':
				// Handle the form, then redirect to the dashboard
				$postData = $this->request->getParsedBody();

				if (!isset($postData['name']) || empty($postData['name'])) {
					$this->flashMessages->addMessage(new FlashMessage(FlashMessage::TYPE_ERROR, 'Name is required'));

					return $this->redirect($this->request->getRequestTarget());
				} else {
					$loadBalancer = new LoadBalancer($postData['name']);

					$this->flashMessages->addMessage(new FlashMessage(FlashMessage::TYPE_SUCCESS,
						'Load balancer created successfully'));

					return $this->redirect('/');
				}
			default:
				throw new HttpMethodNotAllowedException();
		}
	}

}
