<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dolphin\Core\Model;

use Magento\Robots\Model\Config\Value;

/**
 * Sitemap model.
 *
 * @method string getSitemapType()
 * @method \Magento\Sitemap\Model\Sitemap setSitemapType(string $value)
 * @method string getSitemapFilename()
 * @method \Magento\Sitemap\Model\Sitemap setSitemapFilename(string $value)
 * @method string getSitemapPath()
 * @method \Magento\Sitemap\Model\Sitemap setSitemapPath(string $value)
 * @method string getSitemapTime()
 * @method \Magento\Sitemap\Model\Sitemap setSitemapTime(string $value)
 * @method int getStoreId()
 * @method \Magento\Sitemap\Model\Sitemap setStoreId(int $value)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @api
 * @since 100.0.2
 */
class Sitemap extends \Magento\Sitemap\Model\Sitemap {
	protected function _getSitemapRow($url, $lastmod = null, $changefreq = null, $priority = null, $images = null) {
		$url = $this->_getUrl($url);
		$row = '<loc>' . $this->_escaper->escapeUrl($url) . '</loc>';
		if ($lastmod) {
			$row .= '<lastmod>' . $this->_getFormattedLastmodDate($lastmod) . '</lastmod>';
		}
		if ($changefreq) {
			$row .= '<changefreq>' . $this->_escaper->escapeHtml($changefreq) . '</changefreq>';
		}
		if ($priority) {
			$row .= sprintf('<priority>%.1f</priority>', $this->_escaper->escapeHtml($priority));
		}
		if ($images) {
			// Add Images to sitemap
			foreach ($images->getCollection() as $image) {
				$row .= '<image:image>';
				$row .= '<image:loc>' . $this->_escaper->escapeUrl($image->getUrl()) . '</image:loc>';
				if ($image->getTitle()) {
					$row .= '<image:title>' . $this->escapeXmlText($images->getTitle()) . '</image:title>';
				}
				if ($image->getCaption()) {
					$row .= '<image:caption>' . $this->escapeXmlText($image->getCaption()) . '</image:caption>';
				}
				$row .= '</image:image>';
			}
			// Add PageMap image for Google web search
			$row .= '<PageMap xmlns="http://www.google.com/schemas/sitemap-pagemap/1.0"><DataObject type="thumbnail">';
			$row .= '<Attribute name="name" value="' . $this->_escaper->escapeHtmlAttr($images->getTitle()) . '"/>';
			$row .= '<Attribute name="src" value="' . $this->_escaper->escapeUrl($images->getThumbnail()) . '"/>';
			$row .= '</DataObject></PageMap>';
		}

		return '<url>' . $row . '</url>';
	}
}
