<?php
namespace ant\facebook\backend\controllers;

use Yii;
use ant\facebook\models\FacebookInstantArticleLog;

class DefaultController extends \yii\web\Controller {
	public $enableCsrfValidation = false;
	
	public function actionIndex() {
        return $this->render($this->action->id, [

        ]);
	}

	public function actionCallback() {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$shortLife = Yii::$app->request->post('shortLife');
		$longLife = Yii::$app->request->post('longLife');

		$accessToken = $longLife['access_token'];

		Yii::$app->facebook->storeAccessToken($accessToken);
	}
}