import pluginVue from 'eslint-plugin-vue'
export default [
    ...pluginVue.configs['flat/recommended'],
    {
        rules: {
            'vue/valid-v-for': 0,
            'vue/require-v-for-key': 0,
            'vue/multi-word-component-names': 0,
            'object-curly-spacing': ['error', 'always'],
            'indent': [2, 4],
            'vue/html-indent': [2, 4],
            'quotes': [2, 'single', { avoidEscape: true }],
        }
    }
]
