import { Page, expect } from '@playwright/test';
import { waitForPageLoad } from '../helpers';

/**
 * ÉTAPE 4: Fonctions de todolist réutilisables
 */

export async function addTodoItem(
  page: Page,
  todoText: string
): Promise<void> {
  // Vérifier d'abord si on est sur la page dashboard (où est la todolist)
  const dashboard = page.getByText(/to-do list/i);
  if (!await dashboard.isVisible()) {
    // Sinon aller sur le dashboard
    await page.goto('/dashboard');
    await waitForPageLoad(page);
  }

  // Chercher le bouton + pour ajouter une tâche
  const addBtn = page.getByRole('button', { name: /^\+$/ }).first();
  if (await addBtn.isVisible()) {
    await addBtn.click();
    await page.waitForTimeout(200);
  }

  // Remplir le champ de texte "Nouvelle tâche"
  const todoInput = page.getByPlaceholder(/nouvelle tâche/i);
  if (await todoInput.isVisible()) {
    await todoInput.fill(todoText);
    await page.waitForTimeout(100);
    
    // Soumettre le formulaire
    const submitBtn = page.getByRole('button', { name: /ajouter/i });
    if (await submitBtn.isVisible()) {
      await submitBtn.click();
      await page.waitForTimeout(300);
    }
  }

  // ✅ Vérifier que la tâche a été ajoutée
  const taskElement = page.getByText(todoText);
  await expect(taskElement).toBeVisible({ timeout: 5000 });
  
  console.log(`✅ Tâche ajoutée avec succès: ${todoText}`);
}

