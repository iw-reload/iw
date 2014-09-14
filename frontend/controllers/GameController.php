<?php
namespace frontend\controllers;

use frontend\widgets\FlashWidget;
use frontend\models\ConstructBuildingForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;

/**
 * Game controller
 */
class GameController extends Controller
{
  // TODO: enable
  public $enableCsrfValidation = false;
  
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['logout', 'signup'],
        'rules' => [
          [
            'actions' => ['signup'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
    ];
  }

  public function actionIndex()
  {
    return $this->render('index',[
      'user' => Yii::$app->user->identity,
    ]);
  }

  public function actionConstruct()
  {
    /* @var $model ConstructBuildingForm */
    $model = Yii::createObject( ConstructBuildingForm::className() );
    
    if ($model->load(Yii::$app->request->post()))
    {
      if ($model->validate()) {
        $model->createTask();
        FlashWidget::addSuccess( 'Construction in progress.' );
      } else {
        FlashWidget::addModelErrors( $model );
      }
      
      // After handling POST data, respond with 302. This will cause the
      // browser to make a GET request to this action. If the user hits refresh
      // after this, it will refresh the buildings page instead of
      // re-submitting the post data.
      return $this->refresh();
    }
    else
    {
      return $this->render('construct',[
        'user' => Yii::$app->user->identity,
      ]);
    }
  }

  public function actionLogout()
  {
    Yii::$app->user->logout();
    return $this->goHome();
  }

}
