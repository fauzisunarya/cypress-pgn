import '../1-login/0_login_session.cy.js';

//Memunculkan Content List Negative

describe('template spec', () => {
  it('passes', () => {
    cy.viewport(1390, 740)
    cy.visit('https://dev-pgnmobile.pgn.co.id/login');
    cy.login(Cypress.env('loginUsername'), Cypress.env('loginPassword'));
    cy.intercept('/iframe/cms').as('create cms');
    cy.wait(3000);
    cy.get('#email').type('sample_partner ');
    cy.get('#password').type('Neuron#123');
    cy.wait(3000);
    cy.get('.css-j7qwjs > .MuiButtonBase-root').click();
    cy.get("body").should("contain", "User or password mismatch");
    })
  })
