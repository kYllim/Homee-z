import { test, expect, Page } from '@playwright/test';
import { waitForPageLoad } from '../../fixtures/helpers';
import { performLogin } from '../../fixtures/workflow-helpers/login';
import { createChore } from '../../fixtures/workflow-helpers/chores';
import { addTodoItem } from '../../fixtures/workflow-helpers/todos';
import { createEvent } from '../../fixtures/workflow-helpers/events';

/**
 * WORKFLOW COMPLET
 * 
 * Parcours utilisateur avec compte existant:
 * 1️⃣ Connexion (alice@example.com - déjà dans un foyer)
 * 2️⃣ Création de corvée
 * 3️⃣ Ajout d'élément à la todolist
 * 4️⃣ Création d'un événement
 * 
 * Réutilise les fonctions des steps individuelles
 */

test.describe('Complete Workflow - Full User Journey', () => {
  let page: Page;

  test.beforeEach(async ({ page: testPage }) => {
    page = testPage;
  });

  test('should complete full user journey', async () => {
    console.log('\n🚀 WORKFLOW COMPLET - DÉBUT\n');

    // ========================================
    // 1️⃣ CONNEXION
    // ========================================
    console.log('═'.repeat(60));
    console.log('1️⃣  ÉTAPE 1: CONNEXION');
    console.log('═'.repeat(60));
    
    await page.goto('/');
    await waitForPageLoad(page);
    console.log('✓ Utilisateur sur la page d\'accueil');
    
    await performLogin(page, 'alice@example.com', 'Password123!');
    console.log('✅ Connexion effectuée (alice@example.com)\n');

    // ========================================
    // 2️⃣ CORVÉE
    // ========================================
    console.log('═'.repeat(60));
    console.log('2️⃣  ÉTAPE 2: CRÉATION CORVÉE');
    console.log('═'.repeat(60));
    
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    const nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 8);
    const nextWeekStr = nextWeek.toISOString().split('T')[0];
    
    await createChore(
      page,
      'Laver la vaisselle',
      'Laver tous les plats après le dîner',
      tomorrowStr,
      nextWeekStr,
      'cleaning'
    );
    console.log('✅ Corvée créée\n');

    // ========================================
    // 3️⃣ TODOLIST
    // ========================================
    console.log('═'.repeat(60));
    console.log('3️⃣  ÉTAPE 3: AJOUT TODOLIST');
    console.log('═'.repeat(60));
    
    await addTodoItem(page, 'Faire les courses');
    console.log('✅ Tâche ajoutée\n');

    // ========================================
    // 4️⃣ ÉVÉNEMENTS
    // ========================================
    console.log('═'.repeat(60));
    console.log('4️⃣  ÉTAPE 4: CRÉATION ÉVÉNEMENT');
    console.log('═'.repeat(60));
    
    await createEvent(
      page,
      'Repas en famille',
      'Dîner à 19h',
      7,
      '19:00'
    );
    console.log('✅ Événement créé\n');

    // ========================================
    // 🎉 FIN
    // ========================================
    console.log('═'.repeat(60));
    console.log('🎉 WORKFLOW COMPLET TERMINÉ!');
    console.log('═'.repeat(60));
    console.log('✅ Utilisateur connecté');
    console.log('✅ Corvée créée');
    console.log('✅ Tâche ajoutée');
    console.log('✅ Événement créé');
    console.log('═'.repeat(60) + '\n');
  });
});
