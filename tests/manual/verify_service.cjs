const { chromium } = require('playwright');
const path = require('path');
const os = require('os');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1280, height: 900 });

  // Page liste services
  await page.goto('http://localhost:8000/services', { waitUntil: 'networkidle' });
  await page.screenshot({ path: path.join(os.tmpdir(), 'svc-list.png'), fullPage: false });

  // Vérifier que les cards sont des <a>
  const cardTag = await page.evaluate(() => {
    const card = document.querySelector('.svc-card');
    return card ? card.tagName : 'NOT FOUND';
  });
  console.log('Card tag:', cardTag);

  // Vérifier absence du SVG éclair dans le badge
  const badgeSvg = await page.evaluate(() => {
    const badge = document.querySelector('.svc-hero__badge svg');
    return badge ? 'SVG PRESENT' : 'SVG ABSENT (OK)';
  });
  console.log('Badge éclair:', badgeSvg);

  // Vérifier absence des petits points dans les tags
  const tagDot = await page.evaluate(() => {
    const dot = document.querySelector('.svc-card__tag svg');
    return dot ? 'DOT PRESENT' : 'DOT ABSENT (OK)';
  });
  console.log('Dot in tag:', tagDot);

  // Hover sur une card pour vérifier le cursor pointer
  const card = page.locator('.svc-card').first();
  const href = await card.getAttribute('href');
  console.log('Card href:', href);

  // Screenshot scrollé pour voir les cards
  await page.evaluate(() => window.scrollTo(0, 800));
  await page.waitForTimeout(300);
  await page.screenshot({ path: path.join(os.tmpdir(), 'svc-cards.png'), fullPage: false });

  // Cliquer sur la première card et vérifier la navigation
  await page.goto('http://localhost:8000/services', { waitUntil: 'networkidle' });
  await page.evaluate(() => window.scrollTo(0, 800));
  await page.waitForTimeout(300);
  const firstCard = page.locator('.svc-card').first();
  await firstCard.click();
  await page.waitForLoadState('networkidle');
  const detailUrl = page.url();
  console.log('After click URL:', detailUrl);

  // Screenshot page détail
  await page.screenshot({ path: path.join(os.tmpdir(), 'svc-detail.png'), fullPage: false });

  // Vérifier absence du point dans sd-hero__type
  const typeDot = await page.evaluate(() => {
    const dot = document.querySelector('.sd-hero__type svg circle');
    return dot ? 'DOT PRESENT' : 'DOT ABSENT (OK)';
  });
  console.log('Type dot on detail:', typeDot);

  await browser.close();
  console.log('Screenshots: svc-list.png, svc-cards.png, svc-detail.png in', os.tmpdir());
})();
