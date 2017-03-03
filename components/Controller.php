<?php


/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $userInfo;
	public $currentUrl;
	
	public function __construct($id, $module = null)
	{
		parent::__construct($id, $module);
		$this->filterInput();
		//$this->authenticate();
		//$this->userInfo = Yii::app()->session['userInfo'];
		//$this->currentUrl = str_replace('?'.$_SERVER['QUERY_STRING'],'',$this->currentUrl);
	}

	/**
	 * 初始化 
	 */
	public function init()
	{
		
	}
	
	public function authenticate()
	{
		//print_r(Yii::app()->session['codeManager']);
		//unset(Yii::app()->session['userInfo']);print_r(Yii::app()->session['userInfo']);
		
		if(!Yii::app()->session['login']||empty(Yii::app()->session['userInfo']))
		{

			if (!\Yii::$app->user->isGuest) {
	            return $this->goHome();
	        }

	        $model = new LoginForm();
	        if ($model->load(Yii::$app->request->post()) && $model->login()) {
	            return $this->goBack();
	        } else {
	            return $this->render('login', [
	                'model' => $model,
	            ]);
	        }
			//$this->redirect('/site/login');
			
			//$sso_object = new Sso();
			//$sso_user = $sso_object->parse_sso_response_token();
			//$sso_url = $sso_object->get_sso_url(false,'http://'.$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			if ($sso_user)
			{
				//echo $sso_user;
				$hecUserModel = new HecUser();
				//print_r($hecUserModel->getUserInfoByName($sso_user));
				//var_dump(Yii::app()->session['userInfo'] = $hecUserModel->getUserInfoByName($sso_user));
				//die;
				Yii::app()->session['login'] = true;
				Yii::app()->session['userInfo'] = $hecUserModel->getUserInfoByName($sso_user); 
				Yii::app()->session['admin'] = 0;
				/*
				if(AdminUser::model()->checkAdmin($sso_user)) {
					Yii::app()->session['admin'] = 1;
				}
				//是否是code管理员
				Yii::app()->session['codeManager'] = 0;
				$code = Tinit::getCodes();
				foreach ($code as $key => $value) {
					if ($value['manager'] == $sso_user) {
						Yii::app()->session['codeManager'] = 1;
						break;
					}
				}
				*/
				//$identity = new UserIdentity(Yii::app()->session['userInfo'], 'www');
				//$duration = 3600 * 24 * 30;
				//Yii::app()->user->login($identity, $duration);
				//用户组
				//Yii::app()->session['userGroup'] = $hecUserModel->identityGroup(Yii::app()->session['userInfo']->unique_id,Yii::app()->session['userInfo']->username);
			}
			else
			{
				$this->redirect($sso_url);
			}
			
		}
		if(isset($_GET['appid']) && $_GET['appid'] == 'expense')
		{
			$url = 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$arr = explode("?appid", $url);
			$this->redirect($arr[0]);
		}
	}
	
	/**
	 * Addslashes special charactors
	 */
	private function filterInput()
	{
		
		//if(!get_magic_quotes_gpc())
		//{
			$_POST = Tools::getSafeValue($_POST);
			$_GET= Tools::getSafeValue($_GET);
		//}
		//$_GET = Tools::shtmlSpecialchars($_GET);
		//$_POST = Tools::shtmlSpecialchars($_POST);
	
	}

	/**
	 * 跳转
	 */
	public function showAlert($message){
		$this->layout='//layouts/alert';
		$this->render('//site/alert',array(
			'message'=>$message,
		));
		exit;
	}
}