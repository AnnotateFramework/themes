<?php

namespace AnnotateCmsTests\Themes;

use AnnotateCms\Themes\Theme;
use Tester;
use Tester\Assert;


require_once __DIR__ . '/../bootstrap.php';


class ThemeTest extends TestCase
{

	/** @var  Theme */
	private $theme;


	public function setUp()
	{
		$this->theme = new Theme(
			[
				'name' => 'Flatty',
				'version' => 0.1,
				'author' => 'Michal Vyšinský',
				'dependencies' => [
					'TwitterBootstrap' => [
						'version' => '3',
					],
				],
			], ROOT_DIR . '/data/themes/Flatty/',
			'/data/themes/Flatty/'
		);
	}


	public function testThemeReturnsItsName()
	{
		Assert::same('Flatty', $this->theme->getName());
	}


	public function testThemeReturnsItsVersion()
	{
		Assert::equal(0.1, $this->theme->getVersion());
	}


	public function testThemeReturnsItsAuthor()
	{
		Assert::same('Michal Vyšinský', $this->theme->getAuthor());
	}


	public function testThemeReturnsItsDependencies()
	{
		Assert::same(
			[
				'TwitterBootstrap' => [
					'version' => '3'
				]
			],
			$this->theme->getDependencies()
		);
		Assert::true($this->theme->hasDependencies());
	}


	public function testThemeReturnsItsPath()
	{
		Assert::same(ROOT_DIR . '/data/themes/Flatty/', $this->theme->getPath());
	}


	public function testThemeReturnsItsRelativePath()
	{
		Assert::same('/data/themes/Flatty/', $this->theme->getRelativePath());
	}


	public function testCheckWorks()
	{
		Assert::false($this->theme->isChecked());
		$this->theme->setChecked();
		Assert::true($this->theme->isChecked());
	}

}


\run(new ThemeTest);
