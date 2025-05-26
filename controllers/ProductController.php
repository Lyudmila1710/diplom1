<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     *
     * @return string
     */

    public function actionCatalog()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('catalog', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isAdmin()) {
            $this->redirect(['site/login']);
        }
        
        $model = new Product();
    $model->scenario = 'create';
        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->photo = UploadedFile::getInstance($model, 'photo');
            
            if ($model->photo !== null) {
                $file_name = '/img_product/' . Yii::$app->getSecurity()->generateRandomString(50) . '.' . $model->photo->extension;
                $model->photo->saveAs(Yii::$app->basePath . '/web' . $file_name);
                $model->photo = $file_name;
            }
            
            if ($model->save(false)) {
                return $this->redirect(['product/catalog']);
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
$model->scenario = 'update';
        $oldPhoto = $model->photo;
Yii::$app->session->set('fromUpdate', true);
    if ($this->request->isPost && $model->load($this->request->post())) {
        // Пытаемся получить загруженный файл
        $uploadedFile = UploadedFile::getInstance($model, 'photo');

        if ($uploadedFile) {
            // Генерируем уникальное имя
            $file_name = '/img_product/' . Yii::$app->security->generateRandomString(50) . '.' . $uploadedFile->extension;
            $fullPath = Yii::$app->basePath . '/web' . $file_name;

            // Сохраняем файл на сервер
            if ($uploadedFile->saveAs($fullPath)) {
                $model->photo = $file_name;
            }
        } else {
            // Если не загружено новое фото — оставляем старое
            $model->photo = $oldPhoto;
        }

        // Сохраняем модель (с валидацией)
        if ($model->save()) {
            Yii::$app->session->addFlash('success', 'Изменения применены!', ['duration' => 5000]);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->addFlash('error', 'Ошибка сохранения', ['duration' => 5000]);
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

public function actionSoftAdd($id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $model = $this->findModel($id);
    $model->available = 'Доступен';

    if ($model->save()) {
        return [
            'success' => true,
            'message' => 'Товар снова в наличии!',
            'newStatus' => 'В наличии',
            'newButtonText' => 'Нет в наличии',
            'newButtonClass' => 'delete-btn',
            'newButtonUrl' => \yii\helpers\Url::to(['product/soft-delete', 'id' => $model->id])
        ];
    }

    return ['success' => false, 'error' => 'Не удалось сохранить'];
}

public function actionSoftDelete($id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $model = $this->findModel($id);
    $model->available = 'Закрыт'; 

    if ($model->save(false)) {
        return [
            'success' => true,
            'message' => 'Товара больше нет в наличии!',
            'newStatus' => 'Нет в наличии',
            'newButtonText' => 'В наличии',
            'newButtonClass' => 'add-btn',
            'newButtonUrl' => \yii\helpers\Url::to(['product/soft-add', 'id' => $model->id])
        ];
    }

    return ['success' => false, 'error' => 'Не удалось сохранить'];
}
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
