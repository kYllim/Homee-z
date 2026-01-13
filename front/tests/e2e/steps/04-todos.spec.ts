import { test, expect, Page } from '@playwright/test';
import { performLogin } from '../../fixtures/workflow-helpers/login';
import { addTodoItem } from '../../fixtures/workflow-helpers/todos';

/**
 * STEP 4: TODOS/CHECKLIST
 * Test l'ajout d'éléments à la todolist
 */

test.describe('Step 4 - Todos', () => {
  let page: Page;

  test.beforeEach(async ({ page: testPage }) => {
    page = testPage;
    await performLogin(testPage);
  });

  test('should add a single todo', async () => {
    console.log('📍 Starting single todo test');
    
    await addTodoItem(page, 'Faire les courses');
    
    console.log('✅ Todo item added');
  });
});
