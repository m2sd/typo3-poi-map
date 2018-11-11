module.exports = {
  root: true,
  env: {
    browser: true,
    es6: true
  },
  extends: 'plugin:prettier/recommended',
  parserOptions: {
    sourceType: 'module',
    parser: 'babel-eslint'
  },
  plugins: [
    'prettier'
  ],
  rules: {
    'prettier/prettier': 'error',
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'warn',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'warn'
  }
};
