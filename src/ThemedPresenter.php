<?php

namespace Annotate\Themes;

use Annotate\Templating\ITemplateFactory;


trait ThemedPresenter
{

	/** @var ITemplateFactory @inject */
	public $templateFactory;

	/** @var string */
	protected $templateFile;



	public function formatTemplateFiles()
	{
		if (!$this->templateFile) {
			$this->templateFile = $this->action;
		}

		return $this->templateFactory->formatTemplateFiles($this->templateFile, $this);
	}



	public function formatLayoutTemplateFiles()
	{
		$layout = $this->layout ? $this->layout : "layout";

		return $this->templateFactory->formatLayoutTemplateFiles($layout, $this);
	}



	protected function beforeRender()
	{
		$this->templateFactory->setupTemplate($this->template);
	}

}
