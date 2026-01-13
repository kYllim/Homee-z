import { Page, expect } from '@playwright/test';
import { waitForPageLoad, configureApiRequests } from '../helpers';

/**
 * LOGIN: Connexion avec les comptes de test
 * Utilise les utilisateurs créés dans AppFixtures (backend)
 * 
 * Comptes disponibles:
 * - alice@example.com (dans "Maison de la famille Dupont")
 * - bob@example.com (dans "Maison de la famille Dupont")
 * - charlie@example.com (dans "Maison de la famille Dupont")
 */

export async function performLogin(
  page: Page,
  email: string = 'alice@example.com',
  password: string = 'Password123!'
): Promise<void> {
  // Aller à la page de connexion
  await page.goto('/connexion');
  await waitForPageLoad(page);

  // Remplir le formulaire de connexion
  const emailInput = page.getByPlaceholder(/Enter your email/i);
  if (await emailInput.isVisible()) {
    await emailInput.fill(email);
    await page.waitForTimeout(100);
  }

  const passwordInput = page.getByPlaceholder(/Enter your password/i);
  if (await passwordInput.isVisible()) {
    await passwordInput.fill(password);
    await page.waitForTimeout(100);
  }

  // Soumettre le formulaire
  const loginButton = page.getByRole('button', { name: /connexion|sign in|login|se connecter/i });
  if (await loginButton.isVisible()) {
    await loginButton.click();
    
    // Attendre la navigation
    await page.waitForNavigation({ waitUntil: 'networkidle', timeout: 15000 }).catch(() => {});
    await waitForPageLoad(page);
  }

  // ✅ Vérifier que la connexion a réussi
  // Attendre que le dashboard soit visible
  const dashboardElement = page.getByRole('heading', { name: /to-do list|schedule|reminders/i }).first();
  await expect(dashboardElement).toBeVisible({ timeout: 15000 });
  
  // Vérifier que le token existe dans les cookies
  const cookies = await page.context().cookies();
  const tokenCookie = cookies.find(c => c.name === 'token');
  expect(tokenCookie?.value).toBeTruthy();
  
  const token = tokenCookie?.value;
  console.log(`✅ Connexion réussie: ${email}`);
  console.log(`📍 Token reçu: ${token?.substring(0, 20)}...`);
  
  // ⚠️ IMPORTANT: Configurer les requêtes API APRÈS la connexion
  // pour que le token soit correctement récupéré et utilisé
  await configureApiRequests(page);
  console.log(`🔐 Intercepteurs API configurés avec le nouveau token`);
}
