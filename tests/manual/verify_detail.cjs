const { chromium } = require('playwright');
const path = require('path');
const os = require('os');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1280, height: 900 });

  await page.goto('http://localhost:8000/services/creation-sites-web', { waitUntil: 'networkidle' });

  // Screenshot full page
  await page.screenshot({ path: path.join(os.tmpdir(), 'detail-full.png'), fullPage: true });

  // Screenshot haut (hero)
  await page.screenshot({ path: path.join(os.tmpdir(), 'detail-hero.png'), fullPage: false });

  // Screenshot milieu (contenu + sidebar)
  await page.evaluate(() => window.scrollTo(0, 600));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'detail-body.png'), fullPage: false });

  // Screenshot bas (CTA band)
  await page.evaluate(() => window.scrollTo(0, 99999));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'detail-bottom.png'), fullPage: false });

  await browser.close();
  console.log('Done. Screenshots in', os.tmpdir());
})();
