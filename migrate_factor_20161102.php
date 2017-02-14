<?php
date_default_timezone_set('PRC');
define("ROOT_PATH", dirname(__FILE__));
define("STAT_PATH", dirname(dirname(__FILE__)));
include(ROOT_PATH ."/cls_mysql.php");


class Factor {
	private $config;
	private $from;
	private $to;
	public function __construct() {
		$this->config = include(STAT_PATH .'/stat/Conf/config.php');
		$this->initDB();
		
		$this->handle();
	}
	
	private function initDB() {
		$to = [
			'db_host' => 'rm-bp10t4b90gd61862zo.mysql.rds.aliyuncs.com',
			'db_user' => 'yfcloud_web',
			'db_password' => '73c6ae817b3544ac6b22263d62bb93e1',
			'db_name' => 'yunfan_console'
		];
		
		$conf = $this->config;
		$this->from = new cls_mysql($conf['DB_HOST'],$conf['DB_USER'],$conf['DB_PWD'],$conf['DB_NAME'], 'utf8', 1, 1);
		$this->to = new cls_mysql($to['db_host'], $to['db_user'], $to['db_password'], $to['db_name'], 'utf8', 1, 1);
	}
	
	//ת���Ʒѷ�ʽ
	private function convertCostType($costType = '') {
		if (empty($costType) || is_null($costType)) {
			$costType = '1,2';
		}
		
		$costTypes = explode(',', $costType);
		$rs = [];
		foreach($costTypes as $type) {
			$type = trim($type);
			switch($type) {
				case '1':
					array_push($rs, 'middle');
					break;
				case '2':
					array_push($rs, 'edge');
					break;
				case '3':
					array_push($rs, 'backsource');
					break;
			}
		}
		
		return implode(',', $rs);
	}
	
	private function convertSendMultiple($cost = 1, $back = 1) {
		$rs = [
			[
				'st' => 0,
				'et' => 0,
				'cost' => floatval($cost),
				'back' => floatval($back),
				'prob' => 1
			]
		];
		return json_encode($rs);
	}
	
	private function handleCdn() {
		$dms = [];
		$exclude = ['yfcdn.vod.pptv.com', 'yfhcdn.titan.mgtv.com', 'pcdownyf.titan.mgtv.com', 'pcvideoyf.titan.mgtv.com', 'pcvideoyd.titan.mgtv.com', 'pcdownyd.titan.mgtv.com'];
		
		$not_found = [];
		$exceptions = [];
		
		$sql = "select host, cost_type, source_multiple, cost_multiple from stat_service where bid in (2, 3, 4) and host != ''";
		$rs = $this->from->getAll($sql);
		foreach ($rs as $v) {
			$hosts = explode(",", $v['host']);
			$costType = trim($v['cost_type']);
			foreach($hosts as $h) {
				$h = trim($h);
				if (!empty($h)) {
					//����
					$tmp = explode('_', $h);
					$dm = $tmp[0];
					if (!in_array($dm, $exclude)) {
						array_push($dms, [
							'domain' => $h,
							'costType' => $this->convertCostType($costType),
							'send' => $this->convertSendMultiple($v['cost_multiple'], $v['source_multiple'])
						]);	
					}	
				}
			}
		}
		
		//��������
		foreach ($dms as $d) {
			$sql = "select * from domains where name='{$d['domain']}'";
			$row = $this->to->getOne($sql);
			//��֤��������
			if (!empty($row)) {
				//����ϵ��
				try {
					$dt = new DateTime();
					$now = $dt->format('Y-m-d H:i:s');
					$sql = "insert into domain_charge_factor
					(domain, cost, backsource, costType, send, created_at, updated_at)
					values ('{$d['domain']}', 1, 1, '{$d['costType']}', '{$d['send']}', '{$now}', '{$now}')";
					
					$this->to->query($sql);
//					echo $sql;
				} catch (Exception $e) {
					echo $e;
					array_push($exceptions, $d);
				}
			} else {
				//��¼�����ڵ�����
				array_push($not_found, $d);
			}
		}
		
		return [$exceptions, $not_found];
	}
	
	private function handleLive() {
		$dms = [];
		$exclude = [];
		
		$not_found = [];
		$exceptions = [];
		
		$sql = "select host, cost_type, source_multiple, cost_multiple from stat_service where bid = 1 and host != ''";
		$rs = $this->from->getAll($sql);
		foreach ($rs as $v) {
			$hosts = explode(",", $v['host']);
			$costType = trim($v['cost_type']);
			foreach($hosts as $h) {
				$h = trim($h);
				if (!empty($h)) {
					//����
					$tmp = explode('_', $h);
					$dm = $tmp[0];
					if (!in_array($dm, $exclude)) {
						array_push($dms, [
							'domain' => $h,
							'costType' => $this->convertCostType($costType),
							'send' => $this->convertSendMultiple($v['cost_multiple'], $v['source_multiple'])
						]);	
					}	
				}
			}
		}
		
		//��������
		foreach ($dms as $d) {
			$sql = "select * from rtmps where name='{$d['domain']}' or rtmp_host = '{$d['domain']}' or flv_host = '{$d['domain']}' or hls_host='{$d['domain']}'";
			$row = $this->to->getOne($sql);
			//��֤��������
			if (!empty($row)) {
				//����ϵ��
				try {
					$dt = new DateTime();
					$now = $dt->format('Y-m-d H:i:s');
					$sql = "insert into domain_charge_factor
					(domain, cost, backsource, costType, send, created_at, updated_at)
					values ('{$d['domain']}', 1, 1, '{$d['costType']}', '{$d['send']}', '{$now}', '{$now}')";
					
					$this->to->query($sql);
					//echo $sql;
				} catch (Exception $e) {
					echo $e;
					array_push($exceptions, $d);
				}
			} else {
				//��¼�����ڵ�����
				array_push($not_found, $d);
			}
		}
		
		return [$exceptions, $not_found];
	}
	
	public function handle() {
		//Ǩ�Ƶ㲥
		list($exceptions, $not_found) = $this->handleCdn();
		echo "CDN Exception: " . "\r\n";
		var_export($exceptions) . "\r\n";
		
		echo "CDN Not_Found: " . "\r\n";
		var_export($not_found) . "\r\n";
		
		/*
		//Ǩ��ֱ��
		list($exceptions, $not_found) = $this->handleLive();
		echo "Live Exception: " . "\r\n";
		var_export($exceptions) . "\r\n";
		
		echo "Live Not_Found: " . "\r\n";
		var_export($not_found) . "\r\n";		
		*/
	}
}

new Factor();