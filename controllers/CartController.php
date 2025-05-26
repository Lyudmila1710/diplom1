<?php

namespace app\controllers;

use app\models\Cart;
use app\models\CartSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Cart models.
     *
     * @return string
     */
  public function actionIndex()
{
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']);
    }

    $cartItems = Cart::find()
        ->where(['user_id' => Yii::$app->user->id, 'order_id' => null])
        ->with('product')
        ->all();

    return $this->render('index', [
        'cartItems' => $cartItems,
    ]);
}

    /**
     * Displays a single Cart model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Creates a new Cart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'error' => 'Необходимо авторизоваться']);
        }

        $productId = Yii::$app->request->post('product_id');
        $userId = Yii::$app->user->id;

        // Проверяем, есть ли уже такой товар в корзине
        $cartItem = Cart::findOne([
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => null
        ]);

        if ($cartItem) {
            $cartItem->count += 1;
        } else {
            $cartItem = new Cart();
            $cartItem->user_id = $userId;
            $cartItem->product_id = $productId;
            $cartItem->count = 1;
        }

        if ($cartItem->save()) {
            return $this->asJson([
                'success' => true,
                'count' => $cartItem->count
            ]);
        } else {
            return $this->asJson([
                'success' => false,
                'error' => 'Не удалось добавить товар в корзину',
                'errors' => $cartItem->errors
            ]);
        }
    }


    /**
     * Updates an existing Cart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'error' => 'Необходимо авторизоваться']);
        }

        $change = (int)Yii::$app->request->post('change', 1);
        $userId = Yii::$app->user->id;

        $cartItem = Cart::findOne([
            'user_id' => $userId,
            'product_id' => $id,
            'order_id' => null
        ]);

        if (!$cartItem) {
            return $this->asJson(['success' => false, 'error' => 'Товар не найден в корзине']);
        }

        $newCount = $cartItem->count + $change;

        if ($newCount < 1) {
            return $this->actionDelete($id);
        }

        $cartItem->count = $newCount;
        
        if ($cartItem->save()) {
            return $this->asJson([
                'success' => true,
                'count' => $cartItem->count
            ]);
        } else {
            return $this->asJson([
                'success' => false,
                'error' => 'Не удалось обновить количество',
                'errors' => $cartItem->errors
            ]);
        }
    }

    /**
     * Deletes an existing Cart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'error' => 'Необходимо авторизоваться']);
        }

        $userId = Yii::$app->user->id;
        $cartItem = Cart::findOne([
            'user_id' => $userId,
            'product_id' => $id,
            'order_id' => null
        ]);

        if (!$cartItem) {
            return $this->asJson(['success' => false, 'error' => 'Товар не найден в корзине']);
        }

        if ($cartItem->delete()) {
            return $this->asJson(['success' => true]);
        } else {
            return $this->asJson([
                'success' => false,
                'error' => 'Не удалось удалить товар из корзины'
            ]);
        }
    }


    
    /**
     * Finds the Cart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cart::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
