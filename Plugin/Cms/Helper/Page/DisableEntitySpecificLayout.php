<?php
/**
 * Copyright (c) 2025. Volodymyr Hryvinskyi. All rights reserved.
 * Author: Volodymyr Hryvinskyi <volodymyr@hryvinskyi.com>
 * GitHub: https://github.com/hryvinskyi
 */

declare(strict_types=1);

namespace Hryvinskyi\PageLayoutManager\Plugin\Cms\Helper\Page;

use Magento\Cms\Helper\Page;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\Page as ResultPage;

/**
 * Plugin to prevent entity-specific page layout from being applied in frontend
 *
 * This plugin intercepts the prepareResultPage method to clear any entity-specific
 * page layout configuration, ensuring only global/theme-level layouts are used.
 * This prevents cache bloat from entity-specific layout handles.
 */
class DisableEntitySpecificLayout
{
    /**
     * Clear entity-specific page layout configuration
     *
     * This after plugin removes any entity-specific page layout that was set
     * during page preparation, ensuring consistent layout application across all pages.
     * The page will use the global/theme default layout instead.
     *
     * @param Page $subject
     * @param ResultPage|false $result
     * @param ActionInterface $action
     * @param int|null $pageId
     * @return ResultPage|false
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPrepareResultPage(
        Page $subject,
        $result,
        ActionInterface $action,
        $pageId = null
    ) {
        // If prepareResultPage returned false (page not found), return as is
        if ($result === false) {
            return $result;
        }

        // Clear any entity-specific page layout that was set by setLayoutType()
        // This forces the page to use the theme's default layout configuration
        // The page layout configuration is managed at theme level, not per entity
        $result->getConfig()->setPageLayout(null);

        return $result;
    }
}