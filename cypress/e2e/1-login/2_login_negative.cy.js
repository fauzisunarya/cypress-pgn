describe('template spec', () => {
    it('passes', () => {

        // login salah username
        cy.wait(3000);
        cy.visit('https://dev-pgnmobile.pgn.co.id/login');
        cy.get('#email').type('sample_partner');
        cy.wait(2000);
        cy.get('#password').type('Neuron#123');
        cy.wait(2000);
        cy.get('.css-j7qwjs > .MuiButtonBase-root').click();
        cy.get("body").should("contain", "User or password mismatch");

        // //login salah password
        // cy.wait(3000);
        // cy.get ('#email').clear();
        // cy.get ('#email').type('hadifta');
        // cy.wait(2000);
        // cy.get ('#password').clear();
        // cy.get('#password').type('neuron234');
        // cy.wait(2000);
        // cy.get('.css-j7qwjs > .MuiButtonBase-root').click();
        // cy.get("body").should("contain", "User or password mismatch");

        // //login username dan password salah
        // cy.wait(3000);
        // cy.get("#email").clear();
        // cy.get('#email').type('samplee12');
        // cy.wait(2000);
        // cy.get('#password').clear();
        // cy.get('#password').type('works234');
        // cy.wait(2000);
        // cy.get('.css-j7qwjs > .MuiButtonBase-root').click();
        // cy.get("body").should("contain", "User or password mismatch");
    })
})

