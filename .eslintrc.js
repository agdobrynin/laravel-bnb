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
    parserOptions: {
        ecmaVersion: 'latest'
    },
    rules: {
        'quotes': ['error', 'single', { 'avoidEscape': true }]
    }
}
