"use strict";

const assert = require("node:assert/strict");
const { resolve } = require("node:path");
const { pathToFileURL } = require("node:url");
const { chromium, firefox, webkit } = require("playwright");

async function checkRecipes(engine)
{
  const browser = await engine.launch();
  try
  {
    const page = await browser.newPage({ javaScriptEnabled: false, viewport: { width: 1440, height: 900 } });
    await page.goto(pathToFileURL(resolve(process.argv[2], "recipes.html")).href);
    const panels = page.locator("#resumen-contenedor .app-resumen-entrega");
    const columns = await panels.evaluateAll(items => items.map(item => getComputedStyle(item).gridTemplateColumns.split(" ").length));
    assert.deepEqual(columns, [1, 2], "Una misma ventana debe mostrar una y dos columnas");

    const details = page.locator("#acordeones-exclusivos details");
    const summaries = details.locator("summary");
    await summaries.nth(0).focus();
    await page.keyboard.press("Tab");
    assert.equal(await summaries.nth(1).evaluate(item => item === document.activeElement), true);
    await page.keyboard.press("Enter");
    assert.deepEqual(await details.evaluateAll(items => items.map(item => item.open)), [false, true, true, false]);
    await summaries.nth(3).press("Space");
    assert.deepEqual(await details.evaluateAll(items => items.map(item => item.open)), [false, true, false, true]);

    await page.setViewportSize({ width: 390, height: 844 });
    await page.evaluate(() => { document.documentElement.style.fontSize = "200%"; });
    assert.equal(await panels.evaluateAll(items => items.every(item =>
      getComputedStyle(item).gridTemplateColumns.split(" ").length === 1 && item.scrollWidth <= item.clientWidth + 1)), true);
    assert.equal(await page.evaluate(() => document.documentElement.scrollWidth <= document.documentElement.clientWidth + 1), true,
      "La página no debe desbordarse al ampliar el texto");
    await page.goto(pathToFileURL(resolve(process.argv[2], "fundamentals.html")).href);
    const copyButton = page.locator("[data-site-copy]").first();
    assert.ok(await copyButton.count(), "La guía debe incluir un ejemplo copiable");
    assert.equal(await copyButton.isVisible(), false, "Sin JavaScript el botón de copia debe permanecer oculto");
    console.log(`${engine.name()} ${browser.version()}: recetas sin JavaScript verificadas`);
  }
  finally
  {
    await browser.close();
  }

}

(async () =>
{
  for (const engine of [chromium, firefox, webkit])
  {
    await checkRecipes(engine);
  }

})().catch(error =>
{
  console.error(error);
  process.exitCode = 1;
});
