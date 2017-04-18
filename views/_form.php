<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'action' => '/admin/callback/send',
    'method' => 'post',
    'options' => [ 'class' => 'row']
]);
?>

    <div class="col-md-8 col-md-offset-2">


        <?= $form->field($model, 'email')->input('text', ['placeholder' => 'Электронный адрес'])->label(false);?>
        <?= $form->field($model, 'phone')->input('text', ['placeholder' => 'Номер телефона'])->label(false);?>

        <?= Html::input('submit', 'Отправить');?>

    </div>

<? ActiveForm::end();?>