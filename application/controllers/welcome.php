<?php 
class Welcome 
{
	public function index()
	{
		$master = Page::getPageObj();
		$master->load(ROOT . 'application/views/master.html');

		$home = new Page();
		$home->load(ROOT . 'application/views/home.html');

		$master->BASEURL = BASEURL;
		$master->content = $home->getPageContent();
		$master->execute();
	}
}
 ?>