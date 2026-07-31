<?php
Namespace Carouselck;

defined('CK_LOADED') or die;

class CKView {

	protected $name;

	protected $model;

	protected $input;

	protected $items;

	protected $item;

	protected $state;

	protected $pagination;

	public function __construct() {
		// check if the user has the rights to access this page
		if (!CKFof::userCan('edit')) {
			CKFof::_die();
		}
		$this->input = CKFof::getInput();
	}

	public function display($tpl = 'default') {
		if ($this->model) {
			$this->state = $this->model->getState();
			if (method_exists($this->model, 'getPagination')) $this->pagination = $this->model->getPagination();
		}

		include_once CAROUSELCK_PATH . '/views/' . strtolower($this->name) . '/tmpl/' . $tpl . '.php';
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setModel($model) {
		$this->model = $model;
	}

	public function get($func, $params = array()) {
		$model = $this->getModel();
		$funcName = 'get' . ucfirst($func);
		return $model->$funcName($params);
	}

	public function getModel() {
		if (empty($this->model)) {
			include_once(CAROUSELCK_PATH . '/models/' . strtolower($this->name) . '.php');
			$className = '\Carouselck\CKModel' . ucfirst($this->name);
			$this->model = new $className;
		}
		return $this->model;
	}
}