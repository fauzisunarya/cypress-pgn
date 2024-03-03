
// Awal masuk halaman dia itu get element iframenya, dan assert expektasinya existst konten didalam iframenya
const getIframeDocument = () => {
  return cy
    .get('iframe')
    .its('0.contentDocument').should('exist')
}

// Udah masuk ke iframe, selanjutnya lanjutin dari getIframeDocument() terus get element body didalem iframenya
const getIframeBody = () => {
  return getIframeDocument()
    .its('body').should('not.be.undefined')
    .then(cy.wrap)
}

describe('Login positif', () => {
  it('Login valid data', () => {

    Cypress.on('uncaught:exception', (err, runnable) => {
      return false
    });


    cy.viewport(1390, 740)
    cy.visit('https://dev-pgnmobile.pgn.co.id/login');
    cy.get('#email').should('exist').type('sample_cs');
    cy.get('#password').should('exist').type('Neuron#123');
    cy.get('.css-j7qwjs > .MuiButtonBase-root').should('exist').click();
    cy.get('[href="/iframe/cms"] > .MuiButtonBase-root').should('exist').click();

    cy.intercept("POST", "https://dev-cms-pgnmobile.pgn.co.id/api/contents/list").as("getCmsContents");
    // cy.wait("@getCmsContents");
    cy.wait(18000)

    getIframeBody().find('[data-cy="add_new"]').should('have.text', 'Add New').click()

    getIframeBody().find('[name="title"]').should('exist')
    getIframeBody().find('[name="title"]').type('coba saja')

    getIframeBody().find('#mui-component-select-lang').should('exist').click();

    getIframeBody().find('.MuiList-root li').contains('Bahasa').click();

    getIframeBody().find('[name="category_name"]').type('t')

    getIframeBody().find('.MuiAutocomplete-popper').should('exist')
      .find('li').contains('T&C').click();

    getIframeBody().find('[name="category_name"]').should('have.value', 'T&C');

    getIframeBody().find('[name="contents.0.body.0.title"]').should('exist').type('coba saja')
    cy.wait(3000)
    getIframeBody().find('[name="contents.0.body.0.subtitle"]').should('exist').type('coba lagi')
    cy.wait(3000)
    getIframeBody().find('[name="contents.0.body.0.url"]').should('exist').type('coba')
    cy.wait(3000)
    // getIframeBody().find('input[type="file"]').attachFile('blogspot.png');
    
    


    /**
     * Note karena ini interaksi cross origin dan browser securitty tidak mengizinkan, maka di cypress.config.js 
     * set "chromeWebSecurity": false
     */
  })
})


