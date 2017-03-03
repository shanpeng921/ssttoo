<?php
namespace app\components;

use Yii;

class GlobalValue {

	public static function get($n,$k)
	{
		if(!method_exists( new self, $n)) return null;	
		$v = self::$n();
		if(!empty($v)) return $v[$k];
		return null;
	}

	public static function appType()
	{
		return array(
				//0 => Yii::t('leave', 'app_leave'),
				1 => Yii::t('leave', 'app_leave'),
				2 => Yii::t('leave', 'app_ot'),
			);
	}

	public static function dayLevel()
	{
		return [
			1 => 2,
			2 => 5,
			3 => 10,
		];
	}

	public static function appStatus()
	{
		return array(
				-2 => '用户取消',
				-1 => '驳回',
				1 => '审核中',
				//0=>'',
				2 => '审核完成',
				4 => '申请销假',
				-4 => '销假成功', 
			);
	}

	public static function overtimeIndex()
	{
		return array(
			-2 => '用户取消',
			-1 => '驳回',
			1 => '审核中',
			0 => '',
			2 => '审核完成',
			4 => '取消加班',
			-4 => '取消加班成功',
			41 => '驳回',
		);
	}

	public static function emailStatus()
	{
		return [
			'STATUS_NO' => 1,
			'STATUS_SUC' => 2,
		];
	}

	public static function emailErrors()
	{
		return [
			'E0' => 0,
			'E1' => 1,
			'E2' => 2,
			'E3' => 3,
		];
	}

	public static function leaveCategory()
	{

		return  array(
			1 => Yii::t('leave', 'appc1'),
			2 => Yii::t('leave', 'appc2'),
			3 => Yii::t('leave', 'appc3'),
			4 => Yii::t('leave', 'appc4'),
			5 => Yii::t('leave', 'appc5'),
			6 => Yii::t('leave', 'appc6'),
			7 => Yii::t('leave', 'appc7'),
			8 => Yii::t('leave', 'appc8'),
			9 => Yii::t('leave', 'appc9'),
			10 => Yii::t('leave', 'appc10'),
			11 => Yii::t('leave', 'appc11'),
			14 => Yii::t('leave', 'appc12'),
			12 => Yii::t('leave', 'appc13'),
			13 => Yii::t('leave', 'appc14'),
			15 => Yii::t('leave', 'appc15'),
			16 => Yii::t('leave', 'appc16'),
			17 => Yii::t('leave', 'appc17'),
			20 => Yii::t('leave', 'appc20'),
			21 => Yii::t('leave', 'appc21'),
			30 => Yii::t('leave', 'appc30'),
			30 => Yii::t('leave', 'appc30'),
			31 => Yii::t('leave', 'appc31'),
			32 => Yii::t('leave', 'appc32'),
			33 => Yii::t('leave', 'appc33'),
		);
	}

	/**
	 * 发起的类型
	 * @return array
	 */
	public static function leaveCategoryCreate()
	{

		return  array(
			1 => Yii::t('leave', 'appc1'),
			2 => Yii::t('leave', 'appc2'),
			33 => Yii::t('leave', 'appc33'),
			5 => Yii::t('leave', 'appc5'),
			15 => Yii::t('leave', 'appc15'),
			31 => Yii::t('leave', 'appc31'),
			6 => Yii::t('leave', 'appc6'),
			32 => Yii::t('leave', 'appc32'),
			9 => Yii::t('leave', 'appc9'),
		);
	}

	public static function gongchuCategory()
	{
		return array(
			10 => Yii::t('leave', 'appc10'),
			11 => Yii::t('leave', 'appc11'),
			14 => Yii::t('leave', 'appc14'),
			12 => Yii::t('leave', 'appc12'),
			21 => Yii::t('leave', 'appc21'),
		);
	}

	public static function shengyuCategory()
	{
		return array(
			7 => Yii::t('leave', 'appc7'),
			8 => Yii::t('leave', 'appc8'),
			13 => Yii::t('leave', 'appc13'),
			17 => Yii::t('leave', 'appc17'),
		);
	}

	public static function leaveType()
	{
		return array(
			1 => array(
				2,7,8,9,11,12,14,15
			),
			2 => array(
				1,3,4,6,13,5,30,10
			),
		);
	}

	public static function leaveIndex()
	{
		return array(
			0, //未开始
			1, //非普通发起 到ss
			2, //普通 <2天 到直接主管L1 L2 L3
			3, //普通 >=2天 到L1
			4, //普通>=2天 到L2
			5, //普通>=2天 到L3
			6, //非普通  <2天 ss到直接主管
			7, //非普通 >2天 到L1
			8, //非普通 >2天 到L2
			9, //非普通 >2天 到L3
			10, //L1通过 到L2
			11, //L2通过 到xuan.wang
			12, //<3天  或者L3通过后的 xuan.wang通过 完成
			13, //xuan.wang通过 L2通过后 >=3天 去L3审核
			14, //C级用户 L3审核通过 给xuan.wang审核
			15, //L3审核通过  完成
			16, //直接主管审核通过  完成
			21, //ss驳回
			22, //直接主管驳回
			23, //L1驳回
			24, //L2驳回
			25, //xuan.wang 驳回
			26, //L3驳回
		);
	}

	public static function leaveCountWeekend()
	{
		return array('6','7','8','9','11','12','14');
	}

	public static function standardWorkTime()
	{
		return array(
					'start' => '10:00',
					'end' => '18:00',
					'work_hour' => 9,
				);
	}

	public static function auditName()
	{
		return [
			-1 => '驳回',
			1 => '发起',
			2 => '通过',
			3 => '发起销假',
			4 => '通过销假',
		];
	}

	public static function adminLevel()
	{
		return [
			1 => '考勤专员',
			2 => '添加临时卡专员',
			3 => 'HR管理员',
			11 => '超级管理员',
		];

	}

	public static function yearvacationType()
	{
		return [
			1 => '假期',
			-1 => '工作日',
		];
	}







}