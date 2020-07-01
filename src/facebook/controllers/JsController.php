<?php
namespace ant\facebook\controllers;

use Yii;

class JsController extends \yii\web\Controller {
	public function actionLoggedIn() {
		if (isset(Yii::$app->facebook)) {
			Yii::$app->facebook->trigger(\ant\facebook\Module::EVENT_LOGGED_IN);
		}
	}
	
	public function actionPublishInstantArticle() {
		$url = Yii::$app->request->post('url');
		
		if (Yii::$app->facebook->isAuthenticated()) {
			$submissionId = Yii::$app->facebook->submitUrl($url);
			sleep(5);
			echo '<pre>'.print_r(Yii::$app->facebook->checkSubmission($submissionId), 1).'</pre>';
			Yii::$app->facebook->publishPost('', $url);
			
			return ['success'];
		} else {
			throw new \Exception('Not authenticated. ');
		}
	}
}