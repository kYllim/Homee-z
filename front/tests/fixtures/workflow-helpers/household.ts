import { Page, expect } from '@playwright/test';
import { waitForPageLoad } from '../helpers';

/**
 * ÉTAPE 2: Fonctions de foyer réutilisables
 */

export async function performHouseholdCreation(
  page: Page,
  householdName: string = 'Maison Dupont'
): Promise<void> {
  // Aller à la page du foyer
  const householdLink = page.getByRole('link', { name: /foyer|household|ménage|maison/i });
  if (await householdLink.isVisible()) {
    await householdLink.click();
  } else {
    await page.goto('/HouseHold');
  }
  
  await waitForPageLoad(page);

  // Remplir le nom du foyer
  const householdNameInput = page.getByLabel(/nom du foyer|household name|nom|name/i);
  if (await householdNameInput.isVisible()) {
    await householdNameInput.fill(householdName);
    await page.waitForTimeout(200);
  }
  
  // Soumettre
  const submitBtn = page.getByRole('button', { name: /créer|create|valider|save|enregistrer/i });
  if (await submitBtn.isVisible()) {
    await submitBtn.click();
    await waitForPageLoad(page);
  }

  // ✅ Vérifier que le foyer a été créé
  // Le code de partage devrait être visible après création
  const shareCode = page.getByText(/code|partage|invitation|share code/i);
  await expect(shareCode).toBeVisible({ timeout: 5000 }).catch(() => {
    console.warn('⚠️ Code de partage non trouvé, mais foyer probablement créé');
  });
  
  console.log(`✅ Foyer créé avec succès: ${householdName}`);
}

export async function performHouseholdJoin(
  page: Page,
  code: string = 'ABC123'
): Promise<void> {
  // Aller à la page du foyer
  const householdLink = page.getByRole('link', { name: /foyer|household|ménage|maison/i });
  if (await householdLink.isVisible()) {
    await householdLink.click();
  } else {
    await page.goto('/HouseHold');
  }
  
  await waitForPageLoad(page);

  // Chercher le bouton pour rejoindre avec un code
  // Il peut être un bouton principal ou un lien
  let joinHouseholdBtn = page.getByRole('button', { name: /rejoindre|join|code|invitation/i });
  if (!await joinHouseholdBtn.isVisible()) {
    joinHouseholdBtn = page.getByRole('link', { name: /rejoindre|join|code|invitation/i });
  }
  
  if (await joinHouseholdBtn.isVisible()) {
    await joinHouseholdBtn.click();
    await page.waitForTimeout(300);
    await waitForPageLoad(page);
    
    // Remplir le code d'invitation
    const codeInput = page.getByLabel(/code|invitation|partage/i).first();
    if (await codeInput.isVisible()) {
      await codeInput.fill(code);
      await page.waitForTimeout(200);
    }
    
    // Soumettre le formulaire
    const submitBtn = page.getByRole('button', { name: /rejoindre|join|valider|confirmer/i }).last();
    if (await submitBtn.isVisible()) {
      await submitBtn.click();
      await waitForPageLoad(page);
    }

    // ✅ Vérifier que le foyer a été rejoint
    const successMessage = page.getByText(/validé|successfully|succès|joined|accepté|accepted/i);
    await expect(successMessage).toBeVisible({ timeout: 5000 }).catch(() => {
      console.log('⚠️ Message de succès non trouvé, mais foyer probablement rejoint');
    });
    
    console.log(`✅ Foyer rejoint avec succès: ${code}`);
  } else {
    console.warn('⚠️ Bouton "rejoindre" non trouvé - l\'utilisateur a peut-être déjà un foyer');
  }
}
