const { readdirSync } = require("node:fs");
const assert = require("node:assert/strict");
const { resolve } = require("node:path");
const { pathToFileURL } = require("node:url");
const { chromium } = require("playwright");

async function audit()
{
  const directory = resolve(process.argv[2]);
  const pages = readdirSync(directory).filter(name => name.endsWith(".html"));
  if (!pages.length) throw new Error(`No hay páginas HTML en ${directory}`);
  const browser = await chromium.launch();
  try
  {
    for (const width of [320, 1440])
    {
      const page = await browser.newPage({ viewport: { width, height: 900 } });
      for (const name of pages)
      {
        await page.goto(pathToFileURL(resolve(directory, name)).href);
        await page.addScriptTag({ path: require.resolve("axe-core/axe.min.js") });
        const results = await page.evaluate(() => axe.run());
        for (const violation of results.violations)
        {
          for (const node of violation.nodes)
          {
            console.error(`${name} (${width}px): ${violation.id} ${node.target}: ${node.failureSummary}`);
          }

          process.exitCode = 1;
        }

        if (await page.evaluate(() => document.documentElement.scrollWidth > innerWidth + 1))
        {
          console.error(`${name} (${width}px): desbordamiento horizontal`);
          process.exitCode = 1;
        }

        await page.keyboard.press("Tab");
        assert.equal(await page.locator(".dct-skip-link:focus").count(), 1,
          `${name} (${width}px): el primer Tab debe mostrar el salto al contenido`);
        await page.keyboard.press("Enter");
        assert.equal(await page.locator("main:focus").count(), 1,
          `${name} (${width}px): el salto debe llevar el foco al contenido principal`);
      }

      await page.close();
      console.log(`${pages.length} páginas auditadas a ${width}px.`);
    }
  }
  finally
  {
    await browser.close();
  }
}

audit().catch(error =>
{
  console.error(error);
  process.exitCode = 1;
});
