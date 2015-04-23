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

	/** @var array */
	private $definition;

	/** @var string */
	private $aDir;

	/** @var string */
	private $rDir;

	/** @var bool */
	private $checked = FALSE;

	/** @var string */
	private $parent;



	/**
	 * @param  array
	 * @param  string
	 * @param  string
	 */
	public function __construct($def, $aDir, $rDir)
	{
		$this->definition = array_merge($this->defaultDefinition, $def);
		$this->aDir = $aDir;
		$this->rDir = $rDir;
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->definition['name'];
	}



	/**
	 * @return float
	 */
	public function getVersion()
	{
		return (float) $this->definition['version'];
	}



	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->definition['author'];
	}



	/**
	 * @return bool
	 */
	public function isChecked()
	{
		return $this->checked;
	}



	public function setChecked()
	{
		$this->checked = TRUE;
	}



	/**
	 * @return array
	 */
	public function getDependencies()
	{
		return $this->definition['dependencies'];
	}



	/**
	 * @return bool
	 */
	public function hasDependencies()
	{
		return !empty($this->definition['dependencies']);
	}



	/**
	 * @return string
	 */
	public function getRelativePath()
	{
		return str_replace(DIRECTORY_SEPARATOR, '/', $this->rDir);
	}



	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->aDir;
	}



	/**
	 * @param  string
	 */
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



	/**
	 * @param  array
	 */
	public function addDependencies(array $dependencies)
	{
		$this->definition['dependencies'] = array_merge($this->definition['dependencies'], $dependencies);
	}

}
