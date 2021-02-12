<?php
namespace ant\facebook\components;

use Yii;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Facebook\InstantArticles\Client\Client;
use Facebook\InstantArticles\Transformer\Transformer;
use Facebook\InstantArticles\Elements\InstantArticle as FacebookInstanceArticle;

class InstantArticle extends \yii\base\Component {
	public $appId;
	public $appSecret;
	public $pageId;
	public $storeAccessToken;
	public $accessToken;
	public $ruleFile;
	public $rules;
	public $debug = false;
	
	protected $client;
	protected $transformer;
	
	public function init() {
		$accessToken = $this->getAccessToken();

		if (!isset($this->accessToken)) {
			throw new \Exception('Please set access token property of the '.get_class($this).' component. ');
		}
		if (!isset($this->appId) || !isset($this->appSecret)) {
			throw new \Exception('Please set appId and appSecret property for '.get_class($this).'.');
		}
		if (!isset($this->pageId)) {
			throw new \Exception('Please set pageId property for '.get_class($this).'.');
		}
		if (!isset($this->ruleFile) && !isset($this->rules)) {
			throw new \Exception('Please set either ruleFile or rules property for '.get_class($this).'.');
		}

		$this->client = Client::create($this->appId, $this->appSecret, $accessToken, $this->pageId, $this->debug);
	}
	
	public function isAuthenticated() {
		$accessToken = $this->getAccessToken();
		$fb = new \Facebook\Facebook([
			'app_id' => $this->appId,           //Replace {your-app-id} with your app ID
			'app_secret' => $this->appSecret,   //Replace {your-app-secret} with your app secret
			'graph_api_version' => 'v6.0',
		]);
	  
	  
		try {

			// Get your UserNode object, replace {access-token} with your token
			$response = $fb->get('/me', $accessToken);

		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			// Returns Graph API errors when they occur
			// echo 'Graph returned an error: ' . $e->getMessage();
			// exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			// Returns SDK errors when validation fails or other local issues
			// echo 'Facebook SDK returned an error: ' . $e->getMessage();
			// exit;
		}

		$me = isset($response) ? $response->getGraphUser() : null;

		return isset($me);
	}

	public function storeAccessToken($accessToken) {
		return call_user_func_array($this->storeAccessToken, [$accessToken]);
	}
	
	public function getAccessToken() {
		if (isset($this->accessToken)) {
			if (is_callable($this->accessToken)) {
				$accessToken = call_user_func_array($this->accessToken, []);
				if (!isset($accessToken)) {
					throw new \Exception('Access token handler function return a null value.');
				}
				return $accessToken;
			} else {
				return $this->accessToken;
			}
		}
	}
	
	public function submitUrl($url) {
		$response = \Zttp\Zttp::get($url);
		
		if (!$response->isOk()) {
			throw new \Exception('Invalid page: '.$response->status());
		}
		$instantArticle = $this->transformHtml($response->body());
		
		$submittionId = $this->client->importArticle($instantArticle, true);
		return $submittionId;
	}
	
	public function publishPost($message, $url) {
		$fb = new \Facebook\Facebook([
			'app_id' => $this->appId,
			'app_secret' => $this->appSecret,
			//'default_graph_version' => 'v2.10',
		]);

		$linkData = [
			'link' => $url,
			'message' => $message,
		];

		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->post('/'.$this->pageId.'/feed', $linkData, $this->getAccessToken());
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$graphNode = $response->getGraphNode();

		echo 'Posted with id: ' . $graphNode['id'];
		
		return $graphNode;
	}
	
	public function checkSubmission($submittionId) {
		$status = $this->client->getSubmissionStatus($submittionId);
		
		return $status;
	}
	
	protected function transformHtml($html) {
		libxml_use_internal_errors(true);
		$document = new \DOMDocument();
		$document->loadHTML($html);
		libxml_use_internal_errors(false);
		
		// Create a transformer object
		$this->transformer = new Transformer();

		// Load the rules from a file if it is not set
		$rules = $this->rules ?? file_get_contents(Yii::getAlias($this->ruleFile), true);

		// Configure the transformer with the rules
		$this->transformer->loadRules($rules);

		// Invoke transformer on the HTML document
		$instantArticle = FacebookInstanceArticle::create();
		$this->transformer->transform($instantArticle, $document);
		
		return $instantArticle;
	}
}