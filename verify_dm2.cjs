const { chromium } = require('playwright');
const path = require('path');
const os = require('os');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 390, height: 844 });

  await page.goto('http://localhost:8000/services/creation-sites-web', { waitUntil: 'networkidle' });

  await page.screenshot({ path: path.join(os.tmpdir(), 'dm2-hero.png'), fullPage: false });

  await page.evaluate(() => window.scrollTo(0, 700));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'dm2-checklist.png'), fullPage: false });

  await page.evaluate(() => window.scrollTo(0, 1500));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'dm2-steps.png'), fullPage: false });

  await browser.close();
  console.log('Done:', os.tmpdir());
})();
