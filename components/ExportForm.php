<?php

namespace app\components;

use app\models\Results;
use yii\base\Model;
use yii\web\UploadedFile;

class ExportForm extends Model
{
    public $dumps;
    public $exportFormat = 'csv';

    static $exportFormatList = [
        'csv' => 'csv',
        'xml' => 'xml',
        'txt' => 'text'
    ];

    public function rules()
    {
        return [
            ['exportFormat', 'required'],
            [['dumps'], 'safe'],
        ];
    }

    /**
     * Export dumps
     */
    public function export()
    {
        foreach ($this->dumps as $dump) {
            $fileName = \Yii::getAlias('@webroot') . '/db/' . $dump;
            if (file_exists($fileName)) {
                DbHelper::executeDump($fileName, $dump);
            }
        }

        $results = Results::find()->all();

        //clear db buffer
        Results::deleteAll();

        switch ($this->exportFormat) {
            case 'csv':
                DbHelper::exportCSV($results);
                break;
            case 'xml':
                return DbHelper::exportXML($results);
            case 'txt':
                return DbHelper::exportTXT($results);
        }

        return $results;
    }

}