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
	
	
	const MP_BATCH      = '/api/v2/batch';			// used with POST request. Send up to 20 requests in one batch
	const MP_PRODUCTS   = '/api/v2/products';		// add /{id} to load specific product
	const MP_CATEGORIES = '/api/v2/categories';		// add /{id} to load specific category
	const MP_INVENTORY  = '/api/v2/inventory';		// add /{type}/{id} to load specific item by type (ex: /id/1234 /ean/5200000000001 /sku/VTR345)
	const MP_ORDERS     = '/api/v2/orders';			// add /{id} to load specific order
	const MP_CUSTOMERS  = '/api/v2/customers';		// add /{id} to load specific customer
	
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
		$this->client->set_verify($this->verifyHost, $this->verifyPeer);
		
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
	public function patch($url, $data = [])
		{
		$this->init_client();
		
		$responseString = $this->client->set_patch_request(true)->post_json($this->storeUrl . $url, $data);
		
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
	
	
	
	public function http_code()
		{
		$http_code = $this->client->response_info()['http_code'] ?? -1;

		$data = ['code' => $http_code, 'message' => ''];
		
		switch ($http_code)
			{
			case -1:	$data['message'] = 'No requests performed yet';
						break;
			case 200:	$data['message'] = 'Found and returned';
						break;
			case 201:	$data['message'] = 'Resource created';
						break;
			case 204:	$data['message'] = 'Resource deleted';
						break;
			case 400:	$data['message'] = 'Bad Request';
						break;
			case 401:	$data['message'] = 'Unauthorized';
						break;
			case 403:	$data['message'] = 'Forbidden';
						break;
			case 404:	$data['message'] = 'Resource not found';
						break;
			case 405:	$data['message'] = 'Method not allowed';
						break;
			case 429:	$data['message'] = 'Too many requests';
						break;
			case 500:	$data['message'] = 'Internal server error';
						break;
			default:	$data['message'] = 'Not a MerchantPro error code';
						break;
			
			}
		
		return $data;
		}

}
