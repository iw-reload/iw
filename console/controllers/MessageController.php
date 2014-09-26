<?php

namespace console\controllers;

/**
 * Description of MessageController
 *
 * @author ben
 */
class MessageController extends \yii\console\controllers\MessageController
{
  
  protected function extractMessages($fileName, $translator)
  {
    $messages = parent::extractMessages($fileName, $translator);
    $subject = file_get_contents($fileName);

    $nNamespaces = preg_match_all( '/\bnamespace\s+(?P<namespace>.+?);/', $subject, $matches );
    
    if ($nNamespaces === 0) {
      // no further processing without a namespace (views, ...)
      return $messages;
    } else if ($nNamespaces > 1) {
      // multiple namespaces not supported.
      echo "Warning: found '{$nNamespaces}' namespaces. This command only supports one namespace per file.";
      return $messages;
    }
    
    $namespace = $matches['namespace'][0];
//    echo "namespace: '$namespace'\n";
    
    $n = preg_match_all( '/\bclass\s+(?P<classname>.+?)\b/', $subject, $matches );
    
    for ($i = 0; $i < $n; ++$i)
    {
      $className = $matches['classname'][$i];
//      echo "className: '$className'\n";
      
      $fqn = $namespace . '\\' . $className;
//      echo "fqn: '$fqn'\n";
      
      if (is_subclass_of($fqn, \yii\db\ActiveRecord::className()))
      {
        // TODO: check for annotation
        // TODO: select distinct for translatable columns
      }      
    }
    
    return $messages;
  }
  
}
