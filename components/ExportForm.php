<?php

namespace app\components;

use app\models\Results;
use yii\base\Model;

class ExportForm extends Model
{
    public $dumps;
    public $exportFormat = 'csv';

    static $exportFormatList = [
        'csv' => 'csv',
        'xml' => 'xml',
        'txt' => 'txt'
    ];

    public function rules()
    {
        return [
            ['exportFormat', 'required'],
            [['exportFormat'], 'in', 'range' => array_values(self::$exportFormatList)],
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

        //clear all results
        Results::deleteAll();

        switch ($this->exportFormat) {
            case 'csv':
                DbHelper::exportCSV($results);
                break;
            case 'xml':
                return DbHelper::exportXML($results);
                break;
            case 'txt':
                return DbHelper::exportTXT($results);
                break;
        }

        return $results;
    }

    /**
     * Create existing dumps list
     * @return array
     */
    public static function getDumpsList()
    {
        $directory = \Yii::getAlias('@webroot') . '/db';
        $files = array_diff(scandir($directory), array('..', '.'));
        $dumps = [];
        foreach ($files as $file) {
            $dumps[$file] = $file;
        }

        return $dumps;
    }

}