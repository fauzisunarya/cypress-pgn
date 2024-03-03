//Create content dengan menambahkan subcontent	

import '../1-login/0_login_session.cy.js';

const getIframeDocument = () => {
  return cy
    .get('iframe')
    .its('0.contentDocument').should('exist');
};

const getIframeBody = () => {
  return getIframeDocument()
    .its('body').should('not.be.undefined');
};

const baseUrl = Cypress.env('baseUrl');

describe('Subcontent', () => {
  Cypress.on('uncaught:exception', (err, runnable) => {
    return false;
  });
  it('Create content subcontent', () => {
    cy.viewport(1390, 740);

    cy.login(Cypress.env('loginUsername'), Cypress.env('loginPassword'));

    cy.intercept('/iframe/cms').as('create cms');
    cy.visit(baseUrl + '/iframe/cms');

    cy.wait('@create cms');

    getIframeBody().then($body => {

      cy.intercept("POST", "https://dev-cms-pgnmobile.pgn.co.id/api/contents/list").as("getCmsContents");
      // cy.wait("@getCmsContents");
      cy.wait(8000);

      getIframeBody().find('[data-cy="add_new"]').should('have.text', 'Add New').click()

      //Create Content
      getIframeBody().find('[name="title"]').should('exist')
      getIframeBody().find('[name="title"]').type('coba saja')
      cy.wait(3000)

      getIframeBody().find('#mui-component-select-lang').should('exist').click();

      getIframeBody().find('.MuiList-root li').contains('Bahasa').click();
    
      getIframeBody().find('[name="category_name"]').type('Testing Create Subcontent')


      // Add Content
      getIframeBody().find('.MuiAutocomplete-popper').should('exist')
        .find('li').contains('Testing Create Subcontent').click();

      getIframeBody().find('[name="category_name"]').should('have.value', 'Testing Create Subcontent');

      getIframeBody().find('[name="contents.0.header.title"]').should('exist')
      getIframeBody().find('[name="contents.0.header.title"]').type('coba')

      getIframeBody().find('[name="contents.0.header.subtitle"]').should('exist')
      getIframeBody().find('[name="contents.0.header.subtitle"]').type('coba coba')

      getIframeBody().find('[name="contents.0.url"]').should('exist')
      getIframeBody().find('[name="contents.0.url"]').type('https://docs.cypress.io/guides/references/best-practices')

      //getIframeBody().find('[name="start_date"]').type('02/27/2024')
      //getIframeBody().find('[name="end"]').type('02/28/2024')

      getIframeBody().find('input[type="file"]').attachFile('rose.jpeg');

      getIframeBody().find('[name="contents.0.header.desc"]').should('exist')
      getIframeBody().find('[name="contents.0.header.desc"]').type('berhasil menambahkan konten')


      // Add subcontent
      getIframeBody().find('[name="contents.0.body.0.title"]').should('exist')
      getIframeBody().find('[name="contents.0.body.0.title"]').type('coba saja')

      getIframeBody().find('[name="contents.0.body.0.subtitle"]').should('exist')
      getIframeBody().find('[name="contents.0.body.0.subtitle"]').type('coba lagi')

      getIframeBody().find('[name="contents.0.body.0.url"]').should('exist')
      getIframeBody().find('[name="contents.0.body.0.url"]').type('https://docs.cypress.io/guides/references/best-practices')

      //getIframeBody().find('[name="start_date"]').type('02/27/2024')
      //getIframeBody().find('[name="end"]').type('02/28/2024')
      cy.wait(3000)

      getIframeBody().find('input[type="file"]').attachFile('loren.jpeg');

      getIframeBody().find('[name="contents.0.body.0.desc"]').should('exist')
      getIframeBody().find('[name="contents.0.body.0.desc"]').type('berhasil menambahkan konten')

      //getIframeBody().find('[data-cy="save_change"]').should('have.text', 'Save Change').click()


         });
    });
});