<?php

namespace AnnotateCms\Themes;


class Theme
{

	private $defaultDefinition = [
		'name' => NULL,
		'version' => NULL,
		'author' => NULL,
		'dependencies' => [],
	];

	private $definition;

	private $aDir;

	private $rDir;

	private $checked = FALSE;


	function __construct($def, $aDir, $rDir)
	{
		$this->definition = array_merge($this->defaultDefinition, $def);
		$this->aDir = $aDir;
		$this->rDir = $rDir;
	}


	public function getName()
	{
		return $this->definition['name'];
	}


	public function getVersion()
	{
		return $this->definition['version'];
	}


	public function getAuthor()
	{
		return $this->definition['author'];
	}


	public function isChecked()
	{
		return $this->checked;
	}


	public function setChecked()
	{
		$this->checked = TRUE;
	}


	public function getDependencies()
	{
		return $this->definition['dependencies'];
	}


	public function hasDependencies()
	{
		return !empty($this->definition['dependencies']);
	}


	public function getRelativePath()
	{
		return \str_replace('\\', '/', $this->rDir);
	}


	public function getPath()
	{
		return $this->aDir;
	}

}
