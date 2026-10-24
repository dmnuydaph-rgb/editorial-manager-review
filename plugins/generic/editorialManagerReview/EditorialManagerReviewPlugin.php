<?php

/**
 * @file plugins/generic/editorialManagerReview/EditorialManagerReviewPlugin.php
 *
 * Copyright (c) 2026 Davao Oriental State University / Davao Research Journal
 * Distributed under the GNU GPL v3.
 *
 * @class EditorialManagerReviewPlugin
 * @brief Editorial Manager Style Peer Review Portal for OJS 3.4.
 */

namespace APP\plugins\generic\editorialManagerReview;

use APP\core\Application;
use APP\plugins\generic\editorialManagerReview\pages\ReviewerPortalHandler;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

class EditorialManagerReviewPlugin extends GenericPlugin
{
    /**
     * @copydoc Plugin::register()
     */
    public function register($category, $path, $mainContextId = null): bool
    {
        $success = parent::register($category, $path, $mainContextId);
        if (Application::isUnderMaintenance()) {
            return $success;
        }

        if ($success) {
            Hook::add('LoadHandler', $this->callbackLoadHandler(...), Hook::SEQUENCE_CORE);
        }

        return $success;
    }

    /**
     * @copydoc LazyLoadPlugin::getEnabled()
     */
    public function getEnabled($contextId = null): bool
    {
        return true;
    }

    /**
     * @copydoc Plugin::getDisplayName()
     */
    public function getDisplayName(): string
    {
        return __('plugins.generic.editorialManagerReview.displayName');
    }

    /**
     * @copydoc Plugin::getDescription()
     */
    public function getDescription(): string
    {
        return __('plugins.generic.editorialManagerReview.description');
    }

    /**
     * Hook callback for LoadHandler
     */
    public function callbackLoadHandler(string $hookName, array $params): bool
    {
        $page = &$params[0];
        $op = &$params[1];
        $handler = &$params[3];

        $pageLower = strtolower((string)$page);

        if ($this->getEnabled()) {
            if (in_array($pageLower, ['reviewer-portal', 'em-review', 'reviewerportal'])) {
                $handler = new ReviewerPortalHandler($this);
                return true;
            }
        }

        return false;
    }
}
