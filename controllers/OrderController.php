<?php

namespace app\controllers;
use yii;
use app\models\Order;
use app\models\Cart;
use app\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     *
     * @return string
     */
   public function actionIndex()
{
    $searchModel = new OrderSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}
public function actionChangeStatus($id)
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    
    if (!Yii::$app->user->identity->isAdmin()) {
        return ['success' => false, 'message' => 'Доступ запрещен'];
    }
    
    $order = Order::findOne($id);
    if (!$order) {
        return ['success' => false, 'message' => 'Заказ не найден'];
    }
    
    $newStatus = Yii::$app->request->post('status');
    $allowedStatuses = ['В процессе', 'Доставлен', 'Подтверждён', 'Отменён'];
    
    if (!in_array($newStatus, $allowedStatuses)) {
        return ['success' => false, 'message' => 'Недопустимый статус'];
    }
    
    $transaction = Yii::$app->db->beginTransaction();
    try {
        $order->status = $newStatus;
        
        if ($newStatus === 'Отменён') {
            $reason = Yii::$app->request->post('reason');
            if (empty($reason)) {
                throw new \Exception('Не указана причина отмены');
            }
            
            $rejection = new \app\models\Rejection();
            $rejection->order_id = $order->id;
            $rejection->reason = $reason;
            if (!$rejection->save()) {
                throw new \Exception('Ошибка сохранения причины отмены');
            }
        }
        
        if (!$order->save()) {
            throw new \Exception('Ошибка сохранения статуса');
        }
        
        $transaction->commit();
        return ['success' => true];
    } catch (\Exception $e) {
        $transaction->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
 public function actionCancel($id)
    {
        $model = $this->findModel($id);
$model->status="Отменён";
        if ( $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    /**
     * Displays a single Order model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
 public function actionCreate()
{
    $model = new Order();

    if ($this->request->isPost && $model->load($this->request->post())) {
        if ($model->save()) {
            // Получаем ID текущего авторизованного пользователя
            $userId = Yii::$app->user->id;
            
            // Обновляем все товары в корзине пользователя без привязки к заказу
            Cart::updateAll(
                ['order_id' => $model->id], // устанавливаем order_id
                [
                    'user_id' => $userId,   // для текущего пользователя
                    'order_id' => null      // только непривязанные товары
                ]
            );
             Yii::$app->session->setFlash('orderSuccess', true);
            return $this->redirect(['index']);
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */


    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
