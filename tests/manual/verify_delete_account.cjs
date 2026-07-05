const { chromium } = require('playwright');
const path = require('path');
const os = require('os');

const BASE       = 'http://localhost:8000';
const TEST_EMAIL = 'test.del.1781781679@example.com';
const TEST_PASS  = 'TestPass123!';

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1280, height: 900 });

  // 1. Connexion
  await page.goto(BASE + '/auth/connexion', { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', TEST_EMAIL);
  await page.fill('input[name="password"]', TEST_PASS);
  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
  console.log('1. Après connexion:', page.url());

  // 2. Page paramètres
  await page.goto(BASE + '/recruteur/parametres', { waitUntil: 'networkidle' });
  console.log('2. Page paramètres:', page.url());
  await page.screenshot({ path: path.join(os.tmpdir(), 'del-params.png') });

  // 3. Ouvrir la modal
  await page.click('button.rec-btn--danger');
  await page.waitForTimeout(400);
  const modalVisible = await page.evaluate(() =>
    document.getElementById('modal-delete-compte')?.style.display !== 'none'
  );
  console.log('3. Modal visible:', modalVisible);
  await page.screenshot({ path: path.join(os.tmpdir(), 'del-modal.png') });

  // 4. Bouton désactivé (champ vide)
  const btnDisabled = await page.$eval('#btn-delete-confirm', b => b.disabled);
  console.log('4. Bouton désactivé (champ vide):', btnDisabled);

  // 5. Mauvaise casse → encore désactivé
  await page.fill('#confirm-input', 'supprimer');
  await page.waitForTimeout(100);
  const btnStillDisabled = await page.$eval('#btn-delete-confirm', b => b.disabled);
  console.log('5. Bouton désactivé (mauvaise casse):', btnStillDisabled);

  // 6. Bonne saisie → activé
  await page.fill('#confirm-input', 'SUPPRIMER');
  await page.waitForTimeout(200);
  const btnEnabled = await page.$eval('#btn-delete-confirm', b => !b.disabled);
  console.log('6. Bouton activé (SUPPRIMER):', btnEnabled);
  await page.screenshot({ path: path.join(os.tmpdir(), 'del-ready.png') });

  // 7. Soumettre
  await page.click('#btn-delete-confirm');
  await page.waitForLoadState('networkidle');
  const afterUrl = page.url();
  console.log('7. URL après suppression:', afterUrl);
  const redirectedHome = afterUrl.includes('localhost:8000');
  console.log('   Redirigé vers accueil:', redirectedHome);

  // Vérifier le message flash
  const flash = await page.evaluate(() => {
    const el = document.querySelector('.swal2-title') || document.querySelector('[class*="flash"]') || document.querySelector('[class*="success"]');
    return el ? el.textContent.trim() : null;
  });
  console.log('   Message flash:', flash);
  await page.screenshot({ path: path.join(os.tmpdir(), 'del-after.png') });

  // 8. Vérifier que l'email est libéré : tentative d'inscription
  await page.goto(BASE + '/auth/inscription', { waitUntil: 'networkidle' });
  const emailField = await page.$('input[name="email"]');
  if (emailField) {
    await emailField.fill(TEST_EMAIL);
    await page.fill('input[name="prenom"]', 'Nouveau');
    await page.fill('input[name="nom"]', 'Compte');
    await page.fill('input[name="password"]', 'TestPass123!');
    const confirmField = await page.$('input[name="password_confirmation"]');
    if (confirmField) await confirmField.fill('TestPass123!');
    await page.screenshot({ path: path.join(os.tmpdir(), 'del-reinscription.png') });
    console.log('8. Formulaire inscription rempli avec le même email — screenshot pris');
  } else {
    console.log('8. Champ email non trouvé sur la page inscription');
  }

  await browser.close();
  console.log('\nScreenshots dans:', os.tmpdir());
})();
