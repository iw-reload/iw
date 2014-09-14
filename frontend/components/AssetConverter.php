<?php

namespace frontend\components;

/**
 * Description of AssetConverter
 *
 * @author ben
 */
class AssetConverter extends \yii\web\AssetConverter
{

  // Override to always run the command
  // Should only be used during development!
  public function convert($asset, $basePath) {
    $pos = strrpos($asset, '.');
    if ($pos !== false) {
        $ext = substr($asset, $pos + 1);
        if (isset($this->commands[$ext])) {
            list ($ext, $command) = $this->commands[$ext];
            $result = substr($asset, 0, $pos + 1) . $ext;
//            if (@filemtime("$basePath/$result") < filemtime("$basePath/$asset")) {
                $this->runCommand($command, $basePath, $asset, $result);
//            }

            return $result;
        }
    }

    return $asset;
  }
}
