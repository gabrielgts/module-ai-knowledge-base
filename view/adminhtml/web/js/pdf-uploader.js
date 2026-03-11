define([
    'Magento_Ui/js/form/element/file-uploader',
    'uiRegistry'
], function (FileUploader, registry) {
    'use strict';

    return FileUploader.extend({

        defaults: {
            formName: 'ai_knowledge_base_form'
        },

        /**
         * Handle a successful PDF upload.
         *
         * @param {String|Event} event Empty string (Uppy) or jQuery event
         * @param {Object} data        { files: [...], result: <serverResponse> }
         */
        onFileUploaded: function (event, data) {
            this._super(event, data);

            var response = data && data.result ? data.result : {};

            if (!response.success) {
                console.error('PDF upload failed:', response.error || 'Unknown error');
                return;
            }

            var formName = this.formName;
            this._setFieldIfEmpty(formName + '.general.title', this._removeInvisibleChars(response.title));
            this._setField(formName + '.general.content', this._removeInvisibleChars(response.content));
            this._setFieldIfEmpty(formName + '.general.tags', this._removeInvisibleChars(response.tags));
        },

        /**
         * Strip invisible/control characters and normalize whitespace.
         *
         * @param {String} text
         * @return {String}
         */
        _removeInvisibleChars: function (text) {
            if (!text) {
                return text;
            }
            return text
                .replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F\u007F-\u009F]/g, '')
                .replace(/[\u00AD\u200B-\u200F\u202A-\u202E\u2060-\u206F\uFEFF]/g, '')
                .replace(/\r\n/g, '\n')
                .replace(/\r/g, '\n')
                .replace(/\t/g, ' ')
                .replace(/[ \t]+/g, ' ') // collapse multiple spaces
                .replace(/\n{3,}/g, '\n\n') // collapse excess blank lines
                .trim();
        },

        /**
         * Resolve a UI component
         *
         * @param {String} componentName Full uiRegistry component name.
         * @return {Object|undefined}
         */
        _resolveField: function (componentName) {
            var field = registry.get(componentName);
            if (field) {
                return field;
            }
            var index = componentName.split('.').pop();
            var formName = this.formName;
            var matches = registry.filter(function (c) {
                return c.ns === formName && c.index === index;
            });
            return matches.length ? matches[0] : undefined;
        },

        /**
         * Set a field's value, overwriting any existing content.
         *
         * @param {String} componentName Full uiRegistry component name.
         * @param {String} value
         */
        _setField: function (componentName, value) {
            if (!value) {
                return;
            }
            var field = this._resolveField(componentName);
            if (field && typeof field.value === 'function') {
                field.value(value);
            }
        },

        /**
         * Set a field's value only when it is currently blank.
         *
         * @param {String} componentName
         * @param {String} value
         */
        _setFieldIfEmpty: function (componentName, value) {
            if (!value) {
                return;
            }
            var field = this._resolveField(componentName);
            if (field && typeof field.value === 'function' && !field.value()) {
                field.value(value);
            }
        },

        /**
         * Handle upload errors.
         *
         * @param {Object} error
         */
        onFileUploadError: function (error) {
            this._super();
            console.error('PDF upload error:', error);
        }
    });
});
