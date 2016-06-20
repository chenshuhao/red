<?php

namespace Apps\Admin;


class TiaoController extends BaseController
{
	public function indexAction ()
	{
		$this->view->setVar('title',$this->params('title'));
		$this->view->setVar('url',$this->params('url'));
		$this->viewPick();
	}
}