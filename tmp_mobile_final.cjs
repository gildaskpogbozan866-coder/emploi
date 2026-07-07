const { chromium } = require('playwright-core');
const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const context = await browser.newContext({ viewport: { width: 360, height: 1400 } });
  const page = await context.newPage();
  await page.goto('http://localhost:8000/auth/connexion', { waitUntil: 'domcontentloaded' });
  await page.fill('#email', 'recruteur@techbenin.com');
  await page.fill('#password', 'TempAudit#2026!');
  await Promise.all([page.waitForNavigation({ waitUntil: 'domcontentloaded' }), page.click('button[type=submit], input[type=submit]')]);
  await page.goto('http://localhost:8000/recruteur/mes-offres/creer', { waitUntil: 'networkidle' });
  const overflow = await page.evaluate(() => ({ scrollW: document.body.scrollWidth, clientW: document.documentElement.clientWidth }));
  console.log('overflow:', JSON.stringify(overflow));
  await page.screenshot({ path: 'C:\\Users\\hp\\AppData\\Local\\Temp\\claude\\C--xampp-htdocs-emploi\\d8305ff0-ebf6-4033-aadd-287aaebfc853\\scratchpad\\mobile_final.png', fullPage: true });

  await page.goto('http://localhost:8000/recruteur/mes-offres/1/modifier', { waitUntil: 'networkidle' });
  const overflow2 = await page.evaluate(() => ({ scrollW: document.body.scrollWidth, clientW: document.documentElement.clientWidth }));
  console.log('edit overflow:', JSON.stringify(overflow2));

  await browser.close();
})();
