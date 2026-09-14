/**
 * Sample JavaScript file for Editor Tweaks for CodeMirror preview
 * 
 * @package Editor_Tweaks_For_CodeMirror
 */

(function() {
    'use strict';

    const CMForge = {
        version: '1.0.0',
        settings: {
            theme: 'default',
            fontSize: 14,
            lineNumbers: true
        },

        /**
         * Initialize Editor Tweaks for CodeMirror
         */
        init: function() {
            this.loadSettings();
            this.applyCustomizations();
            this.observeEditors();
        },

        /**
         * Load settings from WordPress
         */
        loadSettings: function() {
            if (typeof editorTweaksForCodeMirrorSettings !== 'undefined') {
                Object.assign(this.settings, editorTweaksForCodeMirrorSettings);
            }
        },

        /**
         * Apply customizations to editors
         */
        applyCustomizations: function() {
            const editors = document.querySelectorAll('.CodeMirror');
            
            editors.forEach(editor => {
                const cm = editor.CodeMirror || editor.cm;
                if (cm) {
                    this.customizeEditor(cm);
                }
            });
        },

        /**
         * Customize a single editor
         * 
         * @param {Object} cm CodeMirror instance
         */
        customizeEditor: function(cm) {
            if (this.settings.theme) {
                cm.setOption('theme', this.settings.theme);
            }
            
            if (this.settings.fontSize) {
                cm.getWrapperElement().style.fontSize = this.settings.fontSize + 'px';
            }
            
            cm.setOption('lineNumbers', this.settings.lineNumbers);
        },

        /**
         * Observe for new editor instances
         */
        observeEditors: function() {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1 && node.classList.contains('CodeMirror')) {
                            const cm = node.CodeMirror || node.cm;
                            if (cm) {
                                this.customizeEditor(cm);
                            }
                        }
                    });
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => CMForge.init());
    } else {
        CMForge.init();
    }

})();

