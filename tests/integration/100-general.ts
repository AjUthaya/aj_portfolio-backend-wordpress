/// <reference types="Cypress" />

// DEFINE: Address options
const address = {
  protocol: "http",
  host: "localhost",
  port: "6969",
  alias: ""
};

// DEFINE: Base url
const devUrl = `${address.protocol}://${address.alias}${address.host}:${
  address.port
}`;

// GENERAL
describe("General", () => {
  // PROTOCOL
  describe("Protocol", () => {
    // HTTP
    describe("HTTP", () => {
      // NON-WWW
      it("NON-WWW", () => {
        cy.visit(devUrl);
      });

      /*
      // WWW
      it("WWW", () => {
        cy.visit("http://www.localhost:6969");
      });
      */
    });

    /*
    // HTTPS
    describe("HTTPS", () => {
      // NON-WWW
      it("NON-WWW", () => {
        cy.visit("https://localhost:6969");
      });

      // WWW
      it("WWW", () => {
        cy.visit("https://www.localhost:6969");
      });
    });
    */
  });
});
