<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetTemplate $model
 */
use insolita\wgadminlte\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = Yii::t('widgets', 'Create');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginBlock('crud-navigation') ?>
<div class="clearfix crud-navigation">
    <?= Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> ' .
        \Yii::t('widgets', 'Create'),
        [
            'id'    => 'create-' . $model->formName(),
            'class' => 'btn btn-success',
        ]
    );
    ?>
    <?= Html::submitButton(
        '<span class="glyphicon glyphicon-refresh"></span> ' .
        \Yii::t('widgets', 'Apply'),
        [
            'id'    => 'apply-' . $model->formName(),
            'name' => 'apply',
            'class' => 'btn btn-success',
        ]
    );
    ?>
    <div class="pull-right">
        <?= Html::a(
            '<span class="glyphicon glyphicon-file"></span> ' . Yii::t('widgets', 'Cancel'),
            ['/widgets/crud/widget-template/index'],
            ['class' => 'btn btn-default']
        ) ?>
    </div>
</div>
<?php $this->endBlock() ?>

<div class="giiant-crud widget-create">
    <?php $form = ActiveForm::begin(
        [
            'id'                     => 'widget-template-create',
            'layout'                 => 'default',
            'enableClientValidation' => false,
            'errorSummaryCssClass'   => 'error-summary alert alert-error',
            'fieldConfig'            => [
                'horizontalCssClasses' => [
                    'label'   => 'col-sm-2',
                    'wrapper' => 'col-sm-10',
                    'error'   => '',
                    'hint'    => 'hidden',
                ],
            ],
        ]
    );
    ?>
    <?php Box::begin() ?>
    <h1>
        <?= $model->getAliasModel() ?>
        <small><?= $model->name ?></small>
    </h1>

    <?= $this->blocks['crud-navigation'] ?>
    <hr/>
    <?= $this->render('_form', ['model' => $model, 'form' => $form]); ?>
    <?= $this->blocks['crud-navigation'] ?>
    <?php Box::end() ?>
    <?php ActiveForm::end(); ?>
</div>
