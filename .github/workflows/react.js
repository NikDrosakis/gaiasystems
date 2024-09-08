name: React CI

on:
  push:
    branches:
      - main
  pull_request:
    branches:
      - main

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v3

    - name: Set up Node.js
      uses: actions/setup-node@v3
      with:
        node-version: '20'

    - name: Install dependencies
      run: npm install

    - name: Build
      run: npm run build

    - name: Deploy
      run: |
        if [ "${{ github.event_name }}" == "push" ]; then
          echo "Deploying to production..."
          # Add your deployment commands here
        fi
