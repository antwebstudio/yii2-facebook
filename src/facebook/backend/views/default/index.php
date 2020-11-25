<?php
use yii\helpers\url;

if (Yii::$app->request->isAjax) {
	$data = Yii::$app->request->post();
	file_put_contents(__DIR__ .'/fb-data.php', '<?php die(); '."\n".var_export($_POST, true));
	Yii::$app->cache->set('fb', $data);
	die(__DIR__ .'/fb-data.php');
}
$data = Yii::$app->cache->get('fb');
\yii\web\JqueryAsset::register($this);

$show = isset($_GET['show']);

// 10158131975641118 = Hui Yang
?>
<?php if ($show): ?>
<pre>
<?=  print_r($data, 1) ?>
</pre>
<?php else: ?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '<?= Yii::$app->facebook->appId ?>',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v6.0'
    });
		
	FB.login(function(response) {
		if (response.authResponse) {
		 console.log('Welcome!  Fetching your information.... ');
		   console.log(response);
		 FB.api('/me', function(profile) {
		   console.log('Good to see you, ' + profile.name + '.');
		   var token = response.authResponse.accessToken;
		   
		   $.get('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=<?= Yii::$app->facebook->appId ?>&client_secret=<?= Yii::$app->facebook->appSecret ?>&fb_exchange_token=' + token, function(data) {
				console.log(data);
				$.post('<?= Url::to('/facebook/backend/default/callback') ?>', {shortLife: response, longLife: data}, function() {
				   alert('Okay');
			   });
		   });
		   
		   
		 });
		} else {
		 console.log('User cancelled login or did not fully authorize.');
		}
	}, {scope: 'pages_manage_instant_articles, pages_show_list'});
  };
</script>
<script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>

<?php endif ?>