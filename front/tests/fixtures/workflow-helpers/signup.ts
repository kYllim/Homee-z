import { Page, expect } from '@playwright/test';
import { waitForPageLoad } from '../helpers';

/**
 * ÉTAPE 1: Fonctions d'inscription réutilisables
 */

export async function performSignup(
  page: Page,
  email: string,
  password: string,
  firstName: string = 'Jean',
  lastName: string = 'Dupont'
): Promise<void> {
  // Aller à la page d'inscription
  const signupLink = page.getByRole('link', { name: /inscription|sign up|s'inscrire|créer compte/i });
  if (await signupLink.isVisible()) {
    await signupLink.click();
  } else {
    await page.goto('/connexion?mode=inscription');
  }
  
  await waitForPageLoad(page);

  // Attendre que le formulaire soit visible
  const signupForm = page.getByRole('button', { name: /create account|créer|enregistrer/i });
  await expect(signupForm).toBeVisible({ timeout: 10000 });

  // Remplir le prénom
  const firstNameInput = page.getByLabel(/first name|prénom/i);
  if (await firstNameInput.isVisible()) {
    await firstNameInput.fill(firstName);
    await page.waitForTimeout(100);
  }

  // Remplir le nom
  const lastNameInput = page.getByLabel(/^name|^nom$/i);
  if (await lastNameInput.isVisible()) {
    await lastNameInput.fill(lastName);
    await page.waitForTimeout(100);
  }

  // Remplir l'email
  const emailInput = page.getByLabel(/email|adresse email/i).first();
  if (await emailInput.isVisible()) {
    await emailInput.fill(email);
    await page.waitForTimeout(100);
  }

  // Remplir le mot de passe
  const passwordInput = page.getByLabel(/^password|mot de passe$/i).first();
  if (await passwordInput.isVisible()) {
    await passwordInput.fill(password);
    await page.waitForTimeout(100);
  }

  // Confirmer le mot de passe
  const passwordConfirmInput = page.getByLabel(/confirmer|confirm password/i).first();
  if (await passwordConfirmInput.isVisible()) {
    await passwordConfirmInput.fill(password);
    await page.waitForTimeout(100);
  }

  // Cocher les conditions d'utilisation
  const agreeCheckbox = page.getByLabel(/agree to|conditions|privacy|terms/i);
  if (await agreeCheckbox.isVisible()) {
    await agreeCheckbox.check();
    await page.waitForTimeout(100);
  }

  // Soumettre avec une attente pour la navigation
  const signupButton = page.getByRole('button', { name: /inscription|sign up|créer|enregistrer|create account/i });
  if (await signupButton.isVisible()) {
    // Attendre que le bouton ne soit plus disabled
    await signupButton.isEnabled({ timeout: 5000 });
    await signupButton.click();
    
    // Attendre la redirection vers le dashboard
    await page.waitForNavigation({ waitUntil: 'networkidle', timeout: 15000 }).catch(() => {});
    await waitForPageLoad(page);
  }

  // ✅ Vérifier que l'inscription a réussi et le dashboard s'affiche
  const dashboardElement = page.getByRole('heading', { name: /to-do list|schedule|reminders/i }).first();
  await expect(dashboardElement).toBeVisible({ timeout: 15000 });
  
  console.log('✅ Inscription réussie - Dashboard visible');
}
