<?php

namespace backend\controllers;

use common\models\AuthItem;
use common\models\CrmPush;
use common\models\Exam;
use common\models\Menu;
use common\models\MenuSearch;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentMaster;
use common\models\StudentPerevot;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    use ActionTrait;

    public function actionIndex()
    {
        $query = Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->leftJoin(Exam::tableName() . ' e', 's.id = e.student_id AND e.status = 3 AND e.is_deleted = 0')
            ->leftJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id AND sp.file_status = 2 AND sp.is_deleted = 0')
            ->leftJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id AND sd.file_status = 2 AND sd.is_deleted = 0')
            ->leftJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id AND sm.file_status = 2 AND sm.is_deleted = 0')
            ->where([
                'u.step' => 5,
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.is_deleted' => 0,
            ])
            ->andWhere(getConsIk())
            ->andWhere([
                'or',
                ['not', ['e.student_id' => null]],
                ['not', ['sp.student_id' => null]],
                ['not', ['sd.student_id' => null]],
                ['not', ['sm.student_id' => null]]
            ])->orderBy('s.id desc')->all();

        dd(count($query));

        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubMenu($id)
    {
        $menu = Menu::findOne($id);
        if (!isset($menu)) {

        }
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search($this->request->queryParams , $id);

        return $this->render('sub-menu', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'menu' => $menu
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new Menu(['scenario' => Menu::SCENARIO_MENU]);
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($this->request->post())) {
                $model->status = 0;
                $result = Menu::createItem($model , $post);
                if ($result['is_ok']) {
                    return $this->redirect(['index']);
                } else {
                    \Yii::$app->session->setFlash('error' , $result['error']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionSubMenuCreate($id)
    {
        $menu = Menu::findOne($id);
        if (!isset($menu)) {

        }
        $model = new Menu(['scenario' => Menu::SCENARIO_SUB_MENU]);
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $model->status = 1;
                $result = Menu::createSubMenu($model , $post);
                if ($result) {
                    return $this->redirect(['sub-menu', 'id' => $model->parent_id]);
                } else {
                    dd(22222);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('sub-menu-create', [
            'model' => $model,
            'menu' => $menu
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = new Menu();
        $model = $model->findOne($id);
        $model->scenario = Menu::SCENARIO_MENU;
        $orderOld = $model->order;
        if ($this->request->isPost) {
            $post = $this->request->post();
            $model->load($post);
            $result = Menu::updateItem($model , $post , $orderOld);
            if ($result) {
                return $this->redirect(['index']);
            } else {
                dd(22222);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSubMenuUpdate($id)
    {
        $model = new Menu();
        $model = $model->findOne($id);
        $model->scenario = Menu::SCENARIO_SUB_MENU;
        $orderOld = $model->order;
        if ($this->request->isPost) {
            $post = $this->request->post();
            $model->load($post);
            $result = Menu::updateSubMenu($model , $post , $orderOld);
            if ($result) {
                return $this->redirect(['index', 'id' => $model->parent_id]);
            } else {
                dd(22222);
            }
        }

        return $this->render('sub-menu-update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

}
