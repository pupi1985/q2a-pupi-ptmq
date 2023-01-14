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

class PUPI_PTMQ_Admin
{
    const SAVE_BUTTON = 'pupi_ptmq_save';

    public function option_default($option)
    {
        switch ($option) {
            case 'pupi_ptmq_ignore_moderation_with_points':
            case 'pupi_ptmq_check_email':
            case 'pupi_ptmq_check_phone':
            case 'pupi_ptmq_check_link':
                return true;
            case 'pupi_ptmq_min_points':
                return 0;
            default:
        }

        return null;
    }

    public function admin_form(&$qa_content): array
    {
        $result = null;

        if (qa_clicked(self::SAVE_BUTTON)) {
            $this->saveAllSettings();

            $result = qa_lang_html('admin/options_saved');
        }

        qa_set_display_rules($qa_content, [
            'pupi_ptmq_min_points' => 'pupi_ptmq_ignore_moderation_with_points',
        ]);

        return [
            'ok' => $result,
            'fields' => $this->getFields(),
            'buttons' => $this->getButtons(),
        ];
    }

    private function getButtons(): array
    {
        return [
            self::SAVE_BUTTON => [
                'tags' => sprintf('name="%s"', self::SAVE_BUTTON),
                'label' => qa_lang_html('admin/save_options_button'),
            ],
        ];
    }

    // Fields

    private function getFields(): array
    {
        return [
            $this->getFieldIgnoreModerationWithPoints(),
            $this->getFieldMinPoints(),
            $this->getFieldCheckEmail(),
            $this->getFieldCheckPhone(),
            $this->getFieldCheckLink(),
        ];
    }

    /**
     * @param string $setting
     * @param string $langId
     *
     * @return array
     */
    private function getGenericField(string $setting, string $langId): array
    {
        return [
            'label' => qa_lang_html('pupi_ptmq/' . $langId),
            'tags' => sprintf('name="%s"', qa_html($setting)),
            'value' => qa_opt($setting),
        ];
    }

    /**
     * @param string $setting
     * @param string $langId
     *
     * @return array
     */
    private function getGenericBooleanField(string $setting, string $langId): array
    {
        $field = $this->getGenericField($setting, $langId);
        $field['type'] = 'checkbox';

        return $field;
    }

    /**
     * @param string $setting
     * @param string $langId
     *
     * @return array
     */
    private function getGenericIntegerField(string $setting, string $langId): array
    {
        $field = $this->getGenericField($setting, $langId);
        $field['type'] = 'text';
        $field['value'] = (int)qa_opt($setting);

        return $field;
    }

    /**
     * @return array
     */
    private function getFieldIgnoreModerationWithPoints(): array
    {
        $field = $this->getGenericBooleanField('pupi_ptmq_ignore_moderation_with_points', 'admin_ignore_moderation_with_points_label');
        $field['tags'] .= sprintf(' id="%s"', 'pupi_ptmq_ignore_moderation_with_points');

        return $field;
    }

    /**
     * @return array
     */
    private function getFieldMinPoints(): array
    {
        $field = $this->getGenericIntegerField('pupi_ptmq_min_points', 'admin_min_points_label');
        $field['id'] = 'pupi_ptmq_min_points';
        $field['tags'] .= ' size="8"';

        return $field;
    }

    /**
     * @return array
     */
    private function getFieldCheckEmail(): array
    {
        return $this->getGenericBooleanField('pupi_ptmq_check_email', 'admin_check_email_label');
    }

    /**
     * @return array
     */
    private function getFieldCheckPhone(): array
    {
        return $this->getGenericBooleanField('pupi_ptmq_check_phone', 'admin_check_phone_label');
    }

    /**
     * @return array
     */
    private function getFieldCheckLink(): array
    {
        return $this->getGenericBooleanField('pupi_ptmq_check_link', 'admin_check_link_label');
    }

    /**
     * @param string $setting
     *
     * @return void
     */
    private function saveBooleanSetting(string $setting)
    {
        $value = (bool)qa_post_text($setting);
        qa_opt($setting, $value);
    }

    /**
     * @param string $setting
     *
     * @return void
     */
    private function saveIntegerSetting(string $setting)
    {
        $value = (int)qa_post_text($setting);
        qa_opt($setting, $value);
    }

    private function saveAllSettings()
    {
        $this->saveBooleanSetting('pupi_ptmq_ignore_moderation_with_points');
        $this->saveIntegerSetting('pupi_ptmq_min_points');
        $this->saveBooleanSetting('pupi_ptmq_check_email');
        $this->saveBooleanSetting('pupi_ptmq_check_phone');
        $this->saveBooleanSetting('pupi_ptmq_check_link');
    }
}
