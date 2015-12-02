<?php

/**
 * @author: Albaraa Mishlawi
 * @package: albaraam/php-gcm
 */

namespace albaraam\gcm;

use albaraam\gcm\AuthenticationException;
use albaraam\gcm\HttpException;
use albaraam\gcm\IlegalApiKeyException;
use albaraam\gcm\TooBigPayloadException;
use albaraam\gcm\TooManyRecipientsException;

class GCMClient {

	/**
	 * @var string
	 */

	protected $url = 'https://android.googleapis.com/gcm/send';
	
	/**
	 * @var string
	 */
	protected $apiKey = false;

	/**
	 * Recipients - List of GCM registration IDs (from 1 to 1000 recipients allowed)
	 * @var array
	 */
	private $to = array();

	
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}
	
	public function send($toRegId, GCMMessage $message)
	{
		if(!$message->isValid()){
			return $message->getErrors();
		}

		if (!$this->apiKey) {
			throw new IlegalApiKeyException("Api Key is empty");
		}

		if(is_array($toRegId)) {
			foreach ($toRegId as $to)
			{
				$this->addTo($to);
			}
		} elseif($toRegId) {
			$this->setTo($toRegId);
		}

		if (count($this->getTo()) > 1000)
		{
			throw new TooManyRecipientsException("Recipients maximum is 1000 GCM Registration IDs");
		}

		$data = $this->generateJSONMessage($message);

		if(!empty($data))
		{
			if (strlen($data) > 4096)
				throw new TooBigPayloadException("Data payload maximum is 4096 bytes");
		}

		$headers = array(
			'Content-Type: application/json',
			'Authorization: key='.$this->apiKey,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$resultBody = curl_exec($ch);
		$resultHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		switch ($resultHttpCode)
		{
			case "200":
				break; // its ok
			case "400":
			case "401":
				throw new AuthenticationException("HTTP Authentication Error", $resultHttpCode);
			default:
				throw new HttpException("HTTP Error", $resultHttpCode);
		}

		$response =  new GCMResponse($message, $resultBody);

		return $response;
	}
	
	protected function generateJSONMessage(GCMMessage $message)
	{
		$data = array(
			'registration_ids' => (array) $this->getTo(),
			'notification' => (array) $message->getNotification(),
			'data' => (array) $message->getData(),
			'collapse_key' => $message->getCollapseKey(),
			'delay_while_idle' => $message->getDelayWhileIdle(),
			'time_to_live' => $message->getTimeToLive(),
			'restricted_package_name' => $message->getRestrictedPackageName(),
			'dry_run' => $message->getDryRun(),
		);
		return json_encode($data);
	}

	public function getTo($onlyOne = false)
	{
		if($onlyOne)
			return current($this->to); // firstone
		return $this->to;
	}


	public function setTo($to)
	{
		$this->to = [];
		$this->addTo($to);

		return $this;
	}


	public function addTo($to)
	{
		if(!is_string($to))
			throw new WrongGcmIdException("Recipient must be string GCM Registration ID");

		$this->to[] = $to;
		return $this;
	}
}