<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'action' => '/admin/callback/send',
    'method' => 'get',
    'options' => [
        'class' => 'row',
        'id' => 'callback_form',
    ],
]);

$this->registerJs('$(document).on("beforeSubmit", "#callback_form", function () {
    var form = $(this);
    var values = form.serialize();
    form.find(".text-success").eq(0).addClass("hide");
    form.find("input").prop("disabled", true);
    $.ajax({
      url: form.attr("action"),
      type: "get",
      data: values,
      success: function(data) {
        if(data.success==1){
            form.find("input").prop("disabled", false);
           form.find(".text-success").eq(0).removeClass("hide");
        }else{
           alert(data.error);
        }
      }
    });
    return false;
});', \yii\web\View::POS_END);

?>

    <div class="col-md-8 col-md-offset-2">

        <?= $form->field($model, 'email')->input('text', ['placeholder' => 'Электронный адрес'])->label(false);?>
        <?= $form->field($model, 'phone')->input('text', ['placeholder' => 'Номер телефона'])->label(false);?>

        <div class="text-success hide" style="text-align: center;">
            <i class="fa fa-check" aria-hidden="true"></i>
            Заявка принята
        </div>

        <?= Html::input('submit', 'Отправить');?>

    </div>

<? ActiveForm::end();?>