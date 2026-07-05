const { chromium } = require('playwright');
const path = require('path');
const os = require('os');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1280, height: 900 });

  await page.goto('http://localhost:8000/services', { waitUntil: 'networkidle' });

  // Hero
  await page.screenshot({ path: path.join(os.tmpdir(), 'list-hero.png'), fullPage: false });

  // CV premium card
  await page.evaluate(() => window.scrollTo(0, 700));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'list-cv.png'), fullPage: false });

  // Grille des 8 cards
  await page.evaluate(() => window.scrollTo(0, 1500));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'list-cards1.png'), fullPage: false });

  // Suite grille
  await page.evaluate(() => window.scrollTo(0, 2400));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'list-cards2.png'), fullPage: false });

  // CTA band bas
  await page.evaluate(() => window.scrollTo(0, 99999));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'list-bottom.png'), fullPage: false });

  await browser.close();
  console.log('Done:', os.tmpdir());
})();
