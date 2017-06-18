<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;

$this->title = 'DB Exporter';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>DB Exporter</h1>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'upload-form']) ?>
        <?=
            $form->field($model, 'dumps[]')
                ->fileInput([
                    'multiple' => true,
                    'accept' => '.sql',
                    'id' => 'upload-input',
                    'style' => 'display:none;',
                ])
                ->label(false)
        ?>
        <?php ActiveForm::end() ?>

        <p><a class="btn btn-lg btn-success upload-main-btn" href="#">Upload dumps</a></p>
    </div>

    <div class="body-content">

    </div>
</div>
