<?php
namespace grozzzny\call_back\controllers;

use grozzzny\call_back\models\Base;
use grozzzny\call_back\models\Files;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\SortableController;
use yii\widgets\ActiveForm;

use yii\easyii\components\Controller;


class AController extends Controller
{

    use TraitController;

    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Base::getModel(Yii::$app->request->get('alias'))
            ],
        ];
    }


    /**
     * @param null $alias
     * @return string
     */
    public function actionIndex($alias = null)
    {
        $current_model = Base::getModel($alias);

        $query = $current_model->find();

        $data = new ActiveDataProvider(['query' => $query]);

        $current_model::querySort($data);

        $current_model::queryFilter($query, Yii::$app->request->get());

        return $this->render('index', [
            'data' => $data,
            'current_model' => $current_model
        ]);
    }


    /**
     * Создать
     * @param $alias
     * @return array|string|\yii\web\Response
     */
    public function actionCreate($alias)
    {
        $current_model = Base::getModel($alias);

        if ($current_model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($current_model);
            }
            else{
                if(isset($_FILES)){
                    $this->saveFiles($current_model);
                }

                if($current_model->save()){
                    $this->flash('success', 'Запись создана');
                    return $this->redirect(['/admin/'.$this->module->id]);
                }
                else{
                    $this->flash('error', 'Ошибка');
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'current_model' => $current_model
            ]);
        }
    }


    /**
     * Редактировать
     * @param $id
     * @return array|string|\yii\web\Response
     */
    public function actionEdit($alias, $id)
    {
        $current_model = Base::getModel($alias);

        $current_model = $current_model::findOne($id);

        if($current_model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }
        if ($current_model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($current_model);
            }
            else{
                if(isset($_FILES)){
                    $this->saveFiles($current_model);
                }

                if($current_model->save()){
                    $this->flash('success', 'Запись отредактирована');
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $current_model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'current_model' => $current_model
            ]);
        }
    }


    public function actionPhotos($alias, $id)
    {
        $current_model = Base::getModel($alias);

        $current_model = $current_model::findOne($id);

        if(!($current_model)){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'current_model' => $current_model,
        ]);
    }

    public function actionFiles($alias, $id)
    {
        $current_model = Base::getModel($alias);

        $current_model = $current_model::findOne($id);

        if(!($current_model)){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        $files_model = Yii::createObject(Files::className());

        return $this->render('files', [
            'current_model' => $current_model,
            'files_model' => $files_model
        ]);
    }


    public function actionUpload($id)
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = Yii::createObject(Files::className());

            $model->event_id = $id;

            if(isset($_FILES)){
                $this->saveFiles($model);
                $model->save(false);

                return [
                    'result' => 'success'
                ];
            }

        }
    }

    public function actionFileDelete($id)
    {
        $model = Files::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }else{
            $url = $model->file;
            if($model->delete()){
                @unlink(Yii::getAlias('@webroot').$url);
                $this->flash('success', Yii::t('easyii', 'File cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    /**
     * Удалить
     * @param $alias
     * @param $id
     * @return mixed
     */
    public function actionDelete($alias, $id)
    {
        $current_model = Base::getModel($alias);

        if(($current_model = $current_model::findOne($id))){
            $current_model->delete();
        } else {
            $this->error =  Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse('Запись удалена');
    }


    /**
     * Удалить изображение
     * @param $attribute
     * @param $alias
     * @param $id
     * @return \yii\web\Response
     */
    public function actionClearFile($attribute, $alias, $id)
    {
        $current_model = Base::getModel($alias);

        $current_model = $current_model::findOne($id);

        if($current_model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }else{
            $url = $current_model->$attribute;
            $current_model->$attribute = '';
            if($current_model->update()){
                @unlink(Yii::getAlias('@webroot').$url);
                $this->flash('success', Yii::t('easyii', 'File cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $current_model->formatErrors()));
            }
        }
        return $this->back();
    }


    /**
     * Активировать
     * @param $alias
     * @param $id
     * @return mixed
     */
    public function actionOn($alias, $id)
    {
        return $this->changeStatus($alias, $id, Base::STATUS_ON);
    }


    /**
     * Деактивировать
     * @param $alias
     * @param $id
     * @return mixed
     */
    public function actionOff($alias, $id)
    {
        return $this->changeStatus($alias, $id, Base::STATUS_OFF);
    }

    /**
     * Изменить статус
     * @param $alias
     * @param $id
     * @param $status
     * @return mixed
     */
    public function changeStatus($alias, $id, $status)
    {
        $current_model = Base::getModel($alias);

        if($current_model = $current_model::findOne($id)){
            $current_model->status = $status;
            $current_model->update();
        }else{
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }


    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }
}