const { chromium } = require('playwright-core');
const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
(async () => {
  const browser = await chromium.launch({ executablePath: CHROME_PATH, headless: true });
  const page = await browser.newPage({ viewport: { width: 1280, height: 1400 } });
  await page.goto('http://localhost:8000/auth/connexion', { waitUntil: 'domcontentloaded' });
  await page.fill('#email', 'recruteur@techbenin.com');
  await page.fill('#password', 'TempAudit#2026!');
  await Promise.all([page.waitForNavigation({ waitUntil: 'domcontentloaded' }), page.click('button[type=submit], input[type=submit]')]);
  const resp = await page.goto('http://localhost:8000/recruteur/mes-offres/creer', { waitUntil: 'networkidle' });
  console.log('status:', resp.status());
  await page.screenshot({ path: 'C:\\Users\\hp\\AppData\\Local\\Temp\\claude\\C--xampp-htdocs-emploi\\d8305ff0-ebf6-4033-aadd-287aaebfc853\\scratchpad\\urgent_check.png', fullPage: true });
  await browser.close();
})();
