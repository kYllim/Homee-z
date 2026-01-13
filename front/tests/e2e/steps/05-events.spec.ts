import { test, expect, Page } from '@playwright/test';
import { performLogin } from '../../fixtures/workflow-helpers/login';
import { createEvent } from '../../fixtures/workflow-helpers/events';

/**
 * STEP 5: EVENTS CREATION
 * Test la création d'événements
 */

test.describe('Step 5 - Events', () => {
  let page: Page;

  test.beforeEach(async ({ page: testPage }) => {
    page = testPage;
    await performLogin(testPage);
  });

  test('should create a single event', async () => {
    console.log('📍 Starting single event test');
    
    await createEvent(
      page,
      'Repas en famille',
      'Dîner à 19h',
      7,
      '19:00'
    );
    
    console.log('✅ Event creation completed');
  });
});
