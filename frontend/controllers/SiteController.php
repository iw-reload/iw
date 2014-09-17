<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AuthForm;

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
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
    
    $attributes = $client->getUserAttributes();

    $externalUser = new AuthForm();
    $externalUser->authProvider = $client->getName();
    $externalUser->externalUserId = array_key_exists('id', $attributes) ? $attributes['id'] : null;
    
    if ($externalUser->validate())
    {
      if ($externalUser->isRegistered())
      {
        $externalUser->login();
        return $this->redirect('game/index');
      }
      else
      {
        Yii::$app->session->set( 'game/register/authProvider', $externalUser->authProvider );
        Yii::$app->session->set( 'game/register/attributes'  , $attributes );
        
        return $this->redirect('site/signup');
      }    
    }
    else
    {
      // TODO error. Throw, display actionError?
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

      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
          return $this->goBack();
      } else {
          return $this->render('login', [
              'model' => $model,
          ]);
      }
  }

  public function actionLogout()
  {
      Yii::$app->user->logout();

      return $this->goHome();
  }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
