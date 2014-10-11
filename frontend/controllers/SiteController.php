<?php
namespace frontend\controllers;

use common\models\DevLoginForm;
use common\models\User;
use frontend\models\AuthForm;
use frontend\models\SignupForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * @todo refactor backend/siteController is very similar.
 * Site controller
 */
class SiteController extends Controller
{
  /**
   * @inheritdoc
   */
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
          ['actions' => ['auth','error','index','userlist'] ],
          ['actions' => ['dev-login'] , 'ips' => ['127.0.0.1', '::1'] ],
          ['actions' => ['login']     , 'roles' => ['?'] ],
          ['actions' => ['logout']    , 'roles' => ['@'] ],
          ['actions' => ['signup']    , 'roles' => ['?'] ],
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
      'auth' => [
        'class' => 'yii\authclient\AuthAction',
        'successCallback' => [$this, 'successCallback'],
      ],
    ];
  }

  /**
   * 
   * @param \yii\authclient\ClientInterface $client
   * @return type
   */
  public function successCallback($client)
  {
    // TODO: Group FK's to one local user.
    //       Otherwise, if we log in via FB and another time via google, we
    //       end up with two local accounts.
    
    if (!$this->action instanceof \yii\authclient\AuthAction) {
      throw new \yii\base\InvalidCallException("successCallback is only meant to be executed by AuthAction!");
    }
    
    $attributes = $client->getUserAttributes();

    $externalUser = new AuthForm();
    $externalUser->authProvider = $client->getName();
    $externalUser->externalUserId = array_key_exists('id', $attributes) ? $attributes['id'] : null;
    
    if ($externalUser->validate())
    {
      Yii::info('AuthForm validated.');
      
      if ($externalUser->isRegistered())
      {
        Yii::info('ExternalUser is registered. Logging in and redirecting to game/index.');
        
        $externalUser->login();
        return $this->action->redirect( Url::to(['game/index'],true) );
      }
      else
      {
        Yii::info('ExternalUser is not registered. Redirecting to site/signup.');
        
        Yii::$app->session->set( 'game/register/authProviderName', $client->getName() );
        Yii::$app->session->set( 'game/register/authProviderTitle', $client->getTitle() );
        Yii::$app->session->set( 'game/register/attributes', $attributes );
        
        return $this->action->redirect( Url::to(['site/signup'],true) );
      }    
    }
    else
    {
      // TODO error. Throw, display actionError?
      Yii::info('AuthForm couldn\'t be validated. Errors: ' . print_r($externalUser->errors,true));
      Yii::info('Client attributes: ' . print_r($attributes,true));
    }
  }    
    
  public function actionIndex()
  {
    return $this->render('index');
  }

  public function actionLogin()
  {
    if (Yii::$app->user->isGuest)
    {
      $result = $this->render('login', [
      ]);
    }
    else
    {
      $result = Yii::$app->user->getReturnUrl();
    }
    
    return $result;
  }

  /**
   * Allows us to login during development, when we can't use social login,
   * because the dev machines might not be reachable by external auth providers.
   */
  public function actionDevLogin()
  {
    if (Yii::$app->user->isGuest)
    {
      $model = new DevLoginForm();

      if ($model->load(Yii::$app->request->post()) && $model->login())
      {
        $result = $this->goBack();
      }
      else
      {
        $result = $this->render('devlogin', [
          'model' => $model,
        ]);
      }
    }
    else
    {
      $result = $this->goBack();
    }
    
    return $result;
  }

  public function actionUserlist( $term )
  {
    // TODO: this action can be replaced with a call to /api/users
    //       returning public user information if we ever introduce a REST API.
    
    $userNames = User::find()
      ->select('name')
      ->where(['like', 'name', $term])
      ->orderBy('name')
      ->column();
    
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return $userNames;
  }
  
  public function actionSignup()
  {
    $authProviderName = Yii::$app->session->get( 'game/register/authProviderName' );
    $authProviderTitle = Yii::$app->session->get( 'game/register/authProviderTitle' );
    $externalUserAttributes = Yii::$app->session->get( 'game/register/attributes' );

    $model = new SignupForm();
    $model->setAuthProviderName( $authProviderName );
    $model->setAuthProviderTitle( $authProviderTitle );
    $model->externalUserAttributes = $externalUserAttributes;
    
    if ($model->load(Yii::$app->request->post()))
    {
      if ($user = $model->signup())
      {
        Yii::$app->session->remove( 'game/register/authProvider' );
        Yii::$app->session->remove( 'game/register/attributes' );
        
        if (Yii::$app->getUser()->login($user)) {
          return $this->goHome();
        }
      }
    }
    else
    {
      $model->username = $model->getExternalUserName();
    }
    
    return $this->render('signup', [
      'model' => $model,
    ]);
  }
  
  public function actionLogout()
  {
      Yii::$app->user->logout();

      return $this->goHome();
  }

}
