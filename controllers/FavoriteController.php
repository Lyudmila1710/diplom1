<?php

namespace app\controllers;

use app\models\Favorite;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
class FavoriteController extends Controller
{
    public function actionToggle()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'error' => 'Необходимо авторизоваться'];
        }
        
        $productId = Yii::$app->request->post('product_id');
        $userId = Yii::$app->user->id;
        
        $favorite = Favorite::findOne(['user_id' => $userId, 'product_id' => $productId]);
        
        if ($favorite) {
            if ($favorite->delete()) {
                return ['success' => true, 'status' => 'removed'];
            }
        } else {
            $favorite = new Favorite([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            
            if ($favorite->save()) {
                return ['success' => true, 'status' => 'added'];
            }
        }
        
        return ['success' => false, 'error' => 'Ошибка при сохранении'];
    }

}