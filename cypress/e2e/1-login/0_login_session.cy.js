Cypress.Commands.add('login', (username, password) => {
  cy.session(username, () => {

      cy.visit(Cypress.env('baseUrl'));
      cy.wait(3000)

      cy.get('#email').type(username);
      cy.wait(3000)
      cy.get('#password').type(password);

      cy.intercept('/login').as('submitLogin');
      cy.get('.css-j7qwjs > .MuiButtonBase-root').click();

      //   cy.wait('@submitLogin', {timeout: 10000}).then((interception) => {
      cy.wait(10000);
      // cy.get('#navbarNeuronContent').should('be.visible');
      //cy.screenshot();
      //   });

  }, {
      cacheAcrossSpecs: true
  });
});

