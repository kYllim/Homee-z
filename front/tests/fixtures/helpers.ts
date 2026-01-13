import { Page, expect } from '@playwright/test';

/**
 * Fonctions helper pour les tests E2E
 */

/**
 * Attendre que la page se charge complètement
 */
export async function waitForPageLoad(page: Page) {
  await page.waitForLoadState('networkidle');
}

/**
 * Ajouter un token d'authentification
 */
export async function setAuthToken(page: Page, token: string) {
  await page.context().addCookies([
    {
      name: 'token',
      value: token,
      url: 'http://localhost:5173',
    }
  ]);
}

/**
 * Supprimer le token d'authentification
 */
export async function clearAuthToken(page: Page) {
  await page.context().clearCookies({ name: 'token' });
}

/**
 * Vérifier que la page est protégée (redirection vers connexion)
 */
export async function expectProtectedRoute(page: Page, route: string) {
  await page.goto(route);
  await expect(page).toHaveURL(/connexion/);
}

/**
 * Configurer les requêtes API pour inclure les cookies
 * (workaround pour les tests E2E avec JWT)
 */
export async function configureApiRequests(page: Page) {
  // Intercepter toutes les requêtes fetch pour ajouter les cookies en header
  await page.route('http://localhost:8000/api/**', async (route) => {
    const request = route.request();

    // Récupérer les cookies
    const cookies = await page.context().cookies();
    const tokenCookie = cookies.find(c => c.name === 'token');

    // Si on a un token, l'ajouter en header Authorization
    const headers = { ...request.headers() };
    if (tokenCookie?.value) {
      headers['Authorization'] = `Bearer ${tokenCookie.value}`;
      console.log(`🔐 Ajout du token JWT au header Authorization`);
    }

    route.continue({ headers });
  });
}