<?php

namespace app\controllers;

use app\models\bookings;
use app\models\bookingsSearch;
use app\models\User;
use app\models\Status;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookingsController implements the CRUD actions for bookings model.
 */
class BookingsController extends Controller
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
     * Lists all bookings models.
     *
     * @return string
     */
    public function actionIndex()
    {   $user = User::getInstance();
        if(!$user){
            return $this->goHome();
        }
        $searchModel = new bookingsSearch();
        if($user->isAdmin()){
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index_admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
        $dataProvider = $searchModel->search($this->request->queryParams,$user_id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new bookings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {  $user = User::getInstance();
        if(!$user){
            return $this->goHome();
        }
        $model = new bookings();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id=$user->id;
                $model->status_id=Status::NEW_STATUS_ID;
                if ($model->save())
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing bookings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = User::getInstance();
        if(!$user|| $user->isAdmin()){
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the bookings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return bookings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = bookings::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
