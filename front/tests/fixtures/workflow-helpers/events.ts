import { Page, expect } from '@playwright/test';
import { waitForPageLoad } from '../helpers';

/**
 * ÉTAPE 5: Fonctions d'événements réutilisables
 */

export async function createEvent(
  page: Page,
  title: string,
  description: string,
  daysFromNow: number = 7,
  startTime: string = '19:00'
): Promise<void> {
  // Aller à la page des événements (calendrier)
  await page.goto('/events');
  await waitForPageLoad(page);

  // Cliquer sur une case du calendrier pour ouvrir le modal
  // Chercher une cellule du calendrier
  const dayCells = page.locator('[role="button"].fc-daygrid-day, [role="gridcell"], .fc-daygrid-day');
  
  // Trouver une cellule accessible et cliquer dessus
  if (await dayCells.first().isVisible()) {
    await dayCells.first().click();
    await page.waitForTimeout(500);
  }

  // Attendre que le modal apparaisse
  const modal = page.locator('.fixed.inset-0, [role="dialog"]');
  await modal.first().waitFor({ state: 'visible', timeout: 5000 }).catch(() => {
    console.warn('⚠️ Modal non trouvé après clic sur la date');
  });

  // 1️⃣ Remplir le titre (obligatoire)
  const titleInput = page.getByPlaceholder(/Nom de l'événement/i);
  if (await titleInput.isVisible()) {
    await titleInput.fill(title);
    await page.waitForTimeout(200);
  }

  // 2️⃣ Remplir la description
  const descInput = page.getByPlaceholder(/Détails de l'événement/i);
  if (await descInput.isVisible()) {
    await descInput.fill(description);
    await page.waitForTimeout(200);
  }

  // 3️⃣ Remplir la date et heure de début (datetime-local)
  const dateInputs = page.locator('input[type="datetime-local"]');
  const startAtInput = dateInputs.first();
  if (await startAtInput.isVisible()) {
    // Calculer la date de début
    const futureDate = new Date();
    futureDate.setDate(futureDate.getDate() + daysFromNow);
    futureDate.setHours(parseInt(startTime.split(':')[0] || '19'), parseInt(startTime.split(':')[1] || '0'), 0, 0);
    
    // Format: YYYY-MM-DDTHH:mm
    const dateStr = futureDate.toISOString().slice(0, 16);
    await startAtInput.fill(dateStr);
    await page.waitForTimeout(200);
  }

  // 4️⃣ Remplir la date et heure de fin (optionnel)
  const endAtInput = dateInputs.last();
  if (await endAtInput.isVisible() && endAtInput !== startAtInput) {
    // Une heure après le début
    const endDate = new Date();
    endDate.setDate(endDate.getDate() + daysFromNow);
    endDate.setHours(parseInt(startTime.split(':')[0] || '19') + 1, parseInt(startTime.split(':')[1] || '0'), 0, 0);
    
    const endDateStr = endDate.toISOString().slice(0, 16);
    await endAtInput.fill(endDateStr);
    await page.waitForTimeout(200);
  }

  // 5️⃣ Remplir le type (optionnel)
  const typeInput = page.getByPlaceholder(/Ex: réunion, anniversaire/i);
  if (await typeInput.isVisible()) {
    await typeInput.fill('Réunion');
    await page.waitForTimeout(200);
  }

  // Soumettre
  const submitBtn = page.getByRole('button', { name: /créer|valider|save|ajouter|enregistrer/i }).last();
  if (await submitBtn.isVisible()) {
    await submitBtn.click();
    await waitForPageLoad(page);
    await page.waitForTimeout(500);
  }

  // ✅ Vérifier que l'événement a bien été créé
  const eventElement = page.getByText(title).first();
  await expect(eventElement).toBeVisible({ timeout: 5000 });
  
  console.log(`✅ Événement créé avec succès: ${title}`);
}


