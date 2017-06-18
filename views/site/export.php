<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Export';

?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'id' => 'export-form'])
            ?>
            <?php
                echo $form->field($model, 'exportFormat')->widget(Select2::classname(), [
                    'data' => \app\components\ExportForm::$exportFormatList,
                    'options' => ['placeholder' => 'Select a format ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
            <?php
                echo $form->field($model, 'dumps')->widget(Select2::classname(), [
                    'data' => $dumps,
                    'options' => ['placeholder' => 'Select dump ...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                    ],
                ])->label('Select one or more dumps to import');
            ?>
            <?= Html::submitButton('Export', ['class' => 'btn btn-primary']) ?>
            <a class="btn" href="/site/clear-dumps">Clear dumps</a>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
