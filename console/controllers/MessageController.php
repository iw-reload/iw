<?php

namespace console\controllers;

use yii\db\Query;

/**
 * Description of MessageController
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class MessageController extends \yii\console\controllers\MessageController
{
  /**
   * @var string
   */
  private $_marker = '@translatable';
  
  protected function extractMessages($fileName, $translator)
  {
    $messages = parent::extractMessages( $fileName, $translator );
    
    if (!empty($messages)) {
      echo 'Found messages: ' . print_r($messages,true);
    }
    
    try
    {
      $fileContent = file_get_contents($fileName);
      $markerFound = $this->findMarker( $fileContent );

      if ($markerFound)
      {
        $fqn = $this->extractClassname( $fileName, $fileContent );
        $arMessages = $this->loadMessages( $fqn );

        $messages[$fqn] = $arMessages;
        
        if (!empty($arMessages)) {
          echo 'Added arMessages: ' . print_r($messages,true);
        }
      }
    }
    catch (\Exception $ex)
    {
      $msg = $ex->getMessage();
      echo "  error: '{$msg}'\n";
    }
    
    return $messages;
  }
  
  /**
   * Quick check to see if our marker is present in a file. If it is, we can
   * inspect the file more closely. If not, we don't need to process the file
   * any further.
   * 
   * @param string $fileContent
   * @return bool true if the marker can be found in $fileContent, false otherwise.
   */
  private function findMarker( $fileContent )
  {
    $markerPos = strpos( $fileContent, $this->_marker );
    return is_int( $markerPos );
  }
  
  /**
   * Searches for classes annotated with our marker. We only support exactly
   * one class per file. An exception will be thrown if there is no class,
   * multiple classes or other errors like failing to detect the namespace.
   * 
   * @param string $fileContent
   * @return string the fully qualified name of the class annotated with our
   * marker.
   * @throws Exception in case of an error.
   */
  private function extractClassname( $fileName, $fileContent )
  {
    $className = basename( $fileName, ".php" );
    $namespace = $this->extractNamespace( $fileContent );
    
    return "{$namespace}\\{$className}";
  }
    
  private function extractNamespace( $fileContent )
  {
    $matches = [];
    $nNamespaces = preg_match_all( '/\bnamespace\s+(?P<namespace>.+?);/', $fileContent, $matches );
    
    if ($nNamespaces === 0) {
      throw new \Exception('Failed to extract namespace');
    } else if ($nNamespaces > 1) {
      throw new \Exception("Found '{$nNamespaces}' namespaces. Supporting only one per file.");
    }
    
    return $matches['namespace'][0];
  }
  
  private function loadMessages( $className )
  {
    if (!is_subclass_of($className,\yii\db\ActiveRecord::className())) {
      throw new \Exception("'{$className}' does not extend \yii\db\ActiveRecord.");
    }
    
    $attributeNames = $this->getTranslatableAttributes( $className );
    $messages = [];
    
    foreach ($attributeNames as $attributeName)
    {
      $query = new Query;
      $query->select( $attributeName )
        ->distinct()
        ->from( $className::tableName() )
        ;
      
      $messages = array_merge( $messages, $query->column() );
    }
    
    return $messages;
  }
  
  private function getTranslatableAttributes( $className )
  {
    $rc = new \ReflectionClass($className);
    $docComment = $rc->getDocComment();
    
    $matches = [];
    $regExp = '/' . preg_quote($this->_marker,'/') . '\s*\$(?P<attribute>.+?)\b/';
    $nAnnotations = preg_match_all( $regExp, $docComment, $matches );
    
    if ($nAnnotations === false) {
      throw new \Exception("Error while extracting annotations.");
    } else if ($nAnnotations === 0) {
      throw new \Exception("Failed to extract translatable attribute.");
    }
    
    return $matches['attribute'];
  }
}
