<?php
/**
 * Copyright (c) 2026. Volodymyr Hryvinskyi. All rights reserved.
 * Author: Volodymyr Hryvinskyi <volodymyr@hryvinskyi.com>
 * GitHub: https://github.com/hryvinskyi
 */

declare(strict_types=1);

namespace Hryvinskyi\PageLayoutManager\Plugin\Cms\Model\Page;

use Magento\Cms\Model\Page;

/**
 * Plugin to force page layout methods to return null
 *
 * This plugin intercepts getPageLayout() and getData() methods to ensure
 * that page-specific layout configurations return null, preventing entity-specific
 * layout handles from being applied and reducing cache bloat.
 */
class DisablePageLayoutGetters
{
    /**
     * Force getPageLayout() to return empty string
     *
     * This after plugin ensures that the page layout getter always returns empty string,
     * preventing entity-specific page layouts from being applied.
     *
     * @param Page $subject
     * @param string|null $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetPageLayout(Page $subject, $result): string
    {
        return '';
    }

    /**
     * Force page_layout and custom_page_layout data to return empty string
     *
     * This after plugin intercepts getData() calls for the 'page_layout' and 'custom_page_layout' keys
     * and returns empty string to prevent custom page layouts from being applied.
     *
     * @param Page $subject
     * @param mixed $result
     * @param string|null $key
     * @param string|int|null $index
     * @return mixed
     */
    public function afterGetData(Page $subject, $result, $key = null, $index = null)
    {
        // If requesting page_layout or custom_page_layout, return empty string
        if ($key === 'custom_page_layout' || $key === 'page_layout') {
            return '';
        }

        // For all other cases, return the original result
        return $result;
    }
}
