<?php
class weibo extends spController
{
	//接收微博消息
	function statuses(){
		if($this->spArgs("pass")=="87895841"){
			//$data = $this->spArgs("data");
			$s = spClass('status');

			//foreach( $data['statuses'] as $item ){
				$newrow = array( // PHP的数组
					'sid_stat' => $this->spArgs('id'),
					'text_stat' => $this->spArgs('text'),
					'source_stat' => $this->spArgs('source'),
					'uid_stat'  => $this->spArgs('uid'),
					'thumbnail_stat' => $this->spArgs('thumb'),
					'bmiddle_stat' => $this->spArgs('middle'),
					'original_stat'  => $this->spArgs('original'),
				);
			$s->create($newrow);
			//}
		}
	}

	//接收微博配图
	function picses(){
		if($this->spArgs("pass")=="87895841"){
			//$data = $this->spArgs("data");
			$p = spClass('pics');

			//foreach( $data['statuses'] as $item ){
				$newrow = array( // PHP的数组
					'sid_pics' => $this->spArgs('id'),
					'herf_pics' => $this->spArgs('pic'),
				);
			$p->create($newrow);
			//}
		}
	}

	//接收用户资料
	function wbusers(){
		if($this->spArgs("pass")=="87895841"){
			//$data = $this->spArgs("data");
			$wb = spClass('wbuser');

			//foreach( $data['statuses'] as $item ){
				$newrow = array( // PHP的数组
					'uid_wusr' => $this->spArgs('uid'),
					'name_wusr' => $this->spArgs('name'),
					'description_wusr' => $this->spArgs('desc'),
					'gender_wusr'  => $this->spArgs('gender'),
					'profile_wusr' => $this->spArgs('profile'),
					'large_wusr' => $this->spArgs('large'),
					'hd_wusr' => $this->spArgs('hd'),
				);
			$wb->create($newrow);
			//}
		}
	}
}	



