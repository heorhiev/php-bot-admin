<?php

namespace app\helpers;


class FilesHelper
{
    public function save($files): ?array
    {
        \Yii::debug('save files');
        $uploadDir = \Yii::getAlias('@webroot/uploads'); // Use a safe directory outside of webroot
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }

        foreach ($files as $key => $uploadFile) {
            $fileName = \Yii::$app->security->generateRandomString(10) . '.' . $uploadFile->extension;
            $filePath = $uploadDir . '/' . $fileName;
            if ($uploadFile->saveAs($filePath)) {
                $results[] = $fileName;
            }
        }

        return $results ?? null;
    }
}
