const { defineConfig } = require("cypress"); 
const allureWriter = require('@shelex/cypress-allure-plugin/writer');

module.exports = defineConfig({ 
  e2e: { 
      setupNodeEvents(on, config) { 
          allureWriter(on, config); 
          return config; 
      },
  },
  "chromeWebSecurity": false 
}); 

// export default {
//   e2e: {
//     setupNodeEvents(on, config) {
//       // implement node event listeners here
//     },
//   },
//   "chromeWebSecurity": false
// };


// //Dev PGN
// module.exports = ({
//   video: true,
//   videoCompresssion: 32,
//   videoUploadOnPasses: true,
//   e2e: {
//     specPattern: [
//       "cypress/e2e/**/*.cy.js",
//     ],
//     // loginUsername: 'admin',
//     // loginPassword: 'neuron#123',
//     // experimentalSessionAndOrigin: true,
//     testIsolation: false,
//     cacheAcrossSpecs: true,
//     watchForFileChanges: false,
//     setupNodeEvents(on, config) {
//       // implement node event listeners here
//     },
//   },
// });