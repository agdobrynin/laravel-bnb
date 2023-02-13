/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = {
    root: true,
    env: {
        browser: true, // browser global variables
        // adds all ECMAScript 2021 globals and automatically sets the ecmaVersion parser option to 12.
        es2021: true,
    },
    extends: [
        'plugin:vue/vue3-essential',
        'eslint:recommended',
        '@vue/eslint-config-typescript'
    ],
    plugins: [
        'simple-import-sort'
    ],
    parserOptions: {
        ecmaVersion: 'latest'
    },
    rules: {
        'quotes': ['error', 'single', { 'avoidEscape': true }],
        'simple-import-sort/imports': 'error',
        'simple-import-sort/exports': 'error',
        'object-curly-spacing': ['error', 'always'],
    }
}
