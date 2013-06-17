<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_Wordpress_Block_Sidebar_Widget_Pages extends Fishpig_Wordpress_Block_Sidebar_Widget_Abstract
{
	/**
	 * Returns the currently loaded page model
	 *
	 * @return Fishpig_Wordpress_Model_Page
	 */
	public function getPage()
	{
		return Mage::registry('wordpress_page');
	}
	
	/**
	 * Retrieve a collection  of pages
	 *
	 * @return Fishpig_Wordpress_Model_Mysql4_Page_Collection
	 */
	public function getPages()
	{
		$pages = Mage::getResourceModel('wordpress/page_collection');

		//echo "depth: " . $this->getPageDepth();

		if (parent::getTitle() === 'Top') {
			$pages->addPostParentIdFilter(0);
		}
		elseif ($this->getPage() && $this->getPage()->hasChildren() && $this->getPageDepth() != 1) {
			$pages->addPostParentIdFilter($this->getPage()->getId());
		}
		elseif ($this->getPageDepth() == 2) {
			$page = $this->getPage();
			$parent = $page->getParentPage()->getParentPage();
			$pages->addPostParentIdFilter($parent->getId());			
		}
		else {
			$page = $this->getPage();
			$parent = $page->getParentPage();
			$pages->addPostParentIdFilter($parent->getId());
		}

		$pages->addIsPublishedFilter();
		$pages->orderByMenuOrder();
		$pages->load();
		
		return $pages;
	}

	public function getPageDepth() {
		$depth = 0;
		$parent = $this->getPage()->getParentPage();
		
		if ($parent) {
			$depth++;
			while($parent->getParentPage()) {
				$parent = $parent->getParentPage();
				$depth++;
			}
		}

		return $depth;
	}
	
	/**
	 * Retrieve the block title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if ($this->getPage() && $this->getPage()->hasChildren()) {
			return $this->getPage()->getPostTitle();
		}
		
		return parent::getTitle();
	}
	
	/**
	 * Retrieve the default title
	 *
	 * @return string
	 */
	public function getDefaultTitle()
	{
		return $this->__('Pages');
	}
}
