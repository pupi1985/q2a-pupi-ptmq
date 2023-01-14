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

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module('process', 'PUPI_PTMQ_Admin.php', 'PUPI_PTMQ_Admin', 'PUPI_PTMQ Admin');

qa_register_plugin_module('filter', 'PUPI_PTMQ_PostsFilter.php', 'PUPI_PTMQ_PostsFilter', 'PUPI_PTMQ Posts Filter');

qa_register_plugin_phrases('lang/pupi_ptmq_*.php', 'pupi_ptmq');
