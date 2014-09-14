<?php

namespace frontend\widgets;

use yii\base\Widget;

/**
 * FlashWidget renders messages from session flash. All flash messages are
 * displayed in the sequence they were assigned using setFlash.
 */
class FlashWidget extends Widget
{
  const SUCCESS = 'success';
  const ERROR = 'error';
  
  static public $types = [
    self::SUCCESS, self::ERROR,
  ];

  public $template = '<fieldset class="flash"><legend>{title}</legend>{messages}</fieldset>';
  
  public function run()
  {
    $session = \Yii::$app->getSession();
    $flashes = $session->getAllFlashes();
    $result = '';

    foreach ($flashes as $type => $data)
    {
      // only handle known flash types
      if (false === array_search($type,self::$types,true)) {
        continue;
      }
      
      $data = (array) $data;
      $messageList = \yii\helpers\Html::ul( $data );
      $result .= str_replace( ['{title}', '{messages}'], [$type,$messageList], $this->template );
      $session->removeFlash($type);
    }
    
    echo $result;
  }

  static public function add( $type, $message )
  {
    // TODO: update yii2, use addFlash
    \Yii::$app->session->setFlash( $type, $message );
  }

  static public function addError( $message ) {
    self::add( self::ERROR, $message );
  }
  
  static public function addSuccess( $message ) {
    self::add( self::SUCCESS, $message );
  }
  
  /**
   * @param \yii\base\Model $model
   */
  static public function addModelErrors( $model )
  {
    $errors = $model->getErrors();
    self::addError( self::flatten($errors) );
  }
  
  static private function flatten( $array )
  {
    $return = array();
    array_walk_recursive( $array, function($a) use (&$return) { $return[] = $a; } );
    return $return;
  }
}
