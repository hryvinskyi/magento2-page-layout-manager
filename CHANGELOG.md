# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.4] - 2026-01-13

### Added
- **NEW**: `DisablePageLayoutGetters` plugin for CMS pages - Forces page layout getters to return null
- Added `afterGetPageLayout()` plugin method to intercept and nullify page layout retrieval
- Added `aroundGetData()` plugin method to prevent custom_page_layout data from being returned

### Changed
- CMS pages now have their getPageLayout() method intercepted to always return null
- Custom page layout data access via getData('custom_page_layout') now returns null
- Updated di.xml to register DisablePageLayoutGetters plugin on Magento\Cms\Model\Page

### Fixed
- Prevents entity-specific layout handles from being applied at runtime for CMS pages
- Further reduces cache bloat by ensuring no page-specific layout data is used

## [1.0.2]

### Added
- **NEW**: `DisableEntitySpecificLayout` plugin for CMS pages - Prevents entity-specific page layouts from being applied to CMS pages
- **NEW**: UI component modification to disable page_layout field in CMS page admin form with informative message

### Changed
- CMS page layout field now displays as disabled (grayed out) in admin panel
- Added notice message explaining that entity-specific layout selection has been removed

### Fixed
- Prevented CMS pages from using entity-specific layouts on frontend, ensuring only global/theme-level layouts are used

## [1.0.1]

### Added
- **NEW**: `ParameterModifierInterface` - Interface for parameter modification logic
- **NEW**: `ModificationResultInterface` - Interface for parameter modification results
- **NEW**: `ModificationResult` - Value object for parameter modification results
- **NEW**: `LayoutDecisionInterface` - Interface for layout decision value objects
- **NEW**: `LayoutDecision` - Immutable value object representing layout decisions