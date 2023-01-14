<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://www.question2answer.org/

    Post To Moderation Queue is a Question 2 Answer plugin that
    sends potential SPAM posts to the moderation queue

    Copyright (C) 2023  Gabriel Zanetti  https://question2answer.org/qa/user/pupi1985

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    More about this license: https://www.question2answer.org/license.php
*/

class PUPI_PTMQ_PostsFilter
{
    /**
     * @param array $question
     * @param array $errors
     * @param array|null $oldquestion
     *
     * @return void
     */
    public function filter_question(&$question, &$errors, $oldquestion)
    {
        $this->genericPostFilter($question, 'Q');
    }

    /**
     * @param array $answer
     * @param array $errors
     * @param array $question
     * @param array|null $oldanswer
     *
     * @return void
     */
    public function filter_answer(&$answer, &$errors, $question, $oldanswer)
    {
        $this->genericPostFilter($answer, 'A');
    }

    /**
     * @param array $comment
     * @param array $errors
     * @param array $question
     * @param array $parent
     * @param array|null $oldcomment
     *
     * @return void
     */
    public function filter_comment(&$comment, &$errors, $question, $parent, $oldcomment)
    {
        $this->genericPostFilter($comment, 'C');
    }

    /**
     * @param array $post
     * @param string $postType
     *
     * @return void
     */
    private function genericPostFilter(array &$post, string $postType)
    {
        // For qa_is_logged_in(), qa_get_logged_in_points()
        require_once QA_INCLUDE_DIR . 'app/users.php';

        if (qa_opt('pupi_ptmq_ignore_moderation_with_points') &&
            qa_is_logged_in() &&
            (int)qa_get_logged_in_points() >= (int)qa_opt('pupi_ptmq_min_points')) {
            return;
        }

        try {
            $this->checkEmailInPost($post, $postType);

            $this->checkPhoneInPost($post, $postType);

            $this->checkLinkInPost($post, $postType);
        } catch (Exception $e) {
            $post['queued'] = true;
        }
    }

    /**
     * @param array $post
     * @param string $postType
     *
     * @return void
     * @throws Exception
     */
    private function checkEmailInPost(array $post, string $postType)
    {
        if (qa_opt('pupi_ptmq_check_email')) {
            if ($this->textContainsEmail($post['text'])) {
                throw new Exception();
            }

            if ($postType === 'Q' && $this->textContainsEmail($post['title'])) {
                throw new Exception();
            }
        }
    }

    /**
     * @param array $post
     * @param string $postType
     *
     * @return void
     * @throws Exception
     */
    private function checkPhoneInPost(array $post, string $postType)
    {
        if (qa_opt('pupi_ptmq_check_phone')) {
            if ($this->textContainsPhone($post['text'])) {
                throw new Exception();
            }

            if ($postType === 'Q' && $this->textContainsPhone($post['title'])) {
                throw new Exception();
            }
        }
    }

    /**
     * @param array $post
     * @param string $postType
     *
     * @return void
     * @throws Exception
     */
    private function checkLinkInPost(array $post, string $postType)
    {
        if (qa_opt('pupi_ptmq_check_link')) {
            if ($this->textContainsUrl($post['text']) ||
                ($post['format'] === 'html' && $this->contentContainsLink($post['content']))
            ) {
                throw new Exception();
            }

            if ($postType === 'Q' && $this->textContainsUrl($post['title'])) {
                throw new Exception();
            }
        }
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    private function textContainsEmail($text)
    {
        return (bool)preg_match(
            '/[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
            '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*/s',
            $text
        );
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    private function textContainsPhone($text)
    {
        return (bool)preg_match(
            '/(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?/',
            $text
        );
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    private function textContainsUrl($text)
    {
        return (bool)preg_match(
            '/(?:https?:\/\/|www\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/im',
            $text
        );
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    private function contentContainsLink($text)
    {
        $internal = libxml_use_internal_errors(true);
        $reporting = error_reporting(0);

        $document = DOMDocument::loadHTML($text);
        $anchors = $document->getElementsByTagName('a');

        if ($anchors->length == 0) {
            return false;
        }

        $siteUrl = qa_opt('site_url');
        foreach ($anchors as $anchor) {
            $hrefAttribute = $anchor->getAttribute('href');

            if (strpos($hrefAttribute, $siteUrl) !== 0) {
                return true;
            }
        }

        libxml_use_internal_errors($internal);
        error_reporting($reporting);

        return false;
    }
}
