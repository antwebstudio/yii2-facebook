<?php
namespace ant\facebook\controllers;

use Yii;
use ant\facebook\models\FacebookInstantArticleLog;

class JsController extends \yii\web\Controller {
	public $enableCsrfValidation = false;
	
	public function actionLoggedIn() {
		if (isset(Yii::$app->facebook)) {
			Yii::$app->facebook->trigger(\ant\facebook\Module::EVENT_LOGGED_IN);
		}
	}
	
	public function actionPublishInstantArticle() {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$url = Yii::$app->request->post('url');
		$publishableClass = Yii::$app->request->post('publishable_class');
		$publishableId = Yii::$app->request->post('publishable_id');
		
		if (Yii::$app->facebook->isAuthenticated()) {
			$submissionId = Yii::$app->facebook->submitUrl($url);
			sleep(5);
			$response = Yii::$app->facebook->checkSubmission($submissionId);
			//echo '<pre>'.print_r($response, 1).'</pre>';
			
			//Yii::$app->facebook->publishPost('', $url);
			
			$log = new FacebookInstantArticleLog;
			$log->attributes = [
				'publishable_class' => $publishableClass,
				'publishable_id' => $publishableId,
				'submission_id' => $submissionId,
				'status' => $response->status == 'success' ? FacebookInstantArticleLog::STATUS_SUCCESS : FacebookInstantArticleLog::STATUS_FAILED,
			];
			if (!$log->save()) throw new \Exception(print_r($log->errors, 1));
			
			return $response;
		} else {
			return ['status' => 'not logged in', 'redirect' => Yii::$app->backendUrlManager->createUrl(['/facebook/backend'])];
			return $this->redirect(['/facebook/backend']); // cannot work
			throw new \Exception('Not authenticated. ');
		}
	}
}