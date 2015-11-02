<?php

require_once(SYSCONFIG_CLASS_PATH.'blocks/blockbasephp.class.php');

class MainBlock extends BlockBasePHP {

	var $pageTitle = '';
	var $breadCrumbs = '';
	var $templateTheme = SYSCONFIG_THEME;

	function MainBlock() {
		parent::BlockBasePHP();
		$this->templateDir = SYSCONFIG_THEME_PATH.$this->templateTheme.'/templates/';
		$this->templateFile = 'index.tpl.php';
	}
	function MainBlock_forpopup() {
		parent::BlockBasePHP();
		$this->templateDir = SYSCONFIG_THEME_PATH.$this->templateTheme.'/templates/';
		$this->templateFile = 'index_forpopup.tpl.php';
	}

	function setBreadCrumbs($bc,$attribs="class='breadCrumbs'") {
		$parts = array();
		foreach ($bc as $bckey => $bclink) {
			if ( $bclink ) {
				$parts[] = "<a href='{$bclink}' {$attribs}>{$bckey}</a>";
			} else {
				$parts[] = "<span {$attribs}>{$bckey}</span>";
			}
		}

		$this->breadCrumbs = implode(' &gt; ',$parts);
	}

}
?>