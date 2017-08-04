<?php

namespace Search\Controller;

use Search\Logger\ILogger;
use Search\Service\IClientService;
use Search\Exception\CaptchaException;

class Controller extends AbstractController
{
	/**
	 * @var IClientService
	 */
	protected $clientService;

	/**
	 * Controller constructor.
	 *
	 * @param IClientService $clientService
	 * @param ILogger $logger
	 * @param array $conf
	 */
	public function __construct(IClientService $clientService, ILogger $logger, array $conf) {
		parent::__construct($logger, $conf);
		$this->clientService = $clientService;
	}

	/**
	 * {@inheritdoc}
	 */
	public function mainAction(array $request) {
		return $this->renderHtml('main', [
			'search_service.name' => $this->clientService->getName(),
		]);
	}

	/**
	 * @param array $request
	 *
	 * @return boolean
	 */
	public function searchAction(array $request) {
		$query = $this->getTextFromRequest($request, 'query');

		try {
			$result = $this->clientService->search($query);
			$data = [
				'status' => 'ok',
				'result' => $result,
			];
			$this->logger->info(sprintf('Search is done, query: "%s", result count: %d', $query, count($result)));
		} catch (CaptchaException $exception) {
			$data = [
				'error' => 'Captcha detected, please try again later. ' . $this->clientService->getName(),
			];
			$this->logger->error($exception->getMessage());
		} catch (\Exception $exception) {
			$data = [
				'error' => 'Internal error: ' . $exception->getMessage() . ' ' . $this->clientService->getName(),
			];
			$this->logger->error($exception->getMessage());
		}

		return $this->renderJson($data);
	}
}