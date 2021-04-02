const fs = require('fs')
const path = require('path')

if (!fs.existsSync(path.resolve(__dirname, '.env'))) {
  console.log('onPostInstall', 'Copying .env.example to .env')

  fs.copyFileSync(path.resolve(__dirname, '.env.example'), path.resolve(__dirname, '.env'))
}
