<?php

namespace Annotate\Themes;


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

	/** @var string */
	private $parent;



	public function __construct($def, $aDir, $rDir)
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
		return str_replace(DIRECTORY_SEPARATOR, '/', $this->rDir);
	}



	public function getPath()
	{
		return $this->aDir;
	}



	public function setParent($parent)
	{
		$this->parent = $parent;
	}



	/**
	 * @return string
	 */
	public function getParent()
	{
		return $this->parent;
	}



	public function addDependencies(array $dependencies)
	{
		$this->definition['dependencies'] = array_merge($this->definition['dependencies'], $dependencies);
	}

}
