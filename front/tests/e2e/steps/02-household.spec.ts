import { test, expect, Page } from '@playwright/test';
import { waitForPageLoad } from '../../fixtures/helpers';
import { performLogin } from '../../fixtures/workflow-helpers/login';
import { performHouseholdCreation } from '../../fixtures/workflow-helpers/household';

/**
 * STEP 2: HOUSEHOLD CREATION
 * Test la création d'un foyer
 */

test.describe('Step 2 - Household', () => {
  let page: Page;

  test.beforeEach(async ({ page: testPage }) => {
    page = testPage;
    await performLogin(testPage);
  });

  test('should create a household', async () => {
    console.log('📍 Starting household creation test');
    
    await performHouseholdCreation(page, 'Maison Dupont');
    
    console.log('✅ Household creation completed');
  });
});
