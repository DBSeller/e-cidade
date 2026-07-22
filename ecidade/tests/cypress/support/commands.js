Cypress.Commands.add('loginBehat', () => {
    const url = Cypress.env('url_ecidade') + '/LoginBehat.php';
    const host = Cypress.env('db_host');
    const port = Cypress.env('db_port');
    const base = Cypress.env('db_base');
    const user = Cypress.env('db_user');
    const dbLogin = Cypress.env('usuario_ecidade');

    cy.visit(`${url}?servidor=${host}&port=${port}&base=${base}&user=${user}&db_login=${dbLogin}`);
});

Cypress.Commands.add('login', () => {
    let url = Cypress.env('url_ecidade');
    cy.visit(url);
    cy.get('#usu_login').invoke('val', Cypress.env('usuario_ecidade'));
    cy.get('#usu_senha').invoke('val', Cypress.env('senha_ecidade'));

    cy.window().then((win) => {
        cy.spy(win, 'open').as('redirect');
    });
    cy.intercept('POST', '*').as('postLogin');
    cy.get('#btnlogar').invoke('removeAttr', 'target').click();
    cy.wait('@postLogin');
    cy.wait('@postLogin');

    cy.get('@redirect').should('be.called').then(() => {
        cy.wait(5000);
        cy.visit(url + '/extension/desktop');
    });
});

Cypress.Commands.add('abrirCardapio', () => {
    cy.get('.taskbar-menu-button', { timeout: 2000 }).should('be.visible').click();
});

Cypress.Commands.add('abrirMenu', idItem => {
    cy.get(idItem, { timeout: 15000 }).should('exist').click({force: true});
});

Cypress.Commands.add('getIframeContent', () => {
    const getIframeDocument = () => {
        return cy.get('iframe', { timeout: 15000 }).first().its('0.contentDocument').should('exist');
    }

    // Aguarda a página carregar
    cy.get('iframe', { timeout: 15000 }).first().next({ timeout: 10000 }).should('not.be.visible');

    return getIframeDocument().its('body').should('not.be.undefined').then(cy.wrap);
});

Cypress.Commands.add('getBody', () => {
    return cy.getIframeContent()
        .find('#corpo', { timeout: 15000 })
        .its('0.contentDocument').should('exist')
        .its('body').should('not.be.undefined')
        .then(cy.wrap);
});
