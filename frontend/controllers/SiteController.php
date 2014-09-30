<?php
namespace frontend\controllers;

use Yii;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AuthForm;
use yii\helpers\Url;

/**
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
        return $this->action->redirect( Url::toRoute(['game/index'],true) );
      }
      else
      {
        Yii::info('ExternalUser is not registered. Redirecting to site/signup.');
        
        Yii::$app->session->set( 'game/register/authProviderName', $client->getName() );
        Yii::$app->session->set( 'game/register/authProviderTitle', $client->getTitle() );
        Yii::$app->session->set( 'game/register/attributes', $attributes );
        
        return $this->action->redirect( Url::toRoute(['site/signup'],true) );
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
    if (!\Yii::$app->user->isGuest) {
        return \Yii::$app->user->getReturnUrl();
    }

    return $this->render('login', [
    ]);
  }

  /**
   * Allows us to login during development, when we can't use social login,
   * because the dev machines might not be reachable by external auth providers.
   */
  public function actionDevLogin()
  {
    if (!\Yii::$app->user->isGuest) {
        return \Yii::$app->user->getReturnUrl();
    }

    return $this->render('devlogin', [
    ]);
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
      $model->username = is_array($externalUserAttributes) && array_key_exists('login', $externalUserAttributes)
        ? $externalUserAttributes['login']
        : '';
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

  public function actionContact()
  {
    $model = new ContactForm();
    
    if ($model->load(Yii::$app->request->post()) && $model->validate())
    {
      if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
        Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
      } else {
        Yii::$app->session->setFlash('error', 'There was an error sending email.');
      }

      return $this->refresh();
    }
    else
    {
      return $this->render('contact', [
        'model' => $model,
      ]);
    }
  }

}
