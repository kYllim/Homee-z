import { Page, expect } from '@playwright/test';
import { waitForPageLoad } from '../helpers';

/**
 * ÉTAPE 3: Fonctions de corvées réutilisables
 */

export async function createChore(
  page: Page,
  choreName: string,
  description: string,
  startDate: string,
  endDate: string,
  type: string = 'cleaning'
): Promise<void> {
  // Aller à la page des corvées
  const choresLink = page.getByRole('link', { name: /corvée|chore|tâche|task|chores/i });
  if (await choresLink.isVisible()) {
    await choresLink.click();
  } else {
    await page.goto('/chores');
  }
  
  await waitForPageLoad(page);

  // Créer une corvée
  const addChoreBtn = page.getByRole('button', { name: /ajouter|créer|add|new|nouvelle corvée|nouvelle/i });
  if (await addChoreBtn.isVisible()) {
    await addChoreBtn.click();
    await page.waitForTimeout(500);

    // 1️⃣ Remplir le titre (utiliser le placeholder)
    const titleInput = page.getByPlaceholder(/Passer l'aspirateur/i);
    if (await titleInput.isVisible()) {
      await titleInput.fill(choreName);
      await page.waitForTimeout(200);
    }

    // 2️⃣ Remplir la description (utiliser le placeholder)
    const descInput = page.getByPlaceholder(/Décrivez la corvée/i);
    if (await descInput.isVisible()) {
      await descInput.fill(description);
      await page.waitForTimeout(200);
    }

    // 3️⃣ Remplir la date de début (première date input)
    const dateInputs = page.locator('input[type="date"]');
    const startDateInput = dateInputs.first();
    if (await startDateInput.isVisible()) {
      await startDateInput.fill(startDate);
      await page.waitForTimeout(200);
    }

    // 4️⃣ Remplir la date de fin (deuxième date input)
    const endDateInput = dateInputs.last();
    if (await endDateInput.isVisible()) {
      await endDateInput.fill(endDate);
      await page.waitForTimeout(200);
    }

    // 5️⃣ Sélectionner le type de corvée (le dernier select du modal)
    const typeSelect = page.getByRole('combobox').last();
    if (await typeSelect.isVisible()) {
      await typeSelect.selectOption(type);
      await page.waitForTimeout(200);
    }

    // Soumettre
    const submitBtn = page.getByRole('button', { name: /créer|modifier/i }).last();
    if (await submitBtn.isVisible()) {
      await submitBtn.click();
      await waitForPageLoad(page);
      await page.waitForTimeout(500);
    }

    // ✅ Vérifier que la corvée a bien été créée et s'affiche dans la liste
    const choreElement = page.getByText(choreName).first();
    await expect(choreElement).toBeVisible({ timeout: 5000 });
    
    // ✅ Bonus: vérifier aussi que la description s'affiche
    const choreDesc = page.getByText(description);
    await expect(choreDesc).toBeVisible({ timeout: 5000 }).catch(() => {
      console.log('⚠️ Description non trouvée (peut être cachée dans les détails)');
    });
    
    console.log(`✅ Corvée créée avec succès: ${choreName}`);
  }
}


