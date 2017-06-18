<?php

namespace app\components;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $dumps;
    public $errors = [];

    public function rules()
    {
        return [
            [['dumps'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10],
        ];
    }

    /**
     * Upload files
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->dumps as $file) {

                if ($file->extension !== 'sql') {
                    $this->addError('dumps', $file->baseName . '.' . $file->extension . ' is not an SQL dump');
                    return false;
                }

                $fileName = \Yii::getAlias('@webroot') . '/db/' . $file->baseName . '.' . $file->extension;
                if (!$file->saveAs($fileName)) {
                    $this->errors[] = $file->error;
                };
            }
            return empty($this->errors);
        } else {
            return false;
        }
    }
}