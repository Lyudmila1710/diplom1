<?php

namespace app\controllers;
use Yii;
use yii\web\Response;
use yii\bootstrap5\ActiveForm;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Favorite;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */


    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

   public function actionAccount()
{
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']);
    }

    $user = Yii::$app->user->identity;
    $model = $user;
    $model->scenario = 'update';

    if ($model->load(Yii::$app->request->post())) {
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
            return $this->refresh();
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при сохранении данных');
        }
    }

    // Загрузка избранного
    $favorites = Favorite::find()
        ->where(['user_id' => $user->id])
        ->with('product') // если есть relation getProduct()
        ->all();

    return $this->render('account', [
        'user' => $user,
        'model' => $model,
        'favorites' => $favorites,
    ]);
}

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    if (!Yii::$app->user->isGuest) {
        return $this->goHome();
    }
    
    $model = new User();
     $model->scenario = 'register';
    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
    
    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
            // Пароль уже хеширован на клиенте, но модель его еще раз хеширует перед сохранением
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Регистрация прошла успешно!');
                return $this->redirect(['site/login']);
            } else{
                Yii::$app->session->setFlash('error', 'Ошибка регистрации.');
            }
        }
    } else {
        $model->loadDefaultValues();
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

public function actionRequestPasswordReset()
{
    $email = Yii::$app->request->post('email');
    
    if (Yii::$app->request->isPost && $email) {
        $user = User::findOne(['email' => $email]);
        
        if ($user) {
            $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            
            if ($user->save(false)) {
                if ($user->sendPasswordResetEmail()) {
                    Yii::$app->session->setFlash('success', 'Письмо отправлено! Проверьте вашу почту.');
                   return $this->redirect(['site/login']);
                } else {
                    // Добавьте это для отладки
                    Yii::$app->session->setFlash('error', 'Ошибка отправки: ' . print_r(error_get_last(), true));
                }
            }
        }
        
        Yii::$app->session->setFlash('error', 'Не удалось отправить письмо. Попробуйте позже.');
    }
    
    return $this->render('request-password-reset');
}

public function actionResetPassword($token)
{
    $user = User::findByResetToken($token);
    
    if (!$user) {
        Yii::$app->session->setFlash('error', 'Неверная ссылка.');
        return $this->redirect(['site/login']);
    }
    
    $model = new User();
    $model->scenario = 'reset';
    
    if ($model->load(Yii::$app->request->post())) {
        if ($user->resetPassword($model->password)) {
            Yii::$app->session->setFlash('success', 'Пароль изменен!');
            return $this->redirect(['site/login']);
        }
    }
    
    return $this->render('reset-password', ['model' => $model]);
}

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
