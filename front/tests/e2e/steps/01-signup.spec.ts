import { test, expect, Page } from '@playwright/test';
import { clearAuthToken, waitForPageLoad } from '../../fixtures/helpers';
import { performSignup } from '../../fixtures/workflow-helpers/signup';

/**
 * STEP 1: USER SIGNUP
 * Test uniquement l'inscription d'un utilisateur
 */

test.describe('Step 1 - User Signup', () => {
  let page: Page;
  let userEmail: string;
  let userPassword: string;

  test.beforeEach(async ({ page: testPage }) => {
    page = testPage;
    const timestamp = Date.now();
    userEmail = `user_${timestamp}@test.com`;
    userPassword = 'Password123!';
    
    await clearAuthToken(page);
    await page.goto('/');
    await waitForPageLoad(page);
  });

  test('should complete user signup', async () => {
    console.log('📍 Starting signup test');
    
    await performSignup(page, userEmail, userPassword, 'Jean', 'Dupont');
    
    console.log(`✅ Signup completed for ${userEmail}`);
  });
});
