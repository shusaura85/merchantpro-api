<?php

namespace MerchantPro;

use MerchantPro\Client;

class MerchantPro
{
	/** @var string */
	private $storeUrl = ''; // This is your store url. Example: 'https://www.merchantpro.ro' Do not include a trailing slash
	
	/** @var Client */
	protected $client;

	/** @var string */
	protected $token = '';
	/** @var bool */
	protected $verifyHost = true;
	/** @var bool */
	protected $verifyPeer = true;
	
	
	const MP_PRODUCTS   = '/api/v2/products';		// add /{id} to load specific product
	const MP_CATEGORIES = '/api/v2/categories';		// add /{id} to load specific category
	const MP_INVENTORY  = '/api/v2/inventory';		// add /{id} to load specific category
	const MP_ORDERS     = '/api/v2/orders';			// add /{id} to load specific category

	public function __construct($storeUrl, $apiKey, $apiSecret)
		{
		$this->storeUrl = $storeUrl;
		$this->token   = base64_encode($apiKey.':'.$apiSecret);
		}

	/**
	 * Use this if you need to skip host/peer verification
	 * @param bool $verifyHost
	 * @param bool $verifyPeer
	 */
	public function setVerify($verifyHost = true, $verifyPeer = true)
		{
		$this->verifyHost = $verifyHost;
		$this->verifyPeer = $verifyPeer;
		return $this;
		}
	
	
	private function init_client()
		{
		$this->client = new Client();
		$this->client->setVerify($this->verifyHost, $this->verifyPeer);
		
		// add authorization token
		$this->client->headers_add('Authorization', 'Basic '.$this->token);
		$this->client->headers_add('Accept', 'application/json');
		}
	
	/**
	* @param string $url API gateway
	* @param ?array $data array with data to send
	*/
	public function get($url)
		{
		$this->init_client();
		
		$responseString = $this->client->get($this->storeUrl . $url);
		
		return $responseString;
		}
	
	
	/**
	* @param string $url API gateway
	* @param ?array $data array with data to send
	*/
	public function post($url, $data = [])
		{
		$this->init_client();
		
		$responseString = $this->client->post_json($this->storeUrl . $url, $data);
		
		return $responseString;
		}
	
	
	/**
	* @param string $url API gateway
	* @param ?array $data array with data to send
	*/
	public function put($url, $data = [])
		{
		$this->init_client();
		
		$responseString = $this->client->set_put_request(true)->post_json($this->storeUrl . $url, $data);
		
		return $responseString;
		}
	
	
	/**
	* @param string $url API gateway
	* @param ?array $data array with data to send
	*/
	public function delete($url, $data = [])
		{
		$this->init_client();
		
		$responseString = $this->client->set_delete_request(true)->post_json($this->storeUrl . $url, $data);
		
		return $responseString;
		}
	

}
