<?php
Yii::import('application.forms.request.*');

class AjaxController extends Controller
{
    public function actionRequestValidate() {
        Yii::import('application.forms.request.*');
        $requestForm = new RequestForm();
        $requestForm->attributes = Yii::app()->request->getPost('RequestForm', array());
        if (Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($requestForm);
            Yii::app()->end();
        }
    }

    public function actionRequestSend() {
        Yii::import('application.forms.request.*');
        $requestForm = new RequestForm();
        $requestForm->attributes = Yii::app()->request->getPost('RequestForm', array());
        if (Yii::app()->request->isPostRequest AND isset($_POST['RequestForm'])){
            $requestForm->attributes = Yii::app()->request->getPost('RequestForm', array());
            if ($requestForm->validate())
            {
                $request = new Request();
                $request->attributes = $requestForm->attributes;
                if(!$request->save())
                    $error = 'Не удалось сохранить заявку';
                else {
                    $is_sent = Yii::app()->mailer->sendTemplate('Новая заявка с сайта', 'application.views.mail.request', array(
                        'data' => $request->attributes,
                    ));
                    if ($is_sent) {
                        $view = 'success';
                    }
                    $requestForm->unsetAttributes();
                }
            }else{
                $error = true;
            }
        }
    }

    public function actionJobValidate() {
        Yii::import('application.forms.request.*');
        $jobRequestForm = new JobRequestForm();
        $jobRequestForm->attributes = Yii::app()->request->getPost('RequestForm', array());
        if (Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($jobRequestForm);
            Yii::app()->end();
        }
    }

    public function actionJobSend() {
        $jobRequestForm = new JobRequestForm();
        if (Yii::app()->request->isPostRequest AND isset($_POST['JobRequestForm'])){
            $jobRequestForm->attributes = Yii::app()->request->getPost('JobRequestForm', array());

            if ($jobRequestForm->validate())
            {
                $jobRequest = new JobRequest();
                $jobRequest->attributes = $jobRequestForm->attributes;
                $attribute = 'file';
                $file = CUploadedFile::getInstance($jobRequestForm,$attribute);
                if($file){
                    $fileName=trim(md5($attribute.time().uniqid(rand(),true))).'.'.$file->getExtensionName();
                    $jobRequest->file=$fileName;
                }
                if(!$jobRequest->save())
                    $error = 'Не удалось сохранить заявку';
                else{
                    $jobRequestForm->unsetAttributes();
                    if($file){
                        $path=Yii::getPathOfAlias('webroot').'/uploads/'.$fileName;
                        $file->saveAs($path);
                    }
                    $job_item = Job::model()->findByPk( (int) $jobRequest->attributes['job_id']);
                    $is_sent = Yii::app()->mailer->sendTemplate('Новый отклик на вакансию', 'application.views.mail.vacancy', array(
                        'data' => $jobRequest->attributes,
                        'job_name' => $job_item->name_ru,
                        'file' => isset($fileName) ? Yii::app()->getRequest()->getHostInfo().'/uploads/'.$fileName : '',
                    ));
                }
            }
        }
    }



} //end class