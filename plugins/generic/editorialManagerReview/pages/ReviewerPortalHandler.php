<?php

/**
 * @file plugins/generic/editorialManagerReview/pages/ReviewerPortalHandler.php
 *
 * Copyright (c) 2026 Davao Oriental State University / Davao Research Journal
 * Distributed under the GNU GPL v3.
 *
 * @class ReviewerPortalHandler
 * @brief Handles URL requests for OJS /reviewer-portal page.
 */

namespace APP\plugins\generic\editorialManagerReview\pages;

use APP\handler\Handler;
use PKP\core\PKPRequest;

class ReviewerPortalHandler extends Handler
{
    /** @var \APP\plugins\generic\editorialManagerReview\EditorialManagerReviewPlugin */
    protected $plugin;

    public function __construct($plugin)
    {
        parent::__construct();
        $this->plugin = $plugin;
    }

    /**
     * Fallback / index operation: routes reviewer directly to review.davaoresearchjournal.ph
     */
    public function index($args, $request)
    {
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        $targetUrl = 'https://review.philair.org/';

        if (!empty($queryString)) {
            // Strip index.php path if present in query string
            parse_str($queryString, $params);
            unset($params['url']);
            $filteredQuery = http_build_query($params);
            if (!empty($filteredQuery)) {
                $targetUrl .= '?' . $filteredQuery;
            }
        }

        header('Location: ' . $targetUrl, true, 302);
        exit;
    }
}
