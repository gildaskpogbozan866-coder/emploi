const { chromium } = require('playwright');
const path = require('path');
const os = require('os');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 390, height: 844 }); // iPhone 14

  await page.goto('http://localhost:8000/services', { waitUntil: 'networkidle' });

  // Hero
  await page.screenshot({ path: path.join(os.tmpdir(), 'mob-hero.png'), fullPage: false });

  // CV card
  await page.evaluate(() => window.scrollTo(0, 800));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'mob-cv.png'), fullPage: false });

  // Cards grille
  await page.evaluate(() => window.scrollTo(0, 1800));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'mob-cards.png'), fullPage: false });

  // Suite cards
  await page.evaluate(() => window.scrollTo(0, 3200));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'mob-cards2.png'), fullPage: false });

  await browser.close();
  console.log('Done:', os.tmpdir());
})();
