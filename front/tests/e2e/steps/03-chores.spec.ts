import { test, expect, Page } from '@playwright/test';
import { performLogin } from '../../fixtures/workflow-helpers/login';
import { createChore } from '../../fixtures/workflow-helpers/chores';

/**
 * STEP 3: CHORES CREATION
 * Test la création de corvées
 */

test.describe('Step 3 - Chores', () => {
  let page: Page;

  test.beforeEach(async ({ page: testPage }) => {
    page = testPage;
    await performLogin(testPage);
  });

  test('should create a single chore', async () => {
    console.log('📍 Starting chore creation test');
    
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
    
    console.log('✅ Chore creation completed');
  });
});
