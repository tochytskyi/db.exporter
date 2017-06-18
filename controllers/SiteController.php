<?php

namespace app\controllers;

use app\components\ExportForm;
use app\components\UploadForm;
use app\models\Results;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->dumps = UploadedFile::getInstances($model, 'dumps');
            if (!$model->upload()) {
                Yii::$app->session->setFlash('upload', 'Sorry, some dumps can not be uploaded');
            } else {
                Yii::$app->response->redirect('/site/export');
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    /**
     * @return string
     */
    public function actionExport()
    {
        $directory = \Yii::getAlias('@webroot') . '/db';
        $files = array_diff(scandir($directory), array('..', '.'));
        $dumps = [];
        foreach ($files as $file) {
            $dumps[$file] = $file;
        }

        $model = new ExportForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $results = $model->export();
            Yii::$app->session->setFlash('results-count', count($results));
        }

        return $this->render('export', [
            'model' => $model,
            'dumps' => $dumps
        ]);
    }
}
