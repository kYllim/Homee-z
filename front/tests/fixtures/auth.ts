import { test as base, expect } from '@playwright/test';

/**
 * Fixture personnalisée pour les tests d'authentification
 */
export const test = base.extend({
  authenticatedPage: async ({ page }, use) => {
    // Ajouter un token au contexte
    await page.context().addCookies([
      {
        name: 'token',
        value: 'test_auth_token_123',
        url: 'http://localhost:5173',
      }
    ]);

    await page.goto('/');
    await use(page);
  },

  unauthenticatedPage: async ({ page }, use) => {
    // Assurer qu'il n'y a pas de token
    await page.context().clearCookies();
    await page.goto('/');
    await use(page);
  },
});

export { expect };
