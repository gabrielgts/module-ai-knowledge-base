define([
    'Magento_Ui/js/form/element/file-uploader',
    'uiRegistry'
], function (FileUploader, registry) {
    'use strict';

    return FileUploader.extend({

        defaults: {
            // Must match the ui_component name declared in the form XML.
            formName: 'ai_knowledge_base_form'
        },

        /**
         * Handle a successful PDF upload.
         *
         * The server returns extracted text and PDF metadata.
         * Auto-populate the title, content, and tags fields so the admin
         * does not have to copy-paste the content manually.
         *
         * @param {Object} response
         */
        onFileUploaded: function (response) {
            this._super();

            if (!response.data || !response.data.success) {
                console.error('PDF upload failed:', response.data ? response.data.error : 'Unknown error');
                return;
            }

            var data     = response.data;
            var formName = this.formName;

            this._setFieldIfEmpty(formName + '.general.title', data.title);
            this._setField(formName + '.general.content', data.content);
            this._setFieldIfEmpty(formName + '.general.tags', data.tags);
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
            var field = registry.get(componentName);
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
            var field = registry.get(componentName);
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
