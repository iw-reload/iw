<?php

namespace backend\controllers;

use Yii;
use common\models\CelestialBody;
use common\objects\RbacRole;
use backend\models\CelestialBodySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CelestialBodyController implements the CRUD actions for CelestialBody model.
 */
class CelestialBodyController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'ruleConfig' => [
          'class' => 'yii\filters\AccessRule',
          'allow' => true,
        ],
        'rules' => [
          ['actions' => ['create'], 'roles' => [RbacRole::CELESTIAL_BODY_CREATE]],
          ['actions' => ['delete'], 'roles' => [RbacRole::CELESTIAL_BODY_DELETE]],
          ['actions' => ['index'] , 'roles' => [RbacRole::CELESTIAL_BODY_LIST]],
          ['actions' => ['update'], 'roles' => [RbacRole::CELESTIAL_BODY_UPDATE]],
          ['actions' => ['view']  , 'roles' => [RbacRole::CELESTIAL_BODY_VIEW]],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
        ],
      ],
    ];
  }

    /**
     * Lists all CelestialBody models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CelestialBodySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CelestialBody model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CelestialBody model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CelestialBody();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CelestialBody model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CelestialBody model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CelestialBody model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CelestialBody the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CelestialBody::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
