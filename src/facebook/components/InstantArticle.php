<?php
namespace ant\facebook\components;

use Yii;
use Facebook\InstantArticles\Client\Client;
use Facebook\InstantArticles\Transformer\Transformer;
use Facebook\InstantArticles\Elements\InstantArticle as FacebookInstanceArticle;

class InstantArticle extends \yii\base\Component {
	public $appId;
	public $appSecret;
	public $pageId;
	public $accessToken;
	public $ruleFile;
	public $debug = false;
	
	protected $client;
	protected $transformer;
	
	public function init() {
		$accessToken = $this->getAccessToken();
		
		if (isset($accessToken)) {
			$this->client = Client::create($this->appId, $this->appSecret, $accessToken, $this->pageId, $this->debug);
		}
	}
	
	public function isAuthenticated() {
		return isset($this->client);
	}
	
	public function getAccessToken() {
		return call_user_func_array($this->accessToken, []);
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

		// Load the rules from a file
		$rules = file_get_contents(Yii::getAlias($this->ruleFile), true);

		// Configure the transformer with the rules
		$this->transformer->loadRules($rules);

		// Invoke transformer on the HTML document
		$instantArticle = FacebookInstanceArticle::create();
		$this->transformer->transform($instantArticle, $document);
		
		return $instantArticle;
	}
}