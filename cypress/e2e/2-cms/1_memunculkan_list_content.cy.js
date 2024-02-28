//Memunculkan Content List

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

describe('Create cms', () => {
  it('passes', () => {
    cy.viewport(1390, 740);

    cy.login(Cypress.env('loginUsername'), Cypress.env('loginPassword'));
    cy.intercept('/iframe/cms').as('create cms');
    cy.visit(baseUrl + '/iframe/cms');

    cy.wait('@create cms');

    getIframeBody().then($body => {

      cy.intercept("POST", "https://dev-cms-pgnmobile.pgn.co.id/api/contents/list").as("getCmsContents");
        });
    });
});
