<?php
class AdminPage {
  	
	private $language;
	private $Tpl;
  	
	public $options = array(
		"header"=>true,
		"footer"=>true,
		"data"=>array(
			"body"=>array(
				"class"=>""
			),
			"js"=>array(),
			"head_title"=>"Adminsitração",
			"meta_description"=>"",
			"meta_author"=>"HCODE"
		)
	);
 
	public function __construct($options = array()){

		$options = array_merge_recursive_distinct($this->options, $options);
		$options['data']['head_title'] .= " - CM Stands";

		$this->language = new Language();

		$options['data']['string'] = $this->language->loadString();
		if(isset($_SESSION)) $options['data']['session'] = $_SESSION;
		if(isset($_SERVER)) $options['data']['server'] = $_SERVER;
		$options['data']['path'] = SITE_PATH;

		$tpl = $this->getTpl();
		$this->options = $options;

		$tpl->configure(array(
			'base_url'=>PATH,
			'tpl_dir'=>PATH."/res/tpl/admin/",
			'cache_dir'=>PATH."/res/tpl/tmp/admin/",
			'path_replace'=>false
		));
 
		if(gettype($this->options['data'])=='array'){
			foreach($this->options['data'] as $key=>$val){
				$tpl->assign($key, $val);
			}
		}
 
		if ($this->options['header'] === true) $tpl->draw("header", false);
 
	}

	public function getString($name){

		return $this->language->getString($name);

	}
 
	public function __destruct(){
 
		$tpl = $this->getTpl();
 
		if(gettype($this->options['data'])=='array'){
			foreach($this->options['data'] as $key=>$val){
				$tpl->assign($key, $val);
			}
		}
 
		if ($this->options['footer'] === true) $tpl->draw("footer", false);
 
	}
 
	public function setTpl($tplname, $data = array(), $returnHTML = false){
 
		$tpl = $this->getTpl();
 
		if(gettype($data)=='array'){
			foreach($data as $key=>$val){
				$tpl->assign($key, $val);
			}
		}
 
		return $tpl->draw($tplname, $returnHTML);
 
	}
  
	public function getTpl(){
 
		return ($this->Tpl)?$this->Tpl:$this->Tpl = new RainTPL;
 
	}
 
}
?>