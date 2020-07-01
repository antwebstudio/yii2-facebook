<?php
namespace ant\facebook\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ConnectButton extends \yii\base\Widget {
	public $accessTokenProcessUrl = ['/facebook/js/logged-in'];
	public $appId;
	public $appSecret;
	public $toggleButton = [
		'label' => 'Connect Facebook',
		'options' => ['class' => 'btn btn-primary'],
	];
	
	public function init() {
		if (isset(Yii::$app->facebook)) {
			if (!isset($this->appId)) $this->appId = Yii::$app->facebook->appId;
			if (!isset($this->appSecret)) $this->appSecret = Yii::$app->facebook->appSecret;
		}
	}
	
	public function run() {
		$options = $this->toggleButton['options'];
		
		$id = $options['id'] = $this->id;
		
		$html = Html::a($this->toggleButton['label'], 'javascript:;', $options);
		
		//$this->view->registerJs("
		
		$html .= "<script>
		  window.fbAsyncInit = function() {
			FB.init({
			  appId            : '" . $this->appId . "',
			  autoLogAppEvents : true,
			  xfbml            : true,
			  version          : 'v6.0'
			});
			
			FB.getLoginStatus(function(response) {
				console.log(response);
			});
		  };
		  
		  document.querySelector('#".$id."').addEventListener('click', function() {
			 loginFacebook(); 
		  });
		  
		  function loginFacebook() {
			FB.login(function(response) {
				if (response.authResponse) {
				 console.log('Welcome!  Fetching your information.... ');
				   console.log(response);
				 FB.api('/me', function(profile) {
				   console.log('Good to see you, ' + profile.name + '.');
				   var token = response.authResponse.accessToken;
				   
				   $.get('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=" . $this->appId . "&client_secret=" . $this->appSecret . "&fb_exchange_token=' + token, function(data) {
						console.log(data);
						$.post('" . Url::to($this->accessTokenProcessUrl) . "', {response: response, longLifeResponse: data}, function() {
						   //alert('Okay');
					   });
				   });
				   
				 });
				} else {
				 console.log('User cancelled login or did not fully authorize.');
				}
			}, {scope: 'pages_manage_instant_articles, pages_show_list'});
		  }
		</script>";
		
		//");
		
		$html .= '<script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>';
		
		return $html;
	}
}